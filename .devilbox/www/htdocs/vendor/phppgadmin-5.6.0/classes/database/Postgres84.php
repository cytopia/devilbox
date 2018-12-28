<?php

/**
 * PostgreSQL 8.4 support
 *
 * $Id: Postgres82.php,v 1.10 2007/12/28 16:21:25 ioguix Exp $
 */

include_once('./classes/database/Postgres90.php');

class Postgres84 extends Postgres90 {

	var $major_version = 8.4;

	// List of all legal privileges that can be applied to different types
	// of objects.
	var $privlist = array(
  		'table' => array('SELECT', 'INSERT', 'UPDATE', 'DELETE', 'REFERENCES', 'TRIGGER', 'ALL PRIVILEGES'),
  		'view' => array('SELECT', 'INSERT', 'UPDATE', 'DELETE', 'REFERENCES', 'TRIGGER', 'ALL PRIVILEGES'),
  		'sequence' => array('SELECT', 'UPDATE', 'ALL PRIVILEGES'),
  		'database' => array('CREATE', 'TEMPORARY', 'CONNECT', 'ALL PRIVILEGES'),
  		'function' => array('EXECUTE', 'ALL PRIVILEGES'),
  		'language' => array('USAGE', 'ALL PRIVILEGES'),
  		'schema' => array('CREATE', 'USAGE', 'ALL PRIVILEGES'),
  		'tablespace' => array('CREATE', 'ALL PRIVILEGES'),
		'column' => array('SELECT', 'INSERT', 'UPDATE', 'REFERENCES','ALL PRIVILEGES')
	);

	/**
	 * Constructor
	 * @param $conn The database connection
	 */
	function Postgres84($conn) {
		$this->Postgres($conn);
	}

	// Help functions

	function getHelpPages() {
		include_once('./help/PostgresDoc84.php');
		return $this->help_page;
	}

	// Database functions

