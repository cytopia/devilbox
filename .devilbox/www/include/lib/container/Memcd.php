<?php
namespace devilbox;

/**
 * @requires devilbox::Logger
 */
class Memcd extends BaseClass implements BaseInterface
{
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

		if (class_exists('Memcached')) {
			$memcd = new \Memcached('_devilbox');
			$list = $memcd->getServerList();

			if (empty($list)) {
				$memcd->setOption(\Memcached::OPT_LIBKETAMA_COMPATIBLE, true);
				$memcd->setOption(\Memcached::OPT_BINARY_PROTOCOL, false);
				$memcd->addServer($hostname, 11211);
			}

			$err = false;
			$stats = $memcd->getStats();
			if (!isset($stats[$hostname.':11211'])) {
				$memcd->quit();
				$this->_connect_error = 'Failed to connect to Memcached host on '.$hostname.' (no connection array)';
				$this->_connect_errno = 1;
				return;
			}
			else if (!isset($stats[$hostname.':11211']['pid'])) {
				$memcd->quit();
				$this->_connect_error = 'Failed to connect to Memcached host on '.$hostname.' (no pid)';
				$this->_connect_errno = 2;
				return;
			}
			else if ($stats[$hostname.':11211']['pid'] < 1) {
				$memcd->quit();
				$this->_connect_error = 'Failed to connect to Memcached host on '.$hostname.' (invalid pid)';
				$this->_connect_errno = 3;
				return;
			}

			$memcd->set('devilbox-version', $GLOBALS['DEVILBOX_VERSION'].' ('.$GLOBALS['DEVILBOX_DATE'].')');
			$this->_memcached = $memcd;
		} else {

			$ret = 0;
			loadClass('Helper')->exec('echo "stats" | nc 127.0.0.1 11211', $ret);
			if ($ret == 0) {
				$this->_memcached = true;
			}
		}
	}

	/**
	 * Destructor
	 */
	public function __destruct()
	{
		if (class_exists('Memcached')) {
			if ($this->_memcached) {
				$this->_memcached->quit();
			}
		}
	}



	/*********************************************************************************
	 *
	 * Select functions
	 *
	 *********************************************************************************/


	public function getKeys()
	{
		$store = array();
		if (class_exists('Memcached')) {
			if ($this->_memcached) {
				if (!($keys = $this->_memcached->getAllKeys())) {
					$keys = array();
				}
				$this->_memcached->getDelayed($keys);
				$store = $this->_memcached->fetchAll();
				if (!is_array($store)) {
					$store = array();
				}
			}
		}
		return $store;
	}

	public function getInfo()
	{
		$stats = array();
		if (class_exists('Memcached')) {
			if ($this->_memcached) {
				$stats = $this->_memcached->getStats();
			}
		} else {
			$ret = 0;
			$output = loadClass('Helper')->exec('echo "stats" | nc 127.0.0.1 11211 | sed "s/^STAT[[:space:]]*//g" | grep -v "END"', $ret);
			if ($ret == 0) {
				$output = explode("\n", $output);
				foreach ($output as $line) {
					$tmp = explode(' ', $line);
					$key = isset($tmp[0]) ? $tmp[0] : '';
					$val = isset($tmp[1]) ? $tmp[1] : '';
					$stats['127.0.0.1'][$key] = $val;
				}
			}
		}
		return $stats;
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

		if (class_exists('Memcached')) {
			// Silence errors and try to connect
			//error_reporting(-1);
			$memcd = new \Memcached();
			$memcd->resetServerList();


			if (!$memcd->addServer($hostname, 11211)) {
				$memcd->quit();
				$err = 'Failed to connect to Memcached host on '.$hostname;
				$this->_can_connect[$hostname] = false;
				$this->_can_connect_err[$hostname] = $err;
				return false;
			}

			$stats = $memcd->getStats();
			if (!isset($stats[$hostname.':11211'])) {
				$err = 'Failed to connect to Memcached host on '.$hostname;
				$this->_can_connect[$hostname] = false;
			}
			else if (!isset($stats[$hostname.':11211']['pid'])) {
				$err = 'Failed to connect to Memcached host on '.$hostname;
				$this->_can_connect[$hostname] = false;
			}
			else if ($stats[$hostname.':11211']['pid'] < 1) {
				$err = 'Failed to connect to Memcached host on '.$hostname;
				$this->_can_connect[$hostname] = false;
			}
			else {
				$this->_can_connect[$hostname] = true;
			}

			$memcd->quit();

			$this->_can_connect_err[$hostname] = $err;
		} else {

			$ret = 0;
			loadClass('Helper')->exec('echo "stats" | nc '.$hostname.' 11211', $ret);
			if ($ret == 0) {
				$this->_can_connect[$hostname] = true;
			} else {
				$err = 'Failed to connect to Memcached host on '.$hostname;
				$this->_can_connect[$hostname] = false;
			}
		}

		return $this->_can_connect[$hostname];
	}


	public function getName($default = 'Memcached')
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

		if (class_exists('Memcached')) {

			if ($this->_memcached) {
				$info = $this->_memcached->getVersion();
				$info = array_values($info);
				if (!isset($info[0])) {
					loadClass('Logger')->error('Could not get Memcached version');
					$this->_version = '';
				} else {
					$this->_version = $info[0];
				}
			}
		} else {
			$version = loadClass('Helper')->exec('echo "version" | nc 127.0.0.1 11211 | grep -oE "[0-9.-]+"', $ret);
			$this->_version = $version;
		}
		return $this->_version;
	}
}
