<?php
namespace devilbox;

/**
 * @requires devilbox::Logger
 */
class Pgsql extends BaseClass implements BaseInterface
{
	/*********************************************************************************
	 *
	 * Variables
	 *
	 *********************************************************************************/

	/**
	 * PgSQL connection link
	 * @var null
	 */
	private $_link = null;



	/*********************************************************************************
	 *
	 * Constructor Overwrite
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
	public function __construct($hostname, $data = array())
	{
		parent::__construct($hostname, $data);

		// Faster check if pgsql is not loaded
		if (!$this->isAvailable()) {
			return;
		}

		$user = $data['user'];
		$pass = $data['pass'];
		$db = isset($data['db']) ? $data['db'] : null;

		// Silence errors and try to connect
		error_reporting(0);
		if ($db !== null) {
			$link = @pg_connect('host='.$hostname.' dbname='.$db.' user='.$user.' password='.$pass);
		} else {
			$link = @pg_connect('host='.$hostname.' user='.$user.' password='.$pass);
		}
		error_reporting(-1);

		if (!$link || pg_connection_status($link) !== PGSQL_CONNECTION_OK) {
			$this->setConnectError('Failed to connect to '.$user.'@'.$hostname);
			$this->setConnectErrno(1);
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
			// DO NOT CLOSE. It is kind of shared.
			//pg_close($this->_link);
		}
	}




	/*********************************************************************************
	 *
	 * Select functions
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
			loadClass('Logger')->error('Postgres error, link is no resource in select(): \''.$this->_link.'\'');
			return false;
		}
		if (!($result = pg_query($this->_link, $query))) {
			$this->setError('PostgreSQL - error on result: '.pg_result_error($result)."\n" . 'query:'."\n" . $query);
			$this->setErrno(1);
			loadClass('Logger')->error($this->getError());
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
			$PSQL = new Pgsql(
				$GLOBALS['PGSQL_HOST_NAME'],
				array(
					'user' => loadClass('Helper')->getEnv('PGSQL_ROOT_USER'),
					'pass' => loadClass('Helper')->getEnv('PGSQL_ROOT_PASSWORD'),
					'db' => $name
				)
			);

			//$sql = "SELECT n.nspname AS schemas FROM pg_catalog.pg_namespace AS n WHERE n.nspname !~ '^pg_' AND n.nspname <> 'information_schema';";
			$sql = "SELECT n.nspname AS schemas FROM pg_catalog.pg_namespace AS n;";
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
		$PSQL = new Pgsql(
			$GLOBALS['PGSQL_HOST_NAME'],
			array(
				'user' => loadClass('Helper')->getEnv('PGSQL_ROOT_USER'),
				'pass' => loadClass('Helper')->getEnv('PGSQL_ROOT_PASSWORD'),
				'db' => $database
			)
		);

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
		$PSQL = new Pgsql(
			$GLOBALS['PGSQL_HOST_NAME'],
			array(
				'user' => loadClass('Helper')->getEnv('PGSQL_ROOT_USER'),
				'pass' => loadClass('Helper')->getEnv('PGSQL_ROOT_PASSWORD'),
				'db' => $database
			)
		);

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


	/**
	 * Read out PostgreSQL Server configuration by variable
	 *
	 * @param  string|null $key Config key name
	 * @return string|mixed[]
	 */
	public function getConfig($key = null)
	{
		// Get all configs as array
		if ($key === null) {
			$callback = function ($row, &$data) {
				$key = $row['name'];
				$val = $row['setting'];
				$data[$key] = $val;
			};

			$sql = 'SELECT name, setting FROM pg_settings;';
			$configs = $this->select($sql, $callback);

			return $configs ? $configs : array();

		} else { // Get single config

			$callback = function ($row, &$data) use ($key) {
				$data = isset($row['setting']) ? $row['setting'] : false;
			};

			$sql = "SELECT name, setting FROM pg_settings WHERE name = '".$key."';";
			$val = $this->select($sql, $callback);

			return is_array($val) ? '' : $val;
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
		$link = pg_connect('host='.$hostname.' user='.$data['user'].' password='.$data['pass']);
		error_reporting(-1);

		if (!$link || pg_connection_status($link) !== PGSQL_CONNECTION_OK) {
			$err = 'Failed to connect to host: '.$hostname;
			$this->_can_connect[$hostname] = false;
		} else {
			$this->_can_connect[$hostname] = true;
		}
		if ($link) {
			pg_close($link);
		}

		$this->_can_connect_err[$hostname] = $err;
		return $this->_can_connect[$hostname];
	}

	public function getName($default = 'PostgreSQL')
	{
		// Return if already cached
		if ($this->_name !== null) {
			return $this->_name;
		}

		// Return default if not available
		if (!$this->isAvailable()) {
			return $default;
		}

		$callback = function ($row, &$data) {
			$data = $row['version'];
		};

		$name = loadClass('Helper')->egrep('/[a-zA-Z0-9]*/', $this->select('SELECT version();', $callback));

		if (!$name) {
			loadClass('Logger')->error('Could not get PgSQL Name');
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

		$callback = function ($row, &$data) {
			$data = $row['version'];
		};

		$version = loadClass('Helper')->egrep('/[.0-9]+/', $this->select('SELECT version();', $callback));

		if (!$version) {
			loadClass('Logger')->error('Could not get PgSQL version');
			$this->_version = '';
		} else {
			$this->_version = $version;
		}

		return $this->_version;
	}

	public function isAvailable()
	{
		if (extension_loaded('pgsql')) {
			return parent::isAvailable();
		}

		// when php module 'pgsql' not available or just disable by configuration (see .env PHP_MODULES_DISABLE)
		return false;
	}
}
