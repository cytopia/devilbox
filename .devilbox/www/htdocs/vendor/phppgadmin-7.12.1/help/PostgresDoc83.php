<?php

/**
 * Help links for PostgreSQL 8.3 documentation
 *
 * $Id: PostgresDoc83.php,v 1.3 2008/03/17 21:35:48 ioguix Exp $
 */

include('./help/PostgresDoc82.php');

$this->help_base = sprintf($GLOBALS['conf']['help_base'], '8.3');

$this->help_page['pg.fts'] = 'textsearch.html';

$this->help_page['pg.ftscfg'] = 'textsearch-intro.html#TEXTSEARCH-INTRO-CONFIGURATIONS';
$this->help_page['pg.ftscfg.example'] = 'textsearch-configuration.html';
$this->help_page['pg.ftscfg.drop'] = 'sql-droptsconfig.html';
$this->help_page['pg.ftscfg.create'] = 'sql-createtsconfig.html';
$this->help_page['pg.ftscfg.alter'] = 'sql-altertsconfig.html';

$this->help_page['pg.ftsdict'] = 'textsearch-dictionaries.html';
$this->help_page['pg.ftsdict.drop'] = 'sql-droptsdictionary.html';
$this->help_page['pg.ftsdict.create'] = array('sql-createtsdictionary.html', 'sql-createtstemplate.html');
$this->help_page['pg.ftsdict.alter'] = 'sql-altertsdictionary.html';

$this->help_page['pg.ftsparser'] = 'textsearch-parsers.html';
?>
