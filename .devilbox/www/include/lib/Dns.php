<?php
namespace devilbox;

/**
 * @requires devilbox::Logger
 */
class Dns extends _Base implements _iBase
{

	/*********************************************************************************
	 *
	 * Statics
	 *
	 *********************************************************************************/

	/**
	 * Dns instance
	 * @var Dns|null
	 */
	protected static $instance = null;

	private static $_available = null;

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
		if (self::$_available === null) {

			$output = '';
			$exit_code = -1;
			$cmd = 'dig +time=1 +tries=1 @172.16.238.100 version.bind chaos TXT';
			exec($cmd, $output, $exit_code);

			if ($exit_code != 0) {
				return false;
			}

			self::$_available[$hostname] = ($exit_code != 0) ? false : true;

		}
		return self::$_available;
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
		return self::isAvailable($host);
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

	public function getName($default = 'Bind')
	{
		return $default;
	}

	public function getVersion()
	{
		$output = '';
		$exit_code = -1;
		$cmd = 'dig +time=1 +tries=1 @172.16.238.100 version.bind chaos TXT | grep -iE "^version\.bind.*TXT"';

		exec($cmd, $output, $exit_code);

		$version = $this->egrep('/"[0-9.-]+.*"/', isset($output[0]) ? $output[0] : '');
		$version = $this->egrep('/[0-9.-]+/', $version);
		$version = $version ? $version : '';
		return $version;
	}
}
