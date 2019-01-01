<?php

	/**
	 * Manage languages in a database
	 *
	 * $Id: languages.php,v 1.13 2007/08/31 18:30:11 ioguix Exp $
	 */

	// Include application functions
	include_once('./libraries/lib.inc.php');
	
	$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';
	if (!isset($msg)) $msg = '';

	/**
	 * Show default list of languages in the database
	 */
	function doDefault($msg = '') {
		global $data, $misc, $database;
		global $lang;
		
		$misc->printTrail('database');
		$misc->printTabs('database','languages');
		$misc->printMsg($msg);
		
		$languages = $data->getLanguages();

		$columns = array(
			'language' => array(
				'title' => $lang['strname'],
				'field' => field('lanname'),
			),
			'trusted' => array(
				'title' => $lang['strtrusted'],
				'field' => field('lanpltrusted'),
				'type'  => 'yesno',
			),
			'function' => array(
				'title' => $lang['strfunction'],
				'field' => field('lanplcallf'),
			),
		);

		$actions = array();

		$misc->printTable($languages, $columns, $actions, 'languages-languages', $lang['strnolanguages']);
	}

	/**
	 * Generate XML for the browser tree.
	 */
	function doTree() {
		global $misc, $data;
		
		$languages = $data->getLanguages();
		
		$attrs = array(
			'text'   => field('lanname'),
			'icon'   => 'Language'
		);
		
		$misc->printTree($languages, $attrs, 'languages');
		exit;
	}
	
	if ($action == 'tree') doTree();
	
	$misc->printHeader($lang['strlanguages']);
	$misc->printBody();

	switch ($action) {
		default:
			doDefault();
			break;
	}	

	$misc->printFooter();

?>
