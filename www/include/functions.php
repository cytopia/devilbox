<?php


function my_exec($cmd, &$output = '')
{
	// Clean output
	$output = '';
	exec($cmd, $output, $exit_code);
	return $exit_code;
}

/********************************************************************************
 *
 *  G E T   D A T A
 *
 ********************************************************************************/

/**
 * Get all MySQL Databases.
 * @return mixed Array of name => size
 */
function getDatabases() {
	global $MYSQL_HOST_ADDR;
	global $MYSQL_ROOT_PASS;
	$conn = mysqli_connect($MYSQL_HOST_ADDR, 'root', $MYSQL_ROOT_PASS);

	$sql = 'SELECT
				table_schema AS db_name,
				SUM( data_length + index_length  ) / 1024 / 1024 "MB"
			FROM
				information_schema.TABLES
			WHERE
				table_schema != "mysql" AND
				table_schema != "performance_schema" AND
				table_schema != "information_schema"
			GROUP BY
				table_schema
			ORDER BY db_name;';

	$result = mysqli_query($conn, $sql);
	$data = array();
	while ($row = $result->fetch_array(MYSQLI_NUM)) {
		// name => size
		$data[$row[0]] = $row[1];
	}
	mysqli_close($conn);
	return $data;
}


function is_valid_dir($path) {
	return (is_dir($path) || (is_link($path) && is_dir(readlink($path))));
}

/**
 * Get all VirtualHosts
 * @return [type] [description]
 */
function getVhosts() {
	global $ENV;

	$docRoot	= '/shared/httpd';
	$vhosts		= array();


	if ($handle = opendir($docRoot)) {
		while (false !== ($directory = readdir($handle))) {
			if (is_valid_dir($docRoot . DIRECTORY_SEPARATOR . $directory) && $directory != '.' && $directory != '..') {

				$output;
				$domain		= $directory.'.loc';
				$url		= 'http://'.$domain;

				$htdocs_ok	= is_valid_dir($docRoot.DIRECTORY_SEPARATOR.$directory.DIRECTORY_SEPARATOR.'htdocs');
				$dns_ok		= my_exec('getent hosts '.$domain, $output) == 0 ? TRUE : FALSE;
				$dns_ip		= '';
				if (isset($output[0])) {
					$tmp = explode(' ', $output[0]);
					if (isset($tmp[0])) {
						$dns_ip = $tmp[0];
					}
				}

				$vhosts[] = array(
					'name'		=> $directory,
					'htdocs'	=> $htdocs_ok,
					'dns_ok'	=> $dns_ok,
					'dns_ip'	=> $dns_ip,
					'domain'	=> $directory.'.loc',
					'href'		=> 'http://' . $directory.'.loc'
				);
			}
		}
	}
	return $vhosts;
}



/********************************************************************************
 *
 *  G E T   V E R S I O N
 *
 ********************************************************************************/
/**
 * Get HTTPD Version
 * @return [type] [description]
 */
function getHttpVersion() {
	preg_match('/\w+\/[.0-9]+/i', $_SERVER['SERVER_SOFTWARE'], $matches);
	if (isset($matches[0])) {
		return $matches[0];
	} else {
		return 'Unknown Webserver';
	}
}

/**
 * Get MySQL Version
 * @return [type] [description]
 */

function getMySQLVersion() {
	return getMySQLConfig('version_comment') . ' ' . getMySQLConfig('version');
}

function getPHPVersion() {
	return 'PHP ' . phpversion() .' (' . php_sapi_name().')';
}



function getMySQLConfig($key) {
	global $MYSQL_HOST_ADDR;
	global $MYSQL_ROOT_PASS;

	$link = @mysqli_connect($MYSQL_HOST_ADDR, 'root', $MYSQL_ROOT_PASS);

	if (!$link) {
		return 'Cannot conncet to MySQL Database: '.mysqli_connect_error();
	}
	$key = str_replace('-', '_', $key);
	$query = 'SHOW VARIABLES WHERE Variable_Name = "'.$key.'";';
	$result = mysqli_query($link, $query);
	$data = mysqli_fetch_array($result);
	if (isset($data[1])) {
		return $data[1];
	}
	return FALSE;
}



/********************************************************************************
 *
 *  T E S T   M Y S Q L   C O N N E C T I O N
 *
 ********************************************************************************/


function testMySQLLocalhost() {
	global $MYSQL_ROOT_PASS;

	$link = @mysqli_connect('localhost', 'root', $MYSQL_ROOT_PASS);
	if (!$link) {
		return 'Cannot conncet to MySQL Database: '.mysqli_connect_error();
	}
	return 'OK: Connection via localhost socket';
}
function testMySQLLocalIp() {
	global $MYSQL_ROOT_PASS;

	$link = @mysqli_connect('127.0.0.1', 'root', $MYSQL_ROOT_PASS);
	if (!$link) {
		return 'Cannot conncet to MySQL Database: '.mysqli_connect_error();
	}
	return 'OK: Connection via 127.0.0.1';
}
function testMySQLRemotelIp() {
	global $MYSQL_HOST_ADDR;
	global $MYSQL_ROOT_PASS;

	$link = @mysqli_connect($MYSQL_HOST_ADDR, 'root', $MYSQL_ROOT_PASS);
	if (!$link) {
		return 'Cannot conncet to MySQL Database: '.mysqli_connect_error();
	}
	return 'OK: Connection via '.$MYSQL_HOST_ADDR;
}

