<?php
/**
 * This page should print 'OK' if everything works,
 * 'FAIL' or nothing if an error occured.
 */
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);


$MY_HOST = 'memcd';
$MY_PORT = '11211';

$MY_KEY = 'test';
$MY_VAL = 'OK';


$memcd = new \Memcached('_devilbox');
$list = $memcd->getServerList();

if (empty($list)) {
	$memcd->setOption(\Memcached::OPT_LIBKETAMA_COMPATIBLE, true);
	$memcd->setOption(\Memcached::OPT_BINARY_PROTOCOL, false);
	$memcd->addServer($MY_HOST, $MY_PORT);
}

$stats = $memcd->getStats();

if (!isset($stats[$MY_HOST.':'.$MY_PORT])) {
	$memcd->quit();
	echo "FAIL - Failed to connect to Memcached host on '.$MYHOST' (no connection array)";
	exit(1);
}

if (!isset($stats[$MY_HOST.':'.$MY_PORT]['pid'])) {
	$memcd->quit();
	echo "FAIL - Failed to connect to Memcached host on '.$MYHOST' (no pid)";
	exit(1);
}
if ($stats[$MY_HOST.':'.$MY_PORT]['pid'] < 1) {
	$memcd->quit();
	echo "FAIL - Failed to connect to Memcached host on '.$MYHOST' (invalid pid)";
	exit(1);
}

if (! $memcd->add($MY_KEY, $MY_VAL)) {
	$memcd->quit();
	echo "FAIL - Failed to add key";
	exit(1);
}

if ($memcd->get($MY_KEY) != $MY_VAL) {
	$memcd->quit();
	echo "FAIL - Added and retrieved key do not match.";
	exit(1);
}
$memcd->delete($MY_KEY);
$memcd->quit();
echo "OK";
