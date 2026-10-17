<?php
include("../common/access.php");
include("../common/autoload.php");

$data['breadcrumb'] = array("App Score Reviews" => "");

$ratingLabels = array(
	"very_helpful"     => "Very helpful",
	"somewhat_helpful" => "Somewhat helpful",
	"not_helpful"      => "Not helpful",
);
$featureLabels = array(
	"full_length_mock_exams"   => "Full-length mock exams",
	"subtest_practice_drills"  => "Subtest practice drills",
	"sentence_word_flashcards" => "Sentence & word flashcards",
	"performance_analytics"    => "Performance analytics",
);
$platforms = array("android" => "Android", "ios" => "iOS");

function buildQS($overrides = array())
{
	$params = array_merge($_GET, $overrides);
	unset($params['home_limit'], $params['deleted'], $params['delete_error']); // home_limit is injected by configs/config.php
	foreach ($params as $k => $v) {
		if ($v === '' || $v === null) unset($params[$k]);
	}
	return http_build_query($params);
}

$con = $configs['con'];
$reviewTable = $configs['prev'] . "app_review";
$userTable   = $configs['prev'] . "user";

// ---------- Delete permission ----------
// Super admins, or admins granted this page's menu item.
$canDelete = ($_SESSION['type'] ?? '') === "S";
if (!$canDelete) {
	$menuId = (int) getFldValueById("adminmenu", "id", array("url" => "app-review-list"));
	$canDelete = $menuId > 0 && getTotalRow("admin_grant", array("admin_id" => (int) $_SESSION['admin_id'], "proghead_id" => $menuId)) > 0;
}

if (empty($_SESSION['app_review_csrf'])) {
	$_SESSION['app_review_csrf'] = bin2hex(random_bytes(16));
}

// ---------- Delete (POST only, confirmed in the browser) ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
	$returnQS = isset($_POST['return_qs']) ? (string) $_POST['return_qs'] : '';
	parse_str($returnQS, $returnParams);
	unset($returnParams['id'], $returnParams['deleted'], $returnParams['delete_error']);

	if (!$canDelete) {
		$returnParams['delete_error'] = 'denied';
	} elseif (!hash_equals($_SESSION['app_review_csrf'], (string) ($_POST['csrf'] ?? '')) || ($_POST['confirmed'] ?? '') !== '1') {
		$returnParams['delete_error'] = 'invalid';
	} else {
		$ids = array_unique(array_filter(array_map('intval', (array) ($_POST['review_ids'] ?? array()))));
		$deleted = 0;
		foreach ($ids as $id) {
			if ($id > 0 && deleteData("app_review", array("review_id" => $id))) {
				$deleted += mysqli_affected_rows($con) > 0 ? 1 : 0;
			}
		}
		if ($ids) {
			$returnParams['deleted'] = $deleted;
		} else {
			$returnParams['delete_error'] = 'none';
		}
	}

	header("Location: app-review-list" . ($returnParams ? '?' . http_build_query($returnParams) : ''));
	exit;
}

if (isset($_GET['deleted'])) {
	$n = (int) $_GET['deleted'];
	$data['flash'] = array('ok', $n === 1 ? "1 score review deleted." : "$n score reviews deleted.");
} elseif (isset($_GET['delete_error'])) {
	$errors = array(
		'denied'  => "You don't have permission to delete score reviews.",
		'invalid' => "Delete request expired or wasn't confirmed. Nothing was deleted.",
		'none'    => "No reviews were selected. Nothing was deleted.",
	);
	$data['flash'] = array('err', $errors[$_GET['delete_error']] ?? "Nothing was deleted.");
}

$data['canDelete'] = $canDelete;
$data['csrf']      = $_SESSION['app_review_csrf'];

// ---------- Detail view ----------
$viewId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($viewId > 0) {
	$result = mysqli_query($con, "select r.*, u.first_name, u.last_name, u.mobile from $reviewTable r left join $userTable u on u.user_id = r.user_id where r.review_id = $viewId limit 1");
	$data['review'] = $result ? mysqli_fetch_assoc($result) : null;
}

// ---------- Filters ----------
$courses = array();
$result = mysqli_query($con, "select distinct course_name from $reviewTable where course_name is not null and course_name <> '' order by course_name");
while ($result && $row = mysqli_fetch_assoc($result)) {
	$courses[] = $row['course_name'];
}

