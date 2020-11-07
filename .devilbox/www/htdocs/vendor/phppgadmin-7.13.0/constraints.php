<?php

	/**
	 * List constraints on a table
	 *
	 * $Id: constraints.php,v 1.56 2007/12/31 16:46:07 xzilla Exp $
	 */

	// Include application functions
	include_once('./libraries/lib.inc.php');
	include_once('./classes/class.select.php');

	$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';

	/**
	 * Confirm and then actually add a FOREIGN KEY constraint
	 */
	function addForeignKey($stage, $msg = '') {
		global $data, $misc;
		global $lang;

		if (!isset($_POST['name'])) $_POST['name'] = '';
		if (!isset($_POST['target'])) $_POST['target'] = '';

		switch ($stage) {
			case 2:
				// Check that they've given at least one source column
				if (!isset($_REQUEST['SourceColumnList']) && (!isset($_POST['IndexColumnList']) || !is_array($_POST['IndexColumnList']) || sizeof($_POST['IndexColumnList']) == 0))
					addForeignKey(1, $lang['strfkneedscols']);
				else {
 					// Copy the IndexColumnList variable from stage 1
					if (isset($_REQUEST['IndexColumnList']) && !isset($_REQUEST['SourceColumnList']))
						$_REQUEST['SourceColumnList'] = serialize($_REQUEST['IndexColumnList']);

					// Initialise variables
					if (!isset($_POST['upd_action'])) $_POST['upd_action'] = null;
					if (!isset($_POST['del_action'])) $_POST['del_action'] = null;
					if (!isset($_POST['match'])) $_POST['match'] = null;
					if (!isset($_POST['deferrable'])) $_POST['deferrable'] = null;
					if (!isset($_POST['initially'])) $_POST['initially'] = null;
					$_REQUEST['target'] = unserialize($_REQUEST['target']);

					$misc->printTrail('table');
					$misc->printTitle($lang['straddfk'],'pg.constraint.foreign_key');
					$misc->printMsg($msg);

					// Unserialize target and fetch appropriate table. This is a bit messy
					// because the table could be in another schema.
					$data->setSchema($_REQUEST['target']['schemaname']);
					$attrs = $data->getTableAttributes($_REQUEST['target']['tablename']);
					$data->setSchema($_REQUEST['schema']);

					$selColumns = new XHTML_select('TableColumnList', true, 10);
					$selColumns->set_style('width: 15em;');

					if ($attrs->recordCount() > 0) {
						while (!$attrs->EOF) {
							$XHTML_Option = new XHTML_Option($attrs->fields['attname']);
							$selColumns->add($XHTML_Option);
							$attrs->moveNext();
						}
					}

					$selIndex = new XHTML_select('IndexColumnList[]', true, 10);
					$selIndex->set_style('width: 15em;');
					$selIndex->set_attribute('id', 'IndexColumnList');
					$buttonAdd = new XHTML_Button('add', '>>');
					$buttonAdd->set_attribute('onclick', 'buttonPressed(this);');
					$buttonAdd->set_attribute('type', 'button');

					$buttonRemove = new XHTML_Button('remove', '<<');
					$buttonRemove->set_attribute('onclick', 'buttonPressed(this);');
					$buttonRemove->set_attribute('type', 'button');

					echo "<form onsubmit=\"doSelectAll();\" name=\"formIndex\" action=\"constraints.php\" method=\"post\">\n";

					echo "<table>\n";
					echo "<tr><th class=\"data\" colspan=\"3\">{$lang['strfktarget']}</th></tr>";
					echo "<tr><th class=\"data\">{$lang['strtablecolumnlist']}</th><th class=\"data\">&nbsp;</th><th class=data>{$lang['strfkcolumnlist']}</th></tr>\n";
					echo "<tr><td class=\"data1\">" . $selColumns->fetch() . "</td>\n";
					echo "<td class=\"data1\" style=\"text-align: center\">" . $buttonRemove->fetch() . $buttonAdd->fetch() . "</td>";
					echo "<td class=\"data1\">" . $selIndex->fetch() . "</td></tr>\n";
					echo "<tr><th class=\"data\" colspan=\"3\">{$lang['stractions']}</th></tr>";
					echo "<tr>";
					echo "<td class=\"data1\" colspan=\"3\">\n";
					// ON SELECT actions
					echo "{$lang['stronupdate']} <select name=\"upd_action\">";
					foreach ($data->fkactions as $v)
						echo "<option value=\"{$v}\"", ($_POST['upd_action'] == $v) ? ' selected="selected"' : '', ">{$v}</option>\n";
					echo "</select><br />\n";

					// ON DELETE actions
					echo "{$lang['strondelete']} <select name=\"del_action\">";
					foreach ($data->fkactions as $v)
						echo "<option value=\"{$v}\"", ($_POST['del_action'] == $v) ? ' selected="selected"' : '', ">{$v}</option>\n";
					echo "</select><br />\n";

					// MATCH options
					echo "<select name=\"match\">";
					foreach ($data->fkmatches as $v)
						echo "<option value=\"{$v}\"", ($_POST['match'] == $v) ? ' selected="selected"' : '', ">{$v}</option>\n";
					echo "</select><br />\n";

					// DEFERRABLE options
					echo "<select name=\"deferrable\">";
					foreach ($data->fkdeferrable as $v)
						echo "<option value=\"{$v}\"", ($_POST['deferrable'] == $v) ? ' selected="selected"' : '', ">{$v}</option>\n";
					echo "</select><br />\n";

					// INITIALLY options
					echo "<select name=\"initially\">";
					foreach ($data->fkinitial as $v)
						echo "<option value=\"{$v}\"", ($_POST['initially'] == $v) ? ' selected="selected"' : '', ">{$v}</option>\n";
					echo "</select>\n";
					echo "</td></tr>\n";
					echo "</table>\n";

					echo "<p><input type=\"hidden\" name=\"action\" value=\"save_add_foreign_key\" />\n";
					echo $misc->form;
					echo "<input type=\"hidden\" name=\"table\" value=\"", htmlspecialchars($_REQUEST['table']), "\" />\n";
					echo "<input type=\"hidden\" name=\"name\" value=\"", htmlspecialchars($_REQUEST['name']), "\" />\n";
					echo "<input type=\"hidden\" name=\"target\" value=\"", htmlspecialchars(serialize($_REQUEST['target'])), "\" />\n";
					echo "<input type=\"hidden\" name=\"SourceColumnList\" value=\"", htmlspecialchars($_REQUEST['SourceColumnList']), "\" />\n";
					echo "<input type=\"hidden\" name=\"stage\" value=\"3\" />\n";
					echo "<input type=\"submit\" value=\"{$lang['stradd']}\" />\n";
					echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
					echo "</form>\n";
				}
				break;
			case 3:
				// Unserialize target
				$_POST['target'] = unserialize($_POST['target']);

				// Check that they've given at least one column
				if (isset($_POST['SourceColumnList'])) $temp = unserialize($_POST['SourceColumnList']);
				if (!isset($_POST['IndexColumnList']) || !is_array($_POST['IndexColumnList'])
						|| sizeof($_POST['IndexColumnList']) == 0 || !isset($temp)
						|| !is_array($temp) || sizeof($temp) == 0) addForeignKey(2, $lang['strfkneedscols']);
				else {
					$status = $data->addForeignKey($_POST['table'], $_POST['target']['schemaname'], $_POST['target']['tablename'],
						unserialize($_POST['SourceColumnList']), $_POST['IndexColumnList'], $_POST['upd_action'], $_POST['del_action'],
						$_POST['match'], $_POST['deferrable'], $_POST['initially'], $_POST['name']);
					if ($status == 0)
						doDefault($lang['strfkadded']);
					else
						addForeignKey(2, $lang['strfkaddedbad']);
				}
				break;
			default:
				$misc->printTrail('table');
				$misc->printTitle($lang['straddfk'],'pg.constraint.foreign_key');
				$misc->printMsg($msg);

				$attrs = $data->getTableAttributes($_REQUEST['table']);
				$tables = $data->getTables(true);

				$selColumns = new XHTML_select('TableColumnList', true, 10);
				$selColumns->set_style('width: 15em;');

				if ($attrs->recordCount() > 0) {
					while (!$attrs->EOF) {
						$XHTML_Option = new XHTML_Option($attrs->fields['attname']);
						$selColumns->add($XHTML_Option);
						$attrs->moveNext();
					}
				}

				$selIndex = new XHTML_select('IndexColumnList[]', true, 10);
				$selIndex->set_style('width: 15em;');
				$selIndex->set_attribute('id', 'IndexColumnList');
				$buttonAdd = new XHTML_Button('add', '>>');
				$buttonAdd->set_attribute('onclick', 'buttonPressed(this);');
				$buttonAdd->set_attribute('type', 'button');

				$buttonRemove = new XHTML_Button('remove', '<<');
				$buttonRemove->set_attribute('onclick', 'buttonPressed(this);');
				$buttonRemove->set_attribute('type', 'button');

				echo "<form onsubmit=\"doSelectAll();\" name=\"formIndex\" action=\"constraints.php\" method=\"post\">\n";

				echo "<table>\n";
				echo "<tr><th class=\"data\" colspan=\"3\">{$lang['strname']}</th></tr>\n";
				echo "<tr><td class=\"data1\" colspan=\"3\"><input type=\"text\" name=\"name\" size=\"32\" maxlength=\"{$data->_maxNameLen}\" /></td></tr>\n";
				echo "<tr><th class=\"data\">{$lang['strtablecolumnlist']}</th><th class=\"data\">&nbsp;</th><th class=\"data required\">{$lang['strfkcolumnlist']}</th></tr>\n";
				echo "<tr><td class=\"data1\">" . $selColumns->fetch() . "</td>\n";
				echo "<td class=\"data1\" style=\"text-align: center\">" . $buttonRemove->fetch() . $buttonAdd->fetch() . "</td>\n";
				echo "<td class=data1>" . $selIndex->fetch() . "</td></tr>\n";
				echo "<tr><th class=\"data\" colspan=\"3\">{$lang['strfktarget']}</th></tr>";
				echo "<tr>";
				echo "<td class=\"data1\" colspan=\"3\"><select name=\"target\">";
				while (!$tables->EOF) {
					$key = array('schemaname' => $tables->fields['nspname'], 'tablename' => $tables->fields['relname']);
					$key = serialize($key);
					echo "<option value=\"", htmlspecialchars($key), "\">";
					if ($tables->fields['nspname'] != $_REQUEST['schema']) {
							echo htmlspecialchars($tables->fields['nspname']), '.';
					}
					echo htmlspecialchars($tables->fields['relname']), "</option>\n";
					$tables->moveNext();
				}
				echo "</select>\n";
				echo "</td></tr>";
				echo "</table>\n";

				echo "<p><input type=\"hidden\" name=\"action\" value=\"save_add_foreign_key\" />\n";
				echo $misc->form;
				echo "<input type=\"hidden\" name=\"table\" value=\"", htmlspecialchars($_REQUEST['table']), "\" />\n";
				echo "<input type=\"hidden\" name=\"stage\" value=\"2\" />\n";
				echo "<input type=\"submit\" value=\"{$lang['stradd']}\" />\n";
				echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
				echo "</form>\n";
				break;
		}

	}

	/**
	 * Confirm and then actually add a PRIMARY KEY or UNIQUE constraint
	 */
	function addPrimaryOrUniqueKey($type, $confirm, $msg = '') {
		global $data, $misc;
		global $lang;

		if (!isset($_POST['name'])) $_POST['name'] = '';

		if ($confirm) {
			if (!isset($_POST['name'])) $_POST['name'] = '';
			if (!isset($_POST['tablespace'])) $_POST['tablespace'] = '';

			$misc->printTrail('table');

			switch ($type) {
				case 'primary':
					$misc->printTitle($lang['straddpk'],'pg.constraint.primary_key');
					break;
				case 'unique':
					$misc->printTitle($lang['stradduniq'],'pg.constraint.unique_key');
					break;
				default:
					doDefault($lang['strinvalidparam']);
					return;
			}

			$misc->printMsg($msg);

			$attrs = $data->getTableAttributes($_REQUEST['table']);
			// Fetch all tablespaces from the database
			if ($data->hasTablespaces()) $tablespaces = $data->getTablespaces();


			$selColumns = new XHTML_select('TableColumnList', true, 10);
			$selColumns->set_style('width: 15em;');

			if ($attrs->recordCount() > 0) {
				while (!$attrs->EOF) {
					$XHTML_Option = new XHTML_Option($attrs->fields['attname']);
					$selColumns->add($XHTML_Option);
					$attrs->moveNext();
				}
			}

			$selIndex = new XHTML_select('IndexColumnList[]', true, 10);
			$selIndex->set_style('width: 15em;');
			$selIndex->set_attribute('id', 'IndexColumnList');
			$buttonAdd = new XHTML_Button('add', '>>');
			$buttonAdd->set_attribute('onclick', 'buttonPressed(this);');
			$buttonAdd->set_attribute('type', 'button');

			$buttonRemove = new XHTML_Button('remove', '<<');
			$buttonRemove->set_attribute('onclick', 'buttonPressed(this);');
			$buttonRemove->set_attribute('type', 'button');

			echo "<form onsubmit=\"doSelectAll();\" name=\"formIndex\" action=\"constraints.php\" method=\"post\">\n";

			echo "<table>\n";
			echo "<tr><th class=\"data\" colspan=\"3\">{$lang['strname']}</th></tr>";
			echo "<tr>";
			echo "<td class=\"data1\" colspan=\"3\"><input type=\"text\" name=\"name\" value=\"", htmlspecialchars($_POST['name']),
				"\" size=\"32\" maxlength=\"{$data->_maxNameLen}\" /></td></tr>";
			echo "<tr><th class=\"data\">{$lang['strtablecolumnlist']}</th><th class=\"data\">&nbsp;</th><th class=\"data required\">{$lang['strindexcolumnlist']}</th></tr>\n";
			echo "<tr><td class=\"data1\">" . $selColumns->fetch() . "</td>\n";
			echo "<td class=\"data1\" style=\"text-align: center\">" . $buttonRemove->fetch() . $buttonAdd->fetch() . "</td>";
			echo "<td class=data1>" . $selIndex->fetch() . "</td></tr>\n";

			// Tablespace (if there are any)
			if ($data->hasTablespaces() && $tablespaces->recordCount() > 0) {
				echo "<tr><th class=\"data\" colspan=\"3\">{$lang['strtablespace']}</th></tr>";
				echo "<tr><td class=\"data1\" colspan=\"3\"><select name=\"tablespace\">\n";
				// Always offer the default (empty) option
				echo "\t\t\t\t<option value=\"\"",
					($_POST['tablespace'] == '') ? ' selected="selected"' : '', "></option>\n";
				// Display all other tablespaces
				while (!$tablespaces->EOF) {
					$spcname = htmlspecialchars($tablespaces->fields['spcname']);
					echo "\t\t\t\t<option value=\"{$spcname}\"",
						($spcname == $_POST['tablespace']) ? ' selected="selected"' : '', ">{$spcname}</option>\n";
					$tablespaces->moveNext();
				}
				echo "</select></td></tr>\n";
			}

			echo "</table>\n";

			echo "<p><input type=\"hidden\" name=\"action\" value=\"save_add_primary_key\" />\n";
			echo $misc->form;
			echo "<input type=\"hidden\" name=\"table\" value=\"", htmlspecialchars($_REQUEST['table']), "\" />\n";
			echo "<input type=\"hidden\" name=\"type\" value=\"", htmlspecialchars($type), "\" />\n";
			echo "<input type=\"submit\" value=\"{$lang['stradd']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
			echo "</form>\n";
		}
		else {
			// Default tablespace to empty if it isn't set
			if (!isset($_POST['tablespace'])) $_POST['tablespace'] = '';

			if ($_POST['type'] == 'primary') {
				// Check that they've given at least one column
				if (!isset($_POST['IndexColumnList']) || !is_array($_POST['IndexColumnList'])
						|| sizeof($_POST['IndexColumnList']) == 0) addPrimaryOrUniqueKey($_POST['type'], true, $lang['strpkneedscols']);
				else {
					$status = $data->addPrimaryKey($_POST['table'], $_POST['IndexColumnList'], $_POST['name'], $_POST['tablespace']);
					if ($status == 0)
						doDefault($lang['strpkadded']);
					else
						addPrimaryOrUniqueKey($_POST['type'], true, $lang['strpkaddedbad']);
				}
			}
			elseif ($_POST['type'] == 'unique') {
				// Check that they've given at least one column
				if (!isset($_POST['IndexColumnList']) || !is_array($_POST['IndexColumnList'])
						|| sizeof($_POST['IndexColumnList']) == 0) addPrimaryOrUniqueKey($_POST['type'], true, $lang['struniqneedscols']);
				else {
					$status = $data->addUniqueKey($_POST['table'], $_POST['IndexColumnList'], $_POST['name'], $_POST['tablespace']);
					if ($status == 0)
						doDefault($lang['struniqadded']);
					else
						addPrimaryOrUniqueKey($_POST['type'], true, $lang['struniqaddedbad']);
				}
			}
			else doDefault($lang['strinvalidparam']);
		}
	}

	/**
	 * Confirm and then actually add a CHECK constraint
	 */
	function addCheck($confirm, $msg = '') {
		global $data, $misc;
		global $lang;

		if (!isset($_POST['name'])) $_POST['name'] = '';
		if (!isset($_POST['definition'])) $_POST['definition'] = '';

		if ($confirm) {
			$misc->printTrail('table');
			$misc->printTitle($lang['straddcheck'],'pg.constraint.check');
			$misc->printMsg($msg);

			echo "<form action=\"constraints.php\" method=\"post\">\n";
			echo "<table>\n";
			echo "<tr><th class=\"data\">{$lang['strname']}</th>\n";
			echo "<th class=\"data required\">{$lang['strdefinition']}</th></tr>\n";

			echo "<tr><td class=\"data1\"><input name=\"name\" size=\"16\" maxlength=\"{$data->_maxNameLen}\" value=\"",
				htmlspecialchars($_POST['name']), "\" /></td>\n";

			echo "<td class=\"data1\">(<input name=\"definition\" size=\"32\" value=\"",
				htmlspecialchars($_POST['definition']), "\" />)</td></tr>\n";
			echo "</table>\n";

			echo "<input type=\"hidden\" name=\"action\" value=\"save_add_check\" />\n";
			echo "<input type=\"hidden\" name=\"table\" value=\"", htmlspecialchars($_REQUEST['table']), "\" />\n";
			echo $misc->form;
			echo "<p><input type=\"submit\" name=\"ok\" value=\"{$lang['stradd']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
			echo "</form>\n";

		}
		else {
			if (trim($_POST['definition']) == '')
				addCheck(true, $lang['strcheckneedsdefinition']);
			else {
				$status = $data->addCheckConstraint($_POST['table'],
					$_POST['definition'], $_POST['name']);
				if ($status == 0)
					doDefault($lang['strcheckadded']);
				else
					addCheck(true, $lang['strcheckaddedbad']);
			}
		}
	}

	/**
	 * Show confirmation of drop and perform actual drop
	 */
	function doDrop($confirm) {
		global $data, $misc;
		global $lang;

		if ($confirm) {
			$misc->printTrail('constraint');
			$misc->printTitle($lang['strdrop'],'pg.constraint.drop');

			echo "<p>", sprintf($lang['strconfdropconstraint'], $misc->printVal($_REQUEST['constraint']),
				$misc->printVal($_REQUEST['table'])), "</p>\n";

			echo "<form action=\"constraints.php\" method=\"post\">\n";
			echo "<input type=\"hidden\" name=\"action\" value=\"drop\" />\n";
			echo "<input type=\"hidden\" name=\"table\" value=\"", htmlspecialchars($_REQUEST['table']), "\" />\n";
			echo "<input type=\"hidden\" name=\"constraint\" value=\"", htmlspecialchars($_REQUEST['constraint']), "\" />\n";
			echo "<input type=\"hidden\" name=\"type\" value=\"", htmlspecialchars($_REQUEST['type']), "\" />\n";
			echo $misc->form;
			echo "<p><input type=\"checkbox\" id=\"cascade\" name=\"cascade\" /> <label for=\"cascade\">{$lang['strcascade']}</label></p>\n";
			echo "<input type=\"submit\" name=\"drop\" value=\"{$lang['strdrop']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" />\n";
			echo "</form>\n";
		}
		else {
			$status = $data->dropConstraint($_POST['constraint'], $_POST['table'], $_POST['type'], isset($_POST['cascade']));
			if ($status == 0)
				doDefault($lang['strconstraintdropped']);
			else
				doDefault($lang['strconstraintdroppedbad']);
		}
	}

	/**
	 * List all the constraints on the table
	 */
	function doDefault($msg = '') {
		global $data, $misc, $lang;

		function cnPre(&$rowdata) {
			global $data;
			if (is_null($rowdata->fields['consrc'])) {
				$atts = $data->getAttributeNames($_REQUEST['table'], explode(' ', $rowdata->fields['indkey']));
				$rowdata->fields['+definition'] = ($rowdata->fields['contype'] == 'u' ? "UNIQUE (" : "PRIMARY KEY (") . join(',', $atts) . ')';
			} else {
				$rowdata->fields['+definition'] = $rowdata->fields['consrc'];
			}
		}

		$misc->printTrail('table');
		$misc->printTabs('table','constraints');
		$misc->printMsg($msg);

		$constraints = $data->getConstraints($_REQUEST['table']);

		$columns = array(
			'constraint' => array(
				'title' => $lang['strname'],
				'field' => field('conname'),
			),
			'definition' => array(
				'title' => $lang['strdefinition'],
				'field' => field('+definition'),
				'type'  => 'pre',
			),
			'actions' => array(
				'title' => $lang['stractions'],
			),
			'comment' => array(
				'title' => $lang['strcomment'],
				'field' => field('constcomment'),
			),
		);

		$actions = array(
			'drop' => array(
				'content' => $lang['strdrop'],
				'attr'=> array (
					'href' => array (
						'url' => 'constraints.php',
						'urlvars' => array (
							'action' => 'confirm_drop',
							'table' => $_REQUEST['table'],
							'constraint' => field('conname'),
							'type' => field('contype')
						)
					)
				)
			)
		);

		$misc->printTable($constraints, $columns, $actions, 'constraints-constraints', $lang['strnoconstraints'], 'cnPre');

		$navlinks = array (
			'addcheck' => array (
				'attr'=> array (
					'href' => array (
						'url' => 'constraints.php',
						'urlvars' => array (
							'action' => 'add_check',
							'server' => $_REQUEST['server'],
							'database' => $_REQUEST['database'],
							'schema' => $_REQUEST['schema'],
							'table' => $_REQUEST['table']
						)
					)
				),
				'content' => $lang['straddcheck'],
			),
			'adduniq' => array (
				'attr'=> array (
					'href' => array (
						'url' => 'constraints.php',
						'urlvars' => array (
							'action' => 'add_unique_key',
							'server' => $_REQUEST['server'],
							'database' => $_REQUEST['database'],
							'schema' => $_REQUEST['schema'],
							'table' => $_REQUEST['table']
						)
					)
				),
				'content' => $lang['stradduniq'],
			),
			'addpk' => array (
				'attr'=> array (
					'href' => array (
						'url' => 'constraints.php',
						'urlvars' => array (
							'action' => 'add_primary_key',
							'server' => $_REQUEST['server'],
							'database' => $_REQUEST['database'],
							'schema' => $_REQUEST['schema'],
							'table' => $_REQUEST['table']
						)
					)
				),
				'content' => $lang['straddpk'],
			),
			'addfk' => array (
				'attr'=> array (
					'href' => array (
						'url' => 'constraints.php',
						'urlvars' => array (
							'action' => 'add_foreign_key',
							'server' => $_REQUEST['server'],
							'database' => $_REQUEST['database'],
							'schema' => $_REQUEST['schema'],
							'table' => $_REQUEST['table']
						)
					)
				),
				'content' => $lang['straddfk']
			)
		);
		$misc->printNavLinks($navlinks, 'constraints-constraints', get_defined_vars());
	}

	function doTree() {
		global $misc, $data;

		$constraints = $data->getConstraints($_REQUEST['table']);

		$reqvars = $misc->getRequestVars('schema');

		function getIcon($f) {
			switch($f['contype']) {
				case 'u':
					return 'UniqueConstraint';
				case 'c':
					return 'CheckConstraint';
				case 'f':
					return 'ForeignKey';
				case 'p':
					return 'PrimaryKey';

			}
		}

		$attrs = array(
			'text'   => field('conname'),
			'icon'   => callback('getIcon'),
		);

		$misc->printTree($constraints, $attrs, 'constraints');
		exit;
	}

	if ($action == 'tree') doTree();

	$misc->printHeader($lang['strtables'] . ' - ' . $_REQUEST['table'] . ' - ' . $lang['strconstraints'],
		"<script src=\"indexes.js\" type=\"text/javascript\"></script>");

	if ($action == 'add_unique_key' || $action == 'save_add_unique_key'
			|| $action == 'add_primary_key' || $action == 'save_add_primary_key'
			|| $action == 'add_foreign_key' || $action == 'save_add_foreign_key')
		echo "<body onload=\"init();\">";
	else
		$misc->printBody();

	switch ($action) {
		case 'add_foreign_key':
			addForeignKey(1);
			break;
		case 'save_add_foreign_key':
			if (isset($_POST['cancel'])) doDefault();
			else addForeignKey($_REQUEST['stage']);
			break;
		case 'add_unique_key':
			addPrimaryOrUniqueKey('unique', true);
			break;
		case 'save_add_unique_key':
			if (isset($_POST['cancel'])) doDefault();
			else addPrimaryOrUniqueKey('unique', false);
			break;
		case 'add_primary_key':
			addPrimaryOrUniqueKey('primary', true);
			break;
		case 'save_add_primary_key':
			if (isset($_POST['cancel'])) doDefault();
			else addPrimaryOrUniqueKey('primary', false);
			break;
		case 'add_check':
			addCheck(true);
			break;
		case 'save_add_check':
			if (isset($_POST['cancel'])) doDefault();
			else addCheck(false);
			break;
		case 'save_create':
			doSaveCreate();
			break;
		case 'create':
			doCreate();
			break;
		case 'drop':
			if (isset($_POST['drop'])) doDrop(false);
			else doDefault();
			break;
		case 'confirm_drop':
			doDrop(true);
			break;
		default:
			doDefault();
			break;
	}

	$misc->printFooter();

?>
