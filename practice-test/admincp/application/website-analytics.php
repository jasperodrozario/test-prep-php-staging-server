<?
include("../common/access.php");
include("../common/autoload.php");

$data['page_title'] = "Web Analytics";
$data['breadcrumb'] = array("Web Analytics" => "website-analytics");

// ******* Utility functions *******

// Calculate growth percentage for current month vs previous month
function calculateUserGrowth($platform)
{
    // Current month users

    $current_count = getTotalRow("user", array("[or]" => "reg_at like '%" . $platform . "%' and MONTH(reg_date) = MONTH(CURDATE()) and YEAR(reg_date) = YEAR(CURDATE())"));
    $previous_count = getTotalRow("user", array("[or]" => "reg_at like '%" . $platform . "%' and MONTH(reg_date) = MONTH(CURDATE() - INTERVAL 1 MONTH) and YEAR(reg_date) = YEAR(CURDATE() - INTERVAL 1 MONTH)"));

    // Calculate growth percentage
    if ($previous_count > 0) {
        $growth = (($current_count - $previous_count) / $previous_count) * 100;
        return round($growth, 1);
    }

    return 0;
}

// ******* End Utility functions *******


// ******* Start Header Part *******
$data['total_users'] = getTotalRow("user", array("[or]" => "(reg_at like '%web%' or reg_at like '%app%')"));
$data['new_users_today'] = getTotalRow("user", array("[or]" => "(reg_at like '%web%' or reg_at like '%app%')", "reg_date" => date("Y-m-d")));
$data['active_users'] = getTotalRow("login", array("DATE(last_login)" => date("Y-m-d")));
$data['app_users'] = getTotalRow("user", array("[or]" => "reg_at like '%app%'"));
$data['web_users'] = getTotalRow("user", array("[or]" => "reg_at like '%web%'"));

$app_user_current_count = getTotalRow("user", array("[or]" => "reg_at like '%app%'", "DATE(reg_date)" => date("Y-m-d")));
$data['app_growth'] = calculateUserGrowth('app');
$data['web_growth'] = calculateUserGrowth('web');
$data['new_app_users_today'] = getTotalRow("user", array("[or]" => "reg_at like '%app%'", "reg_date" => date("Y-m-d")));
$data['new_web_users_today'] = getTotalRow("user", array("[or]" => "reg_at like '%web%'", "reg_date" => date("Y-m-d")));

$currentMonth = date('n');
$currentYear = date('Y');
$prevMonth = date('n', strtotime('-1 month'));
$prevYear  = date('Y', strtotime('-1 month'));

$app_revenue_current_month = (int)getDataByJoin(
    array("subscription as s", "user as u"),
    "SUM(s.amount) as total",
    "MONTH(s.subscription_date) = $currentMonth AND YEAR(s.subscription_date) = $currentYear AND s.amount > 0 AND u.reg_at like '%app%' AND s.user_id = u.user_id"
)['total'];
$app_revenue_prev_month = (int)getDataByJoin(
    array("subscription as s", "user as u"),
    "SUM(s.amount) as total",
    "MONTH(s.subscription_date) = $prevMonth AND YEAR(s.subscription_date) = $prevYear AND s.amount > 0 AND u.reg_at like '%app%' AND s.user_id = u.user_id"
)['total'];

$web_revenue_current_month = (int)getDataByWhere(
    "square_payments",
    "SUM(amount) as total",
    "MONTH(updated_at) = $currentMonth AND YEAR(updated_at) = $currentYear AND amount > 0"
)['total'];
$web_revenue_current_month = $web_revenue_current_month/100;
$web_revenue_prev_month = (int)getDataByWhere(
    "square_payments",
    "SUM(amount) as total",
    "MONTH(updated_at) = $prevMonth AND YEAR(updated_at) = $prevYear AND amount > 0"
)['total'];
$web_revenue_prev_month = $web_revenue_prev_month/100;

$data['this_month_revenue'] = $app_revenue_current_month + $web_revenue_current_month;
$data['last_month_revenue'] = $app_revenue_prev_month + $web_revenue_prev_month;
$data['revenue_growth'] = $data['last_month_revenue'] > 0 ? round((($data['this_month_revenue'] - $data['last_month_revenue']) / $data['last_month_revenue']) * 100, 1) : 0;

$data['total_subscriptions'] = getTotalRow("subscription", array("MONTH(subscription_date)" => $currentMonth, "YEAR(subscription_date)" => $currentYear, "amount >" => 0));
// ******* End Header Part *******


