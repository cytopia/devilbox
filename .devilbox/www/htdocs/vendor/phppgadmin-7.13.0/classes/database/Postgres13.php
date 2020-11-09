<?php

/**
 * PostgreSQL 13 support
 *
 */

include_once('./classes/database/Postgres.php');

class Postgres13 extends Postgres {

	var $major_version = 13;

	/**
	 * Constructor
	 * @param $conn The database connection
	 */
	function __construct($conn) {
		parent::__construct($conn);
	}

	// Help functions

	function getHelpPages() {
		include_once('./help/PostgresDoc13.php');
		return $this->help_page;
	}


	// Capabilities

}
?>
