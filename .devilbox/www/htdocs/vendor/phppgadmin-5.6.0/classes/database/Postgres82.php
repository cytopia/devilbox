<?php

/**
 * PostgreSQL 8.2 support
 *
 * $Id: Postgres82.php,v 1.10 2007/12/28 16:21:25 ioguix Exp $
 */

include_once('./classes/database/Postgres83.php');

class Postgres82 extends Postgres83 {

	var $major_version = 8.2;

	// Select operators
	var $selectOps = array('=' => 'i', '!=' => 'i', '<' => 'i', '>' => 'i', '<=' => 'i', '>=' => 'i', '<<' => 'i', '>>' => 'i', '<<=' => 'i', '>>=' => 'i',
		'LIKE' => 'i', 'NOT LIKE' => 'i', 'ILIKE' => 'i', 'NOT ILIKE' => 'i', 'SIMILAR TO' => 'i',
		'NOT SIMILAR TO' => 'i', '~' => 'i', '!~' => 'i', '~*' => 'i', '!~*' => 'i',
		'IS NULL' => 'p', 'IS NOT NULL' => 'p', 'IN' => 'x', 'NOT IN' => 'x');

	/**
	 * Constructor
	 * @param $conn The database connection
	 */
	function Postgres82($conn) {
		$this->Postgres($conn);
	}

	// Help functions

	function getHelpPages() {
		include_once('./help/PostgresDoc82.php');
		return $this->help_page;
	}

	// Database functions

	/**
	 * Returns table locks information in the current database
	 * @return A recordset
	 */
	function getLocks() {
		global $conf;

		if (!$conf['show_system'])
			$where = 'AND pn.nspname NOT LIKE $$pg\_%$$';
		else
			$where = "AND nspname !~ '^pg_t(emp_[0-9]+|oast)$'";

		$sql = "SELECT pn.nspname, pc.relname AS tablename, pl.transaction, pl.pid, pl.mode, pl.granted
		FROM pg_catalog.pg_locks pl, pg_catalog.pg_class pc, pg_catalog.pg_namespace pn
		WHERE pl.relation = pc.oid AND pc.relnamespace=pn.oid {$where}
		ORDER BY nspname,tablename";

		return $this->selectSet($sql);
	}

	// Sequence functions

	/**
	 * Rename a sequence
	 * @param $seqrs The sequence RecordSet returned by getSequence()
	 * @param $name The new name for the sequence
	 * @return 0 success
	 */
	function alterSequenceName($seqrs, $name) {
		/* vars are cleaned in _alterSequence */
		if (!empty($name) && ($seqrs->fields['seqname'] != $name)) {
			$f_schema = $this->_schema;
			$this->fieldClean($f_schema);
			$sql = "ALTER TABLE \"{$f_schema}\".\"{$seqrs->fields['seqname']}\" RENAME TO \"{$name}\"";
			$status = $this->execute($sql);
			if ($status == 0)
				$seqrs->fields['seqname'] = $name;
			else
				return $status;
		}
		return 0;
		}

	// View functions

	/**
	 * Rename a view
	 * @param $vwrs The view recordSet returned by getView()
	 * @param $name The new view's name
	 * @return -1 Failed
	 * @return 0 success
	 */
	function alterViewName($vwrs, $name) {
		// Rename (only if name has changed)
		/* $vwrs and $name are cleaned in _alterView */
		if (!empty($name) && ($name != $vwrs->fields['relname'])) {
			$f_schema = $this->_schema;
			$this->fieldClean($f_schema);
			$sql = "ALTER TABLE \"{$f_schema}\".\"{$vwrs->fields['relname']}\" RENAME TO \"{$name}\"";
			$status =  $this->execute($sql);
			if ($status == 0)
				$vwrs->fields['relname'] = $name;
			else
				return $status;
		}
		return 0;
	}

	// Trigger functions

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
				t.tgname, pg_catalog.pg_get_triggerdef(t.oid) AS tgdef, t.tgenabled, p.oid AS prooid,
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

	// Function functions

	/**
	 * Returns all details for a particular function
	 * @param $func The name of the function to retrieve
	 * @return Function info
	 */
	function getFunction($function_oid) {
		$this->clean($function_oid);

		$sql = "SELECT
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
					proargnames AS proargnames,
					pg_catalog.obj_description(pc.oid, 'pg_proc') AS procomment
				FROM
					pg_catalog.pg_proc pc, pg_catalog.pg_language pl, pg_catalog.pg_namespace pn
				WHERE
					pc.oid = '{$function_oid}'::oid
					AND pc.prolang = pl.oid
					AND pc.pronamespace = pn.oid
				";

		return $this->selectSet($sql);
	}

