<?php

/**
 * PostgreSQL 8.1 support
 *
 * $Id: Postgres81.php,v 1.21 2008/01/19 13:46:15 ioguix Exp $
 */

include_once('./classes/database/Postgres82.php');

class Postgres81 extends Postgres82 {

	var $major_version = 8.1;
	// List of all legal privileges that can be applied to different types
	// of objects.
	var $privlist = array(
		'table' => array('SELECT', 'INSERT', 'UPDATE', 'DELETE', 'RULE', 'REFERENCES', 'TRIGGER', 'ALL PRIVILEGES'),
		'view' => array('SELECT', 'INSERT', 'UPDATE', 'DELETE', 'RULE', 'REFERENCES', 'TRIGGER', 'ALL PRIVILEGES'),
		'sequence' => array('SELECT', 'UPDATE', 'ALL PRIVILEGES'),
		'database' => array('CREATE', 'TEMPORARY', 'ALL PRIVILEGES'),
		'function' => array('EXECUTE', 'ALL PRIVILEGES'),
		'language' => array('USAGE', 'ALL PRIVILEGES'),
		'schema' => array('CREATE', 'USAGE', 'ALL PRIVILEGES'),
		'tablespace' => array('CREATE', 'ALL PRIVILEGES')
	);
	// List of characters in acl lists and the privileges they
	// refer to.
	var $privmap = array(
		'r' => 'SELECT',
		'w' => 'UPDATE',
		'a' => 'INSERT',
		'd' => 'DELETE',
		'R' => 'RULE',
		'x' => 'REFERENCES',
		't' => 'TRIGGER',
		'X' => 'EXECUTE',
		'U' => 'USAGE',
		'C' => 'CREATE',
		'T' => 'TEMPORARY'
	);
	// Array of allowed index types
	var $typIndexes = array('BTREE', 'RTREE', 'GIST', 'HASH');

	/**
	 * Constructor
	 * @param $conn The database connection
	 */
	function __construct($conn) {
		parent::__construct($conn);
	}

	// Help functions
	
	function getHelpPages() {
		include_once('./help/PostgresDoc81.php');
		return $this->help_page;
	}

	// Database functions

	/**
	 * Returns all databases available on the server
	 * @return A list of databases, sorted alphabetically
	 */
	function getDatabases($currentdatabase = NULL) {
		global $conf, $misc;
		
		$server_info = $misc->getServerInfo();
		
		if (isset($conf['owned_only']) && $conf['owned_only'] && !$this->isSuperUser()) {
			$username = $server_info['username'];
			$this->clean($username);
			$clause = " AND pr.rolname='{$username}'";
		}
		else $clause = '';

		if ($currentdatabase != NULL) {
			$this->clean($currentdatabase);
			$orderby = "ORDER BY pdb.datname = '{$currentdatabase}' DESC, pdb.datname";
		}
		else
			$orderby = "ORDER BY pdb.datname";

		if (!$conf['show_system'])
			$where = ' AND NOT pdb.datistemplate';
		else
			$where = ' AND pdb.datallowconn';

		$sql = "SELECT pdb.datname AS datname, pr.rolname AS datowner, pg_encoding_to_char(encoding) AS datencoding,
                               (SELECT description FROM pg_catalog.pg_description pd WHERE pdb.oid=pd.objoid) AS datcomment,
                               (SELECT spcname FROM pg_catalog.pg_tablespace pt WHERE pt.oid=pdb.dattablespace) AS tablespace,
							   pg_catalog.pg_database_size(pdb.oid) as dbsize 
                        FROM pg_catalog.pg_database pdb LEFT JOIN pg_catalog.pg_roles pr ON (pdb.datdba = pr.oid)  
						WHERE true 
			{$where}
			{$clause}
			{$orderby}";

		return $this->selectSet($sql);
	}

	/**
	 * Alters a database
	 * the multiple return vals are for postgres 8+ which support more functionality in alter database
	 * @param $dbName The name of the database
	 * @param $newName new name for the database
	 * @param $newOwner The new owner for the database
	 * @return 0 success
	 * @return -1 transaction error
	 * @return -2 owner error
	 * @return -3 rename error
	 */
	function alterDatabase($dbName, $newName, $newOwner = '', $comment = '') {
		$this->clean($dbName);
		$this->clean($newName);
		$this->clean($newOwner);
		//ignore $comment, not supported pre 8.2
			
		$status = $this->beginTransaction();
		if ($status != 0) {
			$this->rollbackTransaction();
			return -1;
		}

		if ($dbName != $newName) {
			$status = $this->alterDatabaseRename($dbName, $newName);
			if ($status != 0) {
				$this->rollbackTransaction();
				return -3;
			}
		}

		$status = $this->alterDatabaseOwner($newName, $newOwner);
		if ($status != 0) {
			$this->rollbackTransaction();
			return -2;
		}
		return $this->endTransaction();
	}

	// Autovacuum functions

