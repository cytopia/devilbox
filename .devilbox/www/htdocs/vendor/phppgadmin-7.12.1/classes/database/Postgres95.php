<?php

/**
 * PostgreSQL 9.5 support
 *
 */

include_once('./classes/database/Postgres96.php');

class Postgres95 extends Postgres96 {

	var $major_version = 9.5;

	/**
	 * Constructor
	 * @param $conn The database connection
	 */
    function __construct($conn) {
        parent::__construct($conn);
    }

	// Help functions

	function getHelpPages() {
		include_once('./help/PostgresDoc95.php');
		return $this->help_page;
	}


	/**
	 * Returns all available process information.
	 * @param $database (optional) Find only connections to specified database
	 * @return A recordset
	 */
	function getProcesses($database = null) {
		if ($database === null)
			$sql = "SELECT datname, usename, pid, waiting, state_change as query_start,
                  case when state='idle in transaction' then '<IDLE> in transaction' when state = 'idle' then '<IDLE>' else query end as query 
				FROM pg_catalog.pg_stat_activity
				ORDER BY datname, usename, pid";
		else {
			$this->clean($database);
			$sql = "SELECT datname, usename, pid, waiting, state_change as query_start,
                  case when state='idle in transaction' then '<IDLE> in transaction' when state = 'idle' then '<IDLE>' else query end as query 
				FROM pg_catalog.pg_stat_activity
				WHERE datname='{$database}'
				ORDER BY usename, pid";
		}

		return $this->selectSet($sql);
	}


}
?>
