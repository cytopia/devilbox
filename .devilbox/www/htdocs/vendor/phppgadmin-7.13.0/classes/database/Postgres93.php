<?php

/**
 * PostgreSQL 9.3 support
 *
 */

include_once('./classes/database/Postgres94.php');

class Postgres93 extends Postgres94 {

	var $major_version = 9.3;

	/**
	 * Constructor
	 * @param $conn The database connection
	 */
	function __construct($conn) {
		parent::__construct($conn);
	}

	// Help functions

	function getHelpPages() {
		include_once('./help/PostgresDoc93.php');
		return $this->help_page;
	}

}
?>