	/**
	 * Grabs a list of triggers on a table
	 * @param $table The name of a table whose triggers to retrieve
	 * @return A recordset
	 */
	function getTriggers($table = '') {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($table);

		$sql = "SELECT
				t.tgname, pg_catalog.pg_get_triggerdef(t.oid) AS tgdef,
				CASE WHEN t.tgenabled = 'D' THEN FALSE ELSE TRUE END AS tgenabled, p.oid AS prooid,
				p.proname || ' (' || pg_catalog.oidvectortypes(p.proargtypes) || ')' AS proproto,
				ns.nspname AS pronamespace
			FROM pg_catalog.pg_trigger t, pg_catalog.pg_proc p, pg_catalog.pg_namespace ns
			WHERE t.tgrelid = (SELECT oid FROM pg_catalog.pg_class WHERE relname='{$table}'
				AND relnamespace=(SELECT oid FROM pg_catalog.pg_namespace WHERE nspname='{$c_schema}'))
				AND (NOT tgisconstraint OR NOT EXISTS
						(SELECT 1 FROM pg_catalog.pg_depend d    JOIN pg_catalog.pg_constraint c
							ON (d.refclassid = c.tableoid AND d.refobjid = c.oid)
						WHERE d.classid = t.tableoid AND d.objid = t.oid AND d.deptype = 'i' AND c.contype = 'f'))
				AND p.oid=t.tgfoid
				AND p.pronamespace = ns.oid";

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
		$this->clean($term);
		$this->clean($filter);
		$term = str_replace('_', '\_', $term);
		$term = str_replace('%', '\%', $term);

		// Exclude system relations if necessary
		if (!$conf['show_system']) {
			// XXX: The mention of information_schema here is in the wrong place, but
			// it's the quickest fix to exclude the info schema from 7.4
			$where = " AND pn.nspname NOT LIKE \$_PATERN_\$pg\_%\$_PATERN_\$ AND pn.nspname != 'information_schema'";
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

		$term = "\$_PATERN_\$%{$term}%\$_PATERN_\$";

		$sql .= "
			SELECT 'SCHEMA' AS type, oid, NULL AS schemaname, NULL AS relname, nspname AS name
				FROM pg_catalog.pg_namespace pn WHERE nspname ILIKE {$term} {$where}
			UNION ALL
			SELECT CASE WHEN relkind='r' THEN 'TABLE' WHEN relkind='v' THEN 'VIEW' WHEN relkind='S' THEN 'SEQUENCE' END, pc.oid,
				pn.nspname, NULL, pc.relname FROM pg_catalog.pg_class pc, pg_catalog.pg_namespace pn
				WHERE pc.relnamespace=pn.oid AND relkind IN ('r', 'v', 'S') AND relname ILIKE {$term} {$where}
			UNION ALL
			SELECT CASE WHEN pc.relkind='r' THEN 'COLUMNTABLE' ELSE 'COLUMNVIEW' END, NULL, pn.nspname, pc.relname, pa.attname FROM pg_catalog.pg_class pc, pg_catalog.pg_namespace pn,
				pg_catalog.pg_attribute pa WHERE pc.relnamespace=pn.oid AND pc.oid=pa.attrelid
				AND pa.attname ILIKE {$term} AND pa.attnum > 0 AND NOT pa.attisdropped AND pc.relkind IN ('r', 'v') {$where}
			UNION ALL
			SELECT 'FUNCTION', pp.oid, pn.nspname, NULL, pp.proname || '(' || pg_catalog.oidvectortypes(pp.proargtypes) || ')' FROM pg_catalog.pg_proc pp, pg_catalog.pg_namespace pn
				WHERE pp.pronamespace=pn.oid AND NOT pp.proisagg AND pp.proname ILIKE {$term} {$where}
			UNION ALL
			SELECT 'INDEX', NULL, pn.nspname, pc.relname, pc2.relname FROM pg_catalog.pg_class pc, pg_catalog.pg_namespace pn,
				pg_catalog.pg_index pi, pg_catalog.pg_class pc2 WHERE pc.relnamespace=pn.oid AND pc.oid=pi.indrelid
				AND pi.indexrelid=pc2.oid
				AND NOT EXISTS (
					SELECT 1 FROM pg_catalog.pg_depend d JOIN pg_catalog.pg_constraint c
					ON (d.refclassid = c.tableoid AND d.refobjid = c.oid)
					WHERE d.classid = pc2.tableoid AND d.objid = pc2.oid AND d.deptype = 'i' AND c.contype IN ('u', 'p')
				)
				AND pc2.relname ILIKE {$term} {$where}
			UNION ALL
			SELECT 'CONSTRAINTTABLE', NULL, pn.nspname, pc.relname, pc2.conname FROM pg_catalog.pg_class pc, pg_catalog.pg_namespace pn,
				pg_catalog.pg_constraint pc2 WHERE pc.relnamespace=pn.oid AND pc.oid=pc2.conrelid AND pc2.conrelid != 0
				AND CASE WHEN pc2.contype IN ('f', 'c') THEN TRUE ELSE NOT EXISTS (
					SELECT 1 FROM pg_catalog.pg_depend d JOIN pg_catalog.pg_constraint c
					ON (d.refclassid = c.tableoid AND d.refobjid = c.oid)
					WHERE d.classid = pc2.tableoid AND d.objid = pc2.oid AND d.deptype = 'i' AND c.contype IN ('u', 'p')
				) END
				AND pc2.conname ILIKE {$term} {$where}
			UNION ALL
			SELECT 'CONSTRAINTDOMAIN', pt.oid, pn.nspname, pt.typname, pc.conname FROM pg_catalog.pg_type pt, pg_catalog.pg_namespace pn,
				pg_catalog.pg_constraint pc WHERE pt.typnamespace=pn.oid AND pt.oid=pc.contypid AND pc.contypid != 0
				AND pc.conname ILIKE {$term} {$where}
			UNION ALL
			SELECT 'TRIGGER', NULL, pn.nspname, pc.relname, pt.tgname FROM pg_catalog.pg_class pc, pg_catalog.pg_namespace pn,
				pg_catalog.pg_trigger pt WHERE pc.relnamespace=pn.oid AND pc.oid=pt.tgrelid
					AND ( NOT pt.tgisconstraint OR NOT EXISTS
					(SELECT 1 FROM pg_catalog.pg_depend d JOIN pg_catalog.pg_constraint c
					ON (d.refclassid = c.tableoid AND d.refobjid = c.oid)
					WHERE d.classid = pt.tableoid AND d.objid = pt.oid AND d.deptype = 'i' AND c.contype = 'f'))
				AND pt.tgname ILIKE {$term} {$where}
			UNION ALL
			SELECT 'RULETABLE', NULL, pn.nspname AS schemaname, c.relname AS tablename, r.rulename FROM pg_catalog.pg_rewrite r
				JOIN pg_catalog.pg_class c ON c.oid = r.ev_class
				LEFT JOIN pg_catalog.pg_namespace pn ON pn.oid = c.relnamespace
				WHERE c.relkind='r' AND r.rulename != '_RETURN' AND r.rulename ILIKE {$term} {$where}
			UNION ALL
			SELECT 'RULEVIEW', NULL, pn.nspname AS schemaname, c.relname AS tablename, r.rulename FROM pg_catalog.pg_rewrite r
				JOIN pg_catalog.pg_class c ON c.oid = r.ev_class
				LEFT JOIN pg_catalog.pg_namespace pn ON pn.oid = c.relnamespace
				WHERE c.relkind='v' AND r.rulename != '_RETURN' AND r.rulename ILIKE {$term} {$where}
		";

		// Add advanced objects if show_advanced is set
		if ($conf['show_advanced']) {
			$sql .= "
				UNION ALL
				SELECT CASE WHEN pt.typtype='d' THEN 'DOMAIN' ELSE 'TYPE' END, pt.oid, pn.nspname, NULL,
					pt.typname FROM pg_catalog.pg_type pt, pg_catalog.pg_namespace pn
					WHERE pt.typnamespace=pn.oid AND typname ILIKE {$term}
					AND (pt.typrelid = 0 OR (SELECT c.relkind = 'c' FROM pg_catalog.pg_class c WHERE c.oid = pt.typrelid))
					{$where}
			 	UNION ALL
				SELECT 'OPERATOR', po.oid, pn.nspname, NULL, po.oprname FROM pg_catalog.pg_operator po, pg_catalog.pg_namespace pn
					WHERE po.oprnamespace=pn.oid AND oprname ILIKE {$term} {$where}
				UNION ALL
				SELECT 'CONVERSION', pc.oid, pn.nspname, NULL, pc.conname FROM pg_catalog.pg_conversion pc,
					pg_catalog.pg_namespace pn WHERE pc.connamespace=pn.oid AND conname ILIKE {$term} {$where}
				UNION ALL
				SELECT 'LANGUAGE', pl.oid, NULL, NULL, pl.lanname FROM pg_catalog.pg_language pl
					WHERE lanname ILIKE {$term} {$lan_where}
				UNION ALL
				SELECT DISTINCT ON (p.proname) 'AGGREGATE', p.oid, pn.nspname, NULL, p.proname FROM pg_catalog.pg_proc p
					LEFT JOIN pg_catalog.pg_namespace pn ON p.pronamespace=pn.oid
					WHERE p.proisagg AND p.proname ILIKE {$term} {$where}
				UNION ALL
				SELECT DISTINCT ON (po.opcname) 'OPCLASS', po.oid, pn.nspname, NULL, po.opcname FROM pg_catalog.pg_opclass po,
					pg_catalog.pg_namespace pn WHERE po.opcnamespace=pn.oid
					AND po.opcname ILIKE {$term} {$where}
			";
		}
		// Otherwise just add domains
		else {
			$sql .= "
				UNION ALL
				SELECT 'DOMAIN', pt.oid, pn.nspname, NULL,
					pt.typname FROM pg_catalog.pg_type pt, pg_catalog.pg_namespace pn
					WHERE pt.typnamespace=pn.oid AND pt.typtype='d' AND typname ILIKE {$term}
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


	// Capabilities

	function hasByteaHexDefault() { return false; } 

}

?>
