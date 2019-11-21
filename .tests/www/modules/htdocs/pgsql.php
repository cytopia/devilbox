<?php
/**
 * This page should print 'OK' if everything works,
 * 'FAIL' or nothing if an error occured.
 */
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);


$MY_HOST = 'pgsql';
$MY_USER = getenv('PGSQL_ROOT_USER');
$MY_PASS = getenv('PGSQL_ROOT_PASSWORD');

$link = pg_connect('host='.$MY_HOST.' dbname=postgres user='.$MY_USER.' password='.$MY_PASS);

if (!$link || pg_connection_status($link) !== PGSQL_CONNECTION_OK) {
	echo 'FAIL';
	exit(1);
}

$query = "SELECT name, setting FROM pg_settings;";
if (!$result = pg_query($link, $query)) {
	echo 'FAIL';
	exit(1);
}

while ($row = pg_fetch_assoc($result)) {
	$data[] = $row;
}
pg_free_result($result);
pg_close($link);

if (count($data) > 0) {
	echo 'OK';
	exit(0);
} else {
	echo 'FAIL';
	exit(1);
}
