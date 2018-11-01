<?php
require '../config.php';

if (loadClass('Helper')->isLoggedIn()) {

	//
	// ?database=
	//
	if (isset($_GET['database'])) {
		// &type=mysql
		if (isset($_GET['type']) && $_GET['type'] == 'mysql') {
			echo json_encode(array(
				'size' 	=> (string)loadClass('Mysql')->getDBSize($_GET['database']),
				'table'	=> (string)loadClass('Mysql')->getTableCount($_GET['database'])
			));
		// &type=postgres
		} else if (isset($_GET['type']) && $_GET['type'] == 'postgres') {
			$schema = isset($_GET['schema']) ? $_GET['schema'] : '';
			echo json_encode(array(
				'size' 	=> (string)loadClass('Pgsql')->getSchemaSize($_GET['database'], $schema),
				'table'	=> (string)loadClass('Pgsql')->getTableCount($_GET['database'], $schema)
			));
		}
	}

	//
	// ?vhost=
	//
	else if (isset($_GET['vhost'])) {
		echo loadClass('Httpd')->checkVirtualHost($_GET['vhost']);
	}


	//
	// ?software=
	//
	else if (isset($_GET['software'])) {
		$no = '<span class="text-danger">not installed</span>';
		$software = array();

		if ($_GET['software'] == 'composer') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getComposerVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'drupalc') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getDrupalConsoleVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'drush7') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getDrushVersion(7)) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'drush8') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getDrushVersion(8)) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'drush9') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getDrushVersion(9)) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'git') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getGitVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'laravel') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getLaravelVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'mds') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getMdsVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'node') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getNodeVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'npm') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getNpmVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'phalcon') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getPhalconVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'symfony') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getSymfonyVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'wpcli') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getWpcliVersion()) !== false) ? $version : $no
			));
		} else {
			echo json_encode(array($_GET['software'] => 'unknown software'));
		}
	}

	//
	// WRONG REQUEST
	//
	else {
		loadClass('Helper')->redirect('/');
	}
}

//
// Not logged in
//
else {
	loadClass('Helper')->redirect('/');
}
