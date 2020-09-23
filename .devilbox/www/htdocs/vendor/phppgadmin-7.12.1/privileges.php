<?php

	/**
	 * Manage privileges in a database
	 *
	 * $Id: privileges.php,v 1.45 2007/09/13 13:41:01 ioguix Exp $
	 */

	// Include application functions
	include_once('./libraries/lib.inc.php');
	
	$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';
	if (!isset($msg)) $msg = '';

	/**
	 * Grant permissions on an object to a user
	 * @param $confirm To show entry screen
	 * @param $mode 'grant' or 'revoke'
	 * @param $msg (optional) A message to show
	 */
	function doAlter($confirm, $mode, $msg = '') {
		global $data, $misc;
		global $lang;

		if (!isset($_REQUEST['username'])) $_REQUEST['username'] = array();
		if (!isset($_REQUEST['groupname'])) $_REQUEST['groupname'] = array();
		if (!isset($_REQUEST['privilege'])) $_REQUEST['privilege'] = array();
	
		if ($confirm) {
			// Get users from the database
			$users = $data->getUsers();
			// Get groups from the database
			$groups = $data->getGroups();
		
			$misc->printTrail($_REQUEST['subject']);
			
			switch ($mode) {
				case 'grant':
					$misc->printTitle($lang['strgrant'],'pg.privilege.grant');
					break;
				case 'revoke':
					$misc->printTitle($lang['strrevoke'],'pg.privilege.revoke');
					break;
			}
			$misc->printMsg($msg);
			
			echo "<form action=\"privileges.php\" method=\"post\">\n";
			echo "<table>\n";
			echo "<tr><th class=\"data left\">{$lang['strusers']}</th>\n";
			echo "<td class=\"data1\"><select name=\"username[]\" multiple=\"multiple\" size=\"", min(6, $users->recordCount()), "\">\n";
			while (!$users->EOF) {
				$uname = htmlspecialchars($users->fields['usename']);
				echo "<option value=\"{$uname}\"",
					in_array($users->fields['usename'], $_REQUEST['username']) ? ' selected="selected"' : '', ">{$uname}</option>\n";
				$users->moveNext();
			}
			echo "</select></td></tr>\n";
			echo "<tr><th class=\"data left\">{$lang['strgroups']}</th>\n";
			echo "<td class=\"data1\">\n";
			echo "<input type=\"checkbox\" id=\"public\" name=\"public\"", (isset($_REQUEST['public']) ? ' checked="checked"' : ''), " /><label for=\"public\">PUBLIC</label>\n";
			// Only show groups if there are groups!
			if ($groups->recordCount() > 0) {
				echo "<br /><select name=\"groupname[]\" multiple=\"multiple\" size=\"", min(6, $groups->recordCount()), "\">\n";
				while (!$groups->EOF) {
					$gname = htmlspecialchars($groups->fields['groname']);
					echo "<option value=\"{$gname}\"",
						in_array($groups->fields['groname'], $_REQUEST['groupname']) ? ' selected="selected"' : '', ">{$gname}</option>\n";
					$groups->moveNext();
				}
				echo "</select>\n";
			}
			echo "</td></tr>\n";
			echo "<tr><th class=\"data left required\">{$lang['strprivileges']}</th>\n";
			echo "<td class=\"data1\">\n";
			foreach ($data->privlist[$_REQUEST['subject']] as $v) {
				$v = htmlspecialchars($v);
				echo "<input type=\"checkbox\" id=\"privilege[$v]\" name=\"privilege[$v]\"", 
							isset($_REQUEST['privilege'][$v]) ? ' checked="checked"' : '', " /><label for=\"privilege[$v]\">{$v}</label><br />\n";
			}
			echo "</td></tr>\n";
			// Grant option
			if ($data->hasGrantOption()) {
				echo "<tr><th class=\"data left\">{$lang['stroptions']}</th>\n";
				echo "<td class=\"data1\">\n";
				if ($mode == 'grant') {
					echo "<input type=\"checkbox\" id=\"grantoption\" name=\"grantoption\"", 
								isset($_REQUEST['grantoption']) ? ' checked="checked"' : '', " /><label for=\"grantoption\">GRANT OPTION</label>\n";
				}
				elseif ($mode == 'revoke') {
					echo "<input type=\"checkbox\" id=\"grantoption\" name=\"grantoption\"", 
								isset($_REQUEST['grantoption']) ? ' checked="checked"' : '', " /><label for=\"grantoption\">GRANT OPTION FOR</label><br />\n";
					echo "<input type=\"checkbox\" id=\"cascade\" name=\"cascade\"", 
								isset($_REQUEST['cascade']) ? ' checked="checked"' : '', " /><label for=\"cascade\">CASCADE</label><br />\n";
				}
				echo "</td></tr>\n";
			}
			echo "</table>\n";

			echo "<p><input type=\"hidden\" name=\"action\" value=\"save\" />\n";
			echo "<input type=\"hidden\" name=\"mode\" value=\"", htmlspecialchars($mode), "\" />\n";
			echo "<input type=\"hidden\" name=\"subject\" value=\"", htmlspecialchars($_REQUEST['subject']), "\" />\n";
			if (isset($_REQUEST[$_REQUEST['subject'].'_oid']))
				echo "<input type=\"hidden\" name=\"", htmlspecialchars($_REQUEST['subject'].'_oid'),
					"\" value=\"", htmlspecialchars($_REQUEST[$_REQUEST['subject'].'_oid']), "\" />\n";
			echo "<input type=\"hidden\" name=\"", htmlspecialchars($_REQUEST['subject']),
				"\" value=\"", htmlspecialchars($_REQUEST[$_REQUEST['subject']]), "\" />\n";
			if ($_REQUEST['subject'] == 'column')
				echo "<input type=\"hidden\" name=\"table\" value=\"",
					htmlspecialchars($_REQUEST['table']), "\" />\n";
			echo $misc->form;
			if ($mode == 'grant')
				echo "<input type=\"submit\" name=\"grant\" value=\"{$lang['strgrant']}\" />\n";
			elseif ($mode == 'revoke')
				echo "<input type=\"submit\" name=\"revoke\" value=\"{$lang['strrevoke']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>";
			echo "</form>\n";
		}
		else {
			// Determine whether object should be ref'd by name or oid.
			if (isset($_REQUEST[$_REQUEST['subject'].'_oid']))
				$object = $_REQUEST[$_REQUEST['subject'].'_oid'];
			else
				$object = $_REQUEST[$_REQUEST['subject']];

			if (isset($_REQUEST['table'])) $table = $_REQUEST['table'];
			else $table = null;
			$status = $data->setPrivileges(($mode == 'grant') ? 'GRANT' : 'REVOKE', $_REQUEST['subject'], $object,
				isset($_REQUEST['public']), $_REQUEST['username'], $_REQUEST['groupname'], array_keys($_REQUEST['privilege']),
				isset($_REQUEST['grantoption']), isset($_REQUEST['cascade']), $table);

			if ($status == 0)
				doDefault($lang['strgranted']);
			elseif ($status == -3 || $status == -4)
				doAlter(true, $_REQUEST['mode'], $lang['strgrantbad']);
			else
				doAlter(true, $_REQUEST['mode'], $lang['strgrantfailed']);
		}
	}

	/**
	 * Show permissions on a database, namespace, relation, language or function
	 */
	function doDefault($msg = '') {
		global $data, $misc, $database;
		global $lang;

		$misc->printTrail($_REQUEST['subject']);
		
		# @@@FIXME: This switch is just a temporary solution,
		# need a better way, maybe every type of object should
		# have a tab bar???
		switch ($_REQUEST['subject']) {
			case 'server':
			case 'database':
			case 'schema':
			case 'table':
			case 'column':
			case 'view':
				$misc->printTabs($_REQUEST['subject'], 'privileges');
				break;
			default:
				$misc->printTitle($lang['strprivileges'], 'pg.privilege');
		}
		$misc->printMsg($msg);

		// Determine whether object should be ref'd by name or oid.
		if (isset($_REQUEST[$_REQUEST['subject'].'_oid']))
			$object = $_REQUEST[$_REQUEST['subject'].'_oid'];
		else
			$object = $_REQUEST[$_REQUEST['subject']];
		
		// Get the privileges on the object, given its type
		if ($_REQUEST['subject'] == 'column')
			$privileges = $data->getPrivileges($object, 'column', $_REQUEST['table']);
		else
			$privileges = $data->getPrivileges($object, $_REQUEST['subject']);

		if (sizeof($privileges) > 0) {
			echo "<table>\n";
			if ($data->hasRoles())
				echo "<tr><th class=\"data\">{$lang['strrole']}</th>";
			else
				echo "<tr><th class=\"data\">{$lang['strtype']}</th><th class=\"data\">{$lang['struser']}/{$lang['strgroup']}</th>";

			foreach ($data->privlist[$_REQUEST['subject']] as $v2) {
				// Skip over ALL PRIVILEGES
				if ($v2 == 'ALL PRIVILEGES') continue;
				echo "<th class=\"data\">{$v2}</th>\n";
			}
			if ($data->hasGrantOption()) {
				echo "<th class=\"data\">{$lang['strgrantor']}</th>";
			}
			echo "</tr>\n";

			// Loop over privileges, outputting them
			$i = 0;
			foreach ($privileges as $v) {
				$id = (($i % 2) == 0 ? '1' : '2');
				echo "<tr class=\"data{$id}\">\n";
				if (!$data->hasRoles())
					echo "<td>", $misc->printVal($v[0]), "</td>\n";
				echo "<td>", $misc->printVal($v[1]), "</td>\n";
				foreach ($data->privlist[$_REQUEST['subject']] as $v2) {
					// Skip over ALL PRIVILEGES
					if ($v2 == 'ALL PRIVILEGES') continue;
					echo "<td>";
					if (in_array($v2, $v[2]))
						echo $lang['stryes'];
					else
						echo $lang['strno'];
					// If we have grant option for this, end mark
					if ($data->hasGrantOption() && in_array($v2, $v[4])) echo $lang['strasterisk'];
					echo "</td>\n";
				}
				if ($data->hasGrantOption()) {
					echo "<td>", $misc->printVal($v[3]), "</td>\n";
				}
				echo "</tr>\n";
				$i++;
			}

			echo "</table>";
		}
		else {
			echo "<p>{$lang['strnoprivileges']}</p>\n";
		}
		
		// Links for granting to a user or group
		switch ($_REQUEST['subject']) {
			case 'table':
			case 'view':
			case 'sequence':
			case 'function':
			case 'tablespace':
				$alllabel = "showall{$_REQUEST['subject']}s";
				$allurl = "{$_REQUEST['subject']}s.php";
				$alltxt = $lang["strshowall{$_REQUEST['subject']}s"];
				break;
			case 'schema':
				$alllabel = "showallschemas";
				$allurl = "schemas.php";
				$alltxt = $lang["strshowallschemas"];
				break;
			case 'database':
				$alllabel = "showalldatabases";
				$allurl = 'all_db.php';
				$alltxt = $lang['strshowalldatabases'];
				break;
		}

		$subject = $_REQUEST['subject'];
		$object = $_REQUEST[$_REQUEST['subject']];
		
		if ($_REQUEST['subject'] == 'function') {
			$objectoid = $_REQUEST[$_REQUEST['subject'].'_oid'];
			$urlvars = array (
				'action' => 'alter',
				'server' => $_REQUEST['server'],
				'database' => $_REQUEST['database'],
				'schema' => $_REQUEST['schema'],
				$subject => $object,
				"{$subject}_oid" => $objectoid,
				'subject'=> $subject
			);
		}
		else if ($_REQUEST['subject'] == 'column') {
			$urlvars = array (
				'action' => 'alter',
				'server' => $_REQUEST['server'],
				'database' => $_REQUEST['database'],
				'schema' => $_REQUEST['schema'],
				$subject => $object,
				'subject'=> $subject
			);

			if (isset($_REQUEST['table']))
				$urlvars['table'] = $_REQUEST['table'];
			else
				$urlvars['view'] = $_REQUEST['view'];
		}
		else {
			$urlvars = array (
				'action' => 'alter',
				'server' => $_REQUEST['server'],
				'database' => $_REQUEST['database'],
				$subject => $object,
				'subject'=> $subject
			);
			if (isset($_REQUEST['schema'])) {
				$urlvars['schema'] = $_REQUEST['schema'];
			}
		}

		$navlinks = array (
			'grant' => array (
				'attr'=> array (
					'href' => array (
						'url' => 'privileges.php',
						'urlvars' => array_merge($urlvars, array('mode' => 'grant'))
					)
				),
				'content' => $lang['strgrant']
			),
			'revoke' => array (
				'attr'=> array (
					'href' => array (
						'url' => 'privileges.php',
						'urlvars' => array_merge($urlvars, array('mode' => 'revoke'))
					)
				),
				'content' => $lang['strrevoke']
			)
		);

		if (isset($allurl)) {
			$navlinks[$alllabel] = array (
				'attr'=> array (
					'href' => array (
						'url' => $allurl,
						'urlvars' => array (
							'server' => $_REQUEST['server'],
							'database' => $_REQUEST['database']
						)
					)
				),
				'content' => $alltxt
			);
			if (isset($_REQUEST['schema'])) {
				$navlinks[$alllabel]['attr']['href']['urlvars']['schema'] = $_REQUEST['schema'];
			}
		}

		$misc->printNavLinks($navlinks, 'privileges-privileges', get_defined_vars());
	}

	$misc->printHeader($lang['strprivileges']);
	$misc->printBody();

	switch ($action) {
		case 'save':
			if (isset($_REQUEST['cancel'])) doDefault();
			else doAlter(false, $_REQUEST['mode']);
			break;
		case 'alter':
			doAlter(true, $_REQUEST['mode']);
			break;
		default:
			doDefault();
			break;
	}	

	$misc->printFooter();
	
?>
