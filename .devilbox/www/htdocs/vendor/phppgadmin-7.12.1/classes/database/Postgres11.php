<?php

/**
 * PostgreSQL 11 support
 *
 */

include_once('./classes/database/Postgres.php');

class Postgres11 extends Postgres {

	var $major_version = 11;

	/**
	 * Constructor
	 * @param $conn The database connection
	 */
	function __construct($conn) {
		parent::__construct($conn);
	}

	/**
	 * Returns the current default_with_oids setting
	 * @return default_with_oids setting
	 */
	function getDefaultWithOid() {

		$sql = "SHOW default_with_oids";

		return $this->selectField($sql, 'default_with_oids');
	}

    /**
	 * Checks to see whether or not a table has a unique id column
	 * @param $table The table name
	 * @return True if it has a unique id, false otherwise
	 * @return null error
	 **/
	function hasObjectID($table) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($table);

		$sql = "SELECT relhasoids FROM pg_catalog.pg_class WHERE relname='{$table}'
			AND relnamespace = (SELECT oid FROM pg_catalog.pg_namespace WHERE nspname='{$c_schema}')";

		$rs = $this->selectSet($sql);
		if ($rs->recordCount() != 1) return null;
		else {
			$rs->fields['relhasoids'] = $this->phpBool($rs->fields['relhasoids']);
			return $rs->fields['relhasoids'];
		}
	}


	// Help functions

	function getHelpPages() {
		include_once('./help/PostgresDoc11.php');
		return $this->help_page;
	}


	// Capabilities
	function hasServerOids() { return true; }

}
?>
