<?php

/**
 * PostgreSQL 8.3 support
 *
 * $Id: Postgres82.php,v 1.10 2007/12/28 16:21:25 ioguix Exp $
 */

include_once('./classes/database/Postgres84.php');

class Postgres83 extends Postgres84 {

	var $major_version = 8.3;

	// List of all legal privileges that can be applied to different types
	// of objects.
	var $privlist = array(
  		'table' => array('SELECT', 'INSERT', 'UPDATE', 'DELETE', 'REFERENCES', 'TRIGGER', 'ALL PRIVILEGES'),
  		'view' => array('SELECT', 'INSERT', 'UPDATE', 'DELETE', 'REFERENCES', 'TRIGGER', 'ALL PRIVILEGES'),
  		'sequence' => array('USAGE', 'SELECT', 'UPDATE', 'ALL PRIVILEGES'),
  		'database' => array('CREATE', 'TEMPORARY', 'CONNECT', 'ALL PRIVILEGES'),
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
  		'T' => 'TEMPORARY',
  		'c' => 'CONNECT'
	);

	/**
	 * Constructor
	 * @param $conn The database connection
	 */
	function __construct($conn) {
		parent::__construct($conn);
	}

	// Help functions

	function getHelpPages() {
		include_once('./help/PostgresDoc83.php');
		return $this->help_page;
	}

	// Database functions

	/**
	 * Return all database available on the server
	 * @param $currentdatabase database name that should be on top of the resultset
	 * 
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

		$sql = "
			SELECT pdb.datname AS datname, pr.rolname AS datowner, pg_encoding_to_char(encoding) AS datencoding,
				(SELECT description FROM pg_catalog.pg_shdescription pd WHERE pdb.oid=pd.objoid AND pd.classoid='pg_database'::regclass) AS datcomment,
				(SELECT spcname FROM pg_catalog.pg_tablespace pt WHERE pt.oid=pdb.dattablespace) AS tablespace,
				pg_catalog.pg_database_size(pdb.oid) as dbsize
			FROM pg_catalog.pg_database pdb LEFT JOIN pg_catalog.pg_roles pr ON (pdb.datdba = pr.oid)
			WHERE true
				{$where}
				{$clause}
			{$orderby}";

		return $this->selectSet($sql);
	}

	// Administration functions

	/**
	 * Returns all available autovacuum per table information.
	 * @return A recordset
	 */
	function getTableAutovacuum($table='') {
		$sql = '';

		if ($table !== '') {
			$this->clean($table);
			$c_schema = $this->_schema;
			$this->clean($c_schema);

			$sql = "
				SELECT vacrelid, nspname, relname, 
					CASE enabled 
						WHEN 't' THEN 'on' 
						ELSE 'off' 
					END AS autovacuum_enabled, vac_base_thresh AS autovacuum_vacuum_threshold,
					vac_scale_factor AS autovacuum_vacuum_scale_factor, anl_base_thresh AS autovacuum_analyze_threshold, 
					anl_scale_factor AS autovacuum_analyze_scale_factor, vac_cost_delay AS autovacuum_vacuum_cost_delay, 
					vac_cost_limit AS autovacuum_vacuum_cost_limit
				FROM pg_autovacuum AS a
					join pg_class AS c on (c.oid=a.vacrelid)
					join pg_namespace AS n on (n.oid=c.relnamespace)
				WHERE c.relname = '{$table}' AND n.nspname = '{$c_schema}'
				ORDER BY nspname, relname
			";
		}
		else {
			$sql = "
				SELECT vacrelid, nspname, relname, 
					CASE enabled 
						WHEN 't' THEN 'on' 
						ELSE 'off' 
					END AS autovacuum_enabled, vac_base_thresh AS autovacuum_vacuum_threshold,
					vac_scale_factor AS autovacuum_vacuum_scale_factor, anl_base_thresh AS autovacuum_analyze_threshold, 
					anl_scale_factor AS autovacuum_analyze_scale_factor, vac_cost_delay AS autovacuum_vacuum_cost_delay, 
					vac_cost_limit AS autovacuum_vacuum_cost_limit
				FROM pg_autovacuum AS a
					join pg_class AS c on (c.oid=a.vacrelid)
					join pg_namespace AS n on (n.oid=c.relnamespace)
				ORDER BY nspname, relname
			";
		}

		return $this->selectSet($sql);
	}
	
	function saveAutovacuum($table, $vacenabled, $vacthreshold, $vacscalefactor, $anathresold, 
		$anascalefactor, $vaccostdelay, $vaccostlimit) 
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
		
		if (empty($_POST['vacuum_freeze_min_age']))
			$_POST['vacuum_freeze_min_age'] = $defaults['vacuum_freeze_min_age'];
		
