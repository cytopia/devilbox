<?php
/**
 * This page should print 'OK' if everything works,
 * 'FAIL' or nothing if an error occured.
 */
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);


$MY_HOST = 'redis';
$MY_PORT = '6379';

$MY_KEY = 'test';
$MY_VAL = 'OK';


$redis = new \Redis();
if (!$redis->connect($MY_HOST, $MY_PORT, 0.5, NULL)) {
	echo 'FAIL';
	exit(1);
}

$redis->set($MY_KEY, $MY_VAL);

foreach ($redis->info('all') as $key => $val) {
	if (preg_match('/db[0-9]+/', $key)) {
		$database = str_replace('db', '', $key);
		$redis->select($database);
		if ($redis->get($MY_KEY) == $MY_VAL) {
			echo 'OK';
			$redis->close();
			exit(0);
		}
	}
}

$redis->close();
echo 'FAIL';
exit(1);
