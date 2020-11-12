<?php

	/**
	 * Manage groups in a database cluster
	 *
	 * $Id: groups.php,v 1.27 2007/08/31 18:30:11 ioguix Exp $
	 */

	// Include application functions
	include_once('./libraries/lib.inc.php');

	$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';
	if (!isset($msg)) $msg = '';

	/**
	 * Add user to a group
	 */
	function doAddMember() {
		global $data, $misc;
		global $lang;

		$status = $data->addGroupMember($_REQUEST['group'], $_REQUEST['user']);
		if ($status == 0)
			doProperties($lang['strmemberadded']);
		else
			doProperties($lang['strmemberaddedbad']);
	}
	
	/**
	 * Show confirmation of drop user from group and perform actual drop
	 */
	function doDropMember($confirm) {
		global $data, $misc;
		global $lang;

		if ($confirm) { 
			$misc->printTrail('group');
			$misc->printTitle($lang['strdropmember'],'pg.group.alter');
			
			echo "<p>", sprintf($lang['strconfdropmember'], $misc->printVal($_REQUEST['user']), $misc->printVal($_REQUEST['group'])), "</p>\n";
			
			echo "<form action=\"groups.php\" method=\"post\">\n";
			echo $misc->form;
			echo "<input type=\"hidden\" name=\"action\" value=\"drop_member\" />\n";
			echo "<input type=\"hidden\" name=\"group\" value=\"", htmlspecialchars($_REQUEST['group']), "\" />\n";
			echo "<input type=\"hidden\" name=\"user\" value=\"", htmlspecialchars($_REQUEST['user']), "\" />\n";
			echo "<input type=\"submit\" name=\"drop\" value=\"{$lang['strdrop']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" />\n";
			echo "</form>\n";
		}
		else {
			$status = $data->dropGroupMember($_REQUEST['group'], $_REQUEST['user']);
			if ($status == 0)
				doProperties($lang['strmemberdropped']);
			else
				doDropMember(true, $lang['strmemberdroppedbad']);
		}		
	}
	
	/**
	 * Show read only properties for a group
	 */
	function doProperties($msg = '') {
		global $data, $misc;
		global $lang;
	
		if (!isset($_POST['user'])) $_POST['user'] = '';
	
		$misc->printTrail('group');
		$misc->printTitle($lang['strproperties'],'pg.group');
		$misc->printMsg($msg);
		
		$groupdata = $data->getGroup($_REQUEST['group']);
		$users = $data->getUsers();
		
		if ($groupdata->recordCount() > 0) {
			$columns = array (
				'members' => array (
					'title' => $lang['strmembers'],
					'field' => field('usename')
				),
				'actions' => array (
					'title' => $lang['stractions'],
				)
			);

			$actions = array (
				'drop' => array (
					'content' => $lang['strdrop'],
						'attr'=> array (
							'href' => array (
								'url' => 'groups.php',
								'urlvars' => array (
									'action' => 'confirm_drop_member',
									'group' => $_REQUEST['group'],
									'user' => field('usename')
								)
							)
						)
				)
			);

			$misc->printTable($groupdata, $columns, $actions, 'groups-members', $lang['strnousers']);
		}

		// Display form for adding a user to the group			
		echo "<form action=\"groups.php\" method=\"post\">\n";
		echo "<select name=\"user\">";
		while (!$users->EOF) {
			$uname = $misc->printVal($users->fields['usename']);
			echo "<option value=\"{$uname}\"",
				($uname == $_POST['user']) ? ' selected="selected"' : '', ">{$uname}</option>\n";
			$users->moveNext();
		}
		echo "</select>\n";
		echo "<input type=\"submit\" value=\"{$lang['straddmember']}\" />\n";
		echo $misc->form;
		echo "<input type=\"hidden\" name=\"group\" value=\"", htmlspecialchars($_REQUEST['group']), "\" />\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"add_member\" />\n";
		echo "</form>\n";
		
		$misc->printNavLinks(array ('showall' => array (
				'attr'=> array (
					'href' => array (
						'url' => 'groups.php',
						'urlvars' => array (
							'server' => $_REQUEST['server']
						)
					)
				),
				'content' => $lang['strshowallgroups']
			)), 'groups-properties', get_defined_vars());
	}
	
	/**
	 * Show confirmation of drop and perform actual drop
	 */
	function doDrop($confirm) {
		global $data, $misc;
		global $lang;

		if ($confirm) {
			$misc->printTrail('group');
			$misc->printTitle($lang['strdrop'],'pg.group.drop');
			
			echo "<p>", sprintf($lang['strconfdropgroup'], $misc->printVal($_REQUEST['group'])), "</p>\n";
			
			echo "<form action=\"groups.php\" method=\"post\">\n";
			echo $misc->form;
			echo "<input type=\"hidden\" name=\"action\" value=\"drop\" />\n";
			echo "<input type=\"hidden\" name=\"group\" value=\"", htmlspecialchars($_REQUEST['group']), "\" />\n";
			echo "<input type=\"submit\" name=\"drop\" value=\"{$lang['strdrop']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" />\n";
			echo "</form>\n";
		}
		else {
			$status = $data->dropGroup($_REQUEST['group']);
			if ($status == 0)
				doDefault($lang['strgroupdropped']);
			else
				doDefault($lang['strgroupdroppedbad']);
		}		
	}
	
	/**
	 * Displays a screen where they can enter a new group
	 */
	function doCreate($msg = '') {
		global $data, $misc;
		global $lang;
		
		if (!isset($_POST['name'])) $_POST['name'] = '';
		if (!isset($_POST['members'])) $_POST['members'] = array();

		// Fetch a list of all users in the cluster
		$users = $data->getUsers();
		
		$misc->printTrail('server');
		$misc->printTitle($lang['strcreategroup'],'pg.group.create');
		$misc->printMsg($msg);

		echo "<form action=\"\" method=\"post\">\n";
		echo $misc->form;
		echo "<table>\n";
		echo "\t<tr>\n\t\t<th class=\"data left required\">{$lang['strname']}</th>\n";
		echo "\t\t<td class=\"data\"><input size=\"32\" maxlength=\"{$data->_maxNameLen}\" name=\"name\" value=\"", htmlspecialchars($_POST['name']), "\" /></td>\n\t</tr>\n";
		if ($users->recordCount() > 0) {
			echo "\t<tr>\n\t\t<th class=\"data left\">{$lang['strmembers']}</th>\n";

			echo "\t\t<td class=\"data\">\n";
			echo "\t\t\t<select name=\"members[]\" multiple=\"multiple\" size=\"", min(40, $users->recordCount()), "\">\n";
			while (!$users->EOF) {
				$username = $users->fields['usename'];
				echo "\t\t\t\t<option value=\"{$username}\"",
						(in_array($username, $_POST['members']) ? ' selected="selected"' : ''), ">", $misc->printVal($username), "</option>\n";
				$users->moveNext();
			}
			echo "\t\t\t</select>\n";
			echo "\t\t</td>\n\t</tr>\n";
			}
		echo "</table>\n";
		echo "<p><input type=\"hidden\" name=\"action\" value=\"save_create\" />\n";
		echo "<input type=\"submit\" value=\"{$lang['strcreate']}\" />\n";
		echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
		echo "</form>\n";
	}
	
	/**
	 * Actually creates the new group in the database
	 */
	function doSaveCreate() {
		global $data;
		global $lang;

		if (!isset($_POST['members'])) $_POST['members'] = array();

		// Check form vars
		if (trim($_POST['name']) == '')
			doCreate($lang['strgroupneedsname']);
		else {		
			$status = $data->createGroup($_POST['name'], $_POST['members']);
			if ($status == 0)
				doDefault($lang['strgroupcreated']);
			else
				doCreate($lang['strgroupcreatedbad']);
		}
	}	

	/**
	 * Show default list of groups in the database
	 */
	function doDefault($msg = '') {
		global $data, $misc;
		global $lang;
		
		$misc->printTrail('server');
		$misc->printTabs('server','groups');
		$misc->printMsg($msg);
		
		$groups = $data->getGroups();
		
		$columns = array(
			'group' => array(
				'title' => $lang['strgroup'],
				'field' => field('groname'),
				'url'   => "groups.php?action=properties&amp;{$misc->href}&amp;",
				'vars'  => array('group' => 'groname'),
			),
			'actions' => array(
				'title' => $lang['stractions'],
			),
		);
		
		$actions = array(
			'drop' => array(
				'content' => $lang['strdrop'],
				'attr'=> array (
					'href' => array (
						'url' => 'groups.php',
						'urlvars' => array (
							'action' => 'confirm_drop',
							'group' => field('groname')
						)
					)
				)
			),
		);
		
		$misc->printTable($groups, $columns, $actions, 'groups-properties', $lang['strnogroups']);
		
		$misc->printNavLinks(array ('create' => array (
				'attr'=> array (
					'href' => array (
						'url' => 'groups.php',
						'urlvars' => array (
							'action' => 'create',
							'server' => $_REQUEST['server']
						)
					)
				),
				'content' => $lang['strcreategroup']
			)), 'groups-groups', get_defined_vars());

	}

	$misc->printHeader($lang['strgroups']);
	$misc->printBody();

	switch ($action) {
		case 'add_member':
			doAddMember();
			break;
		case 'drop_member':
			if (isset($_REQUEST['drop'])) doDropMember(false);
			else doProperties();
			break;
		case 'confirm_drop_member':
			doDropMember(true);
			break;			
		case 'save_create':
			if (isset($_REQUEST['cancel'])) doDefault();
			else doSaveCreate();
			break;
		case 'create':
			doCreate();
			break;
		case 'drop':
			if (isset($_REQUEST['drop'])) doDrop(false);
			else doDefault();
			break;
		case 'confirm_drop':
			doDrop(true);
			break;			
		case 'save_edit':
			doSaveEdit();
			break;
		case 'edit':
			doEdit();
			break;
		case 'properties':
			doProperties();
			break;
		default:
			doDefault();
			break;
	}	

	$misc->printFooter();

?>
