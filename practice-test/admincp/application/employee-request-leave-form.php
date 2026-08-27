<?php
include("../common/access.php");
include("../common/autoload.php");

$data['breadcrumb'] = array("Request Leave" => "");

$leaveTypes = array(
	"Casual leave" => 12,
	"Sick leave"   => 10,
	"Annual leave" => 15,
	"Unpaid leave" => null, // null = no fixed allotment
);

$employee_id = isset($_SESSION['employee_id']) ? (int) $_SESSION['employee_id'] : 0;

// A real admin session (not the negative admin_id sentinel employees get) with
// the super-admin role is the only one allowed to approve/reject requests.
$isAdminReviewer = empty($_SESSION['employee_id'])
	&& !empty($_SESSION['admin_id']) && (int) $_SESSION['admin_id'] > 0
	&& isset($_SESSION['type']) && $_SESSION['type'] === 'S';

function countWorkingDays($start, $end)
{
	$days = 0;
	$d = new DateTime($start);
	$last = new DateTime($end);
	while ($d <= $last) {
		if ((int) $d->format('N') < 6) $days++;
		$d->modify('+1 day');
	}
	return $days;
}

function leaveDaysUsed($configs, $employee_id, $leave_type, $year)
{
	$esc = mysqli_real_escape_string($configs['con'], $leave_type);
	$sql = "select coalesce(sum(total_days),0) as used from " . $configs['prev'] . "leave_application
		where employee_id = " . (int) $employee_id . " and leave_type = '$esc' and status in ('approved','pending')
		and year(start_date) = " . (int) $year;
	$r = mysqli_query($configs['con'], $sql);
	$row = $r ? mysqli_fetch_assoc($r) : array('used' => 0);
	return (float) $row['used'];
}

$errors = array();
$form = array(
	'leave_type' => '',
	'start_date' => '',
	'end_date'   => '',
	'reason'     => '',
);
$submitted = false;

if ($employee_id && $_SERVER['REQUEST_METHOD'] === 'POST') {

	foreach ($form as $k => $v) {
		if (isset($_POST[$k])) $form[$k] = trim($_POST[$k]);
	}

	if (!array_key_exists($form['leave_type'], $leaveTypes)) {
		$errors['leave_type'] = "Select a leave type.";
	}

	$startOk = (bool) DateTime::createFromFormat('Y-m-d', $form['start_date']);
	$endOk   = (bool) DateTime::createFromFormat('Y-m-d', $form['end_date']);

	if (!$startOk) $errors['start_date'] = "Enter a valid start date.";
	if (!$endOk)   $errors['end_date']   = "Enter a valid end date.";

	if ($startOk && $endOk && $form['end_date'] < $form['start_date']) {
		$errors['end_date'] = "End date can't be before the start date.";
	}

	if ($form['reason'] === '') $errors['reason'] = "Let your manager know why you're requesting leave.";

	if (empty($errors)) {
		$totalDays = countWorkingDays($form['start_date'], $form['end_date']);
		$allotment = $leaveTypes[$form['leave_type']];

		if ($allotment !== null) {
			$year = (int) substr($form['start_date'], 0, 4);
			$used = leaveDaysUsed($configs, $employee_id, $form['leave_type'], $year);
			$remaining = $allotment - $used;

			if ($totalDays > $remaining) {
				$errors['leave_type'] = "This request is for $totalDays working day" . ($totalDays > 1 ? 's' : '') . ", but you only have " . max(0, $remaining) . " day" . ($remaining == 1 ? '' : 's') . " of {$form['leave_type']} left.";
			}
		}

		if (empty($errors)) {
			insertTable("leave_application", array(
				"employee_id" => $employee_id,
				"leave_type"  => $form['leave_type'],
				"start_date"  => $form['start_date'],
				"end_date"    => $form['end_date'],
				"total_days"  => $totalDays,
				"reason"      => mysqli_real_escape_string($configs['con'], $form['reason']),
				"status"      => "pending",
			));

			header("location: employee-request-leave-form?submitted=1");
			exit;
		}
	}
}

if ($isAdminReviewer && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review_action'])) {

	$leaveId = (int) $_POST['leave_id'];
	$action  = $_POST['review_action'] === 'approve' ? 'approved' : ($_POST['review_action'] === 'reject' ? 'rejected' : '');
	$comment = trim((string) ($_POST['review_comment'] ?? ''));

	if ($leaveId && $action) {
		$existing = getDataByWhere("leave_application", "*", array("id" => $leaveId));
		if ($existing && $existing['status'] === 'pending') {
			updateTable("leave_application", array(
				"status"         => $action,
				"reviewed_by"    => (int) $_SESSION['admin_id'],
				"review_comment" => mysqli_real_escape_string($configs['con'], $comment),
			), array("id" => $leaveId));
		}
	}

	$backTo = !empty($_POST['status_filter']) ? "?status=" . urlencode($_POST['status_filter']) : "";
	header("location: employee-request-leave-form" . $backTo);
	exit;
}

if (!empty($_GET['submitted'])) $submitted = true;

$data['leaveTypes']  = $leaveTypes;
$data['errors']      = $errors;
$data['form']        = $form;
$data['submitted']   = $submitted;
$data['employee_id'] = $employee_id;
$data['isAdminReviewer'] = $isAdminReviewer;

if ($isAdminReviewer) {
	$reviewStatus = isset($_GET['status']) && in_array($_GET['status'], array('all', 'pending', 'approved', 'rejected'), true) ? $_GET['status'] : 'pending';

	$reviewRequests = array();
	foreach (getQueryByJoin(
		array("leave_application as la", "employee as e"),
		"la.*, e.full_name, e.employee_code",
		$reviewStatus === 'all' ? array() : array("la.status" => $reviewStatus),
		array("la.employee_id" => "e.id"),
		"la.created_at desc"
	) as $r) {
		$reviewRequests[] = $r;
	}

	$data['reviewRequests'] = $reviewRequests;
	$data['reviewStatus']   = $reviewStatus;
	$data['pendingCount']   = getTotalRowsByJoin(array("leave_application as la", "employee as e"), array("la.status" => "pending"), array("la.employee_id" => "e.id"));
}

if ($employee_id) {
	$employee = getDataByWhere("employee", "*", array("id" => $employee_id));
	$data['mustResetPassword'] = $employee && $employee['must_reset_password'] === 'Y';

	$year = (int) date('Y');
	$balances = array();
	foreach ($leaveTypes as $type => $allotment) {
		if ($allotment === null) {
			$balances[$type] = array('allotment' => null, 'used' => 0, 'remaining' => null);
			continue;
		}
		$used = leaveDaysUsed($configs, $employee_id, $type, $year);
		$balances[$type] = array(
			'allotment' => $allotment,
			'used'      => $used,
			'remaining' => max(0, $allotment - $used),
		);
	}
	$data['balances'] = $balances;

	$requests = array();
	foreach (getQuery("leave_application", "*", array("employee_id" => $employee_id), "created_at desc") as $r) {
		$requests[] = $r;
	}
	$data['requests'] = $requests;
}

echo template($data);
