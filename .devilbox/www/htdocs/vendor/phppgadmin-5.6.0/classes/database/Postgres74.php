<?php

/**
 * A class that implements the DB interface for Postgres
 * Note: This class uses ADODB and returns RecordSets.
 *
 * $Id: Postgres74.php,v 1.72 2008/02/20 21:06:18 ioguix Exp $
 */

include_once('./classes/database/Postgres80.php');

class Postgres74 extends Postgres80 {

	var $major_version = 7.4;
	// List of all legal privileges that can be applied to different types
	// of objects.
	var $privlist = array(
		'table' => array('SELECT', 'INSERT', 'UPDATE', 'DELETE', 'RULE', 'REFERENCES', 'TRIGGER', 'ALL PRIVILEGES'),
		'view' => array('SELECT', 'INSERT', 'UPDATE', 'DELETE', 'RULE', 'REFERENCES', 'TRIGGER', 'ALL PRIVILEGES'),
		'sequence' => array('SELECT', 'UPDATE', 'ALL PRIVILEGES'),
		'database' => array('CREATE', 'TEMPORARY', 'ALL PRIVILEGES'),
		'function' => array('EXECUTE', 'ALL PRIVILEGES'),
		'language' => array('USAGE', 'ALL PRIVILEGES'),
		'schema' => array('CREATE', 'USAGE', 'ALL PRIVILEGES')
	);


	/**
	 * Constructor
	 * @param $conn The database connection
	 */
	function Postgres74($conn) {
		$this->Postgres80($conn);
	}

	// Help functions

	function getHelpPages() {
		include_once('./help/PostgresDoc74.php');
		return $this->help_page;
	}

	// Database functions

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
		//ignore $newowner, not supported pre 8.0
		//ignore $comment, not supported pre 8.2
		$this->clean($dbName);
		$this->clean($newName);

