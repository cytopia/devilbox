<?php
namespace devilbox;

/**
 * @requires devilbox::Logger
 * @requires devilbox::Mysql
 */
class Docker
{

	/*********************************************************************************
	 *
	 * Statics
	 *
	 *********************************************************************************/

	/**
	 * $this Singleton instance
	 * @var Object|null
	 */
	protected static $instance = null;

	/**
	 * Singleton Instance getter.
	 *
	 * @return object|null
	 */
	public static function getInstance()
	{
		if (!isset(static::$instance)) {
			static::$instance = new static();
		}
		return static::$instance;
	}



	/*********************************************************************************
	 *
	 * Private Variables
	 *
	 *********************************************************************************/

	/**
	 * Array of docker environment variables
	 * @var mixed[]
	 */
	private $_env = array();

	/**
	 * Domain suffix.
	 *
	 * @var string
	 */
	private $_tld = 'loc';


	/**
	 * Document root path in PHP docker
	 * @var string
	 */
	private $_doc_root = '/shared/httpd';


	/*********************************************************************************
	 *
	 * Construct/Destructor
	 *
	 *********************************************************************************/

	/**
	 * Private Constructor, so nobody can call it.
	 * Use singleton getInstance() instead.
	 */
	private function __construct()
	{
		// Do not call me

		// Translate Docker environmental variables to $ENV
		exec('env', $output);

		foreach ($output as $var) {
			$tmp = explode('=', $var);
			$this->_env[$tmp[0]] = $tmp[1];
		}

		// Set the TLD suffix (domain ending) for virtual hosts
		// Note: If this is changed it currently also needs to be changed
		//       in each webserver's configuration file in .devilbox/<webserver>/02-vhost-mass.conf
		$this->_tld = $GLOBALS['TLD_SUFFIX'];

	}



	/*********************************************************************************
	 *
	 * Public functions
	 *
	 *********************************************************************************/

	/**
	 * Get Docker environment variables from docker-compose.yml
	 * @param  string $variable Variable name
	 * @return string           Variable value
	 */
	public function getEnv($variable)
	{
		if (!isset($this->_env[$variable])) {
			$Logger = \devilbox\Logger::getInstance();
			$Logger->error('Docker environment variable not found: '.$variable);
			return null;
		}
		return $this->_env[$variable];
	}

	/**
	 * Get tld suffix.
	 *
	 * @return string
	 */
	public function getTld()
	{
		return $this->_tld;
	}

	/**
	 * Get HTTP port.
	 *
	 * @return string
	 */
	public function getPort()
	{
		$port = $this->getEnv('HOST_PORT_HTTPD');

		if ( empty($port) ) {
			return '';
		}

		if ( $port == 80 ) {
			return '';
		}

		return ":$port";
	}


	/*********************************************************************************
	 *
	 * PHP Docker functions
	 *
	 *********************************************************************************/

	/**
	 * Get PHP Version.
	 *
	 * @return string PHP version string
	 */
	public function PHP_version()
	{
		return 'PHP ' . phpversion() .' (' . php_sapi_name().')';
	}

	/**
	 * Read out PHP Server configuration by variable
	 *
	 * @param  string|null $key Config key name
	 * @return string|mixed[]
	 */
	public function PHP_config($key = null)
	{
		// Get all configs as array
		if ($key === null) {
			return ini_get_all();
		} else {
			return ini_get($key);
		}
	}

	/**
	 * Return an array of custom mounted PHP config files.
	 *
	 * @return string[]
	 */
	public function PHP_custom_config_files()
	{
		$files = array();

		foreach (scandir('/etc/php-custom.d') as $file) {
			if (preg_match('/.*\.ini$/', $file) === 1) {
				$files[] = $file;
			}
		}
		return $files;
	}

	/**
	 * Check if mounted MySQL socket is valid inside PHP Docker.
	 *
	 * @param string $error Reference to error message.
	 * @return boolean
	 */
	public function PHP_has_valid_mysql_socket(&$error)
	{
		if (!$this->getEnv('MOUNT_MYSQL_SOCKET_TO_LOCALDISK')) {
			$error = 'Socket mount not enabled.';
			return false;
		}

		if (!file_exists($this->getEnv('MYSQL_SOCKET_PATH'))) {
			$error = 'Socket file not found.';
			return false;
		}

		//if (getMySQLConfigByKey('socket') != $ENV['MYSQL_SOCKET_PATH']) {
		//	$error = 'Mounted from mysql:'.$ENV['MYSQL_SOCKET_PATH']. ', but socket is in mysql:'.getMySQLConfigByKey('socket');
		//	return FALSE;
		//}

		$error = '';
		return true;
	}

	/**
	 * Get all mass virtual Hosts by directories
	 *
	 * @return mixed[]
	 */
	public function PHP_getVirtualHosts()
	{
		$docRoot	= $this->_doc_root;
		$vhosts		= array();

		if ($handle = opendir($docRoot)) {
			while (false !== ($directory = readdir($handle))) {
				if ($this->_is_valid_dir($docRoot . DIRECTORY_SEPARATOR . $directory) && $directory != '.' && $directory != '..') {

					$vhosts[] = array(
						'name'		=> $directory,
						'domain'	=> $directory .'.' . $this->_tld,
						'href'		=> 'http://' . $directory . '.' . $this->_tld
					);
				}
			}
		}
		return $vhosts;
	}

