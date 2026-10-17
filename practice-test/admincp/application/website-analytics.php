<?php
include("../common/access.php");
include("../common/autoload.php");
include(__DIR__ . "/../../configs/revenue.php");

$data['page_title'] = "Web Analytics";
$data['breadcrumb'] = array("Web Analytics" => "website-analytics");

/*
 * Data sources
 *   Users          edu_user.reg_date, edu_user.reg_at ("...app..." = app user, "...web..." = web user)
 *   Logins         edu_login.last_login (one row per user: the most recent login only)
 *   Subscriptions  edu_subscription (amount > 0 = paid, subscription_date = start date)
 *
 * Revenue
 *   Uses the shared rules in configs/revenue.php: each payment is counted once, in dollars,
 *   from the ledger that recorded it (Square in cents, app stores in dollars), and Square
 *   payments by app users count as App revenue.
 *
 * Tester accounts (edu_testing_account, set on the Student List page) are excluded from every
 * figure on this page: users, logins, registrations, subscriptions, courses and revenue.
 * The check runs at query time, so marking/unmarking applies to past data too.
 *
 * All dates are calculated in PHP (America/New_York); MySQL runs in UTC.
 */

$APP_USER = "u.reg_at LIKE '%app%'";
$WEB_USER = "u.reg_at LIKE '%web%'";
$TESTING_USERS = "(SELECT user_id FROM " . $configs['prev'] . "testing_account)";
$NOT_TESTER = "u.user_id NOT IN $TESTING_USERS"; // for queries on edu_user aliased as u

$today = date('Y-m-d');

// ******* Helpers *******

// Single number from an aggregate query (common.php getDataByWhere)
function waScalar($row)
{
	return $row ? (float) $row['total'] : 0;
}

// Daily totals: returns array('Y-m-d' => value) using common.php getQuery() with GROUP BY
function waDaily($table, $dateExpr, $valueExpr, $where)
{
	$out = array();
	$rr = getQuery($table, "$dateExpr AS d, $valueExpr AS total", $where, "", "", 0, $dateExpr);
	while ($rr && $row = mysqli_fetch_assoc($rr)) {
		$out[$row['d']] = (float) $row['total'];
	}
	return $out;
}

// Sum a daily map into the given [start, end] ranges
function waBucket($daily, $ranges, $decimals = 0)
{
	$values = array();
	foreach ($ranges as $r) {
		$sum = 0;
		foreach ($daily as $d => $v) {
			if ($d >= $r[0] && $d <= $r[1]) $sum += $v;
		}
		$values[] = $decimals ? round($sum, $decimals) : (int) $sum;
	}
	return $values;
}

function waGrowth($current, $previous)
{
	return $previous > 0 ? round((($current - $previous) / $previous) * 100, 1) : 0;
}

// ******* Period ranges (shared by all charts) *******

$monthStart = date('Y-m-01');
$periods = array('7d' => array(), '10w' => array(), '12m' => array(), '5y' => array());
$labels  = array('7d' => array(), '10w' => array(), '12m' => array(), '5y' => array());

for ($i = 6; $i >= 0; $i--) {
	$d = date('Y-m-d', strtotime("$today -$i days"));
	$periods['7d'][] = array($d, $d);
	$labels['7d'][]  = date('d M', strtotime($d));
}
for ($i = 9; $i >= 0; $i--) {
	$periods['10w'][] = array(date('Y-m-d', strtotime("$today -" . ($i * 7 + 6) . " days")), date('Y-m-d', strtotime("$today -" . ($i * 7) . " days")));
	$labels['10w'][]  = "W" . (10 - $i);
}
for ($i = 11; $i >= 0; $i--) {
	$start = date('Y-m-01', strtotime("$monthStart -$i month")); // anchored on the 1st so months never skip
	$periods['12m'][] = array($start, date('Y-m-t', strtotime($start)));
	$labels['12m'][]  = date('M Y', strtotime($start));
}
for ($i = 4; $i >= 0; $i--) {
	$year = date('Y') - $i;
	$periods['5y'][] = array("$year-01-01", "$year-12-31");
	$labels['5y'][]  = (string) $year;
}

$rangeStart = $periods['5y'][0][0];
$rangeEnd   = $today;

// This month so far vs the same days of last month
$prevMonthStart  = date('Y-m-01', strtotime("$monthStart -1 month"));
$prevMonthEndMtd = min(date('Y-m-d', strtotime("$prevMonthStart +" . (date('j') - 1) . " days")), date('Y-m-t', strtotime($prevMonthStart)));

