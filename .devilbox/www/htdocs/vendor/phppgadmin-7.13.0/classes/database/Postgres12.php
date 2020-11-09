<?php

/**
 * PostgreSQL 12 support
 *
 */

include_once('./classes/database/Postgres13.php');

class Postgres12 extends Postgres13 {

	var $major_version = 12;

	/**
	 * Constructor
	 * @param $conn The database connection
	 */
	function __construct($conn) {
		parent::__construct($conn);
	}

	// Help functions

	function getHelpPages() {
		include_once('./help/PostgresDoc12.php');
		return $this->help_page;
	}


	// Capabilities

}
?>
