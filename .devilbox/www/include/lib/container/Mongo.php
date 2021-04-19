<?php
namespace devilbox;

/**
 * @requires devilbox::Logger
 */
class Mongo extends BaseClass implements BaseInterface
{
	/*********************************************************************************
	 *
	 * Private Variables
	 *
	 *********************************************************************************/

	/**
	 * MongoDB manager instance
	 * @var object|null
	 */
	private $_mongo = null;



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


		// Faster check if mongo is not loaded
		if (!$this->isAvailable()) {
			return;
		}


		$mongo = new \MongoDB\Driver\Manager('mongodb://'.$hostname);

		// MongoDB uses lazy loading of server list
		// so just execute an arbitrary command in order
		// to make it populate the server list
		$command = new \MongoDB\Driver\Command(array('ping' => 1));

		try {
			$mongo->executeCommand('admin', $command);
		} catch (\MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
			$this->_connect_error = $e;
			$this->_connect_errno = 1;
			return;
		}

		// retrieve server list
		$servers = $mongo->getServers();

		if (!isset($servers[0])) {
			$this->_connect_error = 'Failed to connect to MongoDB host on '.$hostname.' (No host info available)';
			$this->_connect_errno = 2;
			return;
		} else if ($servers[0]->getHost() != $hostname) {
			$this->_connect_error = 'Failed to connect to MongoDB host on '.$hostname.' (servername does not match: '.$servers[0]->getHost().')';
			$this->_connect_errno = 3;
			return;
		}
		$this->_mongo = $mongo;
	}




	/*********************************************************************************
	 *
	 * Select functions
	 *
	 *********************************************************************************/


	/**
	 * Execute MongoDB command and return iteratable
	 * @param  array      $command Command
	 * @return iteratable
	 */
	private function command($command)
	{
		if ($this->_mongo) {
			try {
				$cmd = new \MongoDB\Driver\Command($command);
				$cursor = $this->_mongo->executeCommand('admin', $cmd);
				return $cursor->toArray();
			} catch(\MongoDB\Driver\Exception $e) {
				loadClass('Logger')->error($e->getMessage().'. Could not execute MongoDB command: '.print_r($command, true));
			}
		}

		return array();
	}


	/**
	 * Get all MongoDB Databases.
	 * @return mixed[] Array of databases
	 */
	public function getDatabases()
	{
		$databases = array();
		$tmp = $this->command(array('listDatabases' => true));
		if (isset($tmp[0])) {
			if (is_array($tmp[0])) {
				foreach ($tmp[0]['databases'] as $db) {
					$databases[] = array(
						'name' => $db->name,
						'size' => $db->sizeOnDisk,
						'empty' => $db->empty
					);
				}
			} else {
				foreach ($tmp[0]->databases as $db) {
					$databases[] = array(
						'name' => $db->name,
						'size' => $db->sizeOnDisk,
						'empty' => $db->empty
					);
				}
			}
		}

		return $databases;
	}



	public function getInfo()
	{
		$info = array();
		$tmp = $this->command(array('serverStatus' => true));

		if (isset($tmp[0])) {
			$info = $tmp[0];
		}
		return $info;
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

		$manager = new \MongoDB\Driver\Manager('mongodb://'.$hostname);

		// MongoDB uses lazy loading of server list
		// so just execute an arbitrary command in order
		// to make it populate the server list
		$command = new \MongoDB\Driver\Command(array('ping' => 1));
		$manager->executeCommand('admin', $command);

		// retrieve server list
		$servers = $manager->getServers();

		if (!isset($servers[0])) {
			$err = 'Failed to connect to MongoDB host on '.$hostname.' (No host info available)';
			$this->_can_connect[$hostname] = false;
		} else if ($servers[0]->getHost() != $hostname) {
			$err = 'Failed to connect to MongoDB host on '.$hostname.' (servername does not match: '.$servers[0]->getHost().')';
			$this->_can_connect[$hostname] = false;
		}
		else {
			$this->_can_connect[$hostname] = true;
		}

		$this->_can_connect_err[$hostname] = $err;
		return $this->_can_connect[$hostname];
	}


	public function getName($default = 'MongoDB')
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

		if ($this->_mongo) {
			$info = $this->getInfo();
			if (!isset($info->version)) {
				loadClass('Logger')->error('Could not get MongoDB version');
				$this->_version = '';
			} else {
				$this->_version = $info->version;
			}
		}
		return $this->_version;
	}

	public function isAvailable()
	{
		if (extension_loaded('mongo') || extension_loaded('mongodb')) {
			return parent::isAvailable();
		}

		// when php modules 'mongo' or 'mongodb' not available or just disable by configuration (see .env PHP_MODULES_DISABLE)
		return false;
	}
}
