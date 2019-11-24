<?php

/**
 * Help links for PostgreSQL 8.1 documentation
 *
 * $Id: PostgresDoc81.php,v 1.3 2006/12/28 04:26:55 xzilla Exp $
 */

include('./help/PostgresDoc80.php');

$this->help_base = sprintf($GLOBALS['conf']['help_base'], '8.1');

$this->help_page['pg.role'] = 'user-manag.html';
$this->help_page['pg.role.create'] = array('sql-createrole.html','user-manag.html#DATABASE-ROLES');
$this->help_page['pg.role.alter'] = array('sql-alterrole.html','role-attributes.html');
$this->help_page['pg.role.drop'] = array('sql-droprole.html','user-manag.html#DATABASE-ROLES');

?>
