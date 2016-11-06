<?php
namespace devilbox;

/**
 * @requires devilbox::Logger
 */
class Mysql
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
	public static function getInstance($user = null, $pass = null, $host = null)
	{
		if (!isset(static::$instance)) {
			static::$instance = new static($user, $pass, $host);
		}
		// If current MySQL instance was unable to connect
		if ((static::$instance->getConnectError())) {
			\devilbox\Logger::getInstance()->error('Instance has errors:' . "\r\n" . var_export(static::$instance, true) . "\r\n");
			//return null;
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
	public static function testConnection(&$err, $user, $pass, $host)
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
	public function __construct($user, $pass, $host)
	{
		// Silence errors and try to connect
		error_reporting(0);
		$link = mysqli_connect($host, $user, $pass);
		error_reporting(-1);

		if (mysqli_connect_errno()) {
			$this->_connect_error = 'Failed to connect: ' .mysqli_connect_error();
			$this->_connect_errno = mysqli_connect_errno();
			\devilbox\Logger::getInstance()->error($this->_connect_error);
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
			\devilbox\Logger::getInstance()->error('MySQL error, link is no resource in select()');
			return false;
		}

		if (!($result = mysqli_query($this->_link, $query))) {
			$this->_error = mysqli_error($this->_link);
			$this->_errno = mysqli_errno($this->_link);
			\devilbox\Logger::getInstance()->error($this->_error);
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
					information_schema.SCHEMATA AS S
				 WHERE
					S.SCHEMA_NAME != 'mysql' AND
					S.SCHEMA_NAME != 'performance_schema' AND
					S.SCHEMA_NAME != 'information_schema'";

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
