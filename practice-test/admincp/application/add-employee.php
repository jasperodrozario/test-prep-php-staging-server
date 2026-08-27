<?php
include("../common/access.php");
include("../common/autoload.php");

$genderOptions = array(
	"unspecified" => "Not specified",
	"male"        => "Male",
	"female"      => "Female",
	"other"       => "Other",
);

$employmentTypes = array(
	"full_time" => "Full-time",
	"part_time" => "Part-time",
	"contract"  => "Contract",
);

$statusOptions = array(
	"active"   => "Active",
	"inactive" => "Inactive",
);

$departments = array("Engineering", "Content", "Tutoring", "Support", "Marketing");

function isValidDate($str)
{
	if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $str)) return false;
	return checkdate((int) substr($str, 5, 2), (int) substr($str, 8, 2), (int) substr($str, 0, 4));
}

function fieldTaken($configs, $field, $value, $excludeId = 0)
{
	$esc = mysqli_real_escape_string($configs['con'], $value);
	$sql = "select count(*) as c from " . $configs['prev'] . "employee where " . $field . " = '" . $esc . "'";
	if ($excludeId) $sql .= " and id != " . (int) $excludeId;
	$r = mysqli_query($configs['con'], $sql);
	$row = $r ? mysqli_fetch_assoc($r) : array('c' => 0);
	return (int) $row['c'] > 0;
}

$edit_id  = 0;
$existing = null;

if (isset($_POST['employee_id']) && (int) $_POST['employee_id'] > 0) {
	$edit_id = (int) $_POST['employee_id'];
} elseif (isset($_GET['id']) && (int) $_GET['id'] > 0) {
	$edit_id = (int) $_GET['id'];
}

if ($edit_id) {
	$existing = getDataByWhere("employee", "*", array("id" => $edit_id));
	if (!$existing) $edit_id = 0;
}

$data['breadcrumb'] = $edit_id
	? array("Employees" => "employee-list", "Edit employee" => "")
	: array("Employees" => "employee-list", "Add employee" => "");

$form = array(
	"first_name"      => "",
	"last_name"       => "",
	"email"           => "",
	"phone"           => "",
	"dob"             => "",
	"gender"          => "unspecified",
	"address"         => "",
	"designation"     => "",
	"department"      => "",
	"employment_type" => "full_time",
	"date_of_joining" => "",
	"username"        => "",
	"status"          => "active",
);

if ($existing && $_SERVER['REQUEST_METHOD'] !== 'POST') {
	$parts = explode(" ", $existing['full_name'], 2);
	$form['first_name']      = $parts[0];
	$form['last_name']       = isset($parts[1]) ? $parts[1] : "";
	$form['email']           = $existing['email'];
	$form['phone']           = $existing['phone'];
	$form['dob']             = $existing['dob'];
	$form['gender']          = $existing['gender'];
	$form['address']         = $existing['address'];
	$form['designation']     = $existing['designation'];
	$form['department']      = $existing['department'];
	$form['employment_type'] = $existing['employment_type'];
	$form['date_of_joining'] = $existing['date_of_joining'];
	$form['username']        = $existing['username'];
	$form['status']          = $existing['status'];
}

$errors = array();
$saved  = false;