	/**
	 * Check single mass virtual host
	 *
	 * @param  string $vhost Virtual Host name
	 * @return string Errors
	 */
	public function PHP_checkVirtualHost($vhost)
	{
		$docRoot	= $this->_doc_root;
		$htdocs		= $docRoot . DIRECTORY_SEPARATOR . $vhost . DIRECTORY_SEPARATOR . 'htdocs';
		$domain		= $vhost . '.' . $this->_tld;
		$url		= 'http://'.$domain;
		$error		= array();


		// 1. Check htdocs folder
		if (!$this->_is_valid_dir($htdocs)) {
			$error[] = 'Missing <strong>htdocs</strong> directory in: <strong>'.$this->getEnv('HOST_PATH_TO_WWW_DOCROOTS').'/'.$vhost.'/</strong>';
		}

		if ($GLOBALS['ENABLE_VHOST_DNS_CHECK']) {

			// 2. Check /etc/resolv DNS entry
			$output;
			if ($this->_exec('getent hosts '.$domain, $output) !== 0) {
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
		}

		if (is_array($error) && count($error)) {
			return implode('<br/>', $error);
		} else {
			return '';
		}
	}

	/*********************************************************************************
	 *
	 * MySQL Docker functions
	 *
	 *********************************************************************************/

	/**
	 * Get MySQL Version.
	 *
	 * @return string MySQL version string.
	 */
	public function MySQL_version()
	{
		$name = $this->MySQL_config('version_comment');
		$version = $this->MySQL_config('version');

		if (!$name && !$version) {
			return 'Unknown MySQL version';
		}
		return $name . ' ' . $version;
	}

	/**
	 * Read out MySQL Server configuration by variable
	 *
	 * @param  string|null $key Config key name
	 * @return string|mixed[]
	 */
	public function MySQL_config($key = null)
	{
		// Get all configs as array
		if ($key === null) {
			$callback = function ($row, &$data) {
				$key = $row['Variable_name'];
				$val = $row['Value'];
				$data[$key] = $val;
			};

			$sql = 'SHOW VARIABLES;';
			$configs = \devilbox\Mysql::getInstance()->select($sql, $callback);

			return $configs ? $configs : array();

		} else { // Get single config

			$key = str_replace('-', '_', $key);

			$callback = function ($row, &$data) use ($key) {
				$data = isset($row['Value']) ? $row['Value'] : false;
			};

			$sql = 'SHOW VARIABLES WHERE Variable_Name = "'.$key.'";';
			$val = \devilbox\Mysql::getInstance()->select($sql, $callback);

			if (is_array($val) && $val) {
				return array_values($val)[0];
			} else {
				return $val;
			}
		}
	}


	/*********************************************************************************
	 *
	 * Postgres Docker functions
	 *
	 *********************************************************************************/

	/**
	 * Get Postgres Version.
	 *
	 * @return string Postgres version string.
	 */
	public function Postgres_version()
	{
		$callback = function ($row, &$data) {
			$data = $row['version'];
		};

		$version = \devilbox\Postgres::getInstance()->select('SELECT version();', $callback);

		// Extract shorthand
		preg_match('/\w+[[:space:]]*[.0-9]+/i', $version, $matches);
		if (isset($matches[0])) {
			return $matches[0];
		}

		// Unknown
		if (!$version) {
			return 'Unknown Postgres version';
		}
		return $version;
	}


	/**
	 * Read out PostgreSQL Server configuration by variable
	 *
	 * @param  string|null $key Config key name
	 * @return string|mixed[]
	 */
	public function Postgres_config($key = null)
	{
		// Get all configs as array
		if ($key === null) {
			$callback = function ($row, &$data) {
				$key = $row['name'];
				$val = $row['setting'];
				$data[$key] = $val;
			};

			$sql = 'SELECT name, setting FROM pg_settings;';
			$configs = \devilbox\Postgres::getInstance()->select($sql, $callback);

			return $configs ? $configs : array();

		} else { // Get single config

			$callback = function ($row, &$data) use ($key) {
				$data = isset($row['setting']) ? $row['setting'] : false;
			};

			$sql = "SELECT name, setting FROM pg_settings WHERE name = '".$key."';";
			$val = \devilbox\Postgres::getInstance()->select($sql, $callback);

			return is_array($val) ? '' : $val;
		}
	}



	/*********************************************************************************
	 *
	 * HTTPD Docker functions
	 *
	 *********************************************************************************/

	/**
	 * Get HTTPD Version
	 *
	 * @return string HTTPD server version string.
	 */
	public function HTTPD_version()
	{
		preg_match('/\w+\/[.0-9]+/i', $_SERVER['SERVER_SOFTWARE'], $matches);
		if (isset($matches[0])) {
			return $matches[0];
		} else {
			return 'Unknown Webserver';
		}
	}



	/*********************************************************************************
	 *
	 * Private functions
	 *
	 *********************************************************************************/

	/**
	 * Check if the directory exists or
	 * in case of a symlink the target is an
	 * existing directory.
	 *
	 * @param  string $path The path.
	 * @return boolean
	 */
	private function _is_valid_dir($path)
	{
		return (is_dir($path) || (is_link($path) && is_dir(readlink($path))));
	}

	/**
	 * Executes shell commands on the PHP-FPM Host
	 *
	 * @param  string $cmd    Command
	 * @param  string $output Reference to output
	 * @return integer
	 */
	private function _exec($cmd, &$output = '')
	{
		// Clean output
		$output = '';
		exec($cmd, $output, $exit_code);
		return $exit_code;
	}
}
