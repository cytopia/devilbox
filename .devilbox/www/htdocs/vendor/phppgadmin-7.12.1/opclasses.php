<?php

	/**
	 * Manage opclasss in a database
	 *
	 * $Id: opclasses.php,v 1.10 2007/08/31 18:30:11 ioguix Exp $
	 */

	// Include application functions
	include_once('./libraries/lib.inc.php');
	
	$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';
	if (!isset($msg)) $msg = '';

	/**
	 * Show default list of opclasss in the database
	 */
	function doDefault($msg = '') {
		global $data, $conf, $misc;
		global $lang;
		
		$misc->printTrail('schema');
		$misc->printTabs('schema','opclasses');
		$misc->printMsg($msg);
		
		$opclasses = $data->getOpClasses();
		
		$columns = array(
			'accessmethod' => array(
				'title' => $lang['straccessmethod'],
				'field' => field('amname'),
			),
			'opclass' => array(
				'title' => $lang['strname'],
				'field' => field('opcname'),
			),
			'type' => array(
				'title' => $lang['strtype'],
				'field' => field('opcintype'),
			),
			'default' => array(
				'title' => $lang['strdefault'],
				'field' => field('opcdefault'),
				'type'  => 'yesno',
			),
			'comment' => array(
				'title' => $lang['strcomment'],
				'field' => field('opccomment'),
			),
		);
		
		$actions = array();
		
		$misc->printTable($opclasses, $columns, $actions, 'opclasses-opclasses', $lang['strnoopclasses']);
	}
	
	/**
	 * Generate XML for the browser tree.
	 */
	function doTree() {
		global $misc, $data;
		
		$opclasses = $data->getOpClasses();
		
		// OpClass prototype: "op_class/access_method"
		$proto = concat(field('opcname'),'/',field('amname'));
		
		$attrs = array(
			'text'   => $proto,
			'icon'   => 'OperatorClass',
			'toolTip'=> field('opccomment'),
		);
		
		$misc->printTree($opclasses, $attrs, 'opclasses');
		exit;
	}
	
	if ($action == 'tree') doTree();
	
	$misc->printHeader($lang['stropclasses']);
	$misc->printBody();

	switch ($action) {
		default:
			doDefault();
			break;
	}	

	$misc->printFooter();

?>
