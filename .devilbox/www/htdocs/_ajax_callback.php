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
		$no_mod = '<span class="text-warning">PHP module not loaded</span>';
		$software = array();

		if ($_GET['software'] == 'angular_cli') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getAngularCliVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'asgardcms_installer') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getAsgardCmsInstallerVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'codeception') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getCodeceptionVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'composer') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getComposerVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'deployer') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getDeployerVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'git') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getGitVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'grunt_cli') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getGruntCliVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'gulp') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getGulpVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'laravel_installer') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getLaravelInstallerVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'laravel_lumen') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getLaravelLumenVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'mds') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getMdsVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'mupdf_tools') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getMupdfToolsVersion()) !== false) ? $version : $no
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
		else if ($_GET['software'] == 'phalcon_devtools') {
			if (!extension_loaded('phalcon')) {
				echo json_encode(array(
					$_GET['software'] => $no_mod
				));
			} else {
				echo json_encode(array(
					$_GET['software'] => (($version = loadClass('Php')->getPhalconDevtoolsVersion()) !== false) ? $version : $no
				));
			}
		}
		else if ($_GET['software'] == 'phpunit') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getPhpunitVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'stylelint') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getStylelintVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'symfony_cli') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getSymfonyCliVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'vue_cli') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getVueCliVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'webpack_cli') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getWebpackCliVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'wpcli') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getWpcliVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'wscat') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getWscatVersion()) !== false) ? $version : $no
			));
		}
		else if ($_GET['software'] == 'yarn') {
			echo json_encode(array(
				$_GET['software'] => (($version = loadClass('Php')->getYarnVersion()) !== false) ? $version : $no
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
