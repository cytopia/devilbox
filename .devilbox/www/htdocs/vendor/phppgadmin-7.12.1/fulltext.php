<?php

	/**
	 * Manage fulltext configurations, dictionaries and mappings
	 *
	 * $Id: fulltext.php,v 1.6 2008/03/17 21:35:48 ioguix Exp $
	 */

	// Include application functions
	include_once('./libraries/lib.inc.php');

	$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';
	if (!isset($msg)) $msg = '';

	function doDefault($msg = '') {
		global $data, $misc;
		global $lang;

		$misc->printTrail('schema');
		$misc->printTabs('schema','fulltext');
		$misc->printTabs('fulltext','ftsconfigs');
		$misc->printMsg($msg);

		$cfgs = $data->getFtsConfigurations(false);

		$columns = array(
			'configuration' => array(
				'title' => $lang['strftsconfig'],
				'field' => field('name'),
				'url'   => "fulltext.php?action=viewconfig&amp;{$misc->href}&amp;",
				'vars'	=> array('ftscfg' => 'name'),
			),
			'schema' => array(
				'title' => $lang['strschema'],
				'field' => field('schema'),
			),
			'actions' => array(
				'title' => $lang['stractions'],
			),
			'comment' => array(
				'title' => $lang['strcomment'],
				'field' => field('comment'),
			),
		);

		$actions = array(
			'drop' => array(
				'content' => $lang['strdrop'],
				'attr'=> array (
					'href' => array (
						'url' => 'fulltext.php',
						'urlvars' => array (
							'action' => 'dropconfig',
							'ftscfg' => field('name')
						)
					)
				)
			),
			'alter' => array(
				'content' => $lang['stralter'],
				'attr'=> array (
					'href' => array (
						'url' => 'fulltext.php',
						'urlvars' => array (
							'action' => 'alterconfig',
							'ftscfg' => field('name')
						)
					)
				)
			),
		);

		$misc->printTable($cfgs, $columns, $actions, 'fulltext-fulltext', $lang['strftsnoconfigs']);

		$navlinks = array(
			'createconf' => array (
				'attr'=> array (
					'href' => array (
						'url' => 'fulltext.php',
						'urlvars' => array (
							'action' => 'createconfig',
							'server' => $_REQUEST['server'],
							'database' => $_REQUEST['database'],
							'schema' => $_REQUEST['schema']
						)
					)
				),
				'content' => $lang['strftscreateconfig']
			)
		);
			
		$misc->printNavLinks($navlinks, 'fulltext-fulltext', get_defined_vars());
	}

	function doDropConfig($confirm) {
		global $data, $data, $misc;
		global $lang, $_reload_browser;

		if ($confirm) {
			$misc->printTrail('ftscfg');
			$misc->printTitle($lang['strdrop'], 'pg.ftscfg.drop');

			echo "<p>", sprintf($lang['strconfdropftsconfig'], $misc->printVal($_REQUEST['ftscfg'])), "</p>\n";

			echo "<form action=\"fulltext.php\" method=\"post\">\n";
			echo "<p><input type=\"checkbox\" id=\"cascade\" name=\"cascade\" /> <label for=\"cascade\">{$lang['strcascade']}</label></p>\n";
			echo "<p><input type=\"hidden\" name=\"action\" value=\"dropconfig\" />\n";
			echo "<input type=\"hidden\" name=\"database\" value=\"", htmlspecialchars($_REQUEST['database']), "\" />\n";
			echo "<input type=\"hidden\" name=\"ftscfg\" value=\"", htmlspecialchars($_REQUEST['ftscfg']), "\" />\n";
			echo $misc->form;
			echo "<input type=\"submit\" name=\"drop\" value=\"{$lang['strdrop']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
			echo "</form>\n";
		}
		else {
			$status = $data->dropFtsConfiguration($_POST['ftscfg'], isset($_POST['cascade']));
			if ($status == 0) {
				$_reload_browser = true;
				doDefault($lang['strftsconfigdropped']);
			}
			else
				doDefault($lang['strftsconfigdroppedbad']);
		}

	}

	function doDropDict($confirm) {
		global $data, $data, $misc;
		global $lang, $_reload_browser;

		if ($confirm) {
			$misc->printTrail('ftscfg'); // TODO: change to smth related to dictionary
			$misc->printTitle($lang['strdrop'], 'pg.ftsdict.drop');

			echo "<p>", sprintf($lang['strconfdropftsdict'], $misc->printVal($_REQUEST['ftsdict'])), "</p>\n";

			echo "<form action=\"fulltext.php\" method=\"post\">\n";
			echo "<p><input type=\"checkbox\" id=\"cascade\" name=\"cascade\" /> <label for=\"cascade\">{$lang['strcascade']}</label></p>\n";
			echo "<p><input type=\"hidden\" name=\"action\" value=\"dropdict\" />\n";
			echo "<input type=\"hidden\" name=\"database\" value=\"", htmlspecialchars($_REQUEST['database']), "\" />\n";
			echo "<input type=\"hidden\" name=\"ftsdict\" value=\"", htmlspecialchars($_REQUEST['ftsdict']), "\" />\n";
			//echo "<input type=\"hidden\" name=\"ftscfg\" value=\"", htmlspecialchars($_REQUEST['ftscfg']), "\" />\n";
			echo "<input type=\"hidden\" name=\"prev_action\" value=\"viewdicts\" /></p>\n";
			echo $misc->form;
			echo "<input type=\"submit\" name=\"drop\" value=\"{$lang['strdrop']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
			echo "</form>\n";
		}
		else {
			$status = $data->dropFtsDictionary($_POST['ftsdict'], isset($_POST['cascade']));
			if ($status == 0) {
				$_reload_browser = true;
				doViewDicts($lang['strftsdictdropped']);
			}
			else
				doViewDicts($lang['strftsdictdroppedbad']);
		}

	}

	/**
	 * Displays a screen where one can enter a new FTS configuration
	 */
	function doCreateConfig($msg = '') {
		global $data, $misc;
		global $lang;

		include_once('./classes/Gui.php');

		$server_info = $misc->getServerInfo();

		if (!isset($_POST['formName'])) $_POST['formName'] = '';
		if (!isset($_POST['formParser'])) $_POST['formParser'] = '';
		if (!isset($_POST['formTemplate'])) $_POST['formTemplate'] = '';
		if (!isset($_POST['formWithMap'])) $_POST['formWithMap'] = '';
		if (!isset($_POST['formComment'])) $_POST['formComment'] = '';

		// Fetch all FTS configurations from the database
		$ftscfgs = $data->getFtsConfigurations();
		// Fetch all FTS parsers from the database
		$ftsparsers = $data->getFtsParsers();

		$misc->printTrail('schema');
		$misc->printTitle($lang['strftscreateconfig'], 'pg.ftscfg.create');
		$misc->printMsg($msg);

		echo "<form action=\"fulltext.php\" method=\"post\">\n";
		echo "<table>\n";
		/* conf name */
		echo "\t<tr>\n\t\t<th class=\"data left required\">{$lang['strname']}</th>\n";
		echo "\t\t<td class=\"data1\"><input name=\"formName\" size=\"32\" maxlength=\"{$data->_maxNameLen}\" value=\"",
			htmlspecialchars($_POST['formName']), "\" /></td>\n\t</tr>\n";

		// Template
		echo "\t<tr>\n\t\t<th class=\"data left\">{$lang['strftstemplate']}</th>\n";
		echo "\t\t<td class=\"data1\">";

		$tpls = array();
		$tplsel = '';
		while (!$ftscfgs->EOF) {
			$data->fieldClean($ftscfgs->fields['schema']);
			$data->fieldClean($ftscfgs->fields['name']);
			$tplname = $ftscfgs->fields['schema'] .'.'. $ftscfgs->fields['name'];
			$tpls[$tplname] = serialize(array(
				'name' => $ftscfgs->fields['name'],
				'schema' => $ftscfgs->fields['schema']
			));
			if ($_POST['formTemplate'] == $tpls[$tplname]) {
				$tplsel = htmlspecialchars($tpls[$tplname]);
			}
			$ftscfgs->moveNext();
		}
		echo GUI::printCombo($tpls, 'formTemplate', true, $tplsel, false);
		echo "\n\t\t</td>\n\t</tr>\n";

		// Parser
		echo "\t<tr>\n\t\t<th class=\"data left\">{$lang['strftsparser']}</th>\n";
		echo "\t\t<td class=\"data1\">\n";
		$ftsparsers_ = array();
		$ftsparsel = '';
		while (!$ftsparsers->EOF) {
			$data->fieldClean($ftsparsers->fields['schema']);
			$data->fieldClean($ftsparsers->fields['name']);
			$parsername = $ftsparsers->fields['schema'] .'.'. $ftsparsers->fields['name'];
			
			$ftsparsers_[$parsername] = serialize(array(
				'parser' => $ftsparsers->fields['name'],
				'schema' => $ftsparsers->fields['schema']
			));
			if ($_POST['formParser'] == $ftsparsers_[$parsername]) {
				$ftsparsel = htmlspecialchars($ftsparsers_[$parsername]);
			}
			$ftsparsers->moveNext();
		}
		echo GUI::printCombo($ftsparsers_, 'formParser', true, $ftsparsel, false);
		echo "\n\t\t</td>\n\t</tr>\n";

		// Comment
		echo "\t<tr>\n\t\t<th class=\"data left\">{$lang['strcomment']}</th>\n";
		echo "\t\t<td class=\"data1\"><textarea name=\"formComment\" rows=\"3\" cols=\"32\">",
			htmlspecialchars($_POST['formComment']), "</textarea></td>\n\t</tr>\n";

		echo "</table>\n";
		echo "<p>\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"createconfig\" />\n";
		echo "<input type=\"hidden\" name=\"database\" value=\"", htmlspecialchars($_REQUEST['database']), "\" />\n";
		echo $misc->form;
		echo "<input type=\"submit\" name=\"create\" value=\"{$lang['strcreate']}\" />\n";
		echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" />\n";
		echo "</p>\n";
		echo "</form>\n";
	}

	/**
	 * Actually creates the new FTS configuration in the database
	 */
	function doSaveCreateConfig() {
		global $data, $lang, $_reload_browser;

		$err = '';
		// Check that they've given a name
		if ($_POST['formName'] == '') $err .= "{$lang['strftsconfigneedsname']}<br />";		
		if (($_POST['formParser'] != '') && ($_POST['formTemplate'] != ''))  $err .= "{$lang['strftscantparsercopy']}<br />";

		if ($err != '') return doCreateConfig($err);

		if ($_POST['formParser'] != '') $formParser = unserialize($_POST['formParser']);
		else $formParser = '';
		if ($_POST['formTemplate'] != '') $formTemplate = unserialize($_POST['formTemplate']);
		else $formTemplate = '';

		$status = $data->createFtsConfiguration($_POST['formName'], $formParser, $formTemplate, $_POST['formComment']);
		if ($status == 0) {
			$_reload_browser = true;
			doDefault($lang['strftsconfigcreated']);
		}
		else
			doCreateConfig($lang['strftsconfigcreatedbad']);
	}

	/**
	 * Display a form to permit editing FTS configuration properties.
	 */
	function doAlterConfig($msg = '') {
		global $data, $misc, $lang;

		$misc->printTrail('ftscfg');
		$misc->printTitle($lang['stralter'], 'pg.ftscfg.alter');
		$misc->printMsg($msg);

		$ftscfg = $data->getFtsConfigurationByName($_REQUEST['ftscfg']);
		if ($ftscfg->recordCount() > 0) {
			if (!isset($_POST['formComment'])) $_POST['formComment'] = $ftscfg->fields['comment'];
			if (!isset($_POST['ftscfg'])) $_POST['ftscfg'] = $_REQUEST['ftscfg'];
			if (!isset($_POST['formName'])) $_POST['formName'] = $_REQUEST['ftscfg'];
			if (!isset($_POST['formParser'])) $_POST['formParser'] = '';

			// Fetch all FTS parsers from the database
			$ftsparsers = $data->getFtsParsers();

			echo "<form action=\"fulltext.php\" method=\"post\">\n";
			echo "<table>\n";

			echo "\t<tr>\n";
			echo "\t\t<th class=\"data left required\">{$lang['strname']}</th>\n";
			echo "\t\t<td class=\"data1\">";
			echo "\t\t\t<input name=\"formName\" size=\"32\" maxlength=\"{$data->_maxNameLen}\" value=\"",
				htmlspecialchars($_POST['formName']), "\" />\n";
			echo "\t\t</td>\n";
			echo "\t</tr>\n";

			// Comment
			echo "\t<tr>\n";
			echo "\t\t<th class=\"data\">{$lang['strcomment']}</th>\n";
			echo "\t\t<td class=\"data1\"><textarea cols=\"32\" rows=\"3\"name=\"formComment\">", htmlspecialchars($_POST['formComment']), "</textarea></td>\n";
			echo "\t</tr>\n";
			echo "</table>\n";
			echo "<p><input type=\"hidden\" name=\"action\" value=\"alterconfig\" />\n";
			echo "<input type=\"hidden\" name=\"ftscfg\" value=\"", htmlspecialchars($_POST['ftscfg']), "\" />\n";
			echo $misc->form;
			echo "<input type=\"submit\" name=\"alter\" value=\"{$lang['stralter']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
			echo "</form>\n";
		} else {
			echo "<p>{$lang['strnodata']}</p>\n";
		}
	}

	/**
	 * Save the form submission containing changes to a FTS configuration
	 */
	function doSaveAlterConfig() {
		global $data, $misc, $lang;

		$status = $data->updateFtsConfiguration($_POST['ftscfg'], $_POST['formComment'], $_POST['formName']);
		if ($status == 0)
			doDefault($lang['strftsconfigaltered']);
		else
			doAlterConfig($lang['strftsconfigalteredbad']);
	}

	/**
	 * View list of FTS parsers
	 */
	function doViewParsers($msg = '') {
		global $data, $misc;
		global $lang;

		$misc->printTrail('schema');
		$misc->printTabs('schema','fulltext');
		$misc->printTabs('fulltext','ftsparsers');
		$misc->printMsg($msg);

		$parsers = $data->getFtsParsers(false);

		$columns = array(
			'schema' => array(
				'title' => $lang['strschema'],
				'field' => field('schema'),
			),
			'name' => array(
				'title' => $lang['strname'],
				'field' => field('name'),
			),
			'comment' => array(
				'title' => $lang['strcomment'],
				'field' => field('comment'),
			),
		);

		$actions = array();

		$misc->printTable($parsers, $columns, $actions, 'fulltext-viewparsers', $lang['strftsnoparsers']);

		//TODO: navlink to "create parser"
	}


	/**
	 * View list of FTS dictionaries
	 */
	function doViewDicts($msg = '') {
		global $data, $misc;
		global $lang;

		$misc->printTrail('schema');
		$misc->printTabs('schema','fulltext');
		$misc->printTabs('fulltext','ftsdicts');
		$misc->printMsg($msg);

		$dicts = $data->getFtsDictionaries(false);

		$columns = array(
			'schema' => array(
				'title' => $lang['strschema'],
				'field' => field('schema'),
			),
			'name' => array(
				'title' => $lang['strname'],
				'field' => field('name'),
			),
			'actions' => array(
				'title' => $lang['stractions'],
			),
			'comment' => array(
				'title' => $lang['strcomment'],
				'field' => field('comment'),
			),
		);

		$actions = array(
			'drop' => array(
				'content' => $lang['strdrop'],
				'attr'=> array (
					'href' => array (
						'url' => 'fulltext.php',
						'urlvars' => array (
							'action' => 'dropdict',
							'ftsdict' => field('name')
						)
					)
				)
			),
			'alter' => array(
				'content' => $lang['stralter'],
				'attr'=> array (
					'href' => array (
						'url' => 'fulltext.php',
						'urlvars' => array (
							'action' => 'alterdict',
							'ftsdict' => field('name')
						)
					)
				)
			),
		);

		$misc->printTable($dicts, $columns, $actions, 'fulltext-viewdicts', $lang['strftsnodicts']);

		$navlinks = array(
			'createdict' => array (
				'attr'=> array (
					'href' => array (
						'url' => 'fulltext.php',
						'urlvars' => array (
							'action' => 'createdict',
							'server' => $_REQUEST['server'],
							'database' => $_REQUEST['database'],
							'schema' => $_REQUEST['schema'],
						)
					)
				),
				'content' => $lang['strftscreatedict']
			)
		);

		$misc->printNavLinks($navlinks, 'fulltext-viewdicts', get_defined_vars());
	}


	/**
	 * View details of FTS configuration given
	 */
	function doViewConfig($ftscfg, $msg = '') {
		global $data, $misc;
		global $lang;

		$misc->printTrail('ftscfg');
		$misc->printTabs('schema','fulltext');
		$misc->printTabs('fulltext','ftsconfigs');
		$misc->printMsg($msg);

		echo "<h3>{$lang['strftsconfigmap']}</h3>\n";

		$map = $data->getFtsConfigurationMap($ftscfg);

		$columns = array(
			'name' => array(
				'title' => $lang['strftsmapping'],
				'field' => field('name'),
			),
			'dictionaries' => array(
				'title' => $lang['strftsdicts'],
				'field' => field('dictionaries'),
			),
			'actions' => array(
				'title' => $lang['stractions'],
			),
			'comment' => array(
				'title' => $lang['strcomment'],
				'field' => field('description'),
			),
		);

		$actions = array(
			'drop' => array(
				'multiaction' => 'dropmapping',
				'content' => $lang['strdrop'],
				'attr'=> array (
					'href' => array (
						'url' => 'fulltext.php',
						'urlvars' => array (
							'action' => 'dropmapping',
							'mapping' => field('name'),
							'ftscfg' => field('cfgname')
						)
					)
				)
			),
			'alter' => array(
				'content' => $lang['stralter'],
				'attr'=> array (
					'href' => array (
						'url' => 'fulltext.php',
						'urlvars' => array (
							'action' => 'altermapping',
							'mapping' => field('name'),
							'ftscfg' => field('cfgname')
						)
					)
				)
			),
			'multiactions' => array(
				'keycols' => array('mapping' => 'name'),
				'url' => 'fulltext.php',
				'default' => null,
				'vars'  => array('ftscfg' => $ftscfg),
			),

		);

		$misc->printTable($map, $columns, $actions, 'fulltext-viewconfig', $lang['strftsemptymap']);

		$navlinks = array(
			'addmapping' => array (
				'attr'=> array (
					'href' => array (
						'url' => 'fulltext.php',
						'urlvars' => array (
							'action' => 'addmapping',
							'server' => $_REQUEST['server'],
							'database' => $_REQUEST['database'],
							'schema' => $_REQUEST['schema'],
							'ftscfg' => $ftscfg
						)
					)
				),
				'content' => $lang['strftsaddmapping']
			)
		);

		$misc->printNavLinks($navlinks, 'fulltext-viewconfig', get_defined_vars());
	}

	/**
	 * Displays a screen where one can enter a details of a new FTS dictionary
	 */
	function doCreateDict($msg = '') {
		global $data, $misc;
		global $lang;

		include_once('./classes/Gui.php');

		$server_info = $misc->getServerInfo();

		if (!isset($_POST['formName'])) $_POST['formName'] = '';
		if(!isset($_POST['formIsTemplate'])) $_POST['formIsTemplate'] = false;
		if (!isset($_POST['formTemplate'])) $_POST['formTemplate'] = '';
		if (!isset($_POST['formLexize'])) $_POST['formLexize'] = '';
		if (!isset($_POST['formInit'])) $_POST['formInit'] = '';
		if (!isset($_POST['formOption'])) $_POST['formOption'] = '';
		if (!isset($_POST['formComment'])) $_POST['formComment'] = '';

		// Fetch all FTS dictionaries from the database
		$ftstpls = $data->getFtsDictionaryTemplates();

		$misc->printTrail('schema');
		// TODO: create doc links
		$misc->printTitle($lang['strftscreatedict'], 'pg.ftsdict.create');
		$misc->printMsg($msg);

		echo "<form action=\"fulltext.php\" method=\"post\">\n";
		echo "<table>\n";
		echo "\t<tr>\n\t\t<th class=\"data left required\">{$lang['strname']}</th>\n";
		echo "\t\t<td class=\"data1\"><input name=\"formName\" size=\"32\" maxlength=\"{$data->_maxNameLen}\" value=\"",
			htmlspecialchars($_POST['formName']), "\" />&nbsp;",
			"<input type=\"checkbox\" name=\"formIsTemplate\" id=\"formIsTemplate\"", $_POST['formIsTemplate'] ? ' checked="checked" ' : '', " />\n",
			"<label for=\"formIsTemplate\">{$lang['strftscreatedicttemplate']}</label></td>\n\t</tr>\n";

		// Template
		echo "\t<tr>\n\t\t<th class=\"data left\">{$lang['strftstemplate']}</th>\n";
		echo "\t\t<td class=\"data1\">";
		$tpls = array();
		$tplsel = '';
		while (!$ftstpls->EOF) {
			$data->fieldClean($ftstpls->fields['schema']);
			$data->fieldClean($ftstpls->fields['name']);
			$tplname = $ftstpls->fields['schema'] .'.'. $ftstpls->fields['name'];
			$tpls[$tplname] = serialize(array(
				'name' => $ftstpls->fields['name'],
				'schema' => $ftstpls->fields['schema']
			));
			if ($_POST['formTemplate'] == $tpls[$tplname]) {
				$tplsel = htmlspecialchars($tpls[$tplname]);
			}
			$ftstpls->moveNext();
		}
		echo GUI::printCombo($tpls, 'formTemplate', true, $tplsel, false);
		echo "\n\t\t</td>\n\t</tr>\n";

		// TODO: what about maxlengths?
		// Lexize
		echo "\t<tr>\n\t\t<th class=\"data left\">{$lang['strftslexize']}</th>\n";
		echo "\t\t<td class=\"data1\"><input name=\"formLexize\" size=\"32\" maxlength=\"1000\" value=\"",
			htmlspecialchars($_POST['formLexize']), "\" ", isset($_POST['formIsTemplate']) ? '' : ' disabled="disabled" ',
			"/></td>\n\t</tr>\n";

		// Init
		echo "\t<tr>\n\t\t<th class=\"data left\">{$lang['strftsinit']}</th>\n";
		echo "\t\t<td class=\"data1\"><input name=\"formInit\" size=\"32\" maxlength=\"1000\" value=\"",
			htmlspecialchars($_POST['formInit']), "\"", @$_POST['formIsTemplate'] ? '' : ' disabled="disabled" ',
			"/></td>\n\t</tr>\n";

		// Option
		echo "\t<tr>\n\t\t<th class=\"data left\">{$lang['strftsoptionsvalues']}</th>\n";
		echo "\t\t<td class=\"data1\"><input name=\"formOption\" size=\"32\" maxlength=\"1000\" value=\"",
			htmlspecialchars($_POST['formOption']), "\" /></td>\n\t</tr>\n";

		// Comment
		echo "\t<tr>\n\t\t<th class=\"data left\">{$lang['strcomment']}</th>\n";
		echo "\t\t<td class=\"data1\"><textarea name=\"formComment\" rows=\"3\" cols=\"32\">",
			htmlspecialchars($_POST['formComment']), "</textarea></td>\n\t</tr>\n";

		echo "</table>\n";
		echo "<p>\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"createdict\" />\n";
		echo "<input type=\"hidden\" name=\"database\" value=\"", htmlspecialchars($_REQUEST['database']), "\" />\n";
		echo $misc->form;
		echo "<input type=\"submit\" name=\"create\" value=\"{$lang['strcreate']}\" />\n";
		echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" />\n";
		echo "</p>\n";
		echo "</form>\n",
			"<script type=\"text/javascript\">				
				function templateOpts() {
					isTpl = document.getElementsByName('formIsTemplate')[0].checked;
					document.getElementsByName('formTemplate')[0].disabled = isTpl;
					document.getElementsByName('formOption')[0].disabled = isTpl;
					document.getElementsByName('formLexize')[0].disabled = !isTpl;
					document.getElementsByName('formInit')[0].disabled = !isTpl;
				}
				
				document.getElementsByName('formIsTemplate')[0].onchange = templateOpts;

				templateOpts();
			</script>\n";
	}

	/**
	 * Actually creates the new FTS dictionary in the database
	 */
	function doSaveCreateDict() {
		global $data, $lang, $_reload_browser;

		// Check that they've given a name
		if ($_POST['formName'] == '') doCreateDict($lang['strftsdictneedsname']);
		else {

			if(!isset($_POST['formIsTemplate'])) $_POST['formIsTemplate'] = false;
			if(isset($_POST['formTemplate']))
				$formTemplate = unserialize($_POST['formTemplate']);
			else
				$formTemplate = '';
			if(!isset($_POST['formLexize'])) $_POST['formLexize'] = '';
			if(!isset($_POST['formInit'])) $_POST['formInit'] = '';
			if(!isset($_POST['formOption'])) $_POST['formOption'] = '';
			
			$status = $data->createFtsDictionary($_POST['formName'], $_POST['formIsTemplate'],
				$formTemplate, $_POST['formLexize'],
				$_POST['formInit'], $_POST['formOption'], $_POST['formComment']
			);
			
			if ($status == 0) {
				$_reload_browser = true;
				doViewDicts($lang['strftsdictcreated']);
			}
			else
				doCreateDict($lang['strftsdictcreatedbad']);
		}
	}

	/**
	 * Display a form to permit editing FTS dictionary properties.
	 */
	function doAlterDict($msg = '') {
		global $data, $misc, $lang;

		$misc->printTrail('ftscfg'); // TODO: change to smth related to dictionary
		$misc->printTitle($lang['stralter'], 'pg.ftsdict.alter');
		$misc->printMsg($msg);

		$ftsdict = $data->getFtsDictionaryByName($_REQUEST['ftsdict']);
		if ($ftsdict->recordCount() > 0) {
			if (!isset($_POST['formComment'])) $_POST['formComment'] = $ftsdict->fields['comment'];
			if (!isset($_POST['ftsdict'])) $_POST['ftsdict'] = $_REQUEST['ftsdict'];
			if (!isset($_POST['formName'])) $_POST['formName'] = $_REQUEST['ftsdict'];

			echo "<form action=\"fulltext.php\" method=\"post\">\n";
			echo "<table>\n";

			echo "\t<tr>\n";
			echo "\t\t<th class=\"data left required\">{$lang['strname']}</th>\n";
			echo "\t\t<td class=\"data1\">";
			echo "\t\t\t<input name=\"formName\" size=\"32\" maxlength=\"{$data->_maxNameLen}\" value=\"",
				htmlspecialchars($_POST['formName']), "\" />\n";
			echo "\t\t</td>\n";
			echo "\t</tr>\n";

			// Comment
			echo "\t<tr>\n";
			echo "\t\t<th class=\"data\">{$lang['strcomment']}</th>\n";
			echo "\t\t<td class=\"data1\"><textarea cols=\"32\" rows=\"3\"name=\"formComment\">", htmlspecialchars($_POST['formComment']), "</textarea></td>\n";
			echo "\t</tr>\n";
			echo "</table>\n";
			echo "<p><input type=\"hidden\" name=\"action\" value=\"alterdict\" />\n";
			echo "<input type=\"hidden\" name=\"ftsdict\" value=\"", htmlspecialchars($_POST['ftsdict']), "\" />\n";
			echo "<input type=\"hidden\" name=\"prev_action\" value=\"viewdicts\" /></p>\n";
			echo $misc->form;
			echo "<input type=\"submit\" name=\"alter\" value=\"{$lang['stralter']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
			echo "</form>\n";
		} else {
			echo "<p>{$lang['strnodata']}</p>\n";
		}
	}

	/**
	 * Save the form submission containing changes to a FTS dictionary
	 */
	function doSaveAlterDict() {
		global $data, $misc, $lang;

		$status = $data->updateFtsDictionary($_POST['ftsdict'], $_POST['formComment'], $_POST['formName']);
		if ($status == 0)
			doViewDicts($lang['strftsdictaltered']);
		else
			doAlterDict($lang['strftsdictalteredbad']);
	}

	/**
	 * Show confirmation of drop and perform actual drop of FTS mapping
	 */
	function doDropMapping($confirm) {
		global $data, $misc;
		global $lang, $_reload_drop_database;

		if (empty($_REQUEST['mapping']) && empty($_REQUEST['ma'])) {
			doDefault($lang['strftsspecifymappingtodrop']);
			return;
		}

		if (empty($_REQUEST['ftscfg'])) {
			doDefault($lang['strftsspecifyconfigtoalter']);
			return;
		}

		if ($confirm) {
			$misc->printTrail('ftscfg'); // TODO: proper breadcrumbs
			$misc->printTitle($lang['strdrop'], 'pg.ftscfg.alter');

			echo "<form action=\"fulltext.php\" method=\"post\">\n";

			// Case of multiaction drop
			if (isset($_REQUEST['ma'])) {

				foreach($_REQUEST['ma'] as $v) {
					$a = unserialize(htmlspecialchars_decode($v, ENT_QUOTES));
					echo "<p>", sprintf($lang['strconfdropftsmapping'], $misc->printVal($a['mapping']), $misc->printVal($_REQUEST['ftscfg'])), "</p>\n";
					printf('<input type="hidden" name="mapping[]" value="%s" />', htmlspecialchars($a['mapping']));
				}

			} else {
				echo "<p>", sprintf($lang['strconfdropftsmapping'], $misc->printVal($_REQUEST['mapping']), $misc->printVal($_REQUEST['ftscfg'])), "</p>\n";
				echo "<input type=\"hidden\" name=\"mapping\" value=\"", htmlspecialchars($_REQUEST['mapping']), "\" />\n";
			}

			echo "<input type=\"hidden\" name=\"ftscfg\" value=\"{$_REQUEST['ftscfg']}\" />\n";
			echo "<input type=\"hidden\" name=\"action\" value=\"dropmapping\" />\n";
            echo "<input type=\"hidden\" name=\"prev_action\" value=\"viewconfig\" /></p>\n";            
			echo $misc->form;
			echo "<input type=\"submit\" name=\"drop\" value=\"{$lang['strdrop']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" />\n";
			echo "</form>\n";
		} else {
			// Case of multiaction drop
			if (is_array($_REQUEST['mapping'])) {
				$status = $data->changeFtsMapping($_REQUEST['ftscfg'], $_REQUEST['mapping'], 'drop');
				if ($status != 0) {
					doViewConfig($_REQUEST['ftscfg'], $lang['strftsmappingdroppedbad']);
					return;
				}
				doViewConfig($_REQUEST['ftscfg'], $lang['strftsmappingdropped']);
			} else {
				$status = $data->changeFtsMapping($_REQUEST['ftscfg'], array($_REQUEST['mapping']), 'drop');
				if ($status == 0) {
					doViewConfig($_REQUEST['ftscfg'], $lang['strftsmappingdropped']);
				} else {
					doViewConfig($_REQUEST['ftscfg'], $lang['strftsmappingdroppedbad']);
				}
			}
		}
	}

	function doAlterMapping($msg = '') {
		global $data, $misc, $lang;

		$misc->printTrail('ftscfg');
		$misc->printTitle($lang['stralter'], 'pg.ftscfg.alter');
		$misc->printMsg($msg);

		$ftsdicts = $data->getFtsDictionaries();
		if ($ftsdicts->recordCount() > 0) {
			if (!isset($_POST['formMapping'])) $_POST['formMapping'] = @$_REQUEST['mapping'];
			if (!isset($_POST['formDictionary'])) $_POST['formDictionary'] = '';
			if (!isset($_POST['ftscfg'])) $_POST['ftscfg'] = $_REQUEST['ftscfg'];

			echo "<form action=\"fulltext.php\" method=\"post\">\n";

			echo "<table>\n";
			echo "\t<tr>\n";
			echo "\t\t<th class=\"data left required\">{$lang['strftsmapping']}</th>\n";
			echo "\t\t<td class=\"data1\">";

			// Case of multiaction drop
			if (isset($_REQUEST['ma'])) {
				$ma_mappings = array();
				$ma_mappings_names = array();
				foreach($_REQUEST['ma'] as $v) {
					$a = unserialize(htmlspecialchars_decode($v, ENT_QUOTES));
					printf('<input type="hidden" name="formMapping[]" value="%s" />', htmlspecialchars($a['mapping']));
					$ma_mappings[] = $data->getFtsMappingByName($_POST['ftscfg'], $a['mapping']);
					$ma_mappings_names[] = $a['mapping'];
				}
				echo implode(", ", $ma_mappings_names);
			} else {
				$mapping = $data->getFtsMappingByName($_POST['ftscfg'], $_POST['formMapping']);
				echo $mapping->fields['name'];
				echo "<input type=\"hidden\" name=\"formMapping\" value=\"", htmlspecialchars($_POST['formMapping']), "\" />\n";
			}

			echo "\t\t</td>\n";
			echo "\t</tr>\n";


			// Dictionary
			echo "\t<tr>\n";
			echo "\t\t<th class=\"data left required\">{$lang['strftsdict']}</th>\n";
			echo "\t\t<td class=\"data1\">";
			echo "\t\t\t<select name=\"formDictionary\">\n";
			while (!$ftsdicts->EOF) {
				$ftsdict = htmlspecialchars($ftsdicts->fields['name']);
				echo "\t\t\t\t<option value=\"{$ftsdict}\"",
					($ftsdict == $_POST['formDictionary'] || $ftsdict == @$mapping->fields['dictionaries'] || $ftsdict == @$ma_mappings[0]->fields['dictionaries']) ? ' selected="selected"' : '', ">{$ftsdict}</option>\n";
				$ftsdicts->moveNext();
			}

			echo "\t\t</td>\n";
			echo "\t</tr>\n";

			echo "</table>\n";
			echo "<p><input type=\"hidden\" name=\"action\" value=\"altermapping\" />\n";
			echo "<input type=\"hidden\" name=\"ftscfg\" value=\"", htmlspecialchars($_POST['ftscfg']), "\" />\n";
            echo "<input type=\"hidden\" name=\"prev_action\" value=\"viewconfig\" /></p>\n";
            
			echo $misc->form;
			echo "<input type=\"submit\" name=\"alter\" value=\"{$lang['stralter']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
			echo "</form>\n";
		} else {
			echo "<p>{$lang['strftsnodictionaries']}</p>\n";
		}
	}

	/**
	 * Save the form submission containing changes to a FTS mapping
	 */
	function doSaveAlterMapping() {
		global $data, $misc, $lang;

		$mappingArray = (is_array($_POST['formMapping']) ? $_POST['formMapping'] : array($_POST['formMapping']));
		$status = $data->changeFtsMapping($_POST['ftscfg'], $mappingArray, 'alter', $_POST['formDictionary']);
		if ($status == 0)
			doViewConfig($_POST['ftscfg'], $lang['strftsmappingaltered']);
		else
			doAlterMapping($lang['strftsmappingalteredbad']);
	}

	/**
	 * Show the form to enter parameters of a new FTS mapping
	 */
	function doAddMapping($msg = '') {
		global $data, $misc, $lang;

		$misc->printTrail('ftscfg');
		$misc->printTitle($lang['stralter'], 'pg.ftscfg.alter');
		$misc->printMsg($msg);

		$ftsdicts = $data->getFtsDictionaries();
		if ($ftsdicts->recordCount() > 0) {
			if (!isset($_POST['formMapping'])) $_POST['formMapping'] = '';
			if (!isset($_POST['formDictionary'])) $_POST['formDictionary'] = '';
			if (!isset($_POST['ftscfg'])) $_POST['ftscfg'] = $_REQUEST['ftscfg'];
			$mappings = $data->getFtsMappings($_POST['ftscfg']);

			echo "<form action=\"fulltext.php\" method=\"post\">\n";
			echo "<table>\n";
			echo "\t<tr>\n";
			echo "\t\t<th class=\"data left required\">{$lang['strftsmapping']}</th>\n";
			echo "\t\t<td class=\"data1\">";
			echo "\t\t\t<select name=\"formMapping\">\n";
			while (!$mappings->EOF) {
				$mapping = htmlspecialchars($mappings->fields['name']);
				$mapping_desc = htmlspecialchars($mappings->fields['description']);
				echo "\t\t\t\t<option value=\"{$mapping}\"",
					$mapping == $_POST['formMapping'] ? ' selected="selected"' : '', ">{$mapping}", $mapping_desc ? " - {$mapping_desc}" : "", "</option>\n";
				$mappings->moveNext();
			}
			echo "\t\t</td>\n";
			echo "\t</tr>\n";


			// Dictionary
			echo "\t<tr>\n";
			echo "\t\t<th class=\"data left required\">{$lang['strftsdict']}</th>\n";
			echo "\t\t<td class=\"data1\">";
			echo "\t\t\t<select name=\"formDictionary\">\n";
			while (!$ftsdicts->EOF) {
				$ftsdict = htmlspecialchars($ftsdicts->fields['name']);
				echo "\t\t\t\t<option value=\"{$ftsdict}\"",
					$ftsdict == $_POST['formDictionary'] ? ' selected="selected"' : '', ">{$ftsdict}</option>\n";
				$ftsdicts->moveNext();
			}

			echo "\t\t</td>\n";
			echo "\t</tr>\n";

			echo "</table>\n";
			echo "<p><input type=\"hidden\" name=\"action\" value=\"addmapping\" />\n";
			echo "<input type=\"hidden\" name=\"ftscfg\" value=\"", htmlspecialchars($_POST['ftscfg']), "\" />\n";
			echo "<input type=\"hidden\" name=\"prev_action\" value=\"viewconfig\" /></p>\n";
			echo $misc->form;
			echo "<input type=\"submit\" name=\"add\" value=\"{$lang['stradd']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
			echo "</form>\n";
		} else {
			echo "<p>{$lang['strftsnodictionaries']}</p>\n";
		}
	}

	/**
	 * Save the form submission containing parameters of a new FTS mapping
	 */
	function doSaveAddMapping() {
		global $data, $misc, $lang;

		$mappingArray = (is_array($_POST['formMapping']) ? $_POST['formMapping'] : array($_POST['formMapping']));
		$status = $data->changeFtsMapping($_POST['ftscfg'], $mappingArray, 'add', $_POST['formDictionary']);
		if ($status == 0)
			doViewConfig($_POST['ftscfg'], $lang['strftsmappingadded']);
		else
			doAddMapping($lang['strftsmappingaddedbad']);
	}

	/**
	 * Generate XML for the browser tree.
	 */
	function doTree() {
		global $misc, $data, $lang;

		$tabs = $misc->getNavTabs('fulltext');
		$items = $misc->adjustTabsForTree($tabs);

		$reqvars = $misc->getRequestVars('ftscfg');

		$attrs = array(
			'text'   => field('title'),
			'icon'   => field('icon'),
			'action' => url('fulltext.php',
				$reqvars,
				field('urlvars')
			),
			'branch' => url('fulltext.php',
				$reqvars,
				array(
					'action'  => 'subtree',
					'what'  => field('icon') // IZ: yeah, it's ugly, but I do not want to change navigation tabs arrays
				)
			),
		);

		$misc->printTree($items, $attrs, 'fts');

		exit;
	}

	function doSubTree($what) {
		global $misc, $data, $lang;

		switch($what) {
			case 'FtsCfg':
				$items = $data->getFtsConfigurations(false);
				$urlvars = array('action' => 'viewconfig', 'ftscfg' => field('name'));
				break;
			case 'FtsDict':
				$items = $data->getFtsDictionaries(false);
				$urlvars = array('action' => 'viewdicts');
				break;
			case 'FtsParser':
				$items = $data->getFtsParsers(false);
				$urlvars = array('action' => 'viewparsers');
				break;
			default:
				exit;
		}

		$reqvars = $misc->getRequestVars('ftscfg');

		$attrs = array(
			'text'   => field('name'),
			'icon'   => $what,
			'toolTip'=> field('comment'),
			'action' => url('fulltext.php',
				$reqvars,
				$urlvars
			),
			'branch' => ifempty(field('branch'),
				'',
				url('fulltext.php',
					$reqvars,
					array(
						'action'  => 'subtree',
						'ftscfg'  => field('name')
					)
				)
			),
		);

		$misc->printTree($items, $attrs, strtolower($what));
		exit;
	}


	if ($action == 'tree') doTree();
	if ($action == 'subtree') doSubTree($_REQUEST['what']);

	$misc->printHeader($lang['strschemas']);
	$misc->printBody();

	if (isset($_POST['cancel'])) {
		if (isset($_POST['prev_action'])) {
			$action = $_POST['prev_action'];
		} else {
			$action = '';
		}
	}

	switch ($action) {
		case 'createconfig':
			if (isset($_POST['create'])) doSaveCreateConfig();
			else doCreateConfig();
			break;
		case 'alterconfig':
			if (isset($_POST['alter'])) doSaveAlterConfig();
			else doAlterConfig();
			break;
		case 'dropconfig':
			if (isset($_POST['drop'])) doDropConfig(false);
			else doDropConfig(true);
			break;
		case 'viewconfig':
			doViewConfig($_REQUEST['ftscfg']);
			break;
		case 'viewparsers':
			doViewParsers();
			break;
		case 'viewdicts':
			doViewDicts();
			break;
		case 'createdict':
			if (isset($_POST['create'])) doSaveCreateDict();
			else doCreateDict();
			break;
		case 'alterdict':
			if (isset($_POST['alter'])) doSaveAlterDict();
			else doAlterDict();
			break;
		case 'dropdict':
			if (isset($_POST['drop'])) doDropDict(false);
			else doDropDict(true);
			break;
		case 'dropmapping':
			if (isset($_POST['drop'])) doDropMapping(false);
			else doDropMapping(true);
			break;
		case 'altermapping':
			if (isset($_POST['alter'])) doSaveAlterMapping();
			else doAlterMapping();
			break;
		case 'addmapping':
			if (isset($_POST['add'])) doSaveAddMapping();
			else doAddMapping();
			break;

		default:
			doDefault();
			break;
	}

	$misc->printFooter();

?>
