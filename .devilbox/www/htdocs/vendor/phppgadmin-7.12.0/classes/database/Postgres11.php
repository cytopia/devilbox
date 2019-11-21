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

	// Help functions

	function getHelpPages() {
		include_once('./help/PostgresDoc11.php');
		return $this->help_page;
	}

}
?>
