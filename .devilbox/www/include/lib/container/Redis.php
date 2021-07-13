<?php
namespace devilbox;

/**
 * @requires devilbox::Logger
 */
class Redis extends BaseClass implements BaseInterface
{
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
	 * Constructor Overwrite
	 *
	 *********************************************************************************/

	/**
	 * Use singleton getInstance() instead.
	 *
	 * @param string $user Username
	 * @param string $pass Password
	 * @param string $host Host
	 */
	public function __construct($hostname, $data = array())
	{
		parent::__construct($hostname, $data);

		if ($this->isAvailable()) {
			$redis = new \Redis();
			if (!@$redis->connect($hostname, 6379, 0.5, NULL)) {
				$this->setConnectError('Failed to connect to Redis host on '.$hostname);
				$this->setConnectErrno(1);
				//loadClass('Logger')->error($this->_connect_error);
			} else {
				if (array_key_exists('pass', $data)) {
					$redis->auth($data['pass']);
				}
				$redis->set('devilbox-version', $GLOBALS['DEVILBOX_VERSION'].' ('.$GLOBALS['DEVILBOX_DATE'].')');
				$this->_redis = $redis;
			}
		}
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
	 * Select functions
	 *
	 *********************************************************************************/
	public function flushDB($db)
	{
		if ($this->_redis) {
			if ( !$this->_redis->select($db) ) {
				return FALSE;
			}
			return $this->_redis->flushDb();
		} else {
			return FALSE;
		}
	}

	public function getInfo()
	{
		if ($this->_redis) {
			return $this->_redis->info('all');
		} else {
			return array();
		}
	}

	public function getDatabases()
	{
		$databases = array();

		foreach ($this->getInfo() as $key => $val) {
			if (preg_match('/db[0-9]+/', $key)) {
				$databases[] = str_replace('db', '', $key);
			}
		}
		return ($databases);
	}

	public function getKeys()
	{
		$store = array();
		if ($this->_redis) {
			$databases = $this->getDatabases();
			foreach ($databases as $db) {
				$this->_redis->select($db);
				$keys = $this->_redis->keys('*');
				foreach ($keys as $key) {

					switch($this->_redis->type($key)) {
						case \Redis::REDIS_STRING:
							$dtype = 'string';
							break;
						case \Redis::REDIS_SET:
							$dtype = 'set';
							break;
						case \Redis::REDIS_LIST:
							$dtype = 'list';
							break;
						case \Redis::REDIS_ZSET:
							$dtype = 'zset';
							break;
						case \Redis::REDIS_HASH:
							$dtype = 'hash';
							break;
						case \Redis::REDIS_NOT_FOUND:
							$dtype = 'other';
							break;
						default:
							$dtype = 'unknown';
					}
					$store[$db][] = array(
						'name' => $key,
						'val'  => $this->_redis->get($key),
						'type' => $dtype,
						'ttl'  => $this->_redis->ttl($key)
					);
				}
			}
		}
		return $store;
	}



	/*********************************************************************************
	 *
	 * Interface required functions
	 *
	 *********************************************************************************/

	private $_can_connect = array();
	private $_can_connect_err = array();

	private $_name = null;
	private $_version = null;

	public function canConnect(&$err, $hostname, $data = array())
	{
		$err = false;

		// Return if already cached
		if (isset($this->_can_connect[$hostname])) {
			// Assume error for unset error message
			$err = isset($this->_can_connect_err[$hostname]) ? $this->_can_connect_err[$hostname] : true;
			return $this->_can_connect[$hostname];
		}

		// Silence errors and try to connect
		//error_reporting(0);
		$redis = new \Redis();

		if (!$redis->connect($hostname, 6379)) {
			$err = 'Failed to connect to Redis host on '.$hostname;
			$this->_can_connect[$hostname] = false;
		} else {
			$this->_can_connect[$hostname] = true;
		}
		//error_reporting(-1);

		$redis->close();

		$this->_can_connect_err[$hostname] = $err;
		return $this->_can_connect[$hostname];
	}

	public function getName($default = 'Redis')
	{
		return $default;
	}

	public function getVersion()
	{
		// Return if already cached
		if ($this->_version !== null) {
			return $this->_version;
		}

		// Return empty if not available
		if (!$this->isAvailable()) {
			$this->_version = '';
			return $this->_version;
		}

		$info = $this->getInfo();
		if (!isset($info['redis_version'])) {
			loadClass('Logger')->error('Could not get Redis version');
			$this->_version = '';
		} else {
			$this->_version = $info['redis_version'];
		}

		return $this->_version;
	}

	public function isAvailable()
	{
		if (extension_loaded('redis')) {
			return parent::isAvailable();
		}

		// when php module 'redis' not available or just disable by configuration (see .env PHP_MODULES_DISABLE)
		return false;
	}
}
