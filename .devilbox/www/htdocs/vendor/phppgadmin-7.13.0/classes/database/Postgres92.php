<?php

/**
 * PostgreSQL 9.2 support
 *
 */

include_once('./classes/database/Postgres93.php');

class Postgres92 extends Postgres93 {

	var $major_version = 9.2;

	/**
	 * Constructor
	 * @param $conn The database connection
	 */
	function __construct($conn) {
		parent::__construct($conn);
	}

	// Help functions

	function getHelpPages() {
		include_once('./help/PostgresDoc92.php');
		return $this->help_page;
	}

}
?>
