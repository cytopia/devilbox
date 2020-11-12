<?php

	/**
	 * Manage databases within a server
	 *
	 * $Id: all_db.php,v 1.59 2007/10/17 21:40:19 ioguix Exp $
	 */

	// Include application functions
	include_once('./libraries/lib.inc.php');

	$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';
	if (!isset($msg)) $msg = '';

	/**
	 * Display a form for alter and perform actual alter
	 */
	function doAlter($confirm) {
		global $data, $misc, $_reload_browser;
		global $lang;

		if ($confirm) {
			$misc->printTrail('database');
			$misc->printTitle($lang['stralter'], 'pg.database.alter');

			echo "<form action=\"all_db.php\" method=\"post\">\n";
			echo "<table>\n";
			echo "<tr><th class=\"data left required\">{$lang['strname']}</th>\n";
			echo "<td class=\"data1\">";
			echo "<input name=\"newname\" size=\"32\" maxlength=\"{$data->_maxNameLen}\" value=\"",
				htmlspecialchars($_REQUEST['alterdatabase']), "\" /></td></tr>\n";

			if ($data->hasAlterDatabaseOwner() && $data->isSuperUser()) {
				// Fetch all users

				$rs = $data->getDatabaseOwner($_REQUEST['alterdatabase']);
				$owner = isset($rs->fields['usename']) ? $rs->fields['usename'] : '';
				$users = $data->getUsers();

				echo "<tr><th class=\"data left required\">{$lang['strowner']}</th>\n";
				echo "<td class=\"data1\"><select name=\"owner\">";
				while (!$users->EOF) {
					$uname = $users->fields['usename'];
					echo "<option value=\"", htmlspecialchars($uname), "\"",
						($uname == $owner) ? ' selected="selected"' : '', ">", htmlspecialchars($uname), "</option>\n";
					$users->moveNext();
				}
				echo "</select></td></tr>\n";
			}
			if ($data->hasSharedComments()){
				$rs = $data->getDatabaseComment($_REQUEST['alterdatabase']);
				$comment = isset($rs->fields['description']) ? $rs->fields['description'] : '';
				echo "<tr><th class=\"data left\">{$lang['strcomment']}</th>\n";
				echo "<td class=\"data1\">";
				echo "<textarea rows=\"3\" cols=\"32\" name=\"dbcomment\">",
					htmlspecialchars($comment), "</textarea></td></tr>\n";
			}
			echo "</table>\n";
			echo "<input type=\"hidden\" name=\"action\" value=\"alter\" />\n";
			echo $misc->form;
			echo "<input type=\"hidden\" name=\"oldname\" value=\"",
				htmlspecialchars($_REQUEST['alterdatabase']), "\" />\n";
			echo "<input type=\"submit\" name=\"alter\" value=\"{$lang['stralter']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" />\n";
			echo "</form>\n";
		}
		else {
			if (!isset($_POST['owner'])) $_POST['owner'] = '';
			if (!isset($_POST['dbcomment'])) $_POST['dbcomment'] = '';
			if ($data->alterDatabase($_POST['oldname'], $_POST['newname'], $_POST['owner'], $_POST['dbcomment']) == 0) {
				$_reload_browser = true;
				doDefault($lang['strdatabasealtered']);
			}
			else
				doDefault($lang['strdatabasealteredbad']);
		}
	}

	/**
	 * Show confirmation of drop and perform actual drop
	 */
	function doDrop($confirm) {
		global $data, $misc;
		global $lang, $_reload_drop_database;

		if (empty($_REQUEST['dropdatabase']) && empty($_REQUEST['ma'])) {
			doDefault($lang['strspecifydatabasetodrop']);
			exit();
		}

		if ($confirm) {

            $misc->printTrail('database');
            $misc->printTitle($lang['strdrop'], 'pg.database.drop');

	        echo "<form action=\"all_db.php\" method=\"post\">\n";
            //If multi drop
            if (isset($_REQUEST['ma'])) {

			    foreach($_REQUEST['ma'] as $v) {
			        $a = unserialize(htmlspecialchars_decode($v, ENT_QUOTES));
				    echo "<p>", sprintf($lang['strconfdropdatabase'], $misc->printVal($a['database'])), "</p>\n";
				    printf('<input type="hidden" name="dropdatabase[]" value="%s" />', htmlspecialchars($a['database']));
			    }

			} else {
		            echo "<p>", sprintf($lang['strconfdropdatabase'], $misc->printVal($_REQUEST['dropdatabase'])), "</p>\n";
			        echo "<input type=\"hidden\" name=\"dropdatabase\" value=\"", htmlspecialchars($_REQUEST['dropdatabase']), "\" />\n";
            }// END if multi drop

			echo "<input type=\"hidden\" name=\"action\" value=\"drop\" />\n";
        	echo $misc->form;
			echo "<input type=\"submit\" name=\"drop\" value=\"{$lang['strdrop']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" />\n";
			echo "</form>\n";
		} // END confirm
		else {
            //If multi drop
            if (is_array($_REQUEST['dropdatabase'])) {
                $msg = '';
                foreach($_REQUEST['dropdatabase'] as $d) {
					$status = $data->dropDatabase($d);
					if ($status == 0)
						$msg.= sprintf('%s: %s<br />', htmlentities($d, ENT_QUOTES, 'UTF-8'), $lang['strdatabasedropped']);
					else {
						doDefault(sprintf('%s%s: %s<br />', $msg, htmlentities($d, ENT_QUOTES, 'UTF-8'), $lang['strdatabasedroppedbad']));
						return;
					}
				}// Everything went fine, back to Default page...
                $_reload_drop_database = true;
                doDefault($msg);
            } else {
			    $status = $data->dropDatabase($_POST['dropdatabase']);
			    if ($status == 0) {
				    $_reload_drop_database = true;
				    doDefault($lang['strdatabasedropped']);
			    }
			    else
				    doDefault($lang['strdatabasedroppedbad']);
            }
		}//END DROP
    }// END FUNCTION


	/**
	 * Displays a screen where they can enter a new database
	 */
	function doCreate($msg = '') {
		global $data, $misc;
		global $lang;

		$misc->printTrail('server');
		$misc->printTitle($lang['strcreatedatabase'], 'pg.database.create');
		$misc->printMsg($msg);

		if (!isset($_POST['formName'])) $_POST['formName'] = '';
		// Default encoding is that in language file
		if (!isset($_POST['formEncoding'])) {
		    $_POST['formEncoding'] = '';
		}
		if (!isset($_POST['formTemplate'])) $_POST['formTemplate'] = 'template1';
		if (!isset($_POST['formSpc'])) $_POST['formSpc'] = '';
		if (!isset($_POST['formComment'])) $_POST['formComment'] = '';

		// Fetch a list of databases in the cluster
		$templatedbs = $data->getDatabases(false);

		// Fetch all tablespaces from the database
		if ($data->hasTablespaces()) $tablespaces = $data->getTablespaces();

		echo "<form action=\"all_db.php\" method=\"post\">\n";
		echo "<table>\n";
		echo "\t<tr>\n\t\t<th class=\"data left required\">{$lang['strname']}</th>\n";
		echo "\t\t<td class=\"data1\"><input name=\"formName\" size=\"32\" maxlength=\"{$data->_maxNameLen}\" value=\"",
			htmlspecialchars($_POST['formName']), "\" /></td>\n\t</tr>\n";

		echo "\t<tr>\n\t\t<th class=\"data left required\">{$lang['strtemplatedb']}</th>\n";
		echo "\t\t<td class=\"data1\">\n";
		echo "\t\t\t<select name=\"formTemplate\">\n";
		// Always offer template0 and template1 
		echo "\t\t\t\t<option value=\"template0\"",
			($_POST['formTemplate'] == 'template0') ? ' selected="selected"' : '', ">template0</option>\n";
		echo "\t\t\t\t<option value=\"template1\"",
			($_POST['formTemplate'] == 'template1') ? ' selected="selected"' : '', ">template1</option>\n";
		while (!$templatedbs->EOF) {
			$dbname = htmlspecialchars($templatedbs->fields['datname']);
			if ($dbname != 'template1') { 
				// filter out for $conf[show_system] users so we don't get duplicates 
				echo "\t\t\t\t<option value=\"{$dbname}\"",
					($dbname == $_POST['formTemplate']) ? ' selected="selected"' : '', ">{$dbname}</option>\n";
			}
			$templatedbs->moveNext();
		}
		echo "\t\t\t</select>\n";
		echo "\t\t</td>\n\t</tr>\n";

		// ENCODING
		echo "\t<tr>\n\t\t<th class=\"data left required\">{$lang['strencoding']}</th>\n";
		echo "\t\t<td class=\"data1\">\n";
		echo "\t\t\t<select name=\"formEncoding\">\n";
		echo "\t\t\t\t<option value=\"\"></option>\n";
		while (list ($key) = each ($data->codemap)) {
		    echo "\t\t\t\t<option value=\"", htmlspecialchars($key), "\"",
				($key == $_POST['formEncoding']) ? ' selected="selected"' : '', ">",
				$misc->printVal($key), "</option>\n";
		}
		echo "\t\t\t</select>\n";
		echo "\t\t</td>\n\t</tr>\n";

		if ($data->hasDatabaseCollation()) {
			if (!isset($_POST['formCollate'])) $_POST['formCollate'] = '';
			if (!isset($_POST['formCType'])) $_POST['formCType'] = '';

			// LC_COLLATE
			echo "\t<tr>\n\t\t<th class=\"data left\">{$lang['strcollation']}</th>\n";
			echo "\t\t<td class=\"data1\">\n";
			echo "\t\t\t<input name=\"formCollate\" value=\"", htmlspecialchars($_POST['formCollate']), "\" />\n";
			echo "\t\t</td>\n\t</tr>\n";

			// LC_CTYPE
			echo "\t<tr>\n\t\t<th class=\"data left\">{$lang['strctype']}</th>\n";
			echo "\t\t<td class=\"data1\">\n";
			echo "\t\t\t<input name=\"formCType\" value=\"", htmlspecialchars($_POST['formCType']), "\" />\n";
			echo "\t\t</td>\n\t</tr>\n";
		}

		// Tablespace (if there are any)
		if ($data->hasTablespaces() && $tablespaces->recordCount() > 0) {
			echo "\t<tr>\n\t\t<th class=\"data left\">{$lang['strtablespace']}</th>\n";
			echo "\t\t<td class=\"data1\">\n\t\t\t<select name=\"formSpc\">\n";
			// Always offer the default (empty) option
			echo "\t\t\t\t<option value=\"\"",
				($_POST['formSpc'] == '') ? ' selected="selected"' : '', "></option>\n";
			// Display all other tablespaces
			while (!$tablespaces->EOF) {
				$spcname = htmlspecialchars($tablespaces->fields['spcname']);
				echo "\t\t\t\t<option value=\"{$spcname}\"",
					($spcname == $_POST['formSpc']) ? ' selected="selected"' : '', ">{$spcname}</option>\n";
				$tablespaces->moveNext();
			}
			echo "\t\t\t</select>\n\t\t</td>\n\t</tr>\n";
		}

		// Comments (if available)
		if ($data->hasSharedComments()) {
			echo "\t<tr>\n\t\t<th class=\"data left\">{$lang['strcomment']}</th>\n";
			echo "\t\t<td><textarea name=\"formComment\" rows=\"3\" cols=\"32\">",
				htmlspecialchars($_POST['formComment']), "</textarea></td>\n\t</tr>\n";
		}

		echo "</table>\n";
		echo "<p><input type=\"hidden\" name=\"action\" value=\"save_create\" />\n";
		echo $misc->form;
		echo "<input type=\"submit\" value=\"{$lang['strcreate']}\" />\n";
		echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
		echo "</form>\n";
	}

	/**
	 * Actually creates the new view in the database
	 */
	function doSaveCreate() {
		global $data, $lang, $_reload_browser;

		// Default tablespace to null if it isn't set
		if (!isset($_POST['formSpc'])) $_POST['formSpc'] = null;

		// Default comment to blank if it isn't set
		if (!isset($_POST['formComment'])) $_POST['formComment'] = null;

		// Default collate to blank if it isn't set
		if (!isset($_POST['formCollate'])) $_POST['formCollate'] = null;

		// Default ctype to blank if it isn't set
		if (!isset($_POST['formCType'])) $_POST['formCType'] = null;

		// Check that they've given a name and a definition
		if ($_POST['formName'] == '') doCreate($lang['strdatabaseneedsname']);
		else {
			$status = $data->createDatabase($_POST['formName'], $_POST['formEncoding'], $_POST['formSpc'],
				$_POST['formComment'], $_POST['formTemplate'], $_POST['formCollate'], $_POST['formCType']);
			if ($status == 0) {
				$_reload_browser = true;
				doDefault($lang['strdatabasecreated']);
			}
			else
				doCreate($lang['strdatabasecreatedbad']);
		}
	}

	/**
	 * Displays options for cluster download
	 */
	function doExport($msg = '') {
		global $data, $misc;
		global $lang;

		$misc->printTrail('server');
		$misc->printTabs('server','export');
		$misc->printMsg($msg);

		echo "<form action=\"dbexport.php\" method=\"post\">\n";
		echo "<table>\n";
		echo "<tr><th class=\"data\">{$lang['strformat']}</th><th class=\"data\">{$lang['stroptions']}</th></tr>\n";
		// Data only
		echo "<tr><th class=\"data left\" rowspan=\"". ($data->hasServerOids() ? 2 : 1) ."\">";
		echo "<input type=\"radio\" id=\"what1\" name=\"what\" value=\"dataonly\" checked=\"checked\" /><label for=\"what1\">{$lang['strdataonly']}</label></th>\n";
		echo "<td>{$lang['strformat']}\n";
		echo "<select name=\"d_format\">\n";
		echo "<option value=\"copy\">COPY</option>\n";
		echo "<option value=\"sql\">SQL</option>\n";
		echo "</select>\n</td>\n</tr>\n";
		if ($data->hasServerOids()) {
			echo "<tr><td><input type=\"checkbox\" id=\"d_oids\" name=\"d_oids\" /><label for=\"d_oids\">{$lang['stroids']}</label></td>\n</tr>\n";
		}
		// Structure only
		echo "<tr><th class=\"data left\"><input type=\"radio\" id=\"what2\" name=\"what\" value=\"structureonly\" /><label for=\"what2\">{$lang['strstructureonly']}</label></th>\n";
		echo "<td><input type=\"checkbox\" id=\"s_clean\" name=\"s_clean\" /><label for=\"s_clean\">{$lang['strdrop']}</label></td>\n</tr>\n";
		// Structure and data
		echo "<tr><th class=\"data left\" rowspan=\"". ($data->hasServerOids() ? 3 : 2) ."\">";
		echo "<input type=\"radio\" id=\"what3\" name=\"what\" value=\"structureanddata\" /><label for=\"what3\">{$lang['strstructureanddata']}</label></th>\n";
		echo "<td>{$lang['strformat']}\n";
		echo "<select name=\"sd_format\">\n";
		echo "<option value=\"copy\">COPY</option>\n";
		echo "<option value=\"sql\">SQL</option>\n";
		echo "</select>\n</td>\n</tr>\n";
		echo "<tr><td><input type=\"checkbox\" id=\"sd_clean\" name=\"sd_clean\" /><label for=\"sd_clean\">{$lang['strdrop']}</label></td>\n</tr>\n";
		if ($data->hasServerOids()) {
			echo "<tr><td><input type=\"checkbox\" id=\"sd_oids\" name=\"sd_oids\" /><label for=\"sd_oids\">{$lang['stroids']}</label></td>\n</tr>\n";
		}
		echo "</table>\n";

		echo "<h3>{$lang['stroptions']}</h3>\n";
		echo "<p><input type=\"radio\" id=\"output1\" name=\"output\" value=\"show\" checked=\"checked\" /><label for=\"output1\">{$lang['strshow']}</label>\n";
		echo "<br/><input type=\"radio\" id=\"output2\" name=\"output\" value=\"download\" /><label for=\"output2\">{$lang['strdownload']}</label></p>\n";

		echo "<p><input type=\"hidden\" name=\"action\" value=\"export\" />\n";
		echo "<input type=\"hidden\" name=\"subject\" value=\"server\" />\n";
		echo $misc->form;
		echo "<input type=\"submit\" value=\"{$lang['strexport']}\" /></p>\n";
		echo "</form>\n";
	}

	/**
	 * Show default list of databases in the server
	 */
	function doDefault($msg = '') {
		global $data, $conf, $misc;
		global $lang;

		$misc->printTrail('server');
		$misc->printTabs('server','databases');
		$misc->printMsg($msg);

		$databases = $data->getDatabases();

		$columns = array(
			'database' => array(
				'title' => $lang['strdatabase'],
				'field' => field('datname'),
				'url'   => "redirect.php?subject=database&amp;{$misc->href}&amp;",
				'vars'  => array('database' => 'datname'),
			),
			'owner' => array(
				'title' => $lang['strowner'],
				'field' => field('datowner'),
			),
			'encoding' => array(
				'title' => $lang['strencoding'],
				'field' => field('datencoding'),
			),
			'lc_collate' => array(
				'title' => $lang['strcollation'],
				'field' => field('datcollate'),
			),
			'lc_ctype' => array(
				'title' => $lang['strctype'],
				'field' => field('datctype'),
			),
			'tablespace' => array(
				'title' => $lang['strtablespace'],
				'field' => field('tablespace'),
			),
			'dbsize' => array(
				'title' => $lang['strsize'],
				'field' => field('dbsize'),
				'type' => 'prettysize',
			),
			'actions' => array(
				'title' => $lang['stractions'],
			),
			'comment' => array(
				'title' => $lang['strcomment'],
				'field' => field('datcomment'),
			),
		);

		$actions = array(
			'multiactions' => array(
			    'keycols' => array('database' => 'datname'),
			    'url' => 'all_db.php',
			    'default' => null,
			),
			'drop' => array(
			    'content' => $lang['strdrop'],
			    'attr'=> array (
				'href' => array (
				    'url' => 'all_db.php',
				    'urlvars' => array (
					'subject' => 'database',
					'action' => 'confirm_drop',
					'dropdatabase' => field('datname')
				    )
				)
			    ),
			    'multiaction' => 'confirm_drop',
			),
			'privileges' => array(
			    'content' => $lang['strprivileges'],
			    'attr'=> array (
				'href' => array (
				    'url' => 'privileges.php',
				    'urlvars' => array (
					'subject' => 'database',
					'database' => field('datname')
				    )
				)
			    )
			)
		);
		if ($data->hasAlterDatabase() ) {
		    $actions['alter'] = array(
			'content' => $lang['stralter'],
			'attr'=> array (
			    'href' => array (
				'url' => 'all_db.php',
				'urlvars' => array (
				    'subject' => 'database',
				    'action' => 'confirm_alter',
				    'alterdatabase' => field('datname')
				)
			    )
			)
		    );
		}

		if (!$data->hasTablespaces()) unset($columns['tablespace']);
		if (!$data->hasServerAdminFuncs()) unset($columns['dbsize']);
		if (!$data->hasDatabaseCollation()) unset($columns['lc_collate'], $columns['lc_ctype']);
		if (!isset($data->privlist['database'])) unset($actions['privileges']);

		$misc->printTable($databases, $columns, $actions, 'all_db-databases', $lang['strnodatabases']);

		$navlinks = array (
		    'create' => array (
			'attr'=> array (
			    'href' => array (
				'url' => 'all_db.php',
				'urlvars' => array (
				    'action' => 'create',
				    'server' => $_REQUEST['server']
				)
			    )
			),
			'content' => $lang['strcreatedatabase']
		    )
		);
		$misc->printNavLinks($navlinks, 'all_db-databases', get_defined_vars());
	}

	function doTree() {
		global $misc, $data, $lang;

		$databases = $data->getDatabases();

		$reqvars = $misc->getRequestVars('database');

		$attrs = array(
			'text'   => field('datname'),
			'icon'   => 'Database',
			'toolTip'=> field('datcomment'),
			'action' => url('redirect.php',
							$reqvars,
							array('database' => field('datname'))
						),
			'branch' => url('database.php',
							$reqvars,
							array(
								'action' => 'tree',
								'database' => field('datname')
							)
						),
		);

		$misc->printTree($databases, $attrs, 'databases');
		exit;
	}

	if ($action == 'tree') doTree();

	$misc->printHeader($lang['strdatabases']);
	$misc->printBody();

	switch ($action) {
		case 'export':
			doExport();
			break;
		case 'save_create':
			if (isset($_POST['cancel'])) doDefault();
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
		case 'alter':
			if (isset($_POST['oldname']) && isset($_POST['newname']) && !isset($_POST['cancel']) ) doAlter(false);
			else doDefault();
			break;
		case 'confirm_alter':
			doAlter(true);
			break;
		default:
			doDefault();
			break;
	}

	$misc->printFooter();

?>
