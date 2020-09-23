<?php

/**
 * PostgreSQL 8.0 support
 *
 * $Id: Postgres80.php,v 1.28 2007/12/12 04:11:10 xzilla Exp $
 */

include_once('./classes/database/Postgres81.php');

class Postgres80 extends Postgres81 {

	var $major_version = 8.0;
	// Map of database encoding names to HTTP encoding names.  If a
	// database encoding does not appear in this list, then its HTTP
	// encoding name is the same as its database encoding name.
	var $codemap = array(
		'ALT' => 'CP866',
		'EUC_CN' => 'GB2312',
		'EUC_JP' => 'EUC-JP',
		'EUC_KR' => 'EUC-KR',
		'EUC_TW' => 'EUC-TW',
		'ISO_8859_5' => 'ISO-8859-5',
		'ISO_8859_6' => 'ISO-8859-6',
		'ISO_8859_7' => 'ISO-8859-7',
		'ISO_8859_8' => 'ISO-8859-8',
		'JOHAB' => 'CP1361',
		'KOI8' => 'KOI8-R',
		'LATIN1' => 'ISO-8859-1',
		'LATIN2' => 'ISO-8859-2',
		'LATIN3' => 'ISO-8859-3',
		'LATIN4' => 'ISO-8859-4',
		// The following encoding map is a known error in PostgreSQL < 7.2
		// See the constructor for Postgres72.
		'LATIN5' => 'ISO-8859-5',
		'LATIN6' => 'ISO-8859-10',
		'LATIN7' => 'ISO-8859-13',
		'LATIN8' => 'ISO-8859-14',
		'LATIN9' => 'ISO-8859-15',
		'LATIN10' => 'ISO-8859-16',
		'SQL_ASCII' => 'US-ASCII',
		'TCVN' => 'CP1258',
		'UNICODE' => 'UTF-8',
		'WIN' => 'CP1251',
		'WIN874' => 'CP874',
		'WIN1256' => 'CP1256'
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
		include_once('./help/PostgresDoc80.php');
		return $this->help_page;
	}

	// Database functions

