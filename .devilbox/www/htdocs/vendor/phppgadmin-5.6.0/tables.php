<?php

	/**
	 * List tables in a database
	 *
	 * $Id: tables.php,v 1.112 2008/06/16 22:38:46 ioguix Exp $
	 */

	// Include application functions
	include_once('./libraries/lib.inc.php');

	$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';

	/**
	 * Displays a screen where they can enter a new table
	 */
	function doCreate($msg = '') {
		global $data, $misc;
		global $lang;

		if (!isset($_REQUEST['stage'])) {
			$_REQUEST['stage'] = 1;
			$default_with_oids = $data->getDefaultWithOid();
			if ($default_with_oids == 'off') $_REQUEST['withoutoids'] = 'on';
		}

		if (!isset($_REQUEST['name'])) $_REQUEST['name'] = '';
		if (!isset($_REQUEST['fields'])) $_REQUEST['fields'] = '';
		if (!isset($_REQUEST['tblcomment'])) $_REQUEST['tblcomment'] = '';
		if (!isset($_REQUEST['spcname'])) $_REQUEST['spcname'] = '';

		switch ($_REQUEST['stage']) {
			case 1:
				// Fetch all tablespaces from the database
				if ($data->hasTablespaces()) $tablespaces = $data->getTablespaces();

				$misc->printTrail('schema');
				$misc->printTitle($lang['strcreatetable'], 'pg.table.create');
				$misc->printMsg($msg);

				echo "<form action=\"tables.php\" method=\"post\">\n";
				echo "<table>\n";
				echo "\t<tr>\n\t\t<th class=\"data left required\">{$lang['strname']}</th>\n";
				echo "\t\t<td class=\"data\"><input name=\"name\" size=\"32\" maxlength=\"{$data->_maxNameLen}\" value=\"",
					htmlspecialchars($_REQUEST['name']), "\" /></td>\n\t</tr>\n";
				echo "\t<tr>\n\t\t<th class=\"data left required\">{$lang['strnumcols']}</th>\n";
				echo "\t\t<td class=\"data\"><input name=\"fields\" size=\"5\" maxlength=\"{$data->_maxNameLen}\" value=\"",
					htmlspecialchars($_REQUEST['fields']), "\" /></td>\n\t</tr>\n";
				echo "\t<tr>\n\t\t<th class=\"data left\">{$lang['stroptions']}</th>\n";
				echo "\t\t<td class=\"data\"><label for=\"withoutoids\"><input type=\"checkbox\" id=\"withoutoids\" name=\"withoutoids\"", isset($_REQUEST['withoutoids']) ? ' checked="checked"' : '', " />WITHOUT OIDS</label></td>\n\t</tr>\n";

				// Tablespace (if there are any)
				if ($data->hasTablespaces() && $tablespaces->recordCount() > 0) {
					echo "\t<tr>\n\t\t<th class=\"data left\">{$lang['strtablespace']}</th>\n";
					echo "\t\t<td class=\"data1\">\n\t\t\t<select name=\"spcname\">\n";
					// Always offer the default (empty) option
					echo "\t\t\t\t<option value=\"\"",
						($_REQUEST['spcname'] == '') ? ' selected="selected"' : '', "></option>\n";
					// Display all other tablespaces
					while (!$tablespaces->EOF) {
						$spcname = htmlspecialchars($tablespaces->fields['spcname']);
						echo "\t\t\t\t<option value=\"{$spcname}\"",
							($tablespaces->fields['spcname'] == $_REQUEST['spcname']) ? ' selected="selected"' : '', ">{$spcname}</option>\n";
						$tablespaces->moveNext();
					}
					echo "\t\t\t</select>\n\t\t</td>\n\t</tr>\n";
				}

				echo "\t<tr>\n\t\t<th class=\"data left\">{$lang['strcomment']}</th>\n";
				echo "\t\t<td><textarea name=\"tblcomment\" rows=\"3\" cols=\"32\">",
					htmlspecialchars($_REQUEST['tblcomment']), "</textarea></td>\n\t</tr>\n";

				echo "</table>\n";
				echo "<p><input type=\"hidden\" name=\"action\" value=\"create\" />\n";
				echo "<input type=\"hidden\" name=\"stage\" value=\"2\" />\n";
				echo $misc->form;
				echo "<input type=\"submit\" value=\"{$lang['strnext']}\" />\n";
				echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
				echo "</form>\n";
				break;
			case 2:
				global $lang;

				// Check inputs
				$fields = trim($_REQUEST['fields']);
				if (trim($_REQUEST['name']) == '') {
					$_REQUEST['stage'] = 1;
					doCreate($lang['strtableneedsname']);
					return;
				}
				elseif ($fields == '' || !is_numeric($fields) || $fields != (int)$fields || $fields < 1)  {
					$_REQUEST['stage'] = 1;
					doCreate($lang['strtableneedscols']);
					return;
				}

				$types = $data->getTypes(true, false, true);
				$types_for_js = array();

				$misc->printTrail('schema');
				$misc->printTitle($lang['strcreatetable'], 'pg.table.create');
				$misc->printMsg($msg);

				echo "<script src=\"tables.js\" type=\"text/javascript\"></script>";
				echo "<form action=\"tables.php\" method=\"post\">\n";

				// Output table header
				echo "<table>\n";
				echo "\t<tr><th colspan=\"2\" class=\"data required\">{$lang['strcolumn']}</th><th colspan=\"2\" class=\"data required\">{$lang['strtype']}</th>";
				echo "<th class=\"data\">{$lang['strlength']}</th><th class=\"data\">{$lang['strnotnull']}</th>";
				echo "<th class=\"data\">{$lang['struniquekey']}</th><th class=\"data\">{$lang['strprimarykey']}</th>";
				echo "<th class=\"data\">{$lang['strdefault']}</th><th class=\"data\">{$lang['strcomment']}</th></tr>\n";

				for ($i = 0; $i < $_REQUEST['fields']; $i++) {
					if (!isset($_REQUEST['field'][$i])) $_REQUEST['field'][$i] = '';
					if (!isset($_REQUEST['length'][$i])) $_REQUEST['length'][$i] = '';
					if (!isset($_REQUEST['default'][$i])) $_REQUEST['default'][$i] = '';
					if (!isset($_REQUEST['colcomment'][$i])) $_REQUEST['colcomment'][$i] = '';

					echo "\t<tr>\n\t\t<td>", $i + 1, ".&nbsp;</td>\n";
					echo "\t\t<td><input name=\"field[{$i}]\" size=\"16\" maxlength=\"{$data->_maxNameLen}\" value=\"",
						htmlspecialchars($_REQUEST['field'][$i]), "\" /></td>\n";
					echo "\t\t<td>\n\t\t\t<select name=\"type[{$i}]\" id=\"types{$i}\" onchange=\"checkLengths(this.options[this.selectedIndex].value,{$i});\">\n";
					// Output any "magic" types
					foreach ($data->extraTypes as $v) {
						$types_for_js[strtolower($v)] = 1;
						echo "\t\t\t\t<option value=\"", htmlspecialchars($v), "\"",
						(isset($_REQUEST['type'][$i]) && $v == $_REQUEST['type'][$i]) ? ' selected="selected"' : '', ">",
							$misc->printVal($v), "</option>\n";
					}
					$types->moveFirst();
					while (!$types->EOF) {
						$typname = $types->fields['typname'];
						$types_for_js[$typname] = 1;
						echo "\t\t\t\t<option value=\"", htmlspecialchars($typname), "\"",
						(isset($_REQUEST['type'][$i]) && $typname == $_REQUEST['type'][$i]) ? ' selected="selected"' : '', ">",
							$misc->printVal($typname), "</option>\n";
						$types->moveNext();
					}
					echo "\t\t\t</select>\n\t\t\n";
					if($i==0) { // only define js types array once
						$predefined_size_types = array_intersect($data->predefined_size_types,array_keys($types_for_js));
						$escaped_predef_types = array(); // the JS escaped array elements
						foreach($predefined_size_types as $value) {
							$escaped_predef_types[] = "'{$value}'";
						}
						echo "<script type=\"text/javascript\">predefined_lengths = new Array(". implode(",",$escaped_predef_types) .");</script>\n\t</td>";
					}

					// Output array type selector
					echo "\t\t<td>\n\t\t\t<select name=\"array[{$i}]\">\n";
					echo "\t\t\t\t<option value=\"\"", (isset($_REQUEST['array'][$i]) && $_REQUEST['array'][$i] == '') ? ' selected="selected"' : '', "></option>\n";
					echo "\t\t\t\t<option value=\"[]\"", (isset($_REQUEST['array'][$i]) && $_REQUEST['array'][$i] == '[]') ? ' selected="selected"' : '', ">[ ]</option>\n";
					echo "\t\t\t</select>\n\t\t</td>\n";

					echo "\t\t<td><input name=\"length[{$i}]\" id=\"lengths{$i}\" size=\"10\" value=\"",
						htmlspecialchars($_REQUEST['length'][$i]), "\" /></td>\n";
					echo "\t\t<td><input type=\"checkbox\" name=\"notnull[{$i}]\"", (isset($_REQUEST['notnull'][$i])) ? ' checked="checked"' : '', " /></td>\n";
					echo "\t\t<td style=\"text-align: center\"><input type=\"checkbox\" name=\"uniquekey[{$i}]\""
						.(isset($_REQUEST['uniquekey'][$i]) ? ' checked="checked"' :'')." /></td>\n";
					echo "\t\t<td style=\"text-align: center\"><input type=\"checkbox\" name=\"primarykey[{$i}]\" "
						.(isset($_REQUEST['primarykey'][$i]) ? ' checked="checked"' : '')
						." /></td>\n";
					echo "\t\t<td><input name=\"default[{$i}]\" size=\"20\" value=\"",
						htmlspecialchars($_REQUEST['default'][$i]), "\" /></td>\n";
					echo "\t\t<td><input name=\"colcomment[{$i}]\" size=\"40\" value=\"",
						htmlspecialchars($_REQUEST['colcomment'][$i]), "\" />
						<script type=\"text/javascript\">checkLengths(document.getElementById('types{$i}').value,{$i});</script>
						</td>\n\t</tr>\n";
				}
				echo "</table>\n";
				echo "<p><input type=\"hidden\" name=\"action\" value=\"create\" />\n";
				echo "<input type=\"hidden\" name=\"stage\" value=\"3\" />\n";
				echo $misc->form;
				echo "<input type=\"hidden\" name=\"name\" value=\"", htmlspecialchars($_REQUEST['name']), "\" />\n";
				echo "<input type=\"hidden\" name=\"fields\" value=\"", htmlspecialchars($_REQUEST['fields']), "\" />\n";
				if (isset($_REQUEST['withoutoids'])) {
					echo "<input type=\"hidden\" name=\"withoutoids\" value=\"true\" />\n";
				}
				echo "<input type=\"hidden\" name=\"tblcomment\" value=\"", htmlspecialchars($_REQUEST['tblcomment']), "\" />\n";
				if (isset($_REQUEST['spcname'])) {
					echo "<input type=\"hidden\" name=\"spcname\" value=\"", htmlspecialchars($_REQUEST['spcname']), "\" />\n";
				}
				echo "<input type=\"submit\" value=\"{$lang['strcreate']}\" />\n";
				echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
				echo "</form>\n";

				break;
			case 3:
				global $data, $lang, $_reload_browser;

				if (!isset($_REQUEST['notnull'])) $_REQUEST['notnull'] = array();
				if (!isset($_REQUEST['uniquekey'])) $_REQUEST['uniquekey'] = array();
				if (!isset($_REQUEST['primarykey'])) $_REQUEST['primarykey'] = array();
				if (!isset($_REQUEST['length'])) $_REQUEST['length'] = array();
				// Default tablespace to null if it isn't set
				if (!isset($_REQUEST['spcname'])) $_REQUEST['spcname'] = null;

				// Check inputs
				$fields = trim($_REQUEST['fields']);
				if (trim($_REQUEST['name']) == '') {
					$_REQUEST['stage'] = 1;
					doCreate($lang['strtableneedsname']);
					return;
				}
				elseif ($fields == '' || !is_numeric($fields) || $fields != (int)$fields || $fields <= 0)  {
					$_REQUEST['stage'] = 1;
					doCreate($lang['strtableneedscols']);
					return;
				}

				$status = $data->createTable($_REQUEST['name'], $_REQUEST['fields'], $_REQUEST['field'],
								$_REQUEST['type'], $_REQUEST['array'], $_REQUEST['length'], $_REQUEST['notnull'], $_REQUEST['default'],
								isset($_REQUEST['withoutoids']), $_REQUEST['colcomment'], $_REQUEST['tblcomment'], $_REQUEST['spcname'],
								$_REQUEST['uniquekey'], $_REQUEST['primarykey']);

				if ($status == 0) {
					$_reload_browser = true;
					doDefault($lang['strtablecreated']);
				}
				elseif ($status == -1) {
					$_REQUEST['stage'] = 2;
					doCreate($lang['strtableneedsfield']);
					return;
				}
				else {
					$_REQUEST['stage'] = 2;
					doCreate($lang['strtablecreatedbad']);
					return;
				}
				break;
			default:
				echo "<p>{$lang['strinvalidparam']}</p>\n";
		}
	}

	/**
	 * Dsiplay a screen where user can create a table from an existing one.
	 * We don't have to check if pg supports schema cause create table like
	 * is available under pg 7.4+ which has schema.
	 */
	function doCreateLike($confirm, $msg = '') {
		global $data, $misc, $lang;

		if (!$confirm) {

			include_once('./classes/Gui.php');

			if (!isset($_REQUEST['name'])) $_REQUEST['name'] = '';
			if (!isset($_REQUEST['like'])) $_REQUEST['like'] = '';
			if (!isset($_REQUEST['tablespace'])) $_REQUEST['tablespace'] = '';

			$misc->printTrail('schema');
		    $misc->printTitle($lang['strcreatetable'], 'pg.table.create');
			$misc->printMsg($msg);

			$tbltmp = $data->getTables(true);
			$tbltmp = $tbltmp->getArray();

			$tables = array();
			$tblsel = '';
			foreach ($tbltmp as $a) {
				$data->fieldClean($a['nspname']);
				$data->fieldClean($a['relname']);
				$tables["\"{$a['nspname']}\".\"{$a['relname']}\""] = serialize(array('schema' => $a['nspname'], 'table' => $a['relname']));
				if ($_REQUEST['like'] == $tables["\"{$a['nspname']}\".\"{$a['relname']}\""]) 
					$tblsel = htmlspecialchars($tables["\"{$a['nspname']}\".\"{$a['relname']}\""]);
			}

			unset($tbltmp);

			echo "<form action=\"tables.php\" method=\"post\">\n";
			echo "<table>\n\t<tr>\n\t\t<th class=\"data left required\">{$lang['strname']}</th>\n";
			echo "\t\t<td class=\"data\"><input name=\"name\" size=\"32\" maxlength=\"{$data->_maxNameLen}\" value=\"", htmlspecialchars($_REQUEST['name']), "\" /></td>\n\t</tr>\n";
			echo "\t<tr>\n\t\t<th class=\"data left required\">{$lang['strcreatetablelikeparent']}</th>\n";
			echo "\t\t<td class=\"data\">";
			echo GUI::printCombo($tables, 'like', true, $tblsel, false);
			echo "</td>\n\t</tr>\n";
			if ($data->hasTablespaces()) {
				$tblsp_ = $data->getTablespaces();
				if ($tblsp_->recordCount() > 0) {
					$tblsp_ = $tblsp_->getArray();
					$tblsp = array();
					foreach($tblsp_ as $a) $tblsp[$a['spcname']] = $a['spcname'];

					echo "\t<tr>\n\t\t<th class=\"data left\">{$lang['strtablespace']}</th>\n";
					echo "\t\t<td class=\"data\">";
					echo GUI::printCombo($tblsp, 'tablespace', true, $_REQUEST['tablespace'], false);
					echo "</td>\n\t</tr>\n";
				}
			}
			echo "\t<tr>\n\t\t<th class=\"data left\">{$lang['stroptions']}</th>\n\t\t<td class=\"data\">";
			echo "<label for=\"withdefaults\"><input type=\"checkbox\" id=\"withdefaults\" name=\"withdefaults\"",
				isset($_REQUEST['withdefaults']) ? ' checked="checked"' : '',
				"/>{$lang['strcreatelikewithdefaults']}</label>";
			if ($data->hasCreateTableLikeWithConstraints()) {
				echo "<br /><label for=\"withconstraints\"><input type=\"checkbox\" id=\"withconstraints\" name=\"withconstraints\"",
					isset($_REQUEST['withconstraints']) ? ' checked="checked"' : '',
					"/>{$lang['strcreatelikewithconstraints']}</label>";
			}
			if ($data->hasCreateTableLikeWithIndexes()) {
				echo "<br /><label for=\"withindexes\"><input type=\"checkbox\" id=\"withindexes\" name=\"withindexes\"",
					isset($_REQUEST['withindexes']) ? ' checked="checked"' : '',
					"/>{$lang['strcreatelikewithindexes']}</label>";
			}
			echo "</td>\n\t</tr>\n";
			echo "</table>";

			echo "<input type=\"hidden\" name=\"action\" value=\"confcreatelike\" />\n";
			echo $misc->form;
			echo "<p><input type=\"submit\" value=\"{$lang['strcreate']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
			echo "</form>\n";
		}
		else {
			global $_reload_browser;

			if (trim($_REQUEST['name']) == '') {
				doCreateLike(false, $lang['strtableneedsname']);
				return;
			}
			if (trim($_REQUEST['like']) == '') {
				doCreateLike(false, $lang['strtablelikeneedslike']);
				return;
			}

			if (!isset($_REQUEST['tablespace'])) $_REQUEST['tablespace'] = '';

			$status = $data->createTableLike($_REQUEST['name'], unserialize($_REQUEST['like']), isset($_REQUEST['withdefaults']),
				isset($_REQUEST['withconstraints']), isset($_REQUEST['withindexes']), $_REQUEST['tablespace']);
				
			if ($status == 0) {
				$_reload_browser = true;
				doDefault($lang['strtablecreated']);
			}
			else {
				doCreateLike(false, $lang['strtablecreatedbad']);
				return;
			}
		}
	}

	/**
	 * Ask for select parameters and perform select
	 */
	function doSelectRows($confirm, $msg = '') {
		global $data, $misc, $_no_output;
		global $lang;

		if ($confirm) {
			$misc->printTrail('table');
			$misc->printTabs('table','select');
			$misc->printMsg($msg);

			$attrs = $data->getTableAttributes($_REQUEST['table']);

			echo "<form action=\"tables.php\" method=\"post\" id=\"selectform\">\n";
			if ($attrs->recordCount() > 0) {
				// JavaScript for select all feature
				echo "<script type=\"text/javascript\">\n";
				echo "//<![CDATA[\n";
				echo "	function selectAll() {\n";
				echo "		for (var i=0; i<document.getElementById('selectform').elements.length; i++) {\n";
				echo "			var e = document.getElementById('selectform').elements[i];\n";
				echo "			if (e.name.indexOf('show') == 0) e.checked = document.getElementById('selectform').selectall.checked;\n";
				echo "		}\n";
				echo "	}\n";
				echo "//]]>\n";
				echo "</script>\n";

				echo "<table>\n";

				// Output table header
				echo "<tr><th class=\"data\">{$lang['strshow']}</th><th class=\"data\">{$lang['strcolumn']}</th>";
				echo "<th class=\"data\">{$lang['strtype']}</th><th class=\"data\">{$lang['stroperator']}</th>";
				echo "<th class=\"data\">{$lang['strvalue']}</th></tr>";

				$i = 0;
				while (!$attrs->EOF) {
					$attrs->fields['attnotnull'] = $data->phpBool($attrs->fields['attnotnull']);
					// Set up default value if there isn't one already
					if (!isset($_REQUEST['values'][$attrs->fields['attname']]))
						$_REQUEST['values'][$attrs->fields['attname']] = null;
					if (!isset($_REQUEST['ops'][$attrs->fields['attname']]))
						$_REQUEST['ops'][$attrs->fields['attname']] = null;
					// Continue drawing row
					$id = (($i % 2) == 0 ? '1' : '2');
					echo "<tr class=\"data{$id}\">\n";
					echo "<td style=\"white-space:nowrap;\">";
					echo "<input type=\"checkbox\" name=\"show[", htmlspecialchars($attrs->fields['attname']), "]\"",
						isset($_REQUEST['show'][$attrs->fields['attname']]) ? ' checked="checked"' : '', " /></td>";
					echo "<td style=\"white-space:nowrap;\">", $misc->printVal($attrs->fields['attname']), "</td>";
					echo "<td style=\"white-space:nowrap;\">", $misc->printVal($data->formatType($attrs->fields['type'], $attrs->fields['atttypmod'])), "</td>";
					echo "<td style=\"white-space:nowrap;\">";
					echo "<select name=\"ops[{$attrs->fields['attname']}]\">\n";
					foreach (array_keys($data->selectOps) as $v) {
						echo "<option value=\"", htmlspecialchars($v), "\"", ($v == $_REQUEST['ops'][$attrs->fields['attname']]) ? ' selected="selected"' : '',
						">", htmlspecialchars($v), "</option>\n";
					}
					echo "</select>\n</td>\n";
					echo "<td style=\"white-space:nowrap;\">", $data->printField("values[{$attrs->fields['attname']}]",
						$_REQUEST['values'][$attrs->fields['attname']], $attrs->fields['type']), "</td>";
					echo "</tr>\n";
					$i++;
					$attrs->moveNext();
				}
				// Select all checkbox
				echo "<tr><td colspan=\"5\"><input type=\"checkbox\" id=\"selectall\" name=\"selectall\" accesskey=\"a\" onclick=\"javascript:selectAll()\" /><label for=\"selectall\">{$lang['strselectallfields']}</label></td>";
				echo "</tr></table>\n";
			}
			else echo "<p>{$lang['strinvalidparam']}</p>\n";

			echo "<p><input type=\"hidden\" name=\"action\" value=\"selectrows\" />\n";
			echo "<input type=\"hidden\" name=\"table\" value=\"", htmlspecialchars($_REQUEST['table']), "\" />\n";
			echo "<input type=\"hidden\" name=\"subject\" value=\"table\" />\n";
			echo $misc->form;
			echo "<input type=\"submit\" name=\"select\" accesskey=\"r\" value=\"{$lang['strselect']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
			echo "</form>\n";
		}
		else {
			if (!isset($_POST['show'])) $_POST['show'] = array();
			if (!isset($_POST['values'])) $_POST['values'] = array();
			if (!isset($_POST['nulls'])) $_POST['nulls'] = array();

			// Verify that they haven't supplied a value for unary operators
			foreach ($_POST['ops'] as $k => $v) {
				if ($data->selectOps[$v] == 'p' && $_POST['values'][$k] != '') {
					doSelectRows(true, $lang['strselectunary']);
					return;
				}
			}

			if (sizeof($_POST['show']) == 0)
				doSelectRows(true, $lang['strselectneedscol']);
			else {
				// Generate query SQL
				$query = $data->getSelectSQL($_REQUEST['table'], array_keys($_POST['show']),
					$_POST['values'], $_POST['ops']);
				$_REQUEST['query'] = $query;
				$_REQUEST['return'] = 'selectrows';

				$_no_output = true;
				include('./display.php');
				exit;
			}
		}
	}

	/**
	 * Ask for insert parameters and then actually insert row
	 */
	function doInsertRow($confirm, $msg = '') {
		global $data, $misc, $conf;
		global $lang;

		if ($confirm) {
			$misc->printTrail('table');
			$misc->printTabs('table','insert');
			
			$misc->printMsg($msg);

			$attrs = $data->getTableAttributes($_REQUEST['table']);

			if (($conf['autocomplete'] != 'disable')) {
				$fksprops = $misc->getAutocompleteFKProperties($_REQUEST['table']);
				if ($fksprops !== false)
					echo $fksprops['code'];
			}
			else $fksprops = false;

			echo "<form action=\"tables.php\" method=\"post\" id=\"ac_form\">\n";
			if ($attrs->recordCount() > 0) {
				echo "<table>\n";

				// Output table header
				echo "<tr><th class=\"data\">{$lang['strcolumn']}</th><th class=\"data\">{$lang['strtype']}</th>";
				echo "<th class=\"data\">{$lang['strformat']}</th>";
				echo "<th class=\"data\">{$lang['strnull']}</th><th class=\"data\">{$lang['strvalue']}</th></tr>";

				$i = 0;
				$fields = array();
				while (!$attrs->EOF) {
					$fields[$attrs->fields['attnum']] = $attrs->fields['attname'];
					$attrs->fields['attnotnull'] = $data->phpBool($attrs->fields['attnotnull']);
					// Set up default value if there isn't one already
					if (!isset($_REQUEST['values'][$attrs->fields['attnum']]))
						$_REQUEST['values'][$attrs->fields['attnum']] = $attrs->fields['adsrc'];
					// Default format to 'VALUE' if there is no default,
					// otherwise default to 'EXPRESSION'
					if (!isset($_REQUEST['format'][$attrs->fields['attnum']]))
						$_REQUEST['format'][$attrs->fields['attnum']] = ($attrs->fields['adsrc'] === null) ? 'VALUE' : 'EXPRESSION';
					// Continue drawing row
					$id = (($i % 2) == 0 ? '1' : '2');
					echo "<tr class=\"data{$id}\">\n";
					echo "<td style=\"white-space:nowrap;\">", $misc->printVal($attrs->fields['attname']), "</td>";
					echo "<td style=\"white-space:nowrap;\">\n";
					echo $misc->printVal($data->formatType($attrs->fields['type'], $attrs->fields['atttypmod']));
					echo "<input type=\"hidden\" name=\"types[{$attrs->fields['attnum']}]\" value=\"",
						htmlspecialchars($attrs->fields['type']), "\" /></td>";
					echo "<td style=\"white-space:nowrap;\">\n";
					echo "<select name=\"format[{$attrs->fields['attnum']}]\">\n";
					echo "<option value=\"VALUE\"", ($_REQUEST['format'][$attrs->fields['attnum']] == 'VALUE') ? ' selected="selected"' : '', ">{$lang['strvalue']}</option>\n";
					echo "<option value=\"EXPRESSION\"", ($_REQUEST['format'][$attrs->fields['attnum']] == 'EXPRESSION') ? ' selected="selected"' : '', ">{$lang['strexpression']}</option>\n";
					echo "</select>\n</td>\n";
					echo "<td style=\"white-space:nowrap;\">";
					// Output null box if the column allows nulls (doesn't look at CHECKs or ASSERTIONS)
					if (!$attrs->fields['attnotnull']) {
						echo "<label><span><input type=\"checkbox\" name=\"nulls[{$attrs->fields['attnum']}]\"",
							isset($_REQUEST['nulls'][$attrs->fields['attnum']]) ? ' checked="checked"' : '', " /></span></label></td>";
					}
					else {
						echo "&nbsp;</td>";
					}
					echo "<td id=\"row_att_{$attrs->fields['attnum']}\" style=\"white-space:nowrap;\">";
					if (($fksprops !== false) && isset($fksprops['byfield'][$attrs->fields['attnum']])) {
						echo $data->printField("values[{$attrs->fields['attnum']}]", $_REQUEST['values'][$attrs->fields['attnum']], 'fktype'/*force FK*/,
							array(
								'id' => "attr_{$attrs->fields['attnum']}",
								'autocomplete' => 'off'
							)
						);
					}
					else {
						echo $data->printField("values[{$attrs->fields['attnum']}]", $_REQUEST['values'][$attrs->fields['attnum']], $attrs->fields['type']);
					}
					echo "</td>\n";
					echo "</tr>\n";
					$i++;
					$attrs->moveNext();
				}
				echo "</table>\n";

				if (!isset($_SESSION['counter'])) { $_SESSION['counter'] = 0; }

				echo "<input type=\"hidden\" name=\"action\" value=\"insertrow\" />\n";
				echo "<input type=\"hidden\" name=\"fields\" value=\"", htmlentities(serialize($fields), ENT_QUOTES, 'UTF-8') ,"\" />\n";
				echo "<input type=\"hidden\" name=\"protection_counter\" value=\"".$_SESSION['counter']."\" />\n";
				echo "<input type=\"hidden\" name=\"table\" value=\"", htmlspecialchars($_REQUEST['table']), "\" />\n";
				echo "<p><input type=\"submit\" name=\"insert\" value=\"{$lang['strinsert']}\" />\n";
				echo "<input type=\"submit\" name=\"insertandrepeat\" accesskey=\"r\" value=\"{$lang['strinsertandrepeat']}\" />\n";
				echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" />\n";
				
				if($fksprops !== false) {
					if ($conf['autocomplete'] != 'default off')
						echo "<input type=\"checkbox\" id=\"no_ac\" value=\"1\" checked=\"checked\" /><label for=\"no_ac\">{$lang['strac']}</label>\n";
					else
						echo "<input type=\"checkbox\" id=\"no_ac\" value=\"0\" /><label for=\"no_ac\">{$lang['strac']}</label>\n";
				}
				echo "</p>\n";
			}
			else { 
				echo "<p>{$lang['strnofieldsforinsert']}</p>\n";
				echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" />\n";
			}
			echo $misc->form;
			echo "</form>\n";
		}
		else {
			if (!isset($_POST['values'])) $_POST['values'] = array();
			if (!isset($_POST['nulls'])) $_POST['nulls'] = array();
			$_POST['fields'] = unserialize(htmlspecialchars_decode($_POST['fields'], ENT_QUOTES));

			if ($_SESSION['counter']++ == $_POST['protection_counter']) {
				$status = $data->insertRow($_POST['table'], $_POST['fields'], $_POST['values'],
											$_POST['nulls'], $_POST['format'], $_POST['types']);
				if ($status == 0) {
					if (isset($_POST['insert']))
						doDefault($lang['strrowinserted']);
					else {
						$_REQUEST['values'] = array();
						$_REQUEST['nulls'] = array();
						doInsertRow(true, $lang['strrowinserted']);
					}
				}
				else
					doInsertRow(true, $lang['strrowinsertedbad']);
			} else
				doInsertRow(true, $lang['strrowduplicate']);
		}

	}

	/**
	 * Show confirmation of empty and perform actual empty
	 */
	function doEmpty($confirm) {
		global $data, $misc;
		global $lang;

		if (empty($_REQUEST['table']) && empty($_REQUEST['ma'])) {
			doDefault($lang['strspecifytabletoempty']);
			exit();
		}

		if ($confirm) {
			if (isset($_REQUEST['ma'])) {
				$misc->printTrail('schema');
				$misc->printTitle($lang['strempty'],'pg.table.empty');

				echo "<form action=\"tables.php\" method=\"post\">\n";
				foreach ($_REQUEST['ma'] as $v) {
					$a = unserialize(htmlspecialchars_decode($v, ENT_QUOTES));
					echo "<p>", sprintf($lang['strconfemptytable'], $misc->printVal($a['table'])), "</p>\n";
					printf('<input type="hidden" name="table[]" value="%s" />', htmlspecialchars($a['table']));
				}
			} // END mutli empty
			else {
				$misc->printTrail('table');
				$misc->printTitle($lang['strempty'],'pg.table.empty');

				echo "<p>", sprintf($lang['strconfemptytable'], $misc->printVal($_REQUEST['table'])), "</p>\n";

				echo "<form action=\"tables.php\" method=\"post\">\n";
				echo "<input type=\"hidden\" name=\"table\" value=\"", htmlspecialchars($_REQUEST['table']), "\" />\n";
			} // END not mutli empty

			echo "<input type=\"hidden\" name=\"action\" value=\"empty\" />\n";
			echo $misc->form;
			echo "<input type=\"submit\" name=\"empty\" value=\"{$lang['strempty']}\" /> <input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" />\n";
			echo "</form>\n";
		} // END if confirm
		else { // Do Empty
			if (is_array($_REQUEST['table'])) {
				$msg='';
				foreach($_REQUEST['table'] as $t) {
					$status = $data->emptyTable($t);
					if ($status == 0)
						$msg.= sprintf('%s: %s<br />', htmlentities($t, ENT_QUOTES, 'UTF-8'), $lang['strtableemptied']);
					else {
						doDefault(sprintf('%s%s: %s<br />', $msg, htmlentities($t, ENT_QUOTES, 'UTF-8'), $lang['strtableemptiedbad']));
						return;
					}
				}
				doDefault($msg);
			} // END mutli empty
			else {
				$status = $data->emptyTable($_POST['table']);
				if ($status == 0)
					doDefault($lang['strtableemptied']);
				else
					doDefault($lang['strtableemptiedbad']);
			} // END not mutli empty
		} // END do Empty
	}

	/**
	 * Show confirmation of drop and perform actual drop
	 */
	function doDrop($confirm) {
		global $data, $misc;
		global $lang, $_reload_browser;

		if (empty($_REQUEST['table']) && empty($_REQUEST['ma'])) {
			doDefault($lang['strspecifytabletodrop']);
			exit();
		}

		if ($confirm) {
			//If multi drop
			if (isset($_REQUEST['ma'])) {

				$misc->printTrail('schema');
				$misc->printTitle($lang['strdrop'], 'pg.table.drop');

				echo "<form action=\"tables.php\" method=\"post\">\n";
				foreach($_REQUEST['ma'] as $v) {
					$a = unserialize(htmlspecialchars_decode($v, ENT_QUOTES));
					echo "<p>", sprintf($lang['strconfdroptable'], $misc->printVal($a['table'])), "</p>\n";
					printf('<input type="hidden" name="table[]" value="%s" />', htmlspecialchars($a['table']));
				}
			} else {

				$misc->printTrail('table');
				$misc->printTitle($lang['strdrop'], 'pg.table.drop');

				echo "<p>", sprintf($lang['strconfdroptable'], $misc->printVal($_REQUEST['table'])), "</p>\n";

				echo "<form action=\"tables.php\" method=\"post\">\n";
				echo "<input type=\"hidden\" name=\"table\" value=\"", htmlspecialchars($_REQUEST['table']), "\" />\n";
			}// END if multi drop

			echo "<input type=\"hidden\" name=\"action\" value=\"drop\" />\n";
			echo $misc->form;
			echo "<p><input type=\"checkbox\" id=\"cascade\" name=\"cascade\" /> <label for=\"cascade\">{$lang['strcascade']}</label></p>\n";
			echo "<input type=\"submit\" name=\"drop\" value=\"{$lang['strdrop']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" />\n";
			echo "</form>\n";
		} // END confirm
		else {
			//If multi drop
			if (is_array($_REQUEST['table'])) {
				$msg = '';
				$status = $data->beginTransaction();
				if ($status == 0) {
					foreach($_REQUEST['table'] as $t) {
						$status = $data->dropTable($t, isset($_POST['cascade']));
						if ($status == 0)
							$msg.= sprintf('%s: %s<br />', htmlentities($t, ENT_QUOTES, 'UTF-8'), $lang['strtabledropped']);
						else {
							$data->endTransaction();
							doDefault(sprintf('%s%s: %s<br />', $msg, htmlentities($t, ENT_QUOTES, 'UTF-8'), $lang['strtabledroppedbad']));
							return;
						}
					}
				}
				if($data->endTransaction() == 0) {
					// Everything went fine, back to the Default page....
					$_reload_browser = true;
					doDefault($msg);
				}
				else doDefault($lang['strtabledroppedbad']);
			} else {
				$status = $data->dropTable($_POST['table'], isset($_POST['cascade']));
				if ($status == 0) {
					$_reload_browser = true;
					doDefault($lang['strtabledropped']);
				}
				else
					doDefault($lang['strtabledroppedbad']);
			}
		} // END DROP
	}// END Function

	/**
	 * Show default list of tables in the database
	 */
	function doDefault($msg = '') {
		global $data, $conf, $misc, $data;
		global $lang;

		$misc->printTrail('schema');
		$misc->printTabs('schema','tables');
		$misc->printMsg($msg);

		$tables = $data->getTables();

		$columns = array(
			'table' => array(
				'title' => $lang['strtable'],
				'field' => field('relname'),
				'url'	=> "redirect.php?subject=table&amp;{$misc->href}&amp;",
				'vars'  => array('table' => 'relname'),
			),
			'owner' => array(
				'title' => $lang['strowner'],
				'field' => field('relowner'),
			),
			'tablespace' => array(
				'title' => $lang['strtablespace'],
				'field' => field('tablespace')
			),
			'tuples' => array(
				'title' => $lang['strestimatedrowcount'],
				'field' => field('reltuples'),
				'type'  => 'numeric'
			),
			'actions' => array(
				'title' => $lang['stractions'],
			),
			'comment' => array(
				'title' => $lang['strcomment'],
				'field' => field('relcomment'),
			),
		);

		$actions = array(
			'multiactions' => array(
				'keycols' => array('table' => 'relname'),
				'url' => 'tables.php',
				'default' => 'analyze',
			),
			'browse' => array(
				'content' => $lang['strbrowse'],
				'attr'=> array (
					'href' => array (
						'url' => 'display.php',
						'urlvars' => array (
							'subject' => 'table',
							'return' => 'table',
							'table' => field('relname')
						)
					)
				)
			),
			'select' => array(
				'content' => $lang['strselect'],
				'attr'=> array (
					'href' => array (
						'url' => 'tables.php',
						'urlvars' => array (
							'action' => 'confselectrows',
							'table' => field('relname')
						)
					)
				)
			),
			'insert' => array(
				'content' => $lang['strinsert'],
				'attr'=> array (
					'href' => array (
						'url' => 'tables.php',
						'urlvars' => array (
							'action' => 'confinsertrow',
							'table' => field('relname')
						)
					)
				)
			),
			'empty' => array(
				'multiaction' => 'confirm_empty',
				'content' => $lang['strempty'],
				'attr'=> array (
					'href' => array (
						'url' => 'tables.php',
						'urlvars' => array (
							'action' => 'confirm_empty',
							'table' => field('relname')
						)
					)
				)
			),
			'alter' => array(
				'content' => $lang['stralter'],
				'attr'=> array (
					'href' => array (
						'url' => 'tblproperties.php',
						'urlvars' => array (
							'action' => 'confirm_alter',
							'table' => field('relname')
						)
					)
				)
			),
			'drop' => array(
				'multiaction' => 'confirm_drop',
				'content' => $lang['strdrop'],
				'attr'=> array (
					'href' => array (
						'url' => 'tables.php',
						'urlvars' => array (
							'action' => 'confirm_drop',
							'table' => field('relname')
						)
					)
				)
			),
			'vacuum' => array(
				'multiaction' => 'confirm_vacuum',
				'content' => $lang['strvacuum'],
				'attr'=> array (
					'href' => array (
						'url' => 'tables.php',
						'urlvars' => array (
							'action' => 'confirm_vacuum',
							'table' => field('relname')
						)
					)
				)
			),
			'analyze' => array(
				'multiaction' => 'confirm_analyze',
				'content' => $lang['stranalyze'],
				'attr'=> array (
					'href' => array (
						'url' => 'tables.php',
						'urlvars' => array (
							'action' => 'confirm_analyze',
							'table' => field('relname')
						)
					)
				)
			),
			'reindex' => array(
				'multiaction' => 'confirm_reindex',
				'content' => $lang['strreindex'],
				'attr'=> array (
					'href' => array (
						'url' => 'tables.php',
						'urlvars' => array (
							'action' => 'confirm_reindex',
							'table' => field('relname')
						)
					)
				)
			)
			//'cluster' TODO ?
		);

		if (!$data->hasTablespaces()) unset($columns['tablespace']);

		$misc->printTable($tables, $columns, $actions, 'tables-tables', $lang['strnotables']);

		$navlinks = array (
			'create' => array (
				'attr'=> array (
					'href' => array (
						'url' => 'tables.php',
						'urlvars' => array (
							'action' => 'create',
							'server' => $_REQUEST['server'],
							'database' => $_REQUEST['database'],
							'schema' => $_REQUEST['schema']
						)
					)
				),
				'content' => $lang['strcreatetable']
			)
		);

		if (($tables->recordCount() > 0) && $data->hasCreateTableLike()) {
			$navlinks['createlike'] = array (
				'attr'=> array (
					'href' => array (
						'url' => 'tables.php',
						'urlvars' => array (
							'action' => 'createlike',
							'server' => $_REQUEST['server'],
							'database' => $_REQUEST['database'],
							'schema' => $_REQUEST['schema']
						)
					)
				),
				'content' => $lang['strcreatetablelike']
			);
		}
		$misc->printNavLinks($navlinks, 'tables-tables', get_defined_vars());
	}
	
	require('./admin.php');

	/**
	 * Generate XML for the browser tree.
	 */
	function doTree() {
		global $misc, $data;

		$tables = $data->getTables();

		$reqvars = $misc->getRequestVars('table');

		$attrs = array(
			'text'   => field('relname'),
			'icon'   => 'Table',
			'iconAction' => url('display.php',
							$reqvars,
							array('table' => field('relname'))
						),
			'toolTip'=> field('relcomment'),
			'action' => url('redirect.php',
							$reqvars,
							array('table' => field('relname'))
						),
			'branch' => url('tables.php',
							$reqvars,
							array (
								'action' => 'subtree',
								'table' => field('relname')
							)
						)
		);

		$misc->printTree($tables, $attrs, 'tables');
		exit;
	}

	function doSubTree() {
		global $misc, $data;

		$tabs = $misc->getNavTabs('table');
		$items = $misc->adjustTabsForTree($tabs);
		$reqvars = $misc->getRequestVars('table');

		$attrs = array(
			'text'   => field('title'),
			'icon'   => field('icon'),
			'action' => url(
				field('url'),
				$reqvars,
				field('urlvars'),
				array('table' => $_REQUEST['table'])
			),
			'branch' => ifempty(
				field('branch'), '', url(
					field('url'),
					$reqvars,
					array(
						'action' => 'tree',
						'table' => $_REQUEST['table']
					)
				)
			),
		);

		$misc->printTree($items, $attrs, 'table');
		exit;
	}

	if ($action == 'tree') doTree();
	if ($action == 'subtree') dosubTree();

	$misc->printHeader($lang['strtables']);
	$misc->printBody();

	switch ($action) {
		case 'create':
			if (isset($_POST['cancel'])) doDefault();
			else doCreate();
			break;
		case 'createlike':
			doCreateLike(false);
			break;
		case 'confcreatelike':
			if (isset($_POST['cancel'])) doDefault();
			else doCreateLike(true);
			break;
		case 'selectrows':
			if (!isset($_POST['cancel'])) doSelectRows(false);
			else doDefault();
			break;
		case 'confselectrows':
			doSelectRows(true);
			break;
		case 'insertrow':
			if (!isset($_POST['cancel'])) doInsertRow(false);
			else doDefault();
			break;
		case 'confinsertrow':
			doInsertRow(true);
			break;
		case 'empty':
			if (isset($_POST['empty'])) doEmpty(false);
			else doDefault();
			break;
		case 'confirm_empty':
			doEmpty(true);
			break;
		case 'drop':
			if (isset($_POST['drop'])) doDrop(false);
			else doDefault();
			break;
		case 'confirm_drop':
			doDrop(true);
			break;
		default:
			if (adminActions($action, 'table') === false) doDefault();
			break;
	}

	$misc->printFooter();

?>
