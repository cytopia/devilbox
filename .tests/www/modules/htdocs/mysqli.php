<?php
/**
 * This page should print 'OK' if everything works,
 * 'FAIL' or nothing if an error occured.
 */
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);


$MY_HOST = 'mysql';
$MY_USER = 'root';
$MY_PASS = getenv('MYSQL_ROOT_PASSWORD');

$link = mysqli_connect($MY_HOST, $MY_USER, $MY_PASS, 'mysql');

if (mysqli_connect_errno()) {
	echo 'FAIL';
	exit(1);
}

$query = "SELECT * FROM `user` WHERE `User` = 'root';";
if (!$result = mysqli_query($link, $query)) {
	echo 'FAIL';
	exit(1);
}

while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
	$data[] = $row;
}
mysqli_free_result($result);
mysqli_close($link);

if (!isset($data[0])) {
	echo 'FAIL';
	exit(1);
}
if ($data[0]['User'] == 'root') {
	echo 'OK';
	exit(0);
} else {
	echo 'FAIL';
	exit(1);
}