	function saveAutovacuum($table, $vacenabled, $vacthreshold, $vacscalefactor,
		$anathresold, $anascalefactor, $vaccostdelay, $vaccostlimit)
	{
		$defaults = $this->getAutovacuum();
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($table);

		$rs = $this->selectSet("
			SELECT c.oid
			FROM pg_catalog.pg_class AS c
				LEFT JOIN pg_catalog.pg_namespace AS n ON (n.oid=c.relnamespace)
			WHERE
				c.relname = '{$table}' AND n.nspname = '{$c_schema}'
		");

		if ($rs->EOF)
			return -1;

		$toid = $rs->fields('oid');
		unset ($rs);

		if (empty($_POST['autovacuum_vacuum_threshold']))
			$_POST['autovacuum_vacuum_threshold'] = $defaults['autovacuum_vacuum_threshold'];

		if (empty($_POST['autovacuum_vacuum_scale_factor']))
			$_POST['autovacuum_vacuum_scale_factor'] = $defaults['autovacuum_vacuum_scale_factor'];

		if (empty($_POST['autovacuum_analyze_threshold']))
			$_POST['autovacuum_analyze_threshold'] = $defaults['autovacuum_analyze_threshold'];

		if (empty($_POST['autovacuum_analyze_scale_factor']))
			$_POST['autovacuum_analyze_scale_factor'] = $defaults['autovacuum_analyze_scale_factor'];

		if (empty($_POST['autovacuum_vacuum_cost_delay']))
			$_POST['autovacuum_vacuum_cost_delay'] = $defaults['autovacuum_vacuum_cost_delay'];

		if (empty($_POST['autovacuum_vacuum_cost_limit']))
			$_POST['autovacuum_vacuum_cost_limit'] = $defaults['autovacuum_vacuum_cost_limit'];

		$rs = $this->selectSet("SELECT vacrelid
			FROM \"pg_catalog\".\"pg_autovacuum\"
			WHERE vacrelid = {$toid};");

		$status = -1; // ini
		if ($rs->recordCount() and ($rs->fields['vacrelid'] == $toid)) {
			// table exists in pg_autovacuum, UPDATE
			$sql = sprintf("UPDATE \"pg_catalog\".\"pg_autovacuum\" SET
						enabled = '%s',
						vac_base_thresh = %s,
						vac_scale_factor = %s,
						anl_base_thresh = %s,
						anl_scale_factor = %s,
						vac_cost_delay = %s,
						vac_cost_limit = %s
					WHERE vacrelid = {$toid};
				",
				($_POST['autovacuum_enabled'] == 'on')? 't':'f',
				$_POST['autovacuum_vacuum_threshold'],
				$_POST['autovacuum_vacuum_scale_factor'],
				$_POST['autovacuum_analyze_threshold'],
				$_POST['autovacuum_analyze_scale_factor'],
				$_POST['autovacuum_vacuum_cost_delay'],
				$_POST['autovacuum_vacuum_cost_limit']
			);
			$status = $this->execute($sql);
		}
		else {
			// table doesn't exists in pg_autovacuum, INSERT
			$sql = sprintf("INSERT INTO \"pg_catalog\".\"pg_autovacuum\"
				VALUES (%s, '%s', %s, %s, %s, %s, %s, %s)",
				$toid,
				($_POST['autovacuum_enabled'] == 'on')? 't':'f',
				$_POST['autovacuum_vacuum_threshold'],
				$_POST['autovacuum_vacuum_scale_factor'],
				$_POST['autovacuum_analyze_threshold'],
				$_POST['autovacuum_analyze_scale_factor'],
				$_POST['autovacuum_vacuum_cost_delay'],
				$_POST['autovacuum_vacuum_cost_limit']
			);
			$status = $this->execute($sql);
		}

		return $status;
	}

	/**
	 * Returns all available process information.
	 * @param $database (optional) Find only connections to specified database
	 * @return A recordset
	 */
	function getProcesses($database = null) {
		if ($database === null)
			$sql = "SELECT datname, usename, procpid AS pid, current_query AS query, query_start, 
                  case when (select count(*) from pg_locks where pid=pg_stat_activity.procpid and granted is false) > 0 then 't' else 'f' end as waiting  
				FROM pg_catalog.pg_stat_activity
				ORDER BY datname, usename, procpid";
		else {
			$this->clean($database);
			$sql = "SELECT datname, usename, procpid AS pid, current_query AS query, query_start
                    case when (select count(*) from pg_locks where pid=pg_stat_activity.procpid and granted is false) > 0 then 't' else 'f' end as waiting 
				FROM pg_catalog.pg_stat_activity
				WHERE datname='{$database}'
				ORDER BY usename, procpid";
		}

		$rc = $this->selectSet($sql);

		return $rc;
	}

	// Tablespace functions
	
	/**
	 * Retrieves a tablespace's information
	 * @return A recordset
	 */
	function getTablespace($spcname) {
		$this->clean($spcname);

		$sql = "SELECT spcname, pg_catalog.pg_get_userbyid(spcowner) AS spcowner, spclocation
					FROM pg_catalog.pg_tablespace WHERE spcname='{$spcname}'";

		return $this->selectSet($sql);
	}
	
	/**
	 * Retrieves information for all tablespaces
	 * @param $all Include all tablespaces (necessary when moving objects back to the default space)
	 * @return A recordset
	 */
	function getTablespaces($all = false) {
		global $conf;
		
		$sql = "SELECT spcname, pg_catalog.pg_get_userbyid(spcowner) AS spcowner, spclocation
					FROM pg_catalog.pg_tablespace";

		if (!$conf['show_system'] && !$all) {
			$sql .= ' WHERE spcname NOT LIKE $$pg\_%$$';
		}
	
		$sql .= " ORDER BY spcname";

		return $this->selectSet($sql);
	}

	// Capabilities

	function hasCreateTableLikeWithConstraints() {return false;}
	function hasSharedComments() {return false;}
	function hasConcurrentIndexBuild() {return false;}
}

?>
