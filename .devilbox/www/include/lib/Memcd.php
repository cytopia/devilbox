<?php
namespace devilbox;

/**
 * @requires devilbox::Logger
 */
class Memcd extends _Base implements _iBase
{

	/*********************************************************************************
	 *
	 * Statics
	 *
	 *********************************************************************************/

	/**
	 * Memcached instance
	 * @var Memcached|null
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
	public static function getInstance($host, $user = null, $pass = null)
	{
		if (!isset(static::$instance)) {
			static::$instance = new static($host);
		}
		// If current Memcached instance was unable to connect
		if (!static::$instance) {
			//loadClass('Logger')->error('Instance has errors:' . "\r\n" . var_export(static::$instance, true) . "\r\n");
		}
		return static::$instance;
	}

	/**
	 * Connect to Memcached
	 *
	 * @param  string $err  Reference to error message
	 * @param  string $host Memcached hostname
	 * @return boolean
	 */
	public static function testConnection(&$err, $host, $user = '', $pass = '')
	{
		$err = false;

		// Silence errors and try to connect
		//error_reporting(-1);
		$memcd = new \Memcached();
		$memcd->resetServerList();


		if (!$memcd->addServer($host, 11211)) {
			$memcd->quit();
			$err = 'Failed to connect to Memcached host on '.$host;
			error_reporting(-1);
			return false;
		}

		$stats = $memcd->getStats();
		if (!isset($stats[$host.':11211'])) {
			$memcd->quit();
			$err = 'Failed to connect to Memcached host on '.$host;
			return false;
		}
		if (!isset($stats[$host.':11211']['pid'])) {
			$memcd->quit();
			$err = 'Failed to connect to Memcached host on '.$host;
			return false;
		}
		if ($stats[$host.':11211']['pid'] < 1) {
			$memcd->quit();
			$err = 'Failed to connect to Memcached host on '.$host;
			return false;
		}

		$memcd->quit();
		return true;
	}



	/*********************************************************************************
	 *
	 * Private Variables
	 *
	 *********************************************************************************/

	/**
	 * Memcached instance
	 * @var object|null
	 */
	private $_memcached = null;



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
	public function __construct($host)
	{
		$memcd = new \Memcached();
		$memcd->resetServerList();

		if (!$memcd->addServer($host, 11211)) {
			$this->_connect_error = 'Failed to connect to Memcached host on '.$host;
			$this->_connect_errno = 1;
			//loadClass('Logger')->error($this->_connect_error);
		} else {
			$this->_memcached = $memcd;
		}
	}

	/**
	 * Destructor
	 */
	public function __destruct()
	{
		if ($this->_memcached) {
			$this->_memcached->quit();
		}
	}

	/*********************************************************************************
	 *
	 * Memcached  Select functions
	 *
	 *********************************************************************************/

/*	public function getInfo()
	{
		if ($this->_memcached) {
			return $this->_memcached->info('all');
		} else {
			return array();
		}
	}

	public function getKeys()
	{
		if ($this->_memcached) {
			return $this->_memcached->keys('*');
		} else {
			return array();
		}
	}*/

	/*********************************************************************************
	 *
	 * Interface required functions
	 *
	 *********************************************************************************/

	public function getName($default = 'Memcached')
	{
		return $default;
	}

	public function getVersion()
	{
		$info = $this->_memcached->getVersion();
		$info = array_values($info);
		if (!isset($info[0])) {
			loadClass('Logger')->error('Could not get Memcached version');
			return '';
		}
		return $info[0];
	}
}
