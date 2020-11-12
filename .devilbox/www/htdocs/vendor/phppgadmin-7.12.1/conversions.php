<?php

	/**
	 * Manage conversions in a database
	 *
	 * $Id: conversions.php,v 1.15 2007/08/31 18:30:10 ioguix Exp $
	 */

	// Include application functions
	include_once('./libraries/lib.inc.php');
	
	$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';
	if (!isset($msg)) $msg = '';

	/**
	 * Show default list of conversions in the database
	 */
	function doDefault($msg = '') {
		global $data, $conf, $misc, $database;
		global $lang;

		$misc->printTrail('schema');
		$misc->printTabs('schema', 'conversions');
		$misc->printMsg($msg);
		
		$conversions = $data->getconversions();
		
		$columns = array(
			'conversion' => array(
				'title' => $lang['strname'],
				'field' => field('conname'),
			),
			'source_encoding' => array(
				'title' => $lang['strsourceencoding'],
				'field' => field('conforencoding'),
			),
			'target_encoding' => array(
				'title' => $lang['strtargetencoding'],
				'field' => field('contoencoding'),
			),
			'default' => array(
				'title' => $lang['strdefault'],
				'field' => field('condefault'),
				'type'  => 'yesno',
			),
			'comment' => array(
				'title' => $lang['strcomment'],
				'field' => field('concomment'),
			),
		);
		
		$actions = array();
		
		$misc->printTable($conversions, $columns, $actions, 'conversions-conversions', $lang['strnoconversions']);
	}
	
	/**
	 * Generate XML for the browser tree.
	 */
	function doTree() {
		global $misc, $data;
		
		$conversions = $data->getconversions();
		
		$attrs = array(
			'text'   => field('conname'),
			'icon'   => 'Conversion',
			'toolTip'=> field('concomment')
		);
		
		$misc->printTree($conversions, $attrs, 'conversions');
		exit;
	}
	
	if ($action == 'tree') doTree();
	
	$misc->printHeader($lang['strconversions']);
	$misc->printBody();

	switch ($action) {
		default:
			doDefault();
			break;
	}	

	$misc->printFooter();

?>
