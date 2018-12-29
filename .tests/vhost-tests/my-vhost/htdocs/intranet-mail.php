<?php
/**
 * This page should print 'OK' if everything works,
 * 'FAIL' or nothing if an error occured.
 */
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);


$MY_MAIL = 'test@example.org';
$MY_SUBJ = 'mysubject';
$MY_MESS = 'mymessage';
$MY_URL = 'http://localhost/mail.php';

// Sent Email via POST request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $MY_URL.'?email='.$MY_MAIL.'&subject='.$MY_SUBJ.'&message='.$MY_MESS);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
curl_exec($ch);
curl_close ($ch);
sleep(5);

// Retrieve contents
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $MY_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($ch);
curl_close($ch);

if (strpos($output, $MY_MAIL) === false) {
	echo 'FAIL - no mail';
	//print_r($output);
	exit(1);
}
if (strpos($output, $MY_SUBJ) === false) {
	echo 'FAIL - no subject';
	//print_r($output);
	exit(1);
}
if (strpos($output, $MY_MESS) === false) {
	echo 'FAIL - no message';
	//print_r($output);
	exit(1);
}
echo 'OK';