// ******* Start User Engagement Chart Data *******

// 7 Days Data
$labels7d = [];
$dates7d = [];

for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $dates7d[] = $date;
    $labels7d[] = date('d M', strtotime($date)); // Mon, Tue...
}

$loggedIn7d = [];
$registered7d = [];
$subscribed7d = [];

foreach ($dates7d as $date) {
    $count = getTotalRow("login", [
        "DATE(last_login)" => $date
    ]);
    $loggedIn7d[] = $count;

    $count = getTotalRow("user", [
        "reg_date" => $date
    ]);
    $registered7d[] = $count;

    $count = getTotalRow("subscription", [
        "subscription_date" => $date,
        "amount >" => 0
    ]);

    $subscribed7d[] = $count;
}

// 10 Weeks Data
$labels10w = [];
$loggedIn10w = [];
$registered10w = [];
$subscribed10w = [];

for ($i = 9; $i >= 0; $i--) {

    $start = date('Y-m-d', strtotime("-" . ($i * 7 + 6) . " days"));
    $end   = date('Y-m-d', strtotime("-" . ($i * 7) . " days"));

    $labels10w[] = "W" . (10 - $i);

    // LOGGED IN
    $loggedIn10w[] = (int)getDataByWhere(
        "login",
        "COUNT(*) as total",
        "DATE(last_login) BETWEEN '$start' AND '$end'"
    )['total'];

    // REGISTERED
    $registered10w[] = (int)getDataByWhere(
        "user",
        "COUNT(*) as total",
        "reg_date BETWEEN '$start' AND '$end'"
    )['total'];

    // SUBSCRIBED
    $subscribed10w[] = (int)getDataByWhere(
        "subscription",
        "COUNT(*) as total",
        "subscription_date BETWEEN '$start' AND '$end' AND amount > 0"
    )['total'];
}

// 12 Months Data
$labels12m = [];

$loggedIn12m = [];
$registered12m = [];
$subscribed12m = [];

for ($i = 11; $i >= 0; $i--) {

    $start = date('Y-m-01', strtotime("-$i month"));
    $end   = date('Y-m-t', strtotime($start));

    $labels12m[] = date('M Y', strtotime($start));

    // LOGGED IN
    $loggedIn12m[] = (int)getDataByWhere(
        "login",
        "COUNT(*) as total",
        "DATE(last_login) BETWEEN '$start' AND '$end'"
    )['total'];

    // REGISTERED
    $registered12m[] = (int)getDataByWhere(
        "user",
        "COUNT(*) as total",
        "reg_date BETWEEN '$start' AND '$end'"
    )['total'];

    // SUBSCRIBED
    $subscribed12m[] = (int)getDataByWhere(
        "subscription",
        "COUNT(*) as total",
        "subscription_date BETWEEN '$start' AND '$end' AND amount > 0"
    )['total'];
}

// 5 Years Data
$labels5y = [];

$loggedIn5y = [];
$registered5y = [];
$subscribed5y = [];

for ($i = 4; $i >= 0; $i--) {

    $year = date('Y', strtotime("-$i year"));
    $start = $year . "-01-01";
    $end   = $year . "-12-31";

    $labels5y[] = $year;

    // LOGGED IN
    $loggedIn5y[] = (int)getDataByWhere(
        "login",
        "COUNT(*) as total",
        "DATE(last_login) BETWEEN '$start' AND '$end'"
    )['total'];

    // REGISTERED
    $registered5y[] = (int)getDataByWhere(
        "user",
        "COUNT(*) as total",
        "reg_date BETWEEN '$start' AND '$end'"
    )['total'];

    // SUBSCRIBED
    $subscribed5y[] = (int)getDataByWhere(
        "subscription",
        "COUNT(*) as total",
        "subscription_date BETWEEN '$start' AND '$end' AND amount > 0"
    )['total'];
}

$data['user_engagement_chart_data'] = [
    '7d' => [
        'labels' => $labels7d,
        'loggedIn' => $loggedIn7d,
        'registered' => $registered7d,
        'subscribed' => $subscribed7d
    ],
    '10w' => [
        'labels' => $labels10w,
        'loggedIn' => $loggedIn10w,
        'registered' => $registered10w,
        'subscribed' => $subscribed10w
    ],
    '12m' => [
        'labels' => $labels12m,
        'loggedIn' => $loggedIn12m,
        'registered' => $registered12m,
        'subscribed' => $subscribed12m
    ],
    '5y' => [
        'labels' => $labels5y,
        'loggedIn' => $loggedIn5y,
        'registered' => $registered5y,
        'subscribed' => $subscribed5y
    ]
];
// ******* End User Engagement Chart Data *******


