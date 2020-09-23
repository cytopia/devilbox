<?php

	/**
	 * Manage schemas in a database
	 *
	 * $Id: schemas.php,v 1.22 2007/12/15 22:57:43 ioguix Exp $
	 */

	// Include application functions
	include_once('./libraries/lib.inc.php');

	$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';
	if (!isset($msg)) $msg = '';

	/**
	 * Show default list of schemas in the database
	 */
	function doDefault($msg = '') {
		global $data, $misc, $conf;
		global $lang;

		$misc->printTrail('database');
		$misc->printTabs('database','schemas');
		$misc->printMsg($msg);

		// Check that the DB actually supports schemas
		$schemas = $data->getSchemas();

		$columns = array(
			'schema' => array(
				'title' => $lang['strschema'],
				'field' => field('nspname'),
				'url'   => "redirect.php?subject=schema&amp;{$misc->href}&amp;",
				'vars'  => array('schema' => 'nspname'),
			),
			'owner' => array(
				'title' => $lang['strowner'],
				'field' => field('nspowner'),
			),
			'actions' => array(
				'title' => $lang['stractions'],
			),
			'comment' => array(
				'title' => $lang['strcomment'],
				'field' => field('nspcomment'),
			),
		);

		$actions = array(
			'multiactions' => array(
				'keycols' => array('nsp' => 'nspname'),
				'url' => 'schemas.php',
			),
			'drop' => array(
				'content' => $lang['strdrop'],
				'attr'=> array (
					'href' => array (
						'url' => 'schemas.php',
						'urlvars' => array (
							'action' => 'drop',
							'nsp' => field('nspname')
						)
					)
				),
				'multiaction' => 'drop',
			),
			'privileges' => array(
				'content' => $lang['strprivileges'],
				'attr'=> array (
					'href' => array (
						'url' => 'privileges.php',
						'urlvars' => array (
							'subject' => 'schema',
							'schema' => field('nspname')
						)
					)
				)
			),
			'alter' => array(
				'content' => $lang['stralter'],
				'attr'=> array (
					'href' => array (
						'url' => 'schemas.php',
						'urlvars' => array (
							'action' => 'alter',
							'schema' => field('nspname')
						)
					)
				)
			)
		);

		if (!$data->hasAlterSchema()) unset($actions['alter']);

		$misc->printTable($schemas, $columns, $actions, 'schemas-schemas', $lang['strnoschemas']);

		$misc->printNavLinks(array ('create' => array (
				'attr'=> array (
					'href' => array (
						'url' => 'schemas.php',
						'urlvars' => array (
							'action' => 'create',
							'server' => $_REQUEST['server'],
							'database' => $_REQUEST['database']
						)
					)
				),
				'content' => $lang['strcreateschema']
			)), 'schemas-schemas', get_defined_vars());
	}

	/**
	 * Displays a screen where they can enter a new schema
	 */
	function doCreate($msg = '') {
		global $data, $misc;
		global $lang;

		$server_info = $misc->getServerInfo();

		if (!isset($_POST['formName'])) $_POST['formName'] = '';
		if (!isset($_POST['formAuth'])) $_POST['formAuth'] = $server_info['username'];
		if (!isset($_POST['formSpc'])) $_POST['formSpc'] = '';
		if (!isset($_POST['formComment'])) $_POST['formComment'] = '';

		// Fetch all users from the database
		$users = $data->getUsers();

		$misc->printTrail('database');
		$misc->printTitle($lang['strcreateschema'],'pg.schema.create');
		$misc->printMsg($msg);

		echo "<form action=\"schemas.php\" method=\"post\">\n";
		echo "<table style=\"width: 100%\">\n";
		echo "\t<tr>\n\t\t<th class=\"data left required\">{$lang['strname']}</th>\n";
		echo "\t\t<td class=\"data1\"><input name=\"formName\" size=\"32\" maxlength=\"{$data->_maxNameLen}\" value=\"",
			htmlspecialchars($_POST['formName']), "\" /></td>\n\t</tr>\n";
		// Owner
		echo "\t<tr>\n\t\t<th class=\"data left required\">{$lang['strowner']}</th>\n";
		echo "\t\t<td class=\"data1\">\n\t\t\t<select name=\"formAuth\">\n";
		while (!$users->EOF) {
			$uname = htmlspecialchars($users->fields['usename']);
			echo "\t\t\t\t<option value=\"{$uname}\"",
				($uname == $_POST['formAuth']) ? ' selected="selected"' : '', ">{$uname}</option>\n";
			$users->moveNext();
		}
		echo "\t\t\t</select>\n\t\t</td>\n\t</tr>\n";
		echo "\t<tr>\n\t\t<th class=\"data left\">{$lang['strcomment']}</th>\n";
		echo "\t\t<td class=\"data1\"><textarea name=\"formComment\" rows=\"3\" cols=\"32\">",
			htmlspecialchars($_POST['formComment']), "</textarea></td>\n\t</tr>\n";

		echo "</table>\n";
		echo "<p>\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"create\" />\n";
		echo "<input type=\"hidden\" name=\"database\" value=\"", htmlspecialchars($_REQUEST['database']), "\" />\n";
		echo $misc->form;
		echo "<input type=\"submit\" name=\"create\" value=\"{$lang['strcreate']}\" />\n";
		echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" />\n";
		echo "</p>\n";
		echo "</form>\n";
	}

	/**
	 * Actually creates the new schema in the database
	 */
	function doSaveCreate() {
		global $data, $lang, $_reload_browser;

		// Check that they've given a name
		if ($_POST['formName'] == '') doCreate($lang['strschemaneedsname']);
		else {
			$status = $data->createSchema($_POST['formName'], $_POST['formAuth'], $_POST['formComment']);
			if ($status == 0) {
				$_reload_browser = true;
				doDefault($lang['strschemacreated']);
			}
			else
				doCreate($lang['strschemacreatedbad']);
		}
	}

	/**
	 * Display a form to permit editing schema properties.
	 * TODO: permit changing owner
	 */
	function doAlter($msg = '') {
		global $data, $misc, $lang;

		$misc->printTrail('schema');
		$misc->printTitle($lang['stralter'],'pg.schema.alter');
		$misc->printMsg($msg);

		$schema = $data->getSchemaByName($_REQUEST['schema']);
		if ($schema->recordCount() > 0) {
			if (!isset($_POST['comment'])) $_POST['comment'] = $schema->fields['nspcomment'];
			if (!isset($_POST['schema'])) $_POST['schema'] = $_REQUEST['schema'];
			if (!isset($_POST['name'])) $_POST['name'] = $_REQUEST['schema'];
			if (!isset($_POST['owner'])) $_POST['owner'] = $schema->fields['ownername'];

			echo "<form action=\"schemas.php\" method=\"post\">\n";
			echo "<table>\n";

			echo "\t<tr>\n";
			echo "\t\t<th class=\"data left required\">{$lang['strname']}</th>\n";
			echo "\t\t<td class=\"data1\">";
			echo "\t\t\t<input name=\"name\" size=\"32\" maxlength=\"{$data->_maxNameLen}\" value=\"",
				htmlspecialchars($_POST['name']), "\" />\n";
			echo "\t\t</td>\n";
			echo "\t</tr>\n";

			if ($data->hasAlterSchemaOwner()) {
				$users = $data->getUsers();
				echo "<tr><th class=\"data left required\">{$lang['strowner']}</th>\n";
					echo "<td class=\"data2\"><select name=\"owner\">";
					while (!$users->EOF) {
						$uname = $users->fields['usename'];
						echo "<option value=\"", htmlspecialchars($uname), "\"",
						($uname == $_POST['owner']) ? ' selected="selected"' : '', ">", htmlspecialchars($uname), "</option>\n";
						$users->moveNext();
					}
					echo "</select></td></tr>\n";
			}
			else 
				echo "<input name=\"owner\" value=\"{$_POST['owner']}\" type=\"hidden\" />";

			echo "\t<tr>\n";
			echo "\t\t<th class=\"data\">{$lang['strcomment']}</th>\n";
			echo "\t\t<td class=\"data1\"><textarea cols=\"32\" rows=\"3\" name=\"comment\">", htmlspecialchars($_POST['comment']), "</textarea></td>\n";
			echo "\t</tr>\n";
			echo "</table>\n";
			echo "<p><input type=\"hidden\" name=\"action\" value=\"alter\" />\n";
			echo "<input type=\"hidden\" name=\"schema\" value=\"", htmlspecialchars($_POST['schema']), "\" />\n";
			echo $misc->form;
			echo "<input type=\"submit\" name=\"alter\" value=\"{$lang['stralter']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
			echo "</form>\n";
		} else {
			echo "<p>{$lang['strnodata']}</p>\n";
		}
	}

	/**
	 * Save the form submission containing changes to a schema
	 */
	function doSaveAlter($msg = '') {
		global $data, $misc, $lang, $_reload_browser;

		$status = $data->updateSchema($_POST['schema'], $_POST['comment'], $_POST['name'], $_POST['owner']);
		if ($status == 0) {
			$_reload_browser = true;
			doDefault($lang['strschemaaltered']);
		}
		else
			doAlter($lang['strschemaalteredbad']);
	}

	/**
	 * Show confirmation of drop and perform actual drop
	 */
	function doDrop($confirm) {
		global $data, $misc;
		global $lang, $_reload_browser;

		if (empty($_REQUEST['nsp']) && empty($_REQUEST['ma'])) {
			doDefault($lang['strspecifyschematodrop']);
			exit();
		}

		if ($confirm) {
			$misc->printTrail('schema');
			$misc->printTitle($lang['strdrop'],'pg.schema.drop');

			echo "<form action=\"schemas.php\" method=\"post\">\n";
			//If multi drop
			if (isset($_REQUEST['ma'])) {
				foreach($_REQUEST['ma'] as $v) {
					$a = unserialize(htmlspecialchars_decode($v, ENT_QUOTES));
					echo '<p>', sprintf($lang['strconfdropschema'], $misc->printVal($a['nsp'])), "</p>\n";
					echo '<input type="hidden" name="nsp[]" value="', htmlspecialchars($a['nsp']), "\" />\n";
				}
			}
			else {
				echo "<p>", sprintf($lang['strconfdropschema'], $misc->printVal($_REQUEST['nsp'])), "</p>\n";
				echo "<input type=\"hidden\" name=\"nsp\" value=\"", htmlspecialchars($_REQUEST['nsp']), "\" />\n";
			}

			echo "<p><input type=\"checkbox\" id=\"cascade\" name=\"cascade\" /> <label for=\"cascade\">{$lang['strcascade']}</label></p>\n";
			echo "<p><input type=\"hidden\" name=\"action\" value=\"drop\" />\n";
			echo "<input type=\"hidden\" name=\"database\" value=\"", htmlspecialchars($_REQUEST['database']), "\" />\n";
			echo $misc->form;
			echo "<input type=\"submit\" name=\"drop\" value=\"{$lang['strdrop']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
			echo "</form>\n";
		}
		else {
			if (is_array($_POST['nsp'])) {
				$msg = '';
				$status = $data->beginTransaction();
				if ($status == 0) {
					foreach($_POST['nsp'] as $s) {
						$status = $data->dropSchema($s, isset($_POST['cascade']));
						if ($status == 0)
							$msg.= sprintf('%s: %s<br />', htmlentities($s, ENT_QUOTES, 'UTF-8'), $lang['strschemadropped']);
						else {
							$data->endTransaction();
							doDefault(sprintf('%s%s: %s<br />', $msg, htmlentities($s, ENT_QUOTES, 'UTF-8'), $lang['strschemadroppedbad']));
							return;
						}
					}
				}
				if($data->endTransaction() == 0) {
					// Everything went fine, back to the Default page....
					$_reload_browser = true;
					doDefault($msg);
				}
				else doDefault($lang['strschemadroppedbad']);
			}
			else{
				$status = $data->dropSchema($_POST['nsp'], isset($_POST['cascade']));
				if ($status == 0) {
					$_reload_browser = true;
					doDefault($lang['strschemadropped']);
				}
				else
					doDefault($lang['strschemadroppedbad']);
			}
		}
	}

	/**
	 * Displays options for database download
	 */
	function doExport($msg = '') {
		global $data, $misc;
		global $lang;

		$misc->printTrail('schema');
		$misc->printTabs('schema','export');
		$misc->printMsg($msg);

		echo "<form action=\"dbexport.php\" method=\"post\">\n";
		echo "<table>\n";
		echo "<tr><th class=\"data\">{$lang['strformat']}</th><th class=\"data\" colspan=\"2\">{$lang['stroptions']}</th></tr>\n";
		// Data only
		echo "<tr><th class=\"data left\" rowspan=\"". ($data->hasServerOids() ? 2 : 1) ."\">";
		echo "<input type=\"radio\" id=\"what1\" name=\"what\" value=\"dataonly\" checked=\"checked\" /><label for=\"what1\">{$lang['strdataonly']}</label></th>\n";
		echo "<td>{$lang['strformat']}</td>\n";
		echo "<td><select name=\"d_format\">\n";
		echo "<option value=\"copy\">COPY</option>\n";
		echo "<option value=\"sql\">SQL</option>\n";
		echo "</select>\n</td>\n</tr>\n";
		if ($data->hasServerOids()) {
			echo "<tr><td><label for=\"d_oids\">{$lang['stroids']}</label></td><td><input type=\"checkbox\" id=\"d_oids\" name=\"d_oids\" /></td>\n</tr>\n";
		}
		// Structure only
		echo "<tr><th class=\"data left\"><input type=\"radio\" id=\"what2\" name=\"what\" value=\"structureonly\" /><label for=\"what2\">{$lang['strstructureonly']}</label></th>\n";
		echo "<td><label for=\"s_clean\">{$lang['strdrop']}</label></td><td><input type=\"checkbox\" id=\"s_clean\" name=\"s_clean\" /></td>\n</tr>\n";
		// Structure and data
		echo "<tr><th class=\"data left\" rowspan=\"". ($data->hasServerOids() ? 3 : 2) ."\">";
		echo "<input type=\"radio\" id=\"what3\" name=\"what\" value=\"structureanddata\" /><label for=\"what3\">{$lang['strstructureanddata']}</label></th>\n";
		echo "<td>{$lang['strformat']}</td>\n";
		echo "<td><select name=\"sd_format\">\n";
		echo "<option value=\"copy\">COPY</option>\n";
		echo "<option value=\"sql\">SQL</option>\n";
		echo "</select>\n</td>\n</tr>\n";
		echo "<tr><td><label for=\"sd_clean\">{$lang['strdrop']}</label></td><td><input type=\"checkbox\" id=\"sd_clean\" name=\"sd_clean\" /></td>\n</tr>\n";
		if ($data->hasServerOids()) {
			echo "<tr><td><label for=\"sd_oids\">{$lang['stroids']}</label></td><td><input type=\"checkbox\" id=\"sd_oids\" name=\"sd_oids\" /></td>\n</tr>\n";
		}
		echo "</table>\n";

		echo "<h3>{$lang['stroptions']}</h3>\n";
		echo "<p><input type=\"radio\" id=\"output1\" name=\"output\" value=\"show\" checked=\"checked\" /><label for=\"output1\">{$lang['strshow']}</label>\n";
		echo "<br/><input type=\"radio\" id=\"output2\" name=\"output\" value=\"download\" /><label for=\"output2\">{$lang['strdownload']}</label>\n";
		// MSIE cannot download gzip in SSL mode - it's just broken
		if (!(strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE') && isset($_SERVER['HTTPS']))) {
			echo "<br /><input type=\"radio\" id=\"output3\" name=\"output\" value=\"gzipped\" /><label for=\"output3\">{$lang['strdownloadgzipped']}</label>\n";
		}
		echo "</p>\n";
		echo "<p><input type=\"hidden\" name=\"action\" value=\"export\" />\n";
		echo "<input type=\"hidden\" name=\"subject\" value=\"schema\" />\n";
        echo "<input type=\"hidden\" name=\"database\" value=\"", htmlspecialchars($_REQUEST['database']), "\" />\n";
        echo "<input type=\"hidden\" name=\"schema\" value=\"", htmlspecialchars($_REQUEST['schema']), "\" />\n";
		echo $misc->form;
		echo "<input type=\"submit\" value=\"{$lang['strexport']}\" /></p>\n";
		echo "</form>\n";
	}



	/**
	 * Generate XML for the browser tree.
	 */
	function doTree() {
		global $misc, $data, $lang;

		$schemas = $data->getSchemas();

		$reqvars = $misc->getRequestVars('schema');

		$attrs = array(
			'text'   => field('nspname'),
			'icon'   => 'Schema',
			'toolTip'=> field('nspcomment'),
			'action' => url('redirect.php',
							$reqvars,
							array(
								'subject' => 'schema',
								'schema'  => field('nspname')
							)
						),
			'branch' => url('schemas.php',
							$reqvars,
							array(
								'action'  => 'subtree',
								'schema'  => field('nspname')
							)
						),
		);

		$misc->printTree($schemas, $attrs, 'schemas');

		exit;
	}

	function doSubTree() {
		global $misc, $data, $lang;

		$tabs = $misc->getNavTabs('schema');

		$items = $misc->adjustTabsForTree($tabs);

		$reqvars = $misc->getRequestVars('schema');

		$attrs = array(
			'text'   => field('title'),
			'icon'   => field('icon'),
			'action' => url(field('url'),
							$reqvars,
							field('urlvars', array())
						),
			'branch' => url(field('url'),
							$reqvars,
							field('urlvars'),
							array('action' => 'tree')
						)
		);

		$misc->printTree($items, $attrs, 'schema');
		exit;
	}

	if ($action == 'tree') doTree();
	if ($action == 'subtree') doSubTree();

	$misc->printHeader($lang['strschemas']);
	$misc->printBody();

	if (isset($_POST['cancel'])) $action = '';

	switch ($action) {
		case 'create':
			if (isset($_POST['create'])) doSaveCreate();
			else doCreate();
			break;
		case 'alter':
			if (isset($_POST['alter'])) doSaveAlter();
			else doAlter();
			break;
		case 'drop':
			if (isset($_POST['drop'])) doDrop(false);
			else doDrop(true);
			break;
		case 'export':
			doExport();
			break;
		default:
			doDefault();
			break;
	}

	$misc->printFooter();

?>
