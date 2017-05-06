<?php
namespace devilbox;

/**
 * @requires devilbox::Logger
 */
class Httpd extends _Base implements _iBase
{

	/*********************************************************************************
	 *
	 * Statics
	 *
	 *********************************************************************************/

	/**
	 * Httpd instance
	 * @var Httpd|null
	 */
	protected static $instance = null;

	/**
	 * Singleton Instance getter.
	 *
	 * @param string $user Username
	 * @param string $pass Password
	 * @param string $host Host
	 * @return object|null
	 */
	public static function getInstance($host = null, $user = null, $pass = null)
	{
		if (!isset(static::$instance)) {
			static::$instance = new static();
		}
		return static::$instance;
	}
	/**
	 * @overwrite
	 * @param  string  $hostname [description]
	 * @return boolean           [description]
	 */
	public static function isAvailable($hostname)
	{
		// Always available, otherwise you would not see any browser output.
		return true;
	}


	/**
	 * Connect to Httpd
	 *
	 * @param  string $err  Reference to error message
	 * @param  string $host Redis hostname
	 * @return boolean
	 */
	public static function testConnection(&$err, $host, $user = '', $pass = '')
	{
		$err = false;

		// Silence errors and try to connect
		//error_reporting(0);

		$url = 'http://'.$host.'/not-existing-page-which-returns-404.php';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		error_reporting(-1);

		if ($http_code == 0) {
			$err = 'Failed to connect to Httpd host on '.$host;
			return false;
		}
		return true;
	}



	/*********************************************************************************
	 *
	 * Construct/Destructor
	 *
	 *********************************************************************************/

	/**
	 * Use singleton getInstance() instead.
	 *
	 * @param string $user Username
	 * @param string $pass Password
	 * @param string $host Host
	 */
	public function __construct()
	{
	}

	/**
	 * Destructor
	 */
	public function __destruct()
	{
	}


	/*********************************************************************************
	 *
	 * Interface required functions
	 *
	 *********************************************************************************/

	public function getName($default = 'Httpd')
	{
		$name = $this->egrep('/[a-zA-Z0-9]+/', $_SERVER['SERVER_SOFTWARE']);
		if (!$name) {
			loadClass('Logger')->error('Could not get Httpd name');
			return $default;
		}
		return $name;
	}

	public function getVersion()
	{
		$version = $this->egrep('/[.0-9]+/', $_SERVER['SERVER_SOFTWARE']);
		if (!$version) {
			loadClass('Logger')->error('Could not get Httpd version');
			return '';
		}
		return $version;
	}
}
