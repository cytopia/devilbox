<?php
require '../config.php';

if (isset($_GET['db'])) {
	echo getDBSize($_GET['db']);
} else {
	echo '0';
}
