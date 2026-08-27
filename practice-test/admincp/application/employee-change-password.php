<?php
include("../common/access.php");
include("../common/autoload.php");

$data['breadcrumb'] = array("My Attendance" => "employee-attendance-form", "Change password" => "");

$employee_id = isset($_SESSION['employee_id']) ? (int) $_SESSION['employee_id'] : 0;
$employee    = $employee_id ? getDataByWhere("employee", "*", array("id" => $employee_id)) : null;

if (!$employee) $employee_id = 0;

$errors  = array();
$success = false;

if ($employee_id && $_SERVER['REQUEST_METHOD'] === 'POST') {

	$current = (string) ($_POST['current_password'] ?? '');
	$new     = (string) ($_POST['new_password'] ?? '');
	$confirm = (string) ($_POST['confirm_password'] ?? '');

	if ($current === '' || md5($current) !== $employee['password']) {
		$errors['current_password'] = "That's not your current password.";
	}

	if (strlen($new) < 8) {
		$errors['new_password'] = "New password must be at least 8 characters.";
	} elseif ($new === $current) {
		$errors['new_password'] = "New password must be different from your current one.";
	}

	if ($new !== $confirm) {
		$errors['confirm_password'] = "Passwords don't match.";
	}

	if (empty($errors)) {
		updateTable("employee", array(
			"password"            => md5($new),
			"must_reset_password" => "N",
		), array("id" => $employee_id));

		$success = true;
		$employee['must_reset_password'] = 'N';
	}
}

$data['employee_id'] = $employee_id;
$data['errors']       = $errors;
$data['success']      = $success;
$data['forced']       = $employee && $employee['must_reset_password'] === 'Y';

echo template($data);
