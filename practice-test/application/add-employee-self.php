<?php
include("../configs/autoload.php");

function fieldTaken($configs, $field, $value)
{
	$esc = mysqli_real_escape_string($configs['con'], $value);
	$sql = "select count(*) as c from " . $configs['prev'] . "employee where " . $field . " = '" . $esc . "'";
	$r = mysqli_query($configs['con'], $sql);
	$row = $r ? mysqli_fetch_assoc($r) : array('c' => 0);
	return (int) $row['c'] > 0;
}

$form = array(
	"full_name" => "",
	"email"     => "",
	"username"  => "",
);

$errors = array();
$saved  = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	foreach ($form as $k => $v) {
		if (isset($_POST[$k])) $form[$k] = trim($_POST[$k]);
	}
	$password = isset($_POST['password']) ? trim($_POST['password']) : '';

	if ($form['full_name'] === '') $errors['full_name'] = "Name is required.";

	if ($form['email'] === '') {
		$errors['email'] = "Email address is required.";
	} elseif (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
		$errors['email'] = "Enter a valid email address.";
	} elseif (fieldTaken($configs, 'email', $form['email'])) {
		$errors['email'] = "This email is already registered.";
	}

	if ($form['username'] === '') {
		$errors['username'] = "Username is required.";
	} elseif (fieldTaken($configs, 'username', $form['username'])) {
		$errors['username'] = "This username is already taken.";
	}

	if ($password === '') {
		$errors['password'] = "Password is required.";
	} elseif (strlen($password) < 6) {
		$errors['password'] = "Password must be at least 6 characters.";
	}

	if (empty($errors)) {

		$post = array(
			"full_name"           => mysqli_real_escape_string($configs['con'], $form['full_name']),
			"email"               => mysqli_real_escape_string($configs['con'], $form['email']),
			"username"            => mysqli_real_escape_string($configs['con'], $form['username']),
			"password"            => md5($password),
			"must_reset_password" => "N",
			"status"              => "inactive",
		);

		$employee_id = insertTable("employee", $post);

		if ($employee_id) {
			$employee_code = "EMP-" . str_pad($employee_id, 4, "0", STR_PAD_LEFT);
			updateTable("employee", array("employee_code" => $employee_code), array("id" => $employee_id));

			$saved = true;
		} else {
			$errors['general'] = "Something went wrong while saving. Please try again.";
		}
	}
}

$data['form']   = $form;
$data['errors'] = $errors;
$data['saved']  = $saved;

unset($configs['includes']);
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Create your employee profile</title>
</head>
<body style="margin:0;background:#F5F6F8;">
<?php echo template($data); ?>
</body>
</html>
<?php