// ******* Start Revenue Chart Data *******

// 7 Days Data
$totalRevenue7d = [];
$appRevenue7d = [];
$webRevenue7d = [];

for ($i = 6; $i >= 0; $i--) {

    $date = date('Y-m-d', strtotime("-$i days"));

    // APP REVENUE
    $tmpAppRevenue7d = (int)getDataByJoin(
        array("subscription as s", "user as u"),
        "SUM(s.amount) as total",
        "DATE(s.subscription_date) = '$date'
         AND s.amount > 0
         AND u.reg_at LIKE '%app%'
         AND s.user_id = u.user_id"
    )['total'];

    // WEB REVENUE
    $tmpWebRevenue7d = (int)getDataByWhere(
        "square_payments",
        "SUM(amount) as total",
        "DATE(updated_at) = '$date'
         AND amount > 0"
    )['total'];
    $tmpWebRevenue7d = $tmpWebRevenue7d/100;

    // TOTAL REVENUE
    $totalRevenue7d[] = $tmpAppRevenue7d + $tmpWebRevenue7d;
    $appRevenue7d[] = $tmpAppRevenue7d;
    $webRevenue7d[] = $tmpWebRevenue7d;
}

// 10 Weeks Data
$totalRevenue10w = [];
$appRevenue10w = [];
$webRevenue10w = [];

for ($i = 9; $i >= 0; $i--) {

    // Week range (7-day block)
    $start = date('Y-m-d', strtotime("-" . ($i * 7 + 6) . " days"));
    $end   = date('Y-m-d', strtotime("-" . ($i * 7) . " days"));

    // APP REVENUE (subscription table)
    $tmpAppRevenue10w = (int)getDataByJoin(
        array("subscription as s", "user as u"),
        "SUM(s.amount) as total",
        "DATE(s.subscription_date) BETWEEN '$start' AND '$end'
         AND s.amount > 0
         AND u.reg_at LIKE '%app%'
         AND s.user_id = u.user_id"
    )['total'];

    // WEB REVENUE (square_payments table)
    $tmpWebRevenue10w = (int)getDataByWhere(
        "square_payments",
        "SUM(amount) as total",
        "DATE(updated_at) BETWEEN '$start' AND '$end'
         AND amount > 0"
    )['total'];
    $tmpWebRevenue10w = $tmpWebRevenue10w/100;

    // TOTAL REVENUE
    $totalRevenue10w[] = $tmpAppRevenue10w + $tmpWebRevenue10w;
    $appRevenue10w[] = $tmpAppRevenue10w;
    $webRevenue10w[] = $tmpWebRevenue10w;
}

// 12 Months Data
$totalRevenue12m = [];
$appRevenue12m = [];
$webRevenue12m = [];

for ($i = 11; $i >= 0; $i--) {

    $start = date('Y-m-01 00:00:00', strtotime("-$i month"));
    $end   = date('Y-m-t 23:59:59', strtotime("-$i month"));

    // APP REVENUE (subscription)
    $tmpAppRevenue12m = (int)getDataByJoin(
        array("subscription as s", "user as u"),
        "SUM(s.amount) as total",
        "s.subscription_date BETWEEN '$start' AND '$end'
         AND s.amount > 0
         AND u.reg_at LIKE '%app%'
         AND s.user_id = u.user_id"
    )['total'];

    // WEB REVENUE (square_payments if applicable)
    $tmpWebRevenue12m = (int)getDataByWhere(
        "square_payments",
        "SUM(amount) as total",
        "updated_at BETWEEN '$start' AND '$end'
         AND amount > 0"
    )['total'];
    $tmpWebRevenue12m = $tmpWebRevenue12m/100;

    // TOTAL
    $totalRevenue12m[] = $tmpAppRevenue12m + $tmpWebRevenue12m;
    $appRevenue12m[] = $tmpAppRevenue12m;
    $webRevenue12m[] = $tmpWebRevenue12m;
}

// 5 Years Data
$totalRevenue5y = [];
$appRevenue5y = [];
$webRevenue5y = [];

