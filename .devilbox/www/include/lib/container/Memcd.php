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

		// Faster check if memcached is not loaded
		if (!$this->isAvailable()) {
			return;
		}

		if (class_exists('Memcached')) {
			$memcd = new \Memcached('_devilbox');
			$list = $memcd->getServerList();

			if (empty($list)) {
				$memcd->setOption(\Memcached::OPT_LIBKETAMA_COMPATIBLE, true);
				$memcd->setOption(\Memcached::OPT_BINARY_PROTOCOL, true);
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
			$memcd->getDelayed(array('devilbox-version'));
			if (!$memcd->fetchAll()) {
				$memcd->set('devilbox-version', $GLOBALS['DEVILBOX_VERSION'].' ('.$GLOBALS['DEVILBOX_DATE'].')');
			}
			$this->_memcached = $memcd;
		} else {

			$ret = 0;
			loadClass('Helper')->exec('printf "stats\nquit\n" | nc '.$hostname.' 11211', $ret);
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

		// CLI seems to only sometimes get the results, so we will just loop a bit
		// It's a very quick operation anyway.
		$cli_retries = 100;

		// Memcached >= 1.5
		for ($i=0; $i<$cli_retries; $i++) {

			// Get item number to trigger with stats cachedump
			$output = array();
			exec('printf "stats items\nquit\n" | nc memcd 11211 | grep -E \'items:[0-9]+:number\s[0-9]+\'', $output);
			$num1 = 1;
			$num2 = 0;
			if (isset($output[0])) {
				$matches = array();
				preg_match('/items:([0-9]+):number\s([0-9]+)/', $output[0], $matches);
				if (isset($matches[1])) {
					$num1 = $matches[1];
				}
				if (isset($matches[2])) {
					$num2 = $matches[2];
				}
			}

			// Trigger stats cachedump on item number
			$output = array();
			exec('printf "stats cachedump '.$num1.' '.$num2.' \nquit\n" | nc memcd 11211 | grep -E \'^ITEM\'', $output);
			foreach ($output as $line) {
				$matches = array();
				preg_match('/(^ITEM)\s*(.+?)\s*\[([0-9]+\s*b);\s*([0-9]+\s*s)\s*\]/', $line, $matches);
				$key = $matches[2];
				$store[] = array(
					'key' => $key,
					'val' => $this->_memcached->get($key),
					'ttl' =>  $matches[4],
					'size' => $matches[3],
				);
			}
			// If we actually got a result, we can break here
			if (count($store)) {
				return $store;
			}
		}

		// This will only work for Memcached < 1.5
		if (class_exists('Memcached')) {
			if ($this->_memcached) {

				// Ensure we retrieve data not in binary
				$this->_memcached->setOption(\Memcached::OPT_BINARY_PROTOCOL, false);

				if (!($keys = $this->_memcached->getAllKeys())) {
					$keys = array();
				}
				$this->_memcached->getDelayed($keys, true);
				$data = $this->_memcached->fetchAll();
				if (is_array($data)) {
					for ($i=0; $size=count($data), $i<$size; $i++) {
						$store[$i]['key'] = $data[$i]['key'];
						$store[$i]['val'] = $data[$i]['value'];
						$store[$i]['ttl'] = '?';
						$store[$i]['size'] = strlen($data[$i]['value']);
					}
				}
				// Revert Memcachd protocol
				$this->_memcached->setOption(\Memcached::OPT_BINARY_PROTOCOL, true);
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
			$output = loadClass('Helper')->exec('printf "stats\nquit\n" | nc memcd 11211 | sed "s/^STAT[[:space:]]*//g" | grep -v "END"', $ret);
			if ($ret == 0) {
				$output = explode("\n", $output);
				foreach ($output as $line) {
					$tmp = explode(' ', $line);
					$key = isset($tmp[0]) ? $tmp[0] : '';
					$val = isset($tmp[1]) ? $tmp[1] : '';
					$stats['memcd'][$key] = $val;
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

		$ret = 0;
		loadClass('Helper')->exec('printf "stats\nquit\n" | nc '.$hostname.' 11211', $ret);
		if ($ret == 0) {
			$this->_can_connect[$hostname] = true;
		} else {
			$err = 'Failed to connect to Memcached host on '.$hostname;
			$this->_can_connect[$hostname] = false;
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
			$version = loadClass('Helper')->exec('printf "version\nquit\n" | nc memcd 11211 | grep -oE "[0-9.-]+"', $ret);
			$this->_version = $version;
		}
		return $this->_version;
	}

	public function isAvailable()
	{
		if (extension_loaded('memcached')) {
			return parent::isAvailable();
		}

		// when php module 'memcached' not available or just disable by configuration (see .env PHP_MODULES_DISABLE)
		return false;
	}
}
