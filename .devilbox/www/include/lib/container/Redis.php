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
				if(array_key_exists('pass', $data)){
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
}