// ******* Daily data (one grouped query per metric) *******

$userRange = "u.reg_date BETWEEN '$rangeStart' AND '$rangeEnd' AND $NOT_TESTER";
$subRange  = "s.subscription_date BETWEEN '$rangeStart' AND '$rangeEnd' AND $NOT_TESTER";

$dailyLoggedIn   = waDaily("login", "DATE(last_login)", "COUNT(*)", "last_login BETWEEN '$rangeStart 00:00:00' AND '$rangeEnd 23:59:59' AND user_id NOT IN $TESTING_USERS");
$dailyRegistered = waDaily("user as u", "u.reg_date", "COUNT(*)", $userRange);
$dailyAppReg     = waDaily("user as u", "u.reg_date", "COUNT(*)", "$userRange AND $APP_USER");
$dailyWebReg     = waDaily("user as u", "u.reg_date", "COUNT(*)", "$userRange AND $WEB_USER");

$subJoin = "subscription as s INNER JOIN " . $configs['prev'] . "user as u ON u.user_id = s.user_id";
$dailySubscribed = waDaily($subJoin, "s.subscription_date", "COUNT(*)", "$subRange AND s.amount > 0");
$dailyAppSub     = waDaily($subJoin, "s.subscription_date", "COUNT(*)", "$subRange AND s.amount > 0 AND $APP_USER");
$dailyWebSub     = waDaily($subJoin, "s.subscription_date", "COUNT(*)", "$subRange AND s.amount > 0 AND $WEB_USER");

// Revenue in dollars: app as stored, web converted from cents
// App = app-store purchases + Square payments made by app users; Web = all other Square payments
$dailyAppRevenue = waDaily(revenueAppStoreFrom(), revenueAppStoreDate(), "SUM(" . revenueAppStoreAmount() . ")", revenueAppStoreWhere($rangeStart, $rangeEnd));
foreach (waDaily(revenueSquareFrom(), revenueSquareDate(), "SUM(" . revenueSquareAmount() . ")", revenueSquareWhere('app', $rangeStart, $rangeEnd)) as $d => $v) {
	$dailyAppRevenue[$d] = ($dailyAppRevenue[$d] ?? 0) + $v;
}
$dailyWebRevenue = waDaily(revenueSquareFrom(), revenueSquareDate(), "SUM(" . revenueSquareAmount() . ")", revenueSquareWhere('web', $rangeStart, $rangeEnd));

// ******* Header cards *******

$data['total_users'] = (int) waScalar(getDataByWhere("user as u", "COUNT(*) AS total", "($APP_USER OR $WEB_USER) AND $NOT_TESTER"));
$data['app_users']   = (int) waScalar(getDataByWhere("user as u", "COUNT(*) AS total", "$APP_USER AND $NOT_TESTER"));
$data['web_users']   = (int) waScalar(getDataByWhere("user as u", "COUNT(*) AS total", "$WEB_USER AND $NOT_TESTER"));

$data['new_app_users_today'] = (int) ($dailyAppReg[$today] ?? 0);
$data['new_web_users_today'] = (int) ($dailyWebReg[$today] ?? 0);
$data['new_users_today']     = $data['new_app_users_today'] + $data['new_web_users_today'];
$data['active_users']        = (int) ($dailyLoggedIn[$today] ?? 0);

$thisMonth = array(array($monthStart, $today));
$lastMonth = array(array($prevMonthStart, $prevMonthEndMtd));

$data['app_growth'] = waGrowth(waBucket($dailyAppReg, $thisMonth)[0], waBucket($dailyAppReg, $lastMonth)[0]);
$data['web_growth'] = waGrowth(waBucket($dailyWebReg, $thisMonth)[0], waBucket($dailyWebReg, $lastMonth)[0]);

$data['this_month_revenue'] = round(waBucket($dailyAppRevenue, $thisMonth, 2)[0] + waBucket($dailyWebRevenue, $thisMonth, 2)[0], 2);
$data['last_month_revenue'] = round(waBucket($dailyAppRevenue, $lastMonth, 2)[0] + waBucket($dailyWebRevenue, $lastMonth, 2)[0], 2); // same days last month
$data['revenue_growth']     = waGrowth($data['this_month_revenue'], $data['last_month_revenue']);

