<?php
namespace devilbox;

/**
 * @requires devilbox::Logger
 */
class Helper
{

	/*********************************************************************************
	 *
	 * Statics
	 *
	 *********************************************************************************/

	/**
	 * Environmental variables from PHP docker
	 * @var array
	 */
	private static $_env = null;


	/**
	 * Hostname to IP addresses
	 * @var array
	 */
	private static $_ip_address = null;


	/**
	 * Class instance
	 * @var object
	 */
	private static $_instance = null;


	/**
	 * Generic singleton instance getter.
	 * Make sure to overwrite this in your class
	 * for a more complex initialization.
	 *
	 * @param string $hostname Hostname
	 * @param array  $data Additional data (if required)
	 * @return object|null
	 */
	public static function getInstance()
	{
		if (self::$_instance === null) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}



	/*********************************************************************************
	 *
	 * Private constructor for singleton
	 *
	 *********************************************************************************/

	/**
	 * DO NOT CALL ME!
	 * Use singleton getInstance() instead.
	 */
	private function __construct()
	{
	}



	/*********************************************************************************
	 *
	 * Public Helper Functions
	 *
	 *********************************************************************************/

	/**
	 * Get Docker environment variables from docker-compose.yml
	 * Only values from php docker can be retrieved here, so make
	 * sure they are passed to it.
	 *
	 * Values are cached in static context.
	 *
	 * @param  string $variable Variable name
	 * @return string           Variable value
	 */
	public function getEnv($variable)
	{
		if (self::$_env === null) {

			$output = array();

			// Translate PHP Docker environmental variables to $ENV
			exec('/usr/bin/env', $output);

			foreach ($output as $var) {
				$tmp = explode('=', $var);
				self::$_env[$tmp[0]] = $tmp[1];
			}

		}
		if (!isset(self::$_env[$variable])) {
			loadClass('Logger')->error('Environment variable not found: \''.$variable.'\'');
			return null;
		}
		return self::$_env[$variable];
	}



	/**
	 * Retrieve the IP address of the container.
	 *
	 * @return string|boolean IP address or false
	 */
	public function getIpAddress($hostname)
	{
		// Request was already done before and is cached
		if (isset(self::$_ip_address[$hostname])) {
			return self::$_ip_address[$hostname];
		}

		// New request, generic check
		// Note the traiing dot to prevent recursive lookups
		//$ip = $this->exec('ping -c 1 '.$hostname.'. 2>/dev/null | grep -Eo \'[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+\' | head -1');
		$ip = gethostbyname($hostname.'');

		if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
			//loadClass('Logger')->error('Retrieving the IP address of host \''.$hostname.'\' failed: '.$ip);
			self::$_ip_address[$hostname] = false;
		} else {
			self::$_ip_address[$hostname] = $ip;
		}

		return self::$_ip_address[$hostname];
	}


	/**
	 * Shorter version to regex select a string.
	 *
	 * @param  string $regex  Regex
	 * @param  string $string String to look in to
	 * @return bool|string    Returns false on error otherwise the string
	 */
	public function egrep($regex, $string)
	{
		$match = array();
		$error = preg_match($regex, $string, $match);

		if ($error === false) {
			loadClass('Logger')->error('Error matching regex: \''.$regex. '\' in string: \''.$string.'\'');
			return false;
		}

		return isset($match[0]) ? $match[0] : false;
	}


	/**
	 * Executes shell commands on the PHP-FPM Host
	 *
	 * @param  string  $cmd       Command
	 * @param  integer $exit_code Reference to exit code
	 * @return string
	 */
	public function exec($cmd, &$exit_code = -1)
	{
		$output = array();

		exec($cmd, $output, $exit_code);
		return implode ("\n", $output);
	}


	public function redirect($url)
	{
		header('Location: '.$url);
		exit;
	}


	/*********************************************************************************
	 *
	 * Login Helper Functions
	 *
	 *********************************************************************************/

	public function login($username, $password)
	{
		$dvl_password = loadClass('Helper')->getEnv('DEVILBOX_UI_PASSWORD');

		if ($username == 'devilbox' && $password == $dvl_password) {
			$_SESSION['auth'] = 1;
			return true;
		}
		return false;
	}
	public function logout()
	{
		if (isset($_SESSION['auth'])) {
			$_SESSION['auth'] = 0;
			unset($_SESSION['auth']);
		}
	}
	public function isLoginProtected()
	{
		// No password protection enabled
		if (loadClass('Helper')->getEnv('DEVILBOX_UI_PROTECT') != 1) {
			return false;
		}
		return true;

	}
	public function isloggedIn()
	{
		// No password protection enabled
		if (!$this->isLoginProtected()) {
			return true;
		}

		// Alredy logged in
		if (isset($_SESSION['auth']) && $_SESSION['auth'] == 1) {
			return true;
		}
		return false;
	}
	public function authPage()
	{
		if (!$this->isloggedIn()) {
			$this->redirect('/login.php');
		}
	}
}
