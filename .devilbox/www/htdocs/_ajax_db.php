<?php
require '../config.php';

if (isset($_GET['size'])) {
	echo $MySQL->getDBSize($_GET['size']);
} elseif (isset($_GET['table'])) {
	echo $MySQL->getTableCount($_GET['table']);
} else {
	echo '0';
}
