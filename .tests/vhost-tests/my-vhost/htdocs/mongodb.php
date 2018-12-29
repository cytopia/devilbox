<?php
/**
 * This page should print 'OK' if everything works,
 * 'FAIL' or nothing if an error occured.
 */
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);


$MY_HOST = 'mongo';

$mongo = new \MongoDB\Driver\Manager('mongodb://'.$MY_HOST);

// MongoDB uses lazy loading of server list
// so just execute an arbitrary command in order
// to make it populate the server list
$command = new \MongoDB\Driver\Command(array('ping' => 1));

try {
	$mongo->executeCommand('admin', $command);
} catch (\MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
	echo 'FAIL - ' . $e;
	exit(1);
}

// retrieve server list
$servers = $mongo->getServers();

if (!isset($servers[0])) {
	echo 'FAIL - Failed to connect to MongoDB host on '.$MY_HOST.' (No host info available)';
	exit(1);
}
if ($servers[0]->getHost() != $MY_HOST) {
	echo 'FAIL - Failed to connect to MongoDB host on '.$MY_HOST.' (servername does not match: '.$servers[0]->getHost().')';
	exit(1);
}

echo 'OK';
