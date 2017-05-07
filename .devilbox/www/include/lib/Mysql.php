<?php
namespace devilbox;

/**
 * @requires devilbox::Logger
 */
class Mysql extends _Base implements _iBase
{

	/*********************************************************************************
	 *
	 * Statics
	 *
	 *********************************************************************************/

	/**
	 * Mysql instance
	 * @var Mysql|null
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
	public static function getInstance($host, $user, $pass)
	{
		if (!isset(static::$instance)) {
			static::$instance = new static($user, $pass, $host);
		}
		// If current MySQL instance was unable to connect
		if ((static::$instance->getConnectError())) {
		//	loadClass('Logger')->error('Instance has errors:' . "\r\n" . var_export(static::$instance, true) . "\r\n");
		}
		return static::$instance;
	}

	/**
	 * Connect to database
	 *
	 * @param  string $err  Reference to error message
	 * @param  string $user MySQL username
	 * @param  string $pass MySQL password
	 * @param  string $host MySQL hostname
	 * @return boolean
	 */
	public static function testConnection(&$err, $host, $user, $pass)
	{
		$err = false;

		// Silence errors and try to connect
		error_reporting(0);
		$link = mysqli_connect($host, $user, $pass);
		error_reporting(-1);

		if (mysqli_connect_errno()) {
			$err = 'Failed to connect: ' .mysqli_connect_error();
			return false;
		}
		mysqli_close($link);
		return true;
	}



	/*********************************************************************************
	 *
	 * Private Variables
	 *
	 *********************************************************************************/

	/**
	 * MySQL Resource
	 * @var resource|null
	 */
	private $_link = null;



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
	public function __construct($user, $pass, $host)
	{
		// Silence errors and try to connect
		error_reporting(0);
		$link = mysqli_connect($host, $user, $pass);
		error_reporting(-1);

		if (mysqli_connect_errno()) {
			$this->setConnectError('Failed to connect: ' .mysqli_connect_error());
			$this->setConnectErrno(mysqli_connect_errno());
			//loadClass('Logger')->error($this->_connect_error);
		} else {
			$this->_link = $link;
		}
	}

	/**
	 * Destructor
	 */
	public function __destruct()
	{
		if ($this->_link) {
			mysqli_close($this->_link);
		}
	}

	/*********************************************************************************
	 *
	 * MySQL  Select functions
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

			return $this->select('SHOW VARIABLES;', $callback);

		} else { // Get single config

			$key = str_replace('-', '_', $key);

			$callback = function ($row, &$data) use ($key) {
				$data = isset($row['Value']) ? $row['Value'] : false;
			};

			$sql = 'SHOW VARIABLES WHERE Variable_Name = "'.$key.'";';
			$val = $this->select($sql, $callback);

			if (is_array($val) && $val) {
				return array_values($val)[0];
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

	/**
	 * Get MySQL Name.
	 *
	 * @return string MySQL short name.
	 */
	public function getName($default = 'MySQL')
	{
		if (!static::isAvailable('mysql')) {
			return $default;
		}

		$name = $this->egrep('/[a-zA-Z0-9]+/', $this->getConfig('version_comment'));

		if (!$name) {
			loadClass('Logger')->error('Could not get MySQL Name');
			return $default;
		}
		return $name;
	}


	/**
	 * Get MySQL Version.
	 *
	 * @return string MySQL version.
	 */
	public function getVersion()
	{
		if (!static::isAvailable('mysql')) {
			return '';
		}

		$version = $this->egrep('/[.0-9]+/', $this->getConfig('version'));

		if (!$version) {
			loadClass('Logger')->error('Could not get MySQL version');
			return '';
		}
		return $version;
	}
}