$temp_password = !empty($_POST['temp_password_carry']) ? $_POST['temp_password_carry'] : getPassword(10);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	foreach ($form as $k => $v) {
		if (isset($_POST[$k])) $form[$k] = trim($_POST[$k]);
	}

	if (!array_key_exists($form['gender'], $genderOptions)) {
		$form['gender'] = 'unspecified';
	}
	if (!array_key_exists($form['employment_type'], $employmentTypes)) {
		$form['employment_type'] = 'full_time';
	}
	if (!array_key_exists($form['status'], $statusOptions)) {
		$form['status'] = 'active';
	}

	if ($form['first_name'] === '') $errors['first_name'] = "First name is required.";
	if ($form['last_name'] === '')  $errors['last_name']  = "Last name is required.";

	if ($form['email'] === '') {
		$errors['email'] = "Email address is required.";
	} elseif (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
		$errors['email'] = "Enter a valid email address.";
	} elseif (fieldTaken($configs, 'email', $form['email'], $edit_id)) {
		$errors['email'] = "This email is already in use.";
	}

	if ($form['designation'] === '') $errors['designation'] = "Designation is required.";

	if (!in_array($form['department'], $departments, true)) $errors['department'] = "Select a department.";

	if (!isValidDate($form['date_of_joining'])) $errors['date_of_joining'] = "Enter a valid date of joining.";

	if ($form['username'] === '') {
		$errors['username'] = "Username is required.";
	} elseif (fieldTaken($configs, 'username', $form['username'], $edit_id)) {
		$errors['username'] = "This username is already taken.";
	}

	if (!empty($_FILES['photo']['name'])) {
		if (!in_array($_FILES['photo']['type'], array('image/jpeg', 'image/png'), true)) {
			$errors['photo'] = "Only JPG or PNG images are allowed.";
		} elseif ($_FILES['photo']['size'] > 2 * 1024 * 1024) {
			$errors['photo'] = "Photo must be under 2MB.";
		}
	}

	if (empty($errors)) {

		$post = array(
			"full_name"       => mysqli_real_escape_string($configs['con'], $form['first_name'] . " " . $form['last_name']),
			"email"           => mysqli_real_escape_string($configs['con'], $form['email']),
			"phone"           => mysqli_real_escape_string($configs['con'], $form['phone']),
			"gender"          => $form['gender'],
			"address"         => mysqli_real_escape_string($configs['con'], $form['address']),
			"designation"     => mysqli_real_escape_string($configs['con'], $form['designation']),
			"department"      => $form['department'],
			"employment_type" => $form['employment_type'],
			"date_of_joining" => $form['date_of_joining'],
			"username"        => mysqli_real_escape_string($configs['con'], $form['username']),
			"status"          => $form['status'],
		);

		if (isValidDate($form['dob'])) {
			$post['dob'] = $form['dob'];
		} elseif ($edit_id) {
			$post['dob'] = null;
		}

		if ($edit_id) {

			$resetPassword = !empty($_POST['reset_password']);
			if ($resetPassword) {
				$post['password']            = md5($temp_password);
				$post['must_reset_password'] = 'Y';
			}

			updateTable("employee", $post, array("id" => $edit_id), array("photo"));

			$saved                       = true;
			$data['saved_employee_code'] = $existing['employee_code'];
			$data['saved_username']      = $form['username'];
			if ($resetPassword) {
				$data['saved_password'] = $temp_password;
			}
		} else {

			$post['password']            = md5($temp_password);
			$post['must_reset_password'] = 'Y';

			$employee_id = insertTable("employee", $post, array("photo"));

			if ($employee_id) {
				$employee_code = "EMP-" . str_pad($employee_id, 4, "0", STR_PAD_LEFT);
				updateTable("employee", array("employee_code" => $employee_code), array("id" => $employee_id));

				$saved                       = true;
				$data['saved_username']      = $form['username'];
				$data['saved_password']      = $temp_password;
				$data['saved_employee_code'] = $employee_code;
			} else {
				$errors['general'] = "Something went wrong while saving. Please try again.";
			}
		}

		if ($saved && $edit_id && empty($data['saved_password'])) {
			header("location: employee-list?updated=1");
			exit;
		}
	}
}

$data['errors']          = $errors;
$data['form']             = $form;
$data['temp_password']    = $temp_password;
$data['genderOptions']    = $genderOptions;
$data['employmentTypes']  = $employmentTypes;
$data['statusOptions']    = $statusOptions;
$data['departments']      = $departments;
$data['saved']            = $saved;
$data['edit_id']          = $edit_id;
$data['existing_photo']   = $existing ? $existing['photo'] : '';
$data['employee_code']    = $existing ? $existing['employee_code'] : '';

echo template($data);