	/**
	 * Return all database available on the server
	 * @return A list of databases, sorted alphabetically
	 */
	function getDatabases($currentdatabase = NULL) {
		global $conf, $misc;

		$server_info = $misc->getServerInfo();

		if (isset($conf['owned_only']) && $conf['owned_only'] && !$this->isSuperUser()) {
			$username = $server_info['username'];
			$this->clean($username);
			$clause = " AND pu.usename='{$username}'";
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

		$sql = "SELECT pdb.datname AS datname, pu.usename AS datowner, pg_encoding_to_char(encoding) AS datencoding,
                               (SELECT description FROM pg_description pd WHERE pdb.oid=pd.objoid) AS datcomment,
                               (SELECT spcname FROM pg_catalog.pg_tablespace pt WHERE pt.oid=pdb.dattablespace) AS tablespace
                        FROM pg_database pdb, pg_user pu
			WHERE pdb.datdba = pu.usesysid
			{$where}
			{$clause}
			{$orderby}";

		return $this->selectSet($sql);
	}

	// Schema functions

	/**
	 * Return all schemas in the current database.
	 * @return All schemas, sorted alphabetically
	 */
	function getSchemas() {
		global $conf;

		if (!$conf['show_system']) {
			$where = "WHERE nspname NOT LIKE 'pg@_%' ESCAPE '@' AND nspname != 'information_schema'";
		}
		else $where = "WHERE nspname !~ '^pg_t(emp_[0-9]+|oast)$'";
		$sql = "
			SELECT pn.nspname, pu.usename AS nspowner,
				pg_catalog.obj_description(pn.oid, 'pg_namespace') AS nspcomment
			FROM pg_catalog.pg_namespace pn
				LEFT JOIN pg_catalog.pg_user pu ON (pn.nspowner = pu.usesysid)
			{$where}
			ORDER BY nspname";

		return $this->selectSet($sql);
	}

	/**
	 * Return all information relating to a schema
	 * @param $schema The name of the schema
	 * @return Schema information
	 */
	function getSchemaByName($schema) {
		$this->clean($schema);
		$sql = "
			SELECT nspname, nspowner, u.usename AS ownername, nspacl,
				pg_catalog.obj_description(pn.oid, 'pg_namespace') as nspcomment
            FROM pg_catalog.pg_namespace pn
            	LEFT JOIN pg_shadow as u ON pn.nspowner = u.usesysid
			WHERE nspname='{$schema}'";
		return $this->selectSet($sql);
	}

	// Table functions

	/**
	 * Protected method which alter a table
	 * SHOULDN'T BE CALLED OUTSIDE OF A TRANSACTION
	 * @param $tblrs The table recordSet returned by getTable()
	 * @param $name The new name for the table
	 * @param $owner The new owner for the table
	 * @param $schema The new schema for the table
	 * @param $comment The comment on the table
	 * @param $tablespace The new tablespace for the table ('' means leave as is)
	 * @return 0 success
	 * @return -3 rename error
	 * @return -4 comment error
	 * @return -5 owner error
	 * @return -6 tablespace error
	 */
	protected
	function _alterTable($tblrs, $name, $owner, $schema, $comment, $tablespace) {

		/* $schema not supported in pg80- */

		// Comment
		$status = $this->setComment('TABLE', '', $tblrs->fields['relname'], $comment);
		if ($status != 0) return -4;

		// Owner
		$this->fieldClean($owner);
		$status = $this->alterTableOwner($tblrs, $owner);
		if ($status != 0) return -5;

		// Tablespace
		$this->fieldClean($tablespace);
		$status = $this->alterTableTablespace($tblrs, $tablespace);
			if ($status != 0) return -6;

		// Rename
		$this->fieldClean($name);
		$status = $this->alterTableName($tblrs, $name);
		if ($status != 0) return -3;

		return 0;
	}

	// View functions

	/**
	 * Protected method which alter a view
	 * SHOULDN'T BE CALLED OUTSIDE OF A TRANSACTION
	 * @param $vwrs The view recordSet returned by getView()
	 * @param $name The new name for the view
	 * @param $owner The new owner for the view
	 * @param $comment The comment on the view
	 * @return 0 success
	 * @return -3 rename error
	 * @return -4 comment error
	 * @return -5 owner error
	 */
	protected
    function _alterView($vwrs, $name, $owner, $schema, $comment) {

    	/* $schema not supported in pg80- */
    	$this->fieldArrayClean($vwrs->fields);

		// Comment
		if ($this->setComment('VIEW', $vwrs->fields['relname'], '', $comment) != 0)
			return -4;

		// Owner
		$this->fieldClean($owner);
		$status = $this->alterViewOwner($vwrs, $owner);
		if ($status != 0) return -5;

		// Rename
		$this->fieldClean($name);
		$status = $this->alterViewName($vwrs, $name);
		if ($status != 0) return -3;

		return 0;
	}

	// Sequence functions

	/**
	 * Protected method which alter a sequence
	 * SHOULDN'T BE CALLED OUTSIDE OF A TRANSACTION
	 * @param $seqrs The sequence recordSet returned by getSequence()
	 * @param $name The new name for the sequence
	 * @param $comment The comment on the sequence
	 * @param $owner The new owner for the sequence
	 * @param $schema The new schema for the sequence
	 * @param $increment The increment
	 * @param $minvalue The min value
	 * @param $maxvalue The max value
	 * @param $restartvalue The starting value
	 * @param $cachevalue The cache value
	 * @param $cycledvalue True if cycled, false otherwise
	 * @param $startvalue The sequence start value when issuing a restart
	 * @return 0 success
	 * @return -3 rename error
	 * @return -4 comment error
	 * @return -5 owner error
	 * @return -6 get sequence props error
	 * @return -7 schema error
	 */
	protected
	function _alterSequence($seqrs, $name, $comment, $owner, $schema, $increment,
	$minvalue, $maxvalue, $restartvalue, $cachevalue, $cycledvalue, $startvalue) {

		/* $schema not supported in pg80- */
		$this->fieldArrayClean($seqrs->fields);

		// Comment
		$status = $this->setComment('SEQUENCE', $seqrs->fields['seqname'], '', $comment);
		if ($status != 0)
			return -4;

		// Owner
		$this->fieldClean($owner);
		$status = $this->alterSequenceOwner($seqrs, $owner);
		if ($status != 0)
			return -5;

		// Props
		$this->clean($increment);
		$this->clean($minvalue);
		$this->clean($maxvalue);
		$this->clean($restartvalue);
		$this->clean($cachevalue);
		$this->clean($cycledvalue);
		$this->clean($startvalue);
		$status = $this->alterSequenceProps($seqrs, $increment,	$minvalue,
			$maxvalue, $restartvalue, $cachevalue, $cycledvalue, null);
		if ($status != 0)
			return -6;

		// Rename
		$this->fieldClean($name);
		$status = $this->alterSequenceName($seqrs, $name);
		if ($status != 0)
			return -3;

		return 0;
	}

	// Role, User/group functions

	/**
	 * Changes a user's password
	 * @param $username The username
	 * @param $password The new password
	 * @return 0 success
	 */
	function changePassword($username, $password) {
		$enc = $this->_encryptPassword($username, $password);
		$this->fieldClean($username);
		$this->clean($enc);

		$sql = "ALTER USER \"{$username}\" WITH ENCRYPTED PASSWORD '{$enc}'";

		return $this->execute($sql);
	}

	// Aggregate functions

	/**
	 * Gets all information for an aggregate
	 * @param $name The name of the aggregate
	 * @param $basetype The input data type of the aggregate
	 * @return A recordset
	 */
	function getAggregate($name, $basetype) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($name);
		$this->clean($basetype);

		$sql = "
			SELECT p.proname,
				CASE p.proargtypes[0]
					WHEN 'pg_catalog.\"any\"'::pg_catalog.regtype THEN NULL
					ELSE pg_catalog.format_type(p.proargtypes[0], NULL)
				END AS proargtypes, a.aggtransfn, format_type(a.aggtranstype, NULL) AS aggstype,
				a.aggfinalfn, a.agginitval, u.usename, pg_catalog.obj_description(p.oid, 'pg_proc') AS aggrcomment
			FROM pg_catalog.pg_proc p, pg_catalog.pg_namespace n, pg_catalog.pg_user u, pg_catalog.pg_aggregate a
			WHERE n.oid = p.pronamespace AND p.proowner=u.usesysid AND p.oid=a.aggfnoid
				AND p.proisagg AND n.nspname='{$c_schema}'
				AND p.proname='{$name}'
				AND CASE p.proargtypes[0]
					WHEN 'pg_catalog.\"any\"'::pg_catalog.regtype THEN ''
					ELSE pg_catalog.format_type(p.proargtypes[0], NULL)
				END ='{$basetype}'";

		return $this->selectSet($sql);
	}

	// Capabilities

	function hasAggregateSortOp() { return false; }
	function hasAlterTableSchema() { return false; }
	function hasAutovacuum() { return false; }
	function hasDisableTriggers() { return false; }
	function hasFunctionAlterSchema() { return false; }
	function hasPreparedXacts() { return false; }
	function hasRoles() { return false; }
	function hasAlterSequenceSchema() { return false; }
	function hasServerAdminFuncs() { return false; }
}
?>