for ($i = 4; $i >= 0; $i--) {

    $year = date('Y', strtotime("-$i year"));

    $start = $year . "-01-01 00:00:00";
    $end   = $year . "-12-31 23:59:59";

    // APP REVENUE (subscription)
    $tmpAppRevenue5y = (int)getDataByJoin(
        array("subscription as s", "user as u"),
        "SUM(s.amount) as total",
        "s.subscription_date BETWEEN '$start' AND '$end'
         AND s.amount > 0
         AND u.reg_at LIKE '%app%'
         AND s.user_id = u.user_id"
    )['total'];

    // WEB REVENUE (square_payments)
    $tmpWebRevenue5y = (int)getDataByWhere(
        "square_payments",
        "SUM(amount) as total",
        "updated_at BETWEEN '$start' AND '$end'
         AND amount > 0"
    )['total'];
    $tmpWebRevenue5y = $tmpWebRevenue5y/100;

    // TOTAL
    $totalRevenue5y[] = $tmpAppRevenue5y + $tmpWebRevenue5y;
    $appRevenue5y[] = $tmpAppRevenue5y;
    $webRevenue5y[] = $tmpWebRevenue5y;
}

$data['revenue_chart_data'] = [
    '7d' => [
        'labels' => $labels7d,
        'total' => $totalRevenue7d,
        'app' => $appRevenue7d,
        'web' => $webRevenue7d
    ],
    '10w' => [
        'labels' => $labels10w,
        'total' => $totalRevenue10w,
        'app' => $appRevenue10w,
        'web' => $webRevenue10w
    ],
    '12m' => [
        'labels' => $labels12m,
        'total' => $totalRevenue12m,
        'app' => $appRevenue12m,
        'web' => $webRevenue12m
    ],
    '5y' => [
        'labels' => $labels5y,
        'total' => $totalRevenue5y,
        'app' => $appRevenue5y,
        'web' => $webRevenue5y
    ]
];
// ******* End Revenue Chart Data *******


// ******* App vs Web Chart Data *******

// 7 days
$appRegistered7d = [];
$appSubscribed7d = [];
$webRegistered7d = [];
$webSubscribed7d = [];

for ($i = 6; $i >= 0; $i--) {

    $date = date('Y-m-d', strtotime("-$i days"));

    // APP REGISTERED
    $appRegistered7d[] = (int)getTotalRow("user", [
        "[or]" => "reg_at like '%app%'",
        "reg_date" => $date
    ]);

    // WEB REGISTERED
    $webRegistered7d[] = (int)getTotalRow("user", [
        "[or]" => "reg_at like '%web%'",
        "reg_date" => $date
    ]);

    // APP SUBSCRIBED
    $appSubscribed7d[] = (int)getDataByJoin(
        ["subscription as s", "user as u"],
        "COUNT(*) as total",
        "DATE(s.subscription_date) = '$date'
         AND s.amount > 0
         AND s.user_id = u.user_id
         AND u.reg_at LIKE '%app%'"
    )['total'];

    // WEB SUBSCRIBED
    $webSubscribed7d[] = (int)getDataByJoin(
        ["subscription as s", "user as u"],
        "COUNT(*) as total",
        "DATE(s.subscription_date) = '$date'
         AND s.amount > 0
         AND s.user_id = u.user_id
         AND u.reg_at LIKE '%web%'"
    )['total'];
}

// 10 weeks
$appRegistered10w = [];
$appSubscribed10w = [];
$webRegistered10w = [];
$webSubscribed10w = [];
$labels10w = [];

for ($i = 9; $i >= 0; $i--) {

    $start = date('Y-m-d', strtotime("-" . ($i * 7 + 6) . " days"));
    $end   = date('Y-m-d', strtotime("-" . ($i * 7) . " days"));

    $labels10w[] = "W" . (10 - $i);

    // APP REGISTERED
    $appRegistered10w[] = (int)getDataByWhere("user", "count(*) as total", "reg_at like '%app%' AND DATE(reg_date) BETWEEN '$start' AND '$end'")['total'];

    // WEB REGISTERED
    $webRegistered10w[] = (int)getDataByWhere("user", "count(*) as total", "reg_at like '%web%' AND DATE(reg_date) BETWEEN '$start' AND '$end'")['total'];

    // APP SUBSCRIBED
    $appSubscribed10w[] = (int)getDataByJoin(
        ["subscription as s", "user as u"],
        "COUNT(*) as total",
        "DATE(s.subscription_date) BETWEEN '$start' AND '$end'
         AND s.amount > 0
         AND s.user_id = u.user_id
         AND u.reg_at LIKE '%app%'"
    )['total'];

    // WEB SUBSCRIBED
    $webSubscribed10w[] = (int)getDataByJoin(
        ["subscription as s", "user as u"],
        "COUNT(*) as total",
        "DATE(s.subscription_date) BETWEEN '$start' AND '$end'
         AND s.amount > 0
         AND s.user_id = u.user_id
         AND u.reg_at LIKE '%web%'"
    )['total'];
}

