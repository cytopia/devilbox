<?php


/**
 * Executes shell commands on the PHP-FPM Host
 *
 * @param  string $cmd    [description]
 * @param  string $output [description]
 * @return integer
 */
function my_exec($cmd, &$output = '')
{
	// Clean output
	$output = '';
	exec($cmd, $output, $exit_code);
	return $exit_code;
}

/**
 * Connect to database
 *
 * @param  [type] $err  [description]
 * @param  [type] $host [description]
 * @param  [type] $pass [description]
 * @param  [type] $user [description]
 * @return [type]       [description]
 */
function my_mysql_connect(&$err, $host = NULL, $pass = NULL, $user = NULL)
{
	if ($host === NULL) {
		$host = $GLOBALS['MYSQL_HOST_ADDR'];
	}
	if ($pass === NULL) {
		$pass = $GLOBALS['MYSQL_ROOT_PASS'];
	}
	if ($user === NULL) {
		$user = 'root';
	}

	try {
		$link = mysqli_connect($host, $user, $pass);
	} catch (Exception $e) {
		$err = $e->getMessage().': '.mysqli_connect_error();
		return FALSE;
	}

//	if (!($link = @mysqli_connect($host, $user, $pass))) {
//		$err = mysqli_connect_error();
//		return FALSE;
//	}
	return $link;
}

/**
 * Close Database connection
 *
 * @param  [type] $link [description]
 * @return [type]       [description]
 */
function my_mysqli_close($link) {
	return mysqli_close($link);
}


/**
 * Query Database
 *
 * @param  [type] $err      [description]
 * @param  [type] $link     [description]
 * @param  [type] $query    [description]
 * @param  [type] $callback [description]
 * @return [type]           [description]
 */
function my_mysqli_select(&$err, $link, $query, $callback = NULL)
{
	if (!($result = mysqli_query($link, $query))) {
		$err = mysqli_error($link);
		return FALSE;
	}

	$data	= array();

	if ($callback) {
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
			$callback($row, $data);
		}
	} else {
		$data[] = $row;
	}
	mysqli_free_result($result);

	return $data;
}




/********************************************************************************
 *
 *  M Y S Q L   F U N C T I O N S
 *
 ********************************************************************************/

/**
 * Get all MySQL Databases.
 * @return mixed Array of name => size
 */
function getDatabases() {
	$error;
	$callback = function ($row, &$data) {
		$data[$row['database']] = array(
			'charset'	=> $row['charset'],
			'collation'	=> $row['collation']
		);
	};

	$sql = "SELECT
				S.SCHEMA_NAME AS 'database',
				S.DEFAULT_COLLATION_NAME AS 'collation',
				S.default_character_set_name AS 'charset'
			FROM
				information_schema.SCHEMATA AS S
			WHERE
				S.SCHEMA_NAME != 'mysql' AND
				S.SCHEMA_NAME != 'performance_schema' AND
				S.SCHEMA_NAME != 'information_schema'";

	return my_mysqli_select($error, $GLOBALS['MY_MYSQL_LINK'], $sql, $callback);
}

/**
 * Get Database size in Megabytes
 * @param  [type] $db_name [description]
 * @return [type]          [description]
 */
function getDBSize($db_name) {
	$error;
	$callback = function ($row, &$data) {
		$data = $row['size'];
	};

	$sql = "SELECT
				ROUND( SUM((T.data_length+T.index_length)/1048576), 2 ) AS 'size'
			FROM
				information_schema.TABLES AS T
			WHERE
				T.TABLE_SCHEMA = '".$db_name."';";

	$size = my_mysqli_select($error, $GLOBALS['MY_MYSQL_LINK'], $sql, $callback);
	return $size ? $size : 0;

}

/**
 * Get Number of Tables per Database
 * @param  [type] $db_name [description]
 * @return [type]          [description]
 */
function getTableCount($db_name) {
	$error;
	$callback = function ($row, &$data) {
		$data = $row['count'];
	};

	$sql = "SELECT
				COUNT(*) AS 'count'
			FROM
				information_schema.TABLES AS T
			WHERE
				T.TABLE_SCHEMA = '".$db_name."';";

	$count = my_mysqli_select($error, $GLOBALS['MY_MYSQL_LINK'], $sql, $callback);
	return $count ? $count : 0;
}



/**
 * Read out MySQL Server variables
 *
 * @param  [type] $key [description]
 * @return [type]      [description]
 */
function getMySQLConfig($key) {
	$key = str_replace('-', '_', $key);

	$callback = function ($row, &$data) use ($key) {
		$data = isset($row['Value']) ? $row['Value'] : FALSE;
	};

	$sql = 'SHOW VARIABLES WHERE Variable_Name = "'.$key.'";';
	$val = my_mysqli_select($error, $GLOBALS['MY_MYSQL_LINK'], $sql, $callback);

	if (!is_array($val)) {
		return $val;
	} else if (is_array($val) && $val) {
		return print_r($val, TRUE);
	} else {
		return '';
	}
}









function is_valid_dir($path) {
	return (is_dir($path) || (is_link($path) && is_dir(readlink($path))));
}

function getVirtualHosts()
{
	$docRoot	= '/shared/httpd';
	$vhosts		= array();

	if ($handle = opendir($docRoot)) {
		while (false !== ($directory = readdir($handle))) {
			if (is_valid_dir($docRoot . DIRECTORY_SEPARATOR . $directory) && $directory != '.' && $directory != '..') {

				$vhosts[] = array(
					'name'		=> $directory,
					'domain'	=> $directory.'.loc',
					'href'		=> 'http://' . $directory.'.loc'
				);
			}
		}
	}

	return $vhosts;
}
function checkVirtualHost($vhost)
{
	global $ENV;

	$docRoot	= '/shared/httpd';
	$htdocs		= $docRoot.DIRECTORY_SEPARATOR.$vhost.DIRECTORY_SEPARATOR.'htdocs';
	$domain		= $vhost.'.loc';
	$url		= 'http://'.$domain;
	$error		= array();


	// 1. Check htdocs folder
	if (!is_valid_dir($htdocs)) {
		$error[] = 'Missing <strong>htdocs</strong> directory in: <strong>'.$ENV['HOST_PATH_TO_WWW_DOCROOTS'].'/'.$vhost.'/</strong>';
	}


	// 2. Check /etc/resolv DNS entry
	$output;
	if (my_exec('getent hosts '.$domain, $output) !== 0) {
		$error[] = 'Missing entry in <strong>/etc/hosts</strong>:<br/><code>127.0.0.1 '.$domain.'</code>';
	}

	// 3. Check correct /etc/resolv entry
	$dns_ip = '127.0.0.1';
	if (isset($output[0])) {
		$tmp = explode(' ', $output[0]);
		if (isset($tmp[0])) {
			$dns_ip = $tmp[0];
		}
	}
	if ($dns_ip != '127.0.0.1') {
		$error[] = 'Error in <strong>/etc/hosts</strong><br/>'.
					'Found:<br/>'.
					'<code>'.$dns_ip.' '.$domain.'</code><br/>'.
					'But it should be:<br/>'.
					'<code>127.0.0.1 '.$domain.'</code><br/>';
	}

	if (is_array($error) && count($error)) {
		return implode('<br/>', $error);
	} else {
		return '';
	}
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