		$status = $this->alterDatabaseRename($dbName, $newName);
		if ($status != 0) return -3;
		else return 0;
	}

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
                               (SELECT description FROM pg_description pd WHERE pdb.oid=pd.objoid) AS datcomment
                        FROM pg_database pdb, pg_user pu
			WHERE pdb.datdba = pu.usesysid
			{$where}
			{$clause}
			{$orderby}";

		return $this->selectSet($sql);
	}

	/**
	 * Searches all system catalogs to find objects that match a certain name.
	 * @param $term The search term
	 * @param $filter The object type to restrict to ('' means no restriction)
	 * @return A recordset
	 */
	function findObject($term, $filter) {
		global $conf;

		/*about escaping:
		 * SET standard_conforming_string is not available before 8.2
		 * So we must use PostgreSQL specific notation :/
		 * E'' notation is not available before 8.1
		 * $$ is available since 8.0
		 * Nothing specific from 7.4
		 **/

		// Escape search term for ILIKE match
		$term = str_replace('_', '\\_', $term);
		$term = str_replace('%', '\\%', $term);
		$this->clean($term);
		$this->clean($filter);

		// Exclude system relations if necessary
		if (!$conf['show_system']) {
			// XXX: The mention of information_schema here is in the wrong place, but
			// it's the quickest fix to exclude the info schema from 7.4
			$where = " AND pn.nspname NOT LIKE 'pg\\\\_%' AND pn.nspname != 'information_schema'";
			$lan_where = "AND pl.lanispl";
		}
		else {
			$where = '';
			$lan_where = '';
		}

		// Apply outer filter
		$sql = '';
		if ($filter != '') {
			$sql = "SELECT * FROM (";
		}

		$sql .= "
			SELECT 'SCHEMA' AS type, oid, NULL AS schemaname, NULL AS relname, nspname AS name
				FROM pg_catalog.pg_namespace pn WHERE nspname ILIKE '%{$term}%' {$where}
			UNION ALL
			SELECT CASE WHEN relkind='r' THEN 'TABLE' WHEN relkind='v' THEN 'VIEW' WHEN relkind='S' THEN 'SEQUENCE' END, pc.oid,
				pn.nspname, NULL, pc.relname FROM pg_catalog.pg_class pc, pg_catalog.pg_namespace pn
				WHERE pc.relnamespace=pn.oid AND relkind IN ('r', 'v', 'S') AND relname ILIKE '%{$term}%' {$where}
			UNION ALL
			SELECT CASE WHEN pc.relkind='r' THEN 'COLUMNTABLE' ELSE 'COLUMNVIEW' END, NULL, pn.nspname, pc.relname, pa.attname FROM pg_catalog.pg_class pc, pg_catalog.pg_namespace pn,
				pg_catalog.pg_attribute pa WHERE pc.relnamespace=pn.oid AND pc.oid=pa.attrelid
				AND pa.attname ILIKE '%{$term}%' AND pa.attnum > 0 AND NOT pa.attisdropped AND pc.relkind IN ('r', 'v') {$where}
			UNION ALL
			SELECT 'FUNCTION', pp.oid, pn.nspname, NULL, pp.proname || '(' || pg_catalog.oidvectortypes(pp.proargtypes) || ')' FROM pg_catalog.pg_proc pp, pg_catalog.pg_namespace pn
				WHERE pp.pronamespace=pn.oid AND NOT pp.proisagg AND pp.proname ILIKE '%{$term}%' {$where}
			UNION ALL
			SELECT 'INDEX', NULL, pn.nspname, pc.relname, pc2.relname FROM pg_catalog.pg_class pc, pg_catalog.pg_namespace pn,
				pg_catalog.pg_index pi, pg_catalog.pg_class pc2 WHERE pc.relnamespace=pn.oid AND pc.oid=pi.indrelid
				AND pi.indexrelid=pc2.oid
				AND NOT EXISTS (
					SELECT 1 FROM pg_catalog.pg_depend d JOIN pg_catalog.pg_constraint c
					ON (d.refclassid = c.tableoid AND d.refobjid = c.oid)
					WHERE d.classid = pc2.tableoid AND d.objid = pc2.oid AND d.deptype = 'i' AND c.contype IN ('u', 'p')
				)
				AND pc2.relname ILIKE '%{$term}%' {$where}
			UNION ALL
			SELECT 'CONSTRAINTTABLE', NULL, pn.nspname, pc.relname, pc2.conname FROM pg_catalog.pg_class pc, pg_catalog.pg_namespace pn,
				pg_catalog.pg_constraint pc2 WHERE pc.relnamespace=pn.oid AND pc.oid=pc2.conrelid AND pc2.conrelid != 0
				AND CASE WHEN pc2.contype IN ('f', 'c') THEN TRUE ELSE NOT EXISTS (
					SELECT 1 FROM pg_catalog.pg_depend d JOIN pg_catalog.pg_constraint c
					ON (d.refclassid = c.tableoid AND d.refobjid = c.oid)
					WHERE d.classid = pc2.tableoid AND d.objid = pc2.oid AND d.deptype = 'i' AND c.contype IN ('u', 'p')
				) END
				AND pc2.conname ILIKE '%{$term}%' {$where}
			UNION ALL
			SELECT 'CONSTRAINTDOMAIN', pt.oid, pn.nspname, pt.typname, pc.conname FROM pg_catalog.pg_type pt, pg_catalog.pg_namespace pn,
				pg_catalog.pg_constraint pc WHERE pt.typnamespace=pn.oid AND pt.oid=pc.contypid AND pc.contypid != 0
				AND pc.conname ILIKE '%{$term}%' {$where}
			UNION ALL
			SELECT 'TRIGGER', NULL, pn.nspname, pc.relname, pt.tgname FROM pg_catalog.pg_class pc, pg_catalog.pg_namespace pn,
				pg_catalog.pg_trigger pt WHERE pc.relnamespace=pn.oid AND pc.oid=pt.tgrelid
					AND ( pt.tgisconstraint = 'f' OR NOT EXISTS
					(SELECT 1 FROM pg_catalog.pg_depend d JOIN pg_catalog.pg_constraint c
					ON (d.refclassid = c.tableoid AND d.refobjid = c.oid)
					WHERE d.classid = pt.tableoid AND d.objid = pt.oid AND d.deptype = 'i' AND c.contype = 'f'))
				AND pt.tgname ILIKE '%{$term}%' {$where}
			UNION ALL
			SELECT 'RULETABLE', NULL, pn.nspname AS schemaname, c.relname AS tablename, r.rulename FROM pg_catalog.pg_rewrite r
				JOIN pg_catalog.pg_class c ON c.oid = r.ev_class
				LEFT JOIN pg_catalog.pg_namespace pn ON pn.oid = c.relnamespace
				WHERE c.relkind='r' AND r.rulename != '_RETURN' AND r.rulename ILIKE '%{$term}%' {$where}
			UNION ALL
			SELECT 'RULEVIEW', NULL, pn.nspname AS schemaname, c.relname AS tablename, r.rulename FROM pg_catalog.pg_rewrite r
				JOIN pg_catalog.pg_class c ON c.oid = r.ev_class
				LEFT JOIN pg_catalog.pg_namespace pn ON pn.oid = c.relnamespace
				WHERE c.relkind='v' AND r.rulename != '_RETURN' AND r.rulename ILIKE '%{$term}%' {$where}
		";

		// Add advanced objects if show_advanced is set
		if ($conf['show_advanced']) {
			$sql .= "
				UNION ALL
				SELECT CASE WHEN pt.typtype='d' THEN 'DOMAIN' ELSE 'TYPE' END, pt.oid, pn.nspname, NULL,
					pt.typname FROM pg_catalog.pg_type pt, pg_catalog.pg_namespace pn
					WHERE pt.typnamespace=pn.oid AND typname ILIKE '%{$term}%'
					AND (pt.typrelid = 0 OR (SELECT c.relkind = 'c' FROM pg_catalog.pg_class c WHERE c.oid = pt.typrelid))
					{$where}
				UNION ALL
				SELECT 'OPERATOR', po.oid, pn.nspname, NULL, po.oprname FROM pg_catalog.pg_operator po, pg_catalog.pg_namespace pn
					WHERE po.oprnamespace=pn.oid AND oprname ILIKE '%{$term}%' {$where}
				UNION ALL
				SELECT 'CONVERSION', pc.oid, pn.nspname, NULL, pc.conname FROM pg_catalog.pg_conversion pc,
					pg_catalog.pg_namespace pn WHERE pc.connamespace=pn.oid AND conname ILIKE '%{$term}%' {$where}
				UNION ALL
				SELECT 'LANGUAGE', pl.oid, NULL, NULL, pl.lanname FROM pg_catalog.pg_language pl
					WHERE lanname ILIKE '%{$term}%' {$lan_where}
				UNION ALL
				SELECT DISTINCT ON (p.proname) 'AGGREGATE', p.oid, pn.nspname, NULL, p.proname FROM pg_catalog.pg_proc p
					LEFT JOIN pg_catalog.pg_namespace pn ON p.pronamespace=pn.oid
					WHERE p.proisagg AND p.proname ILIKE '%{$term}%' {$where}
				UNION ALL
				SELECT DISTINCT ON (po.opcname) 'OPCLASS', po.oid, pn.nspname, NULL, po.opcname FROM pg_catalog.pg_opclass po,
					pg_catalog.pg_namespace pn WHERE po.opcnamespace=pn.oid
					AND po.opcname ILIKE '%{$term}%' {$where}
			";
		}
		// Otherwise just add domains
		else {
			$sql .= "
				UNION ALL
				SELECT 'DOMAIN', pt.oid, pn.nspname, NULL,
					pt.typname FROM pg_catalog.pg_type pt, pg_catalog.pg_namespace pn
					WHERE pt.typnamespace=pn.oid AND pt.typtype='d' AND typname ILIKE '%{$term}%'
					AND (pt.typrelid = 0 OR (SELECT c.relkind = 'c' FROM pg_catalog.pg_class c WHERE c.oid = pt.typrelid))
					{$where}
			";
		}

		if ($filter != '') {
			// We use like to make RULE, CONSTRAINT and COLUMN searches work
			$sql .= ") AS sub WHERE type LIKE '{$filter}%' ";
		}

		$sql .= "ORDER BY type, schemaname, relname, name";

		return $this->selectSet($sql);
	}

	/**
	 * Returns table locks information in the current database
	 * @return A recordset
	 */
	function getLocks() {
		global $conf;

		if (!$conf['show_system'])
			$where = "AND pn.nspname NOT LIKE 'pg\\\\_%'";
		else
			$where = "AND nspname !~ '^pg_t(emp_[0-9]+|oast)$'";

		$sql = "SELECT pn.nspname, pc.relname AS tablename, pl.transaction, pl.pid, pl.mode, pl.granted
		FROM pg_catalog.pg_locks pl, pg_catalog.pg_class pc, pg_catalog.pg_namespace pn
		WHERE pl.relation = pc.oid AND pc.relnamespace=pn.oid {$where}
		ORDER BY nspname,tablename";

		return $this->selectSet($sql);
	}

	/**
	 * Returns the current database encoding
	 * @return The encoding.  eg. SQL_ASCII, UTF-8, etc.
	 */
	function getDatabaseEncoding() {
		$sql = "SELECT getdatabaseencoding() AS encoding";

		return $this->selectField($sql, 'encoding');
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
	 */
	protected
	function _alterTable($tblrs, $name, $owner, $schema, $comment, $tablespace) {

		/* $schema and tablespace not supported in pg74- */
		$this->fieldArrayClean($tblrs->fields);

		// Comment
		$status = $this->setComment('TABLE', '', $tblrs->fields['relname'], $comment);
		if ($status != 0) return -4;

		// Owner
		$this->fieldClean($owner);
		$status = $this->alterTableOwner($tblrs, $owner);
		if ($status != 0) return -5;

		// Rename
		$this->fieldClean($name);
		$status = $this->alterTableName($tblrs, $name);
		if ($status != 0) return -3;

		return 0;
	}

	/**
	 * Alters a column in a table OR view
	 * @param $table The table in which the column resides
	 * @param $column The column to alter
	 * @param $name The new name for the column
	 * @param $notnull (boolean) True if not null, false otherwise
	 * @param $oldnotnull (boolean) True if column is already not null, false otherwise
	 * @param $default The new default for the column
	 * @param $olddefault The old default for the column
	 * @param $type The new type for the column
	 * @param $array True if array type, false otherwise
	 * @param $length The optional size of the column (ie. 30 for varchar(30))
	 * @param $oldtype The old type for the column
	 * @param $comment Comment for the column
	 * @return 0 success
	 * @return -2 set not null error
	 * @return -3 set default error
	 * @return -4 rename column error
	 * @return -5 comment error
	 * @return -6 transaction error
	 */
	function alterColumn($table, $column, $name, $notnull, $oldnotnull, $default, $olddefault,
	$type, $length, $array, $oldtype, $comment)
	{
		$status = $this->beginTransaction();
		if ($status != 0) return -1;

		// @@ NEED TO HANDLE "NESTED" TRANSACTION HERE
		if ($notnull != $oldnotnull) {
			$status = $this->setColumnNull($table, $column, !$notnull);
			if ($status != 0) {
				$this->rollbackTransaction();
				return -2;
			}
		}

		// Set default, if it has changed
		if ($default != $olddefault) {
			if ($default == '')
				$status = $this->dropColumnDefault($table, $column);
			else
				$status = $this->setColumnDefault($table, $column, $default);

			if ($status != 0) {
				$this->rollbackTransaction();
				return -3;
			}
		}

		// Rename the column, if it has been changed
		if ($column != $name) {
			$status = $this->renameColumn($table, $column, $name);
			if ($status != 0) {
				$this->rollbackTransaction();
				return -4;
			}
		}

		// The $name and $table parameters must be cleaned for the setComment function.  
                // It's ok to do that here since this is the last time these variables are used.
		$this->fieldClean($name);
		$this->fieldClean($table);
		$status = $this->setComment('COLUMN', $name, $table, $comment);
		if ($status != 0) {
			$this->rollbackTransaction();
			return -5;
		}

		return $this->endTransaction();
	}

	/**
	 * Returns table information
	 * @param $table The name of the table
	 * @return A recordset
	 */
	function getTable($table) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($table);

		$sql = "
			SELECT
			  c.relname, n.nspname, u.usename AS relowner,
			  pg_catalog.obj_description(c.oid, 'pg_class') AS relcomment
			FROM pg_catalog.pg_class c
			     LEFT JOIN pg_catalog.pg_user u ON u.usesysid = c.relowner
			     LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
			WHERE c.relkind = 'r'
				AND n.nspname = '{$c_schema}'
			    AND c.relname = '{$table}'";

		return $this->selectSet($sql);
	}

	/**
	 * Return all tables in current database (and schema)
	 * @param $all True to fetch all tables, false for just in current schema
	 * @return All tables, sorted alphabetically
	 */
	function getTables($all = false) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		if ($all) {
			// Exclude pg_catalog and information_schema tables
			$sql = "SELECT schemaname AS nspname, tablename AS relname, tableowner AS relowner
					FROM pg_catalog.pg_tables
					WHERE schemaname NOT IN ('pg_catalog', 'information_schema', 'pg_toast')
					ORDER BY schemaname, tablename";
		} else {
			$sql = "SELECT c.relname, pg_catalog.pg_get_userbyid(c.relowner) AS relowner,
						pg_catalog.obj_description(c.oid, 'pg_class') AS relcomment,
						reltuples::bigint
					FROM pg_catalog.pg_class c
					LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
					WHERE c.relkind = 'r'
					AND nspname='{$c_schema}'
					ORDER BY c.relname";
		}

		return $this->selectSet($sql);
	}

	/**
	 * Returns the current default_with_oids setting
	 * @return default_with_oids setting
	 */
	function getDefaultWithOid() {
		// 8.0 is the first release to have this setting
		// Prior releases don't have this setting... oids always activated
		return 'on';
		}

	// Constraint functions

	/**
	 * Returns a list of all constraints on a table,
	 * including constraint name, definition, related col and referenced namespace,
	 * table and col if needed
	 * @param $table the table where we are looking for fk
	 * @return a recordset
	 */
	function getConstraintsWithFields($table) {

		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($table);

		// get the max number of col used in a constraint for the table
		$sql = "SELECT DISTINCT
			max(SUBSTRING(array_dims(c.conkey) FROM '^\\\\[.*:(.*)\\\\]$')) as nb
		FROM pg_catalog.pg_constraint AS c
			JOIN pg_catalog.pg_class AS r ON (c.conrelid=r.oid)
		    JOIN pg_catalog.pg_namespace AS ns ON (r.relnamespace=ns.oid)
		WHERE
			r.relname = '{$table}' AND ns.nspname='{$c_schema}'";

		$rs = $this->selectSet($sql);

		if ($rs->EOF) $max_col = 0;
		else $max_col = $rs->fields['nb'];

		$sql = '
			SELECT
				c.oid AS conid, c.contype, c.conname, pg_catalog.pg_get_constraintdef(c.oid, true) AS consrc,
				ns1.nspname as p_schema, r1.relname as p_table, ns2.nspname as f_schema,
				r2.relname as f_table, f1.attname as p_field, f1.attnum AS p_attnum, f2.attname as f_field,
				f2.attnum AS f_attnum, pg_catalog.obj_description(c.oid, \'pg_constraint\') AS constcomment,
				c.conrelid, c.confrelid
			FROM
				pg_catalog.pg_constraint AS c
				JOIN pg_catalog.pg_class AS r1 ON (c.conrelid=r1.oid)
				JOIN pg_catalog.pg_attribute AS f1 ON (f1.attrelid=r1.oid AND (f1.attnum=c.conkey[1]';
		for ($i = 2; $i <= $rs->fields['nb']; $i++) {
			$sql.= " OR f1.attnum=c.conkey[$i]";
		}
		$sql.= '))
				JOIN pg_catalog.pg_namespace AS ns1 ON r1.relnamespace=ns1.oid
				LEFT JOIN (
					pg_catalog.pg_class AS r2 JOIN pg_catalog.pg_namespace AS ns2 ON (r2.relnamespace=ns2.oid)
				) ON (c.confrelid=r2.oid)
				LEFT JOIN pg_catalog.pg_attribute AS f2 ON
					(f2.attrelid=r2.oid AND ((c.confkey[1]=f2.attnum AND c.conkey[1]=f1.attnum)';
		for ($i = 2; $i <= $rs->fields['nb']; $i++)
			$sql.= " OR (c.confkey[$i]=f2.attnum AND c.conkey[$i]=f1.attnum)";

		$sql .= sprintf("))
			WHERE
				r1.relname = '%s' AND ns1.nspname='%s'
			ORDER BY 1", $table, $c_schema);

		return $this->selectSet($sql);
	}

	// Sequence functions

	/**
	 * Returns all sequences in the current database
	 * @return A recordset
	 */
	function getSequences($all = false) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		if ($all) {
			// Exclude pg_catalog and information_schema tables
			$sql = "SELECT n.nspname, c.relname AS seqname, u.usename AS seqowner
				FROM pg_catalog.pg_class c, pg_catalog.pg_user u, pg_catalog.pg_namespace n
				WHERE c.relowner=u.usesysid AND c.relnamespace=n.oid
				AND c.relkind = 'S'
				AND n.nspname NOT IN ('pg_catalog', 'information_schema', 'pg_toast')
				ORDER BY nspname, seqname";
		} else {
			$sql = "SELECT c.relname AS seqname, u.usename AS seqowner, pg_catalog.obj_description(c.oid, 'pg_class') AS seqcomment
				FROM pg_catalog.pg_class c, pg_catalog.pg_user u, pg_catalog.pg_namespace n
				WHERE c.relowner=u.usesysid AND c.relnamespace=n.oid
				AND c.relkind = 'S' AND n.nspname='{$c_schema}' ORDER BY seqname";
		}

		return $this->selectSet( $sql );
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
			pc.oid AS prooid,
			proname,
			pg_catalog.pg_get_userbyid(proowner) AS proowner,
			nspname as proschema,
			lanname as prolanguage,
			pg_catalog.format_type(prorettype, NULL) as proresult,
			prosrc,
			probin,
			proretset,
			proisstrict,
			provolatile,
			prosecdef,
			pg_catalog.oidvectortypes(pc.proargtypes) AS proarguments,
			pg_catalog.obj_description(pc.oid, 'pg_proc') AS procomment
		FROM
			pg_catalog.pg_proc pc, pg_catalog.pg_language pl, pg_catalog.pg_namespace n
		WHERE
			pc.oid = '$function_oid'::oid
			AND pc.prolang = pl.oid
			AND n.oid = pc.pronamespace
		";

		return $this->selectSet($sql);
	}

	/**
	 * Returns a list of all casts in the database
	 * @return All casts
	 */
	function getCasts() {
		global $conf;

		if ($conf['show_system'])
			$where = '';
		else
			$where = "
				AND n1.nspname NOT LIKE 'pg\\\\_%'
				AND n2.nspname NOT LIKE 'pg\\\\_%'
				AND n3.nspname NOT LIKE 'pg\\\\_%'
			";

		$sql = "
			SELECT
				c.castsource::pg_catalog.regtype AS castsource,
				c.casttarget::pg_catalog.regtype AS casttarget,
				CASE WHEN c.castfunc=0 THEN NULL
				ELSE c.castfunc::pg_catalog.regprocedure END AS castfunc,
				c.castcontext,
				obj_description(c.oid, 'pg_cast') as castcomment
			FROM
				(pg_catalog.pg_cast c LEFT JOIN pg_catalog.pg_proc p ON c.castfunc=p.oid JOIN pg_catalog.pg_namespace n3 ON p.pronamespace=n3.oid),
				pg_catalog.pg_type t1,
				pg_catalog.pg_type t2,
				pg_catalog.pg_namespace n1,
				pg_catalog.pg_namespace n2
			WHERE
				c.castsource=t1.oid
				AND c.casttarget=t2.oid
				AND t1.typnamespace=n1.oid
				AND t2.typnamespace=n2.oid
				{$where}
			ORDER BY 1, 2
		";

		return $this->selectSet($sql);
	}

	// Capabilities

	function hasAlterColumnType() { return false; }
	function hasCreateFieldWithConstraints() { return false; }
	function hasAlterDatabaseOwner() { return false; }
	function hasAlterSchemaOwner() { return false; }
	function hasFunctionAlterOwner() { return false; }
	function hasNamedParams() { return false; }
	function hasQueryCancel() { return false; }
	function hasTablespaces() { return false; }
	function hasMagicTypes() { return false; }
}
?>
