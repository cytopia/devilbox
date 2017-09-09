<?php
require '../config.php';

if (loadClass('Helper')->isLoggedIn()) {

	if (isset($_GET['database'])) {
		if (isset($_GET['type']) && $_GET['type'] == 'mysql') {
			echo json_encode(array(
				'size' 	=> (string)loadClass('Mysql')->getDBSize($_GET['database']),
				'table'	=> (string)loadClass('Mysql')->getTableCount($_GET['database'])
			));
		} else if (isset($_GET['type']) && $_GET['type'] == 'postgres') {
			$schema = isset($_GET['schema']) ? $_GET['schema'] : '';
			echo json_encode(array(
				'size' 	=> (string)loadClass('Pgsql')->getSchemaSize($_GET['database'], $schema),
				'table'	=> (string)loadClass('Pgsql')->getTableCount($_GET['database'], $schema)
			));
		}
	} else if (isset($_GET['vhost'])) {
		echo loadClass('Httpd')->checkVirtualHost($_GET['vhost']);
	} else {
		loadClass('Helper')->redirect('/');
	}

} else {
	loadClass('Helper')->redirect('/');
}
