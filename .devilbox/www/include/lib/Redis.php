<?php
namespace devilbox;

/**
 * @requires devilbox::Logger
 */
class Redis extends _Base implements _iBase
{

	/*********************************************************************************
	 *
	 * Statics
	 *
	 *********************************************************************************/

	/**
	 * Redis instance
	 * @var Redis|null
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
		// If current Redis instance was unable to connect
		if (!static::$instance) {
			//loadClass('Logger')->error('Instance has errors:' . "\r\n" . var_export(static::$instance, true) . "\r\n");
		}
		return static::$instance;
	}

	/**
	 * Connect to Redis
	 *
	 * @param  string $err  Reference to error message
	 * @param  string $host Redis hostname
	 * @return boolean
	 */
	public static function testConnection(&$err, $host, $user = '', $pass = '')
	{
		$err = false;

		// Silence errors and try to connect
		error_reporting(0);
		$redis = new \Redis();

		if (!$redis->connect($host, 6379)) {
			$err = 'Failed to connect to Redis host on '.$host;
			error_reporting(-1);
			return false;
		}
		error_reporting(-1);
		$redis->close();
		return true;
	}



	/*********************************************************************************
	 *
	 * Private Variables
	 *
	 *********************************************************************************/

	/**
	 * Redis instance
	 * @var object|null
	 */
	private $_redis = null;




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
		// Silence errors and try to connect
		error_reporting(0);
		$redis = new \Redis();

		if (!$redis->connect($host, 6379)) {
			$this->setConnectError('Failed to connect to Redis host on '.$host);
			$this->setConnectErrno(1);
			//loadClass('Logger')->error($this->_connect_error);
		} else {
			$redis->set('devilbox-version', $GLOBALS['DEVILBOX_VERSION'].' ('.$GLOBALS['DEVILBOX_DATE'].')');
			$this->_redis = $redis;
		}
		error_reporting(-1);
	}

	/**
	 * Destructor
	 */
	public function __destruct()
	{
		if ($this->_redis) {
			$this->_redis->close();
		}
	}



	/*********************************************************************************
	 *
	 * Redis  Select functions
	 *
	 *********************************************************************************/

	public function getInfo()
	{
		if ($this->_redis) {
			return $this->_redis->info('all');
		} else {
			return array();
		}
	}

	public function getKeys()
	{
		$store = array();
		if ($this->_redis) {
			$keys = $this->_redis->keys('*');
			foreach ($keys as $key) {
				$store[$key] = $this->_redis->get($key);
			}
		}
		return $store;
	}



	/*********************************************************************************
	 *
	 * Interface required functions
	 *
	 *********************************************************************************/

	public function getName($default = 'Redis')
	{
		return $default;
	}

	public function getVersion()
	{
		$info = $this->getInfo();
		if (!isset($info['redis_version'])) {
			loadClass('Logger')->error('Could not get Redis version');
			return '';
		}
		return $info['redis_version'];
	}
}
