<?php

	/**
	 * Manage servers
	 *
	 * $Id: servers.php,v 1.12 2008/02/18 22:20:26 ioguix Exp $
	 */

	// Include application functions
	$_no_db_connection = true;
	include_once('./libraries/lib.inc.php');
	
	$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';
	if (!isset($msg)) $msg = '';
	
	function doLogout() {
		global $misc, $lang, $_reload_browser, $plugin_manager;

		$plugin_manager->do_hook('logout', $_REQUEST['logoutServer']);

		$server_info = $misc->getServerInfo($_REQUEST['logoutServer']);
		$misc->setServerInfo(null, null, $_REQUEST['logoutServer']);

		unset($_SESSION['sharedUsername'], $_SESSION['sharedPassword']);

		doDefault(sprintf($lang['strlogoutmsg'], $server_info['desc']));

		$_reload_browser = true;
	}

	function doDefault($msg = '') {
		global $conf, $misc;
		global $lang;
		
		$misc->printTabs('root','servers');
		$misc->printMsg($msg);
		$group = isset($_GET['group']) ? $_GET['group'] : false;
		
		$groups = $misc->getServersGroups(true,$group);

		$columns = array(
			'group' => array(
				'title' => $lang['strgroup'],
				'field' => field('desc'),
				'url' => 'servers.php?',
				'vars' => array('group' => 'id'),
			),
		);
		$actions = array();

		if (($group !== false) and (isset($conf['srv_groups'][$group])) and ($groups->recordCount()>0)) {
			$misc->printTitle(sprintf($lang['strgroupgroups'],htmlentities($conf['srv_groups'][$group]['desc'], ENT_QUOTES, 'UTF-8')));
		}

		$misc->printTable($groups, $columns, $actions,'servers-servers');

		$servers = $misc->getServers(true, $group);
		
		function svPre(&$rowdata, $actions) {
			$actions['logout']['disable'] = empty($rowdata->fields['username']);
			return $actions;
		}
		
		$columns = array(
			'server' => array(
				'title' => $lang['strserver'],
				'field' => field('desc'),
				'url'   => "redirect.php?subject=server&amp;",
				'vars'  => array('server' => 'id'),
			),
			'host' => array(
				'title' => $lang['strhost'],
				'field' => field('host'),
			),
			'port' => array(
				'title' => $lang['strport'],
				'field' => field('port'),
			),
			'username' => array(
				'title' => $lang['strusername'],
				'field' => field('username'),
			),
			'actions' => array(
				'title' => $lang['stractions'],
			),
		);
		
		$actions = array(
			'logout' => array(
				'content' => $lang['strlogout'],
				'attr'=> array (
					'href' => array (
						'url' => 'servers.php',
						'urlvars' => array (
							'action' => 'logout',
							'logoutServer' => field('id')
						)
					)
				)
			),
		);

		if (($group !== false) and isset($conf['srv_groups'][$group])) {
			$misc->printTitle(sprintf($lang['strgroupservers'],htmlentities($conf['srv_groups'][$group]['desc'], ENT_QUOTES, 'UTF-8')));
			$actions['logout']['attr']['href']['urlvars']['group'] = $group;
		}

		$misc->printTable($servers, $columns, $actions, 'servers-servers', $lang['strnoobjects'], 'svPre');
	}
	
	function doTree() {
		global $misc, $conf;

		$nodes = array();
		$group_id = isset($_GET['group']) ? $_GET['group'] : false;

		/* root with srv_groups */
		if (isset($conf['srv_groups']) and count($conf['srv_groups']) > 0
			and $group_id === false)
		{
			$nodes = $misc->getServersGroups(true);
		}
		/* group subtree */
		else if (isset($conf['srv_groups']) and $group_id !== false) {
			if ($group_id !== 'all')
				$nodes = $misc->getServersGroups(false, $group_id);
			$nodes = array_merge($nodes, $misc->getServers(false, $group_id));
			include_once('./classes/ArrayRecordSet.php');
			$nodes = new ArrayRecordSet($nodes);
		}
		/* no srv_group */
		else {
			$nodes = $misc->getServers(true, false);
		}
		
		$reqvars = $misc->getRequestVars('server');
		
		$attrs = array(
			'text'   => field('desc'),
			
			// Show different icons for logged in/out
			'icon'   => field('icon'),
			
			'toolTip'=> field('id'),
			
			'action' => field('action'),

			// Only create a branch url if the user has
			// logged into the server.
			'branch' => field('branch'),
		);
		
		$misc->printTree($nodes, $attrs, 'servers');
		exit;
	}


	if ($action == 'tree')
		doTree();

	$misc->printHeader($lang['strservers']);
	$misc->printBody();
	$misc->printTrail('root');

	switch ($action) {
		case 'logout':
			doLogout();
			break;
		default:
			doDefault($msg);
			break;
	}

	$misc->printFooter();
?>
