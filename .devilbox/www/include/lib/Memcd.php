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
		$memcd = new \Memcached('_devilbox');
		$list = $memcd->getServerList();

//		if (!empty($list)) {
//			$memcd->resetServerList();
//		}
		if (empty($list)) {
			//$memcd->setOption(\Memcached::OPT_RECV_TIMEOUT, 100);
			//$memcd->setOption(\Memcached::OPT_SEND_TIMEOUT, 100);
			$memcd->setOption(\Memcached::OPT_LIBKETAMA_COMPATIBLE, true);
			$memcd->setOption(\Memcached::OPT_BINARY_PROTOCOL, false);
			//$memcd->setOption(\Memcached::OPT_SERVER_FAILURE_LIMIT, 50);
			//$memcd->setOption(\Memcached::OPT_CONNECT_TIMEOUT, 100);
			//$memcd->setOption(\Memcached::OPT_RETRY_TIMEOUT, 100);
			//$memcd->setOption(\Memcached::OPT_REMOVE_FAILED_SERVERS, true);
			$memcd->addServer($host, 11211);
		}

		$err = false;
		$stats = $memcd->getStats();
		if (!isset($stats[$host.':11211'])) {
			$memcd->quit();
			$err = 'Failed to connect to Memcached host on '.$host;
		}
		else if (!isset($stats[$host.':11211']['pid'])) {
			$memcd->quit();
			$err = 'Failed to connect to Memcached host on '.$host;
		}
		else if ($stats[$host.':11211']['pid'] < 1) {
			$memcd->quit();
			$err = 'Failed to connect to Memcached host on '.$host;
		}

		else if ($err === false) {
			$memcd->set('devilbox-version', $GLOBALS['DEVILBOX_VERSION'].' ('.$GLOBALS['DEVILBOX_DATE'].')');
			$this->_memcached = $memcd;
		} else {
			$this->_connect_error = 'Failed to connect to Memcached host on '.$host;
			$this->_connect_errno = 1;
			//loadClass('Logger')->error($this->_connect_error);
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


	public function getKeys()
	{
		$store = array();
		if ($this->_memcached) {
			if (!($keys = $this->_memcached->getAllKeys())) {
				$keys = array();
			}
			$this->_memcached->getDelayed($keys);
			$store = $this->_memcached->fetchAll();
		}
		return $store;
	}

	public function getInfo()
	{
		$stats = array();
		if ($this->_memcached) {
			$stats = $this->_memcached->getStats();
		}
		return $stats;

	}


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
		$version = '';
		if ($this->_memcached) {
			$info = $this->_memcached->getVersion();
			$info = array_values($info);
			if (!isset($info[0])) {
				loadClass('Logger')->error('Could not get Memcached version');
			} else {
				$version = $info[0];
			}
		}

		return $version;
	}
}