		if (empty($_POST['autovacuum_freeze_max_age']))
			$_POST['autovacuum_freeze_max_age'] = $defaults['autovacuum_freeze_max_age'];
		

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
						vac_cost_limit = %s,
						freeze_min_age = %s,
						freeze_max_age = %s
					WHERE vacrelid = {$toid};
				",
				($_POST['autovacuum_enabled'] == 'on')? 't':'f',
				$_POST['autovacuum_vacuum_threshold'],
				$_POST['autovacuum_vacuum_scale_factor'],
				$_POST['autovacuum_analyze_threshold'],
				$_POST['autovacuum_analyze_scale_factor'],
				$_POST['autovacuum_vacuum_cost_delay'],
				$_POST['autovacuum_vacuum_cost_limit'],
				$_POST['vacuum_freeze_min_age'],
				$_POST['autovacuum_freeze_max_age']
			);
			$status = $this->execute($sql);
		}
		else {
			// table doesn't exists in pg_autovacuum, INSERT
			$sql = sprintf("INSERT INTO \"pg_catalog\".\"pg_autovacuum\" 
				VALUES (%s, '%s', %s, %s, %s, %s, %s, %s, %s, %s )",
				$toid,
				($_POST['autovacuum_enabled'] == 'on')? 't':'f',
				$_POST['autovacuum_vacuum_threshold'],
				$_POST['autovacuum_vacuum_scale_factor'],
				$_POST['autovacuum_analyze_threshold'],
				$_POST['autovacuum_analyze_scale_factor'],
				$_POST['autovacuum_vacuum_cost_delay'],
				$_POST['autovacuum_vacuum_cost_limit'],
				$_POST['vacuum_freeze_min_age'],
				$_POST['autovacuum_freeze_max_age']
			);
			$status = $this->execute($sql);
		}
		
		return $status;
	}

	function dropAutovacuum($table) {
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
		
		return $this->deleteRow('pg_autovacuum', array('vacrelid' => $rs->fields['oid']), 'pg_catalog');
	}
	
	// Sequence functions
	
	/**
	 * Alter a sequence's properties
	 * @param $seqrs The sequence RecordSet returned by getSequence()
	 * @param $increment The sequence incremental value
	 * @param $minvalue The sequence minimum value
	 * @param $maxvalue The sequence maximum value
	 * @param $restartvalue The sequence current value
	 * @param $cachevalue The sequence cache value
	 * @param $cycledvalue Sequence can cycle ?
	 * @param $startvalue The sequence start value when issuing a restart (ignored)
	 * @return 0 success
	 */
	function alterSequenceProps($seqrs, $increment,	$minvalue, $maxvalue,
								$restartvalue, $cachevalue, $cycledvalue, $startvalue) {

		$sql = '';
		/* vars are cleaned in _alterSequence */
		if (!empty($increment) && ($increment != $seqrs->fields['increment_by'])) $sql .= " INCREMENT {$increment}";
		if (!empty($minvalue) && ($minvalue != $seqrs->fields['min_value'])) $sql .= " MINVALUE {$minvalue}";
		if (!empty($maxvalue) && ($maxvalue != $seqrs->fields['max_value'])) $sql .= " MAXVALUE {$maxvalue}";
		if (!empty($restartvalue) && ($restartvalue != $seqrs->fields['last_value'])) $sql .= " RESTART {$restartvalue}";
		if (!empty($cachevalue) && ($cachevalue != $seqrs->fields['cache_value'])) $sql .= " CACHE {$cachevalue}";
		// toggle cycle yes/no
		if (!is_null($cycledvalue))	$sql .= (!$cycledvalue ? ' NO ' : '') . " CYCLE";
		if ($sql != '') {
			$f_schema = $this->_schema;
			$this->fieldClean($f_schema);
			$sql = "ALTER SEQUENCE \"{$f_schema}\".\"{$seqrs->fields['seqname']}\" {$sql}";
			return $this->execute($sql);
		}
		return 0;
	}

	/**
	 * Alter a sequence's owner
	 * @param $seqrs The sequence RecordSet returned by getSequence()
	 * @param $name The new owner for the sequence
	 * @return 0 success
	 */
	function alterSequenceOwner($seqrs, $owner) {
		// If owner has been changed, then do the alteration.  We are
		// careful to avoid this generally as changing owner is a
		// superuser only function.
		/* vars are cleaned in _alterSequence */
		if (!empty($owner) && ($seqrs->fields['seqowner'] != $owner)) {
			$f_schema = $this->_schema;
			$this->fieldClean($f_schema);
			$sql = "ALTER TABLE \"{$f_schema}\".\"{$seqrs->fields['seqname']}\" OWNER TO \"{$owner}\"";
			return $this->execute($sql);
		}
		return 0;
	}

	// Function functions

	/**
	 * Returns all details for a particular function
	 * @param $func The name of the function to retrieve
	 * @return Function info
	 */
	function getFunction($function_oid) {
		$this->clean($function_oid);

		$sql = "
			SELECT
				pc.oid AS prooid, proname, pg_catalog.pg_get_userbyid(proowner) AS proowner,
				nspname as proschema, lanname as prolanguage, procost, prorows,
				pg_catalog.format_type(prorettype, NULL) as proresult, prosrc,
				probin, proretset, proisstrict, provolatile, prosecdef,
				pg_catalog.oidvectortypes(pc.proargtypes) AS proarguments,
				proargnames AS proargnames,
				pg_catalog.obj_description(pc.oid, 'pg_proc') AS procomment,
				proconfig
			FROM
				pg_catalog.pg_proc pc, pg_catalog.pg_language pl,
				pg_catalog.pg_namespace pn
			WHERE
				pc.oid = '{$function_oid}'::oid AND pc.prolang = pl.oid
				AND pc.pronamespace = pn.oid
			";

		return $this->selectSet($sql);
	}


	// Capabilities
	function hasQueryKill() { return false; }
	function hasDatabaseCollation() { return false; }
	function hasAlterSequenceStart() { return false; }
}

?>
