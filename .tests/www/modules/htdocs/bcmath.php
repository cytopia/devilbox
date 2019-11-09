<?php
/**
 * This page should print 'OK' if everything works,
 * 'FAIL' or nothing if an error occured.
 */
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

$a = '1.234';
$b = '5';

$num1 = bcadd($a, $b);     // 6
$num2 = bcadd($a, $b, 4);  // 6.2340

if ($num1 != 6) {
	echo 'FAIL: ' . $num1;
	exit(1);
}

if ($num2 != 6.2340) {
	echo 'FAIL: ' . $num2;
	exit(1);
}

echo 'OK';
