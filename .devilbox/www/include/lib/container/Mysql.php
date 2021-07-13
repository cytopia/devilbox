<?php
namespace devilbox;

/**
 * @requires devilbox::Logger
 */
class Mysql extends BaseClass implements BaseInterface
{

	/*********************************************************************************
	 *
	 * Variables
	 *
	 *********************************************************************************/

	/**
	 * MySQL connection link
	 * @var null
	 */
	private $_link = null;



	/*********************************************************************************
	 *
	 * Constructor Overwrite
	 *
	 *********************************************************************************/

	public function __construct($hostname, $data = array())
	{
		parent::__construct($hostname, $data);

		// Faster check if mysql is not loaded
		if (!$this->isAvailable()) {
			return;
		}

		$user = $data['user'];
		$pass = $data['pass'];


		// Silence errors and try to connect
		error_reporting(0);
		$link = @mysqli_connect($hostname, $user, $pass);
		error_reporting(-1);

		if (mysqli_connect_errno()) {
			$this->setConnectError('Failed to connect: ' .mysqli_connect_error());
			$this->setConnectErrno(mysqli_connect_errno());
			//loadClass('Logger')->error($this->_connect_error);
		} else {
			$this->_link = $link;
		}
	}

	public function __destruct()
	{
		if ($this->_link) {
			mysqli_close($this->_link);
		}
	}


	/*********************************************************************************
	 *
	 * Select Functions
	 *
	 *********************************************************************************/

	/**
	 * Query Database
	 *
	 * @param  string   $query    MySQL Query
	 * @param  function $callback Callback function
	 * @return mixed[]
	 */
	public function select($query, $callback = null)
	{
		if (!$this->_link) {
			loadClass('Logger')->error('MySQL error, link is no resource in select(): '.$query);
			return false;
		}

		if (!($result = mysqli_query($this->_link, $query))) {
			$this->setError(mysqli_error($this->_link));
			$this->setErrno(mysqli_errno($this->_link));
			loadClass('Logger')->error($this->getError());
			return false;
		}

		$data	= array();

		if ($callback) {
			while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
				$callback($row, $data);
			}
		} else {
			while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
				$data[] = $row;
			}
		}
		mysqli_free_result($result);

		return $data;
	}

	/**
	 * Get all MySQL Databases.
	 * @return mixed[] Array of databases
	 */
	public function getDatabases()
	{
		$callback = function ($row, &$data) {
			$data[$row['database']] = array(
				'charset'	=> $row['charset'],
				'collation'	=> $row['collation']
			);
		};

		$sql = "SELECT
					S.SCHEMA_NAME AS 'database',
					S.DEFAULT_COLLATION_NAME AS 'collation',
					S.default_character_set_name AS 'charset'
				FROM
					information_schema.SCHEMATA AS S;";

		$databases = $this->select($sql, $callback);

		return $databases ? $databases : array();
	}

	/**
	 * Get Database size in Megabytes.
	 *
	 * @param  string $database Database name.
	 * @return integer
	 */
	public function getDBSize($database)
	{
		$callback = function ($row, &$data) {
			$data = $row['size'];
		};

		$sql = "SELECT
					ROUND( SUM((T.data_length+T.index_length)/1048576), 2 ) AS 'size'
				FROM
					information_schema.TABLES AS T
				WHERE
					T.TABLE_SCHEMA = '".$database."';";

		$size = $this->select($sql, $callback);
		return $size ? $size : 0;

	}

	/**
	 * Get Number of Tables per Database
	 *
	 * @param  string $database Database name.
	 * @return integer
	 */
	public function getTableCount($database)
	{
		$callback = function ($row, &$data) {
			$data = $row['count'];
		};

		$sql = "SELECT
					COUNT(*) AS 'count'
				FROM
					information_schema.TABLES AS T
				WHERE
					T.TABLE_SCHEMA = '".$database."';";

		$count = $this->select($sql, $callback);
		return $count ? $count : 0;
	}


	/**
	 * Read out MySQL Server configuration by variable
	 *
	 * @param  string|null $key Config key name
	 * @return string|mixed[]
	 */
	public function getConfig($key = null)
	{
		// Get all configs as array
		if ($key === null) {
			$callback = function ($row, &$data) {
				$key = $row['Variable_name'];
				$val = $row['Value'];
				$data[$key] = $val;
			};

			$config = $this->select('SHOW VARIABLES;', $callback);
			if (!$config) {
				$config = array();
			}
			return $config;

		} else { // Get single config

			$key = str_replace('-', '_', $key);

			$callback = function ($row, &$data) use ($key) {
				$data = isset($row['Value']) ? $row['Value'] : false;
			};

			$sql = 'SHOW VARIABLES WHERE Variable_Name = "'.$key.'";';
			$val = $this->select($sql, $callback);

			if (is_array($val) && $val) {
				$array_values = array_values($val);
				return $array_values[0];
			} else {
				return $val;
			}
		}
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
		error_reporting(0);
		$link = mysqli_connect($hostname, $data['user'], $data['pass']);
		error_reporting(-1);

		if (mysqli_connect_errno()) {
			$err = 'Failed to connect: ' .mysqli_connect_error();
			$this->_can_connect[$hostname] = false;
		} else {
			$this->_can_connect[$hostname] = true;
		}

		if ($link) {
			mysqli_close($link);
		}

		$this->_can_connect_err[$hostname] = $err;
		return $this->_can_connect[$hostname];
	}

	public function getName($default = 'MySQL')
	{
		// Return if already cached
		if ($this->_name !== null) {
			return $this->_name;
		}

		// Return default if not available
		if (!$this->isAvailable()) {
			return $default;
		}

		$name = loadClass('Helper')->egrep('/[a-zA-Z0-9]+/', $this->getConfig('version_comment'));

		if (!$name) {
			loadClass('Logger')->error('Could not get MySQL Name');
			$this->_name = $default;
		} else {
			$this->_name = $name;
		}

		return $this->_name;
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

		$version = loadClass('Helper')->egrep('/[.0-9]+/', $this->getConfig('version'));

		if (!$version) {
			loadClass('Logger')->error('Could not get MySQL Version');
			$this->_version = '';
		} else {
			$this->_version = $version;
		}

		return $this->_version;
	}

	public function isAvailable()
	{
		if (extension_loaded('mysqli')) {
			return parent::isAvailable();
		}

		// when php module 'mysqli' not available or just disable by configuration (see .env PHP_MODULES_DISABLE)
		return false;
	}
}
