<?php

/**
 * PostgreSQL 9.4 support
 *
 */

include_once('./classes/database/Postgres.php');

class Postgres94 extends Postgres {

	var $major_version = 9.4;

	/**
	 * Constructor
	 * @param $conn The database connection
	 */
	function Postgres94($conn) {
		$this->Postgres($conn);
	}

	// Help functions

	function getHelpPages() {
		include_once('./help/PostgresDoc94.php');
		return $this->help_page;
	}

}
?>