$search   = isset($_GET['q']) ? trim($_GET['q']) : '';
$course   = isset($_GET['course']) && in_array($_GET['course'], $courses, true) ? $_GET['course'] : '';
$rating   = isset($_GET['rating']) && isset($ratingLabels[$_GET['rating']]) ? $_GET['rating'] : '';
$platform = isset($_GET['platform']) && isset($platforms[$_GET['platform']]) ? $_GET['platform'] : '';
$share    = isset($_GET['share']) && in_array($_GET['share'], array('1', '0'), true) ? $_GET['share'] : '';
$feature  = isset($_GET['feature']) && isset($featureLabels[$_GET['feature']]) ? $_GET['feature'] : '';
$from     = isset($_GET['from']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['from']) ? $_GET['from'] : '';
$to       = isset($_GET['to']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['to']) ? $_GET['to'] : '';
$sort     = isset($_GET['sort']) && in_array($_GET['sort'], array('recent', 'oldest', 'score_high', 'score_low', 'improvement'), true) ? $_GET['sort'] : 'recent';

$whereParts = array();
if ($search !== '') {
	$esc = mysqli_real_escape_string($con, $search);
	$idCond = ctype_digit($search) ? " or r.review_id = " . (int) $search . " or r.user_id = " . (int) $search : "";
	$whereParts[] = "(r.email like '%$esc%' or r.experience_text like '%$esc%' or concat_ws(' ', u.first_name, u.last_name) like '%$esc%'$idCond)";
}
if ($course !== '') {
	$whereParts[] = "r.course_name = '" . mysqli_real_escape_string($con, $course) . "'";
}
if ($rating !== '') {
	$whereParts[] = "r.helpfulness_rating = '" . $rating . "'";
}
if ($platform !== '') {
	$whereParts[] = "r.platform = '" . $platform . "'";
}
if ($share !== '') {
	$whereParts[] = $share === '1' ? "r.share_permission = 1" : "(r.share_permission = 0 or r.share_permission is null)";
}
if ($feature !== '') {
	$whereParts[] = "find_in_set('" . $feature . "', r.features_used)";
}
if ($from !== '') {
	$whereParts[] = "r.submitted_at >= '" . $from . " 00:00:00'";
}
if ($to !== '') {
	$whereParts[] = "r.submitted_at <= '" . $to . " 23:59:59'";
}
$whereSql = count($whereParts) ? implode(" and ", $whereParts) : "1=1";

$orderBy = "r.submitted_at desc, r.review_id desc";
if ($sort === 'oldest')      $orderBy = "r.submitted_at asc, r.review_id asc";
if ($sort === 'score_high')  $orderBy = "r.final_actual_score desc, r.review_id desc";
if ($sort === 'score_low')   $orderBy = "r.final_actual_score asc, r.review_id desc";
if ($sort === 'improvement') $orderBy = "(r.initial_practice_score is null), (cast(r.final_actual_score as signed) - cast(r.initial_practice_score as signed)) desc";

$perPageOptions = array(10, 20, 50, 100);
$perPage = isset($_GET['per_page']) && in_array((int) $_GET['per_page'], $perPageOptions, true) ? (int) $_GET['per_page'] : 20;
$page   = isset($_GET['limit']) ? max(1, (int) $_GET['limit']) : 1;
$offset  = ($page - 1) * $perPage;

$fromSql = "from $reviewTable r left join $userTable u on u.user_id = r.user_id where " . $whereSql;

$countResult = mysqli_query($con, "select count(*) as c " . $fromSql);
$total = $countResult ? (int) mysqli_fetch_assoc($countResult)['c'] : 0;

$page   = min($page, max(1, (int) ceil($total / $perPage)));
$offset = ($page - 1) * $perPage;

$reviews = array();
$result = mysqli_query($con, "select r.*, u.first_name, u.last_name " . $fromSql . " order by " . $orderBy . " limit $offset, $perPage");
while ($result && $row = mysqli_fetch_assoc($result)) {
	$reviews[] = $row;
}

// ---------- Stats (whole table) ----------
$statResult = mysqli_query($con, "select count(*) as total, sum(helpfulness_rating = 'very_helpful') as very_helpful, sum(share_permission = 1) as shareable, sum(submitted_at >= '" . date('Y-m-01') . " 00:00:00') as this_month from $reviewTable");
$stats = $statResult ? mysqli_fetch_assoc($statResult) : array();

$data['reviews']       = $reviews;
$data['courses']       = $courses;
$data['ratingLabels']  = $ratingLabels;
$data['featureLabels'] = $featureLabels;
$data['platforms']     = $platforms;

$data['stat_total']        = (int) ($stats['total'] ?? 0);
$data['stat_very_helpful'] = (int) ($stats['very_helpful'] ?? 0);
$data['stat_shareable']    = (int) ($stats['shareable'] ?? 0);
$data['stat_this_month']   = (int) ($stats['this_month'] ?? 0);

$data['search']   = $search;
$data['course']   = $course;
$data['rating']   = $rating;
$data['platform'] = $platform;
$data['share']    = $share;
$data['feature']  = $feature;
$data['from']     = $from;
$data['to']       = $to;
$data['sort']     = $sort;
$data['page']     = $page;
$data['perPage']  = $perPage;
$data['perPageOptions'] = $perPageOptions;
$data['total']    = $total;
$data['viewId']   = $viewId;

echo template($data);
