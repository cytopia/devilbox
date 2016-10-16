<?php
require '../config.php';

if (isset($_GET['valid'])) {
	echo checkVirtualHost($_GET['valid']);
} else {
	echo '';
}