// 12 months
$appRegistered12m = [];
$appSubscribed12m = [];
$webRegistered12m = [];
$webSubscribed12m = [];

for ($i = 11; $i >= 0; $i--) {

    $start = date('Y-m-01', strtotime("-$i month"));
    $end   = date('Y-m-t', strtotime($start));


    // APP REGISTERED
    $appRegistered12m[] = (int)getDataByWhere("user", "count(*) as total", "reg_at like '%app%' AND DATE(reg_date) BETWEEN '$start' AND '$end'")['total'];

    // WEB REGISTERED
    $webRegistered12m[] = (int)getDataByWhere("user", "count(*) as total", "reg_at like '%web%' AND DATE(reg_date) BETWEEN '$start' AND '$end'")['total'];

    // APP SUBSCRIBED
    $appSubscribed12m[] = (int)getDataByJoin(
        ["subscription as s", "user as u"],
        "COUNT(*) as total",
        "s.subscription_date BETWEEN '$start' AND '$end'
         AND s.amount > 0
         AND s.user_id = u.user_id
         AND u.reg_at LIKE '%app%'"
    )['total'];

    // WEB SUBSCRIBED
    $webSubscribed12m[] = (int)getDataByJoin(
        ["subscription as s", "user as u"],
        "COUNT(*) as total",
        "s.subscription_date BETWEEN '$start' AND '$end'
         AND s.amount > 0
         AND s.user_id = u.user_id
         AND u.reg_at LIKE '%web%'"
    )['total'];
}

// 5 years
$appRegistered5y = [];
$appSubscribed5y = [];
$webRegistered5y = [];
$webSubscribed5y = [];

for ($i = 4; $i >= 0; $i--) {

    $year = date('Y', strtotime("-$i year"));
    $start = $year . "-01-01";
    $end   = $year . "-12-31";


    // APP REGISTERED
    $appRegistered5y[] = (int)getDataByWhere("user", "count(*) as total", "reg_at like '%app%' AND DATE(reg_date) BETWEEN '$start' AND '$end'")['total'];

    // WEB REGISTERED
    $webRegistered5y[] = (int)getDataByWhere("user", "count(*) as total", "reg_at like '%web%' AND DATE(reg_date) BETWEEN '$start' AND '$end'")['total'];

    // APP SUBSCRIBED
    $appSubscribed5y[] = (int)getDataByJoin(
        ["subscription as s", "user as u"],
        "COUNT(*) as total",
        "s.subscription_date BETWEEN '$start' AND '$end'
         AND s.amount > 0
         AND s.user_id = u.user_id
         AND u.reg_at LIKE '%app%'"
    )['total'];

    // WEB SUBSCRIBED
    $webSubscribed5y[] = (int)getDataByJoin(
        ["subscription as s", "user as u"],
        "COUNT(*) as total",
        "s.subscription_date BETWEEN '$start' AND '$end'
         AND s.amount > 0
         AND s.user_id = u.user_id
         AND u.reg_at LIKE '%web%'"
    )['total'];
}

// Compile App vs Web Chart Data
$app_vs_web_chart_data = [
    '7d' => [
        'labels' => $labels7d,
        'app_registered' => $appRegistered7d,
        'app_subscribed' => $appSubscribed7d,
        'web_registered' => $webRegistered7d,
        'web_subscribed' => $webSubscribed7d
    ],
    '10w' => [
        'labels' => $labels10w,
        'app_registered' => $appRegistered10w,
        'app_subscribed' => $appSubscribed10w,
        'web_registered' => $webRegistered10w,
        'web_subscribed' => $webSubscribed10w
    ],
    '12m' => [
        'labels' => $labels12m,
        'app_registered' => $appRegistered12m,
        'app_subscribed' => $appSubscribed12m,
        'web_registered' => $webRegistered12m,
        'web_subscribed' => $webSubscribed12m
    ],
    '5y' => [
        'labels' => $labels5y,
        'app_registered' => $appRegistered5y,
        'app_subscribed' => $appSubscribed5y,
        'web_registered' => $webRegistered5y,
        'web_subscribed' => $webSubscribed5y
    ]
];
$data['app_vs_web_chart_data'] = $app_vs_web_chart_data;
// ******* End App vs Web Chart Data *******


