<?php
namespace devilbox;

/**
 * @requires devilbox::Logger
 */
class Php extends _Base implements _iBase
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
	 * Connect to PHP
	 *
	 * @param  string $err  Reference to error message
	 * @param  string $host Redis hostname
	 * @return boolean
	 */
	public static function testConnection(&$err, $host, $user = '', $pass = '')
	{
		// Connection is always working, otherwise you would not see any browser output.
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
	 * PHP  Select functions
	 *
	 *********************************************************************************/

	public function getUid()
	{
		$output = null;
		$return = $this->_exec('id', $output);

		$uid = $this->egrep('/uid=[0-9]+/', isset($output[0]) ? $output[0] : '');
		$uid = $this->egrep('/[0-9]+/', $uid);
		return $uid;
	}
	public function getGid()
	{
		$output = null;
		$return = $this->_exec('id', $output);

		$uid = $this->egrep('/gid=[0-9]+/', isset($output[0]) ? $output[0] : '');
		$uid = $this->egrep('/[0-9]+/', $uid);
		return $uid;
	}


	/*********************************************************************************
	 *
	 * Interface required functions
	 *
	 *********************************************************************************/

	public function getName($default = 'PHP')
	{
		if (defined('HHVM_VERSION')) {
			return 'HHVM';
		}
		return $default;
	}

	public function getVersion()
	{
		if (defined('HHVM_VERSION')) {
			return HHVM_VERSION . ' php-'.str_replace('-hhvm', '', phpversion());
		} else {
			return phpversion();
		}
	}


	/*********************************************************************************
	 *
	 * Private functions
	 *
	 *********************************************************************************/

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
