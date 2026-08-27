<?php
include("../common/access.php");
include("../common/autoload.php");

$data['breadcrumb'] = array("Employees" => "employee-list", "Govt. Holidays" => "");

$employee_id     = isset($_SESSION['employee_id']) ? (int) $_SESSION['employee_id'] : 0;
$actorId         = (int) $_SESSION['admin_id'];
$actorName       = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown';
$defaultCountryId = (int) $configs['country_id'];

function holidaysRedirectQS()
{
	$qs = array();
	if (!empty($_POST['year'])) $qs['year'] = (int) $_POST['year'];
	if (isset($_POST['filter_country']) && $_POST['filter_country'] !== '') $qs['country'] = (int) $_POST['filter_country'];
	return count($qs) ? '?' . http_build_query($qs) : '';
}

$errors = array();
$form   = array('id' => '', 'holiday_date' => '', 'country_id' => '', 'title' => '', 'description' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_holiday'])) {

	$form['id']           = (int) ($_POST['id'] ?? 0);
	$form['holiday_date'] = trim($_POST['holiday_date'] ?? '');
	$form['country_id']   = (int) ($_POST['country_id'] ?? 0);
	$form['title']        = trim($_POST['title'] ?? '');
	$form['description']  = trim($_POST['description'] ?? '');

	if (!DateTime::createFromFormat('Y-m-d', $form['holiday_date'])) {
		$errors['holiday_date'] = "Enter a valid date.";
	}
	if ($form['title'] === '') {
		$errors['title'] = "Give the holiday a name.";
	}

	$country = $form['country_id'] ? getDataByWhere("country", "id, name, code", array("id" => $form['country_id'])) : null;
	if (!$country) {
		$errors['country_id'] = "Select a country.";
	}

	if (empty($errors)) {
		$payload = array(
			"holiday_date" => $form['holiday_date'],
			"country_id"   => $country['id'],
			"country_name" => mysqli_real_escape_string($configs['con'], $country['name']),
			"country_code" => mysqli_real_escape_string($configs['con'], $country['code']),
			"title"        => mysqli_real_escape_string($configs['con'], $form['title']),
			"description"  => mysqli_real_escape_string($configs['con'], $form['description']),
		);

		if ($form['id']) {
			$existing = getDataByWhere("govt_holiday", "*", array("id" => $form['id']));
			if ($existing) {
				$payload['updated_by_id']   = $actorId;
				$payload['updated_by_name'] = mysqli_real_escape_string($configs['con'], $actorName);
				updateTable("govt_holiday", $payload, array("id" => $form['id']));
			}
		} else {
			$payload['created_by_id']   = $actorId;
			$payload['created_by_name'] = mysqli_real_escape_string($configs['con'], $actorName);
			insertTable("govt_holiday", $payload);
		}

		header("location: govt-holidays" . holidaysRedirectQS());
		exit;
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_holiday'])) {
	$delId = (int) $_POST['id'];
	if ($delId) deleteData("govt_holiday", array("id" => $delId));

	header("location: govt-holidays" . holidaysRedirectQS());
	exit;
}

$yearParam    = isset($_GET['year']) && preg_match('/^\d{4}$/', $_GET['year']) ? (int) $_GET['year'] : (int) date('Y');
$countryParam = isset($_GET['country']) ? (int) $_GET['country'] : 0; // 0 = all countries

$years = array();
foreach (getQuery("govt_holiday", "distinct year(holiday_date) as y", array(), "y desc") as $row) {
	$years[] = (int) $row['y'];
}
if (!in_array((int) date('Y'), $years, true)) array_unshift($years, (int) date('Y'));
if (!in_array($yearParam, $years, true)) array_unshift($years, $yearParam);
rsort($years);

$usedCountries = array();
foreach (getQuery("govt_holiday", "distinct country_id, country_name", array(), "country_name asc") as $row) {
	$usedCountries[(int) $row['country_id']] = $row['country_name'];
}
if (!isset($usedCountries[$defaultCountryId])) {
	$defC = getDataByWhere("country", "name", array("id" => $defaultCountryId));
	$usedCountries[$defaultCountryId] = $defC ? $defC['name'] : 'United States';
}
asort($usedCountries);

$allCountries = array();
foreach (getQuery("country", "id, name, code", array("status" => "Y"), "name asc") as $c) {
	$allCountries[] = $c;
}

$whereSql = "year(holiday_date) = " . (int) $yearParam;
if ($countryParam) $whereSql .= " and country_id = " . (int) $countryParam;

$holidays = array();
foreach (getQuery("govt_holiday", "*", $whereSql, "country_name asc, holiday_date asc") as $r) {
	$holidays[] = $r;
}

$data['holidays']         = $holidays;
$data['years']            = $years;
$data['year']             = $yearParam;
$data['usedCountries']    = $usedCountries;
$data['allCountries']     = $allCountries;
$data['countryParam']     = $countryParam;
$data['defaultCountryId'] = $defaultCountryId;
$data['today']            = date('Y-m-d');
$data['employee_id']      = $employee_id;

echo template($data);