// ******* Start Course Performance Data *******

$labelsAll = [];
$enrolledAll = [];
$subscribedAll = [];
$enrolledTop = [];
$subscribedTop = [];
$enrolledBottom = [];
$subscribedBottom = [];

$course_groups = getQuery("course_group", "*", array(), "ord");

foreach ($course_groups as $group) {

    $courses = getQuery("course", "*", array("group_id" => $group['group_id']), "ord");

    foreach ($courses as $course) {

        $labelsAll[] = $course['course_name'];
        $course_id = $course['course_id'];

        // Weekly data
        $start = date('Y-m-d', strtotime('-6 days'));
        $end   = date('Y-m-d');
        $enrolledAll[] = (int)getDataByJoin(
            ["subscription as s", "user as u"],
            "COUNT(*) as total",
            "s.subscription_date BETWEEN '$start' AND '$end'
            AND s.course_id = $course_id
            AND s.user_id = u.user_id"
        )['total'];
        $subscribedAll[] = (int)getDataByJoin(
            ["subscription as s", "user as u"],
            "COUNT(*) as total",
            "s.subscription_date BETWEEN '$start' AND '$end'
            AND s.course_id = $course_id
            AND s.amount > 0
            AND s.user_id = u.user_id"
        )['total'];

        // Monthly data
        $start = date('Y-m-d', strtotime('-30 days'));
        $end   = date('Y-m-d');
        $enrolledTop[] = (int)getDataByJoin(
            ["subscription as s", "user as u"],
            "COUNT(*) as total",
            "s.subscription_date BETWEEN '$start' AND '$end'
            AND s.course_id = $course_id
            AND s.user_id = u.user_id"
        )['total'];
        $subscribedTop[] = (int)getDataByJoin(
            ["subscription as s", "user as u"],
            "COUNT(*) as total",
            "s.subscription_date BETWEEN '$start' AND '$end'
            AND s.course_id = $course_id
            AND s.amount > 0
            AND s.user_id = u.user_id"
        )['total'];

        // Yearly data
        $start = date('Y-m-d', strtotime('-365 days'));
        $end   = date('Y-m-d');
        $enrolledBottom[] = (int)getDataByJoin(
            ["subscription as s", "user as u"],
            "COUNT(*) as total",
            "s.subscription_date BETWEEN '$start' AND '$end'
            AND s.course_id = $course_id
            AND s.user_id = u.user_id"
        )['total'];
        $subscribedBottom[] = (int)getDataByJoin(
            ["subscription as s", "user as u"],
            "COUNT(*) as total",
            "s.subscription_date BETWEEN '$start' AND '$end'
            AND s.course_id = $course_id
            AND s.amount > 0
            AND s.user_id = u.user_id"
        )['total'];
    }
}

$course_chart_data = [
    'all' => [
        'labels' => $labelsAll,
        'enrolled' => $enrolledAll,
        'subscribed' => $subscribedAll
    ],
    'top' => [
        'labels' => $labelsAll,
        'enrolled' => $enrolledTop,
        'subscribed' => $subscribedTop
    ],
    'bottom' => [
        'labels' => $labelsAll,
        'enrolled' => $enrolledBottom,
        'subscribed' => $subscribedBottom
    ]
];

$data['course_chart_data'] = $course_chart_data;
// ********* end course chart data ***********


// ******** start login pie chart ************
$login_app_month = (int)getDataByJoin(
    ["login as l", "user as u"],
    "COUNT(*) as total",
    "l.user_id = u.user_id
     AND MONTH(l.last_login) = $currentMonth
     AND YEAR(l.last_login) = $currentYear
     AND u.reg_at LIKE '%app%'"
)['total'];

$login_web_month = (int)getDataByJoin(
    ["login as l", "user as u"],
    "COUNT(*) as total",
    "l.user_id = u.user_id
     AND MONTH(l.last_login) = $currentMonth
     AND YEAR(l.last_login) = $currentYear
     AND u.reg_at LIKE '%web%'"
)['total'];

$data['login_pie_data'] = [
    'app' => $login_app_month,
    'web' => $login_web_month
];
// ********* end login pie chart **********

echo template($data);
?>