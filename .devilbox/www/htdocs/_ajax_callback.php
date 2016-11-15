<?php
require '../config.php';

if (isset($_GET['database'])) {
	if (isset($_GET['type']) && $_GET['type'] == 'mysql') {
		echo json_encode(array(
			'size' 	=> (string)$MySQL->getDBSize($_GET['database']),
			'table'	=> (string)$MySQL->getTableCount($_GET['database'])
		));
	} else if (isset($_GET['type']) && $_GET['type'] == 'postgres') {
		$schema = isset($_GET['schema']) ? $_GET['schema'] : '';
		echo json_encode(array(
			'size' 	=> (string)$Postgres->getSchemaSize($_GET['database'], $schema),
			'table'	=> (string)$Postgres->getTableCount($_GET['database'], $schema)
		));
	}
} else if (isset($_GET['vhost'])) {
	echo $Docker->PHP_checkVirtualHost($_GET['vhost']);
}
