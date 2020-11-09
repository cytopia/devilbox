<?php

	/**
	 * Manage casts in a database
	 *
	 * $Id: casts.php,v 1.16 2007/09/25 16:08:05 ioguix Exp $
	 */

	// Include application functions
	include_once('./libraries/lib.inc.php');
	
	$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';
	if (!isset($msg)) $msg = '';

	/**
	 * Show default list of casts in the database
	 */
	function doDefault($msg = '') {
		global $data, $misc, $database;
		global $lang;

		function renderCastContext($val) {
			global $lang;
			switch ($val) {
				case 'e': return $lang['strno'];
				case 'a': return $lang['strinassignment'];
				default: return $lang['stryes'];
			}
		}
		
		$misc->printTrail('database');
		$misc->printTabs('database','casts');
		$misc->printMsg($msg);
		
		$casts = $data->getCasts();

		$columns = array(
			'source_type' => array(
				'title' => $lang['strsourcetype'],
				'field' => field('castsource'),
			),
			'target_type' => array(
				'title' => $lang['strtargettype'],
				'field' => field('casttarget'),
			),
			'function' => array(
				'title' => $lang['strfunction'],
				'field' => field('castfunc'),
				'params'=> array('null' => $lang['strbinarycompat']),
			),
			'implicit' => array(
				'title' => $lang['strimplicit'],
				'field' => field('castcontext'),
				'type'  => 'callback',
				'params'=> array('function' => 'renderCastContext', 'align' => 'center'),
			),
			'comment' => array(
				'title' => $lang['strcomment'],
				'field' => field('castcomment'),
			),
		);

		$actions = array();
		
		$misc->printTable($casts, $columns, $actions, 'casts-casts', $lang['strnocasts']);
	}

	/**
	 * Generate XML for the browser tree.
	 */
	function doTree() {
		global $misc, $data;
		
		$casts = $data->getCasts();
		
		$proto = concat(field('castsource'), ' AS ', field('casttarget'));
		
		$attrs = array(
			'text'   => $proto,
			'icon'   => 'Cast'
		);
		
		$misc->printTree($casts, $attrs, 'casts');
		exit;
	}
	
	if ($action == 'tree') doTree();
	
	$misc->printHeader($lang['strcasts']);
	$misc->printBody();

	switch ($action) {
		case 'tree':
			doTree();
			break;
		default:
			doDefault();
			break;
	}	

	$misc->printFooter();

?>
