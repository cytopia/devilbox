<?php
require '../config.php';

if (isset($_GET['size'])) {
	echo getDBSize($_GET['size']);
} elseif (isset($_GET['table'])) {
	echo getTableCount($_GET['table']);
} else {
	echo '0';
}
