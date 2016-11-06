<?php
require '../config.php';

if (isset($_GET['valid'])) {
	echo $Docker->PHP_checkVirtualHost($_GET['valid']);
	exit();
} else {
	echo '';
	exit();
}