$data['total_subscriptions'] = waBucket($dailySubscribed, $thisMonth)[0];

// ******* Charts *******

$user_engagement_chart_data = array();
$revenue_chart_data = array();
$app_vs_web_chart_data = array();

foreach ($periods as $key => $ranges) {
	$user_engagement_chart_data[$key] = array(
		'labels'     => $labels[$key],
		'loggedIn'   => waBucket($dailyLoggedIn, $ranges),
		'registered' => waBucket($dailyRegistered, $ranges),
		'subscribed' => waBucket($dailySubscribed, $ranges)
	);

	$app = waBucket($dailyAppRevenue, $ranges, 2);
	$web = waBucket($dailyWebRevenue, $ranges, 2);
	$total = array();
	foreach ($app as $i => $v) {
		$total[] = round($v + $web[$i], 2);
	}
	$revenue_chart_data[$key] = array(
		'labels' => $labels[$key],
		'total'  => $total,
		'app'    => $app,
		'web'    => $web
	);

	$app_vs_web_chart_data[$key] = array(
		'labels'         => $labels[$key],
		'app_registered' => waBucket($dailyAppReg, $ranges),
		'app_subscribed' => waBucket($dailyAppSub, $ranges),
		'web_registered' => waBucket($dailyWebReg, $ranges),
		'web_subscribed' => waBucket($dailyWebSub, $ranges)
	);
}

$data['user_engagement_chart_data'] = $user_engagement_chart_data;
$data['revenue_chart_data']         = $revenue_chart_data;
$data['app_vs_web_chart_data']      = $app_vs_web_chart_data;

// ******* Course chart (Week / Month / Year = last 7 / 30 / 365 days) *******

$courses = array();
$rr = getQuery("course as c INNER JOIN " . $configs['prev'] . "course_group as g ON g.group_id = c.group_id", "c.course_id, c.course_name", "1=1", "g.ord, c.ord");
while ($rr && $row = mysqli_fetch_assoc($rr)) {
	$courses[] = $row;
}

$courseWindows = array('all' => 6, 'top' => 30, 'bottom' => 365);
$course_chart_data = array();

foreach ($courseWindows as $key => $days) {
	$start = date('Y-m-d', strtotime("$today -$days days"));

	// Enrolled = users who registered in the window, by their course
	$enrolled = array();
	$rr = getQuery($subJoin, "s.course_id, COUNT(*) AS total", "u.reg_date BETWEEN '$start' AND '$today' AND $NOT_TESTER", "", "", 0, "s.course_id");
	while ($rr && $row = mysqli_fetch_assoc($rr)) {
		$enrolled[$row['course_id']] = (int) $row['total'];
	}

	// Subscribed = paid subscriptions that started in the window
	$subscribed = array();
	$rr = getQuery($subJoin, "s.course_id, COUNT(*) AS total", "s.subscription_date BETWEEN '$start' AND '$today' AND s.amount > 0 AND $NOT_TESTER", "", "", 0, "s.course_id");
	while ($rr && $row = mysqli_fetch_assoc($rr)) {
		$subscribed[$row['course_id']] = (int) $row['total'];
	}

	$course_chart_data[$key] = array('labels' => array(), 'enrolled' => array(), 'subscribed' => array());
	foreach ($courses as $c) {
		$course_chart_data[$key]['labels'][]     = $c['course_name'];
		$course_chart_data[$key]['enrolled'][]   = $enrolled[$c['course_id']] ?? 0;
		$course_chart_data[$key]['subscribed'][] = $subscribed[$c['course_id']] ?? 0;
	}
}

$data['course_chart_data'] = $course_chart_data;

// ******* Login pie (last 1 month = last 30 days) *******

$loginStart = date('Y-m-d', strtotime("$today -30 days"));
$loginJoin  = "login as l INNER JOIN " . $configs['prev'] . "user as u ON u.user_id = l.user_id";
$loginWhere = "l.last_login BETWEEN '$loginStart 00:00:00' AND '$today 23:59:59' AND $NOT_TESTER";

$data['login_pie_data'] = array(
	'app' => (int) waScalar(getDataByWhere($loginJoin, "COUNT(*) AS total", "$loginWhere AND $APP_USER")),
	'web' => (int) waScalar(getDataByWhere($loginJoin, "COUNT(*) AS total", "$loginWhere AND $WEB_USER"))
);

echo template($data);
