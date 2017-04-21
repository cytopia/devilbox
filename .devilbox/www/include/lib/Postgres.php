<?php
namespace devilbox;

/**
 * @requires devilbox::Logger
 */
class Postgres
{

	/*********************************************************************************
	 *
	 * Statics
	 *
	 *********************************************************************************/

	/**
	 * Postgres instance
	 * @var Postgres|null
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
		// If current Postgres instance was unable to connect
		if ((static::$instance->getConnectError())) {
			loadClass('Logger')->error('Instance has errors:' . "\r\n" . var_export(static::$instance, true) . "\r\n");
			//return null;
		}
		return static::$instance;
	}

	/**
	 * Connect to database
	 *
	 * @param  string $err  Reference to error message
	 * @param  string $user Postgres username
	 * @param  string $pass Postgres password
	 * @param  string $host Postgres hostname
	 * @return boolean
	 */
	public static function testConnection(&$err, $user, $pass, $host)
	{
		$err = false;

		// Silence errors and try to connect
		error_reporting(0);
		$link = pg_connect('host='.$host.' user='.$user.' password='.$pass);
		error_reporting(-1);

		if (!$link || pg_connection_status($link) !== PGSQL_CONNECTION_OK) {
			$err = 'Failed to connect: ' .pg_last_error($link);
			return false;
		}
		pg_close($link);
		return true;
	}



	/*********************************************************************************
	 *
	 * Private Variables
	 *
	 *********************************************************************************/

	/**
	 * Postgres Resource
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
	 * @param string $user     Username
	 * @param string $pass     Password
	 * @param string $host     Host
	 * @param string $database Database name
	 */
	public function __construct($user, $pass, $host, $database = null)
	{
		// Silence errors and try to connect
		error_reporting(0);
		if ($database !== null) {
			$link = pg_connect('host='.$host.' dbname='.$database.' user='.$user.' password='.$pass);
		} else {
			// NOTE: using dbname=postgres prevents HHVM from segfaulting
			$link = pg_connect('host='.$host.' user='.$user.' password='.$pass);
		}
		error_reporting(-1);

		if (!$link || pg_connection_status($link) !== PGSQL_CONNECTION_OK) {
			$this->_connect_error = 'Failed to connect to '.$user.'@'.$host;
			$this->_connect_errno = 1;
			loadClass('Logger')->error($this->_connect_error);
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
			pg_close($this->_link);
		}
	}

	/*********************************************************************************
	 *
	 * PostgreSQL  Select functions
	 *
	 *********************************************************************************/

	/**
	 * Query Database
	 *
	 * @param  string   $query    Postgres Query
	 * @param  function $callback Callback function
	 * @return mixed[]
	 */
	public function select($query, $callback = null)
	{
		if (!$this->_link) {
			loadClass('Logger')->error('Postgres error, link is no resource in select()');
			return false;
		}

		if (!($result = pg_query($this->_link, $query))) {
			$this->_error = 'PostgreSQL - error on result: '.pg_result_error($result)."\n" . 'query:'."\n" . $query;
			$this->_errno = 1;
			loadClass('Logger')->error($this->_error);
			return false;
		}

		$data	= array();

		if ($callback) {
			while ($row = pg_fetch_assoc($result)) {
				$callback($row, $data);
			}
		} else {
			while ($row = pg_fetch_assoc($result)) {
				$data[] = $row;
			}
		}
		pg_free_result($result);

		return $data;
	}

	/**
	 * Get all PostgreSQL Databases.
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

		$sql = 'SELECT
					S.datname AS database,
					S.datcollate AS collation,
					pg_encoding_to_char(S.encoding) AS charset
				FROM
					pg_database AS S
				WHERE datistemplate = false;';

		$databases = $this->select($sql, $callback);
		$databases = $databases ? $databases : array();

		// Get schemas for each database
		foreach ($databases as $name => &$database) {
			$PSQL = new Postgres('postgres', loadClass('Docker')->getEnv('POSTGRES_PASSWORD'), $GLOBALS['POSTGRES_HOST_ADDR'], $name);

			$sql = "SELECT n.nspname AS schemas FROM pg_catalog.pg_namespace AS n WHERE n.nspname !~ '^pg_' AND n.nspname <> 'information_schema';";
			$callback = function ($row, &$data) {
				$data[$row['schemas']] = array();
			};
			$schemas = $PSQL->select($sql, $callback);
			$databases[$name]['schemas'] = $schemas;
		}

		return $databases;
	}


	/**
	 * Get Schema size in Megabytes.
	 *
	 * @param  string $database Database name.
	 * @param  string $schema   Schema name.
	 * @return integer
	 */
	public function getSchemaSize($database, $schema)
	{
		$PSQL = new Postgres('postgres', loadClass('Docker')->getEnv('POSTGRES_PASSWORD'), $GLOBALS['POSTGRES_HOST_ADDR'], $database);
		$callback = function ($row, &$data) {
			$data = $row['size'];

		};
		$sql = "SELECT
					ROUND(sum(table_size) / 1048576, 2) AS size
				FROM (
					SELECT pg_catalog.pg_namespace.nspname AS schema_name,
					pg_relation_size(pg_catalog.pg_class.oid) AS table_size
					FROM pg_catalog.pg_class
					JOIN pg_catalog.pg_namespace ON relnamespace = pg_catalog.pg_namespace.oid
					WHERE pg_catalog.pg_namespace.nspname = '".$schema."'
				) t
				GROUP BY schema_name;";

		$size = $PSQL->select($sql, $callback);
		return $size ? $size : 0;
	}

	/**
	 * Get Number of Tables per Schema
	 *
	 * @param  string $database Database name.
	 * @param  string $schema   Schema name.
	 * @return integer
	 */
	public function getTableCount($database, $schema)
	{
		$PSQL = new Postgres('postgres', loadClass('Docker')->getEnv('POSTGRES_PASSWORD'), $GLOBALS['POSTGRES_HOST_ADDR'], $database);
		$callback = function ($row, &$data) {
			$data = $row['count'];
		};

		$sql = "SELECT
					COUNT(*) AS count
				FROM
					information_schema.tables
				WHERE
					table_schema = '".$schema."'
				AND
					table_type = 'BASE TABLE';
				";

		$count = $PSQL->select($sql, $callback);
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