	/**
	 * Creates a new function.
	 * @param $funcname The name of the function to create
	 * @param $args A comma separated string of types
	 * @param $returns The return type
	 * @param $definition The definition for the new function
	 * @param $language The language the function is written for
	 * @param $flags An array of optional flags
	 * @param $setof True if it returns a set, false otherwise
	 * @param $rows number of rows planner should estimate will be returned
     * @param $cost cost the planner should use in the function execution step
	 * @param $comment The comment on the function
	 * @param $replace (optional) True if OR REPLACE, false for normal
	 * @return 0 success
	 * @return -1 create function failed
	 * @return -4 set comment failed
	 */
	function createFunction($funcname, $args, $returns, $definition, $language, $flags, $setof, $cost, $rows, $comment, $replace = false) {
		
		// Begin a transaction
		$status = $this->beginTransaction();
		if ($status != 0) {
			$this->rollbackTransaction();
			return -1;
		}
		
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($funcname);
		$this->clean($args);
		$this->fieldClean($language);
		$this->arrayClean($flags);

		$sql = "CREATE";
		if ($replace) $sql .= " OR REPLACE";
		$sql .= " FUNCTION \"{$f_schema}\".\"{$funcname}\" (";

		if ($args != '')
			$sql .= $args;

		// For some reason, the returns field cannot have quotes...
		$sql .= ") RETURNS ";
		if ($setof) $sql .= "SETOF ";
		$sql .= "{$returns} AS ";

		if (is_array($definition)) {
			$this->arrayClean($definition);
			$sql .= "'" . $definition[0] . "'";
			if ($definition[1]) {
				$sql .= ",'" . $definition[1] . "'";
			}
		} else {
			$this->clean($definition);
			$sql .= "'" . $definition . "'";
	}

		$sql .= " LANGUAGE \"{$language}\"";

		// Add flags
		foreach ($flags as  $v) {
			// Skip default flags
			if ($v == '') continue;
			else $sql .= "\n{$v}";
		}

		$status = $this->execute($sql);
		if ($status != 0) {
			$this->rollbackTransaction();
			return -3;
		}

		/* set the comment */
		$status = $this->setComment('FUNCTION', "\"{$funcname}\"({$args})", null, $comment);
		if ($status != 0) {
			$this->rollbackTransaction();
			return -4;
		}

		return $this->endTransaction();
	}

	// Index functions

	/**
	 * Clusters an index
	 * @param $index The name of the index
	 * @param $table The table the index is on
	 * @return 0 success
	 */
	function clusterIndex($table='', $index='') {

		$sql = 'CLUSTER';
		
		// We don't bother with a transaction here, as there's no point rolling
		// back an expensive cluster if a cheap analyze fails for whatever reason
		
		if (!empty($table)) {
			$f_schema = $this->_schema;
			$this->fieldClean($f_schema);
			$this->fieldClean($table);
			
			if (!empty($index)) {
				$this->fieldClean($index);
				$sql .= " \"{$index}\" ON \"{$f_schema}\".\"{$table}\"";
			}
			else {
				$sql .= " \"{$f_schema}\".\"{$table}\"";
			}
		}

		return $this->execute($sql);
	}

	// Operator functions

	/**
	 * Returns all details for a particular operator
	 * @param $operator_oid The oid of the operator
	 * @return Function info
	 */
	function getOperator($operator_oid) {
		$this->clean($operator_oid);

		$sql = "
			SELECT
				po.oid, po.oprname,
				oprleft::pg_catalog.regtype AS oprleftname,
				oprright::pg_catalog.regtype AS oprrightname,
				oprresult::pg_catalog.regtype AS resultname,
				po.oprcanhash,
				oprcom::pg_catalog.regoperator AS oprcom,
				oprnegate::pg_catalog.regoperator AS oprnegate,
				oprlsortop::pg_catalog.regoperator AS oprlsortop,
				oprrsortop::pg_catalog.regoperator AS oprrsortop,
				oprltcmpop::pg_catalog.regoperator AS oprltcmpop,
				oprgtcmpop::pg_catalog.regoperator AS oprgtcmpop,
				po.oprcode::pg_catalog.regproc AS oprcode,
				po.oprrest::pg_catalog.regproc AS oprrest,
				po.oprjoin::pg_catalog.regproc AS oprjoin
			FROM
				pg_catalog.pg_operator po
			WHERE
				po.oid='{$operator_oid}'
		";

		return $this->selectSet($sql);
	}

	// Operator Class functions

	/**
	 * Gets all opclasses
	 * @return A recordset
	 */
	function getOpClasses() {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$sql = "
			SELECT
				pa.amname,
				po.opcname,
				po.opcintype::pg_catalog.regtype AS opcintype,
				po.opcdefault,
				pg_catalog.obj_description(po.oid, 'pg_opclass') AS opccomment
			FROM
				pg_catalog.pg_opclass po, pg_catalog.pg_am pa, pg_catalog.pg_namespace pn
			WHERE
				po.opcamid=pa.oid
				AND po.opcnamespace=pn.oid
				AND pn.nspname='{$c_schema}'
			ORDER BY 1,2
		";

		return $this->selectSet($sql);
	}

	// Capabilities

	function hasCreateTableLikeWithIndexes() {return false;}
	function hasEnumTypes() {return false;}
	function hasFTS() {return false;}
	function hasFunctionCosting() {return false;}
	function hasFunctionGUC() {return false;}
	function hasVirtualTransactionId() {return false;}

}

?>
