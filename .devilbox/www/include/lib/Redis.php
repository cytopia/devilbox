<?php
namespace devilbox;

/**
 * @requires devilbox::Logger
 */
class Redis
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
	public static function getInstance($host = null)
	{
		if (!isset(static::$instance)) {
			static::$instance = new static($host);
		}
		// If current Redis instance was unable to connect
		if (!static::$instance) {
			loadClass('Logger')->error('Instance has errors:' . "\r\n" . var_export(static::$instance, true) . "\r\n");
			//return null;
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
	public static function testConnection(&$err, $host)
	{
		$err = false;

		// Silence errors and try to connect
		error_reporting(0);
		$redis = new \Redis();

		if (!$redis->connect($host, 6379)) {
			$err = 'Failed to connect to Redis host on '.$host.': ' .$redis->getLastError();
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

	/**
	 * Connection error string
	 * @var string
	 */
	private $_connect_error = '';

	/**
	 * Connection error code
	 * @var integer
	 */
	private $_connect_errno = 0;

	/**
	 * Error string
	 * @var string
	 */
	private $_error = '';


	/**
	 * Error code
	 * @var integer
	 */
	private $_errno = 0;



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
			$this->_connect_error = 'Failed to connect to Redis host on '.$host.': ' .$redis->getLastError();
			$this->_connect_errno = 1;
			loadClass('Logger')->error($this->_connect_error);
		} else {
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

	public function getVersion()
	{
		$info = $this->_redis->info();
		return 'Redis '.$info['redis_version'];
	}

	public function getInfo()
	{
		return $this->_redis->info('all');
	}

	public function getKeys()
	{
		return $this->_redis->keys('*');
	}


	/*********************************************************************************
	 *
	 * MySQL Error functions
	 *
	 *********************************************************************************/

	/**
	 * Return connection error message.
	 *
	 * @return string Error message
	 */
	public function getConnectError()
	{
		return $this->_connect_error;
	}

	/**
	 * Return connection errno code.
	 *
	 * @return integer Error code
	 */
	public function getConnectErrno()
	{
		return $this->_connect_errno;
	}

	/**
	 * Return error message.
	 *
	 * @return string Error message
	 */
	public function getError()
	{
		return $this->_error;
	}

	/**
	 * Return errno code.
	 *
	 * @return integer Error code
	 */
	public function getErrno()
	{
		return $this->_errno;
	}
}
