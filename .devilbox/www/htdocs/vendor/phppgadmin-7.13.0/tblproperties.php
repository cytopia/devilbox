<?php

	/**
	 * List tables in a database
	 *
	 * $Id: tblproperties.php,v 1.92 2008/01/19 13:46:15 ioguix Exp $
	 */

	// Include application functions
	include_once('./libraries/lib.inc.php');

	$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';

	/**
	 * Function to save after altering a table
	 */
	function doSaveAlter() {
		global $data, $lang, $_reload_browser, $misc;

		// For databases that don't allow owner change
		if (!isset($_POST['owner'])) $_POST['owner'] = '';
		// Default tablespace to null if it isn't set
		if (!isset($_POST['tablespace'])) $_POST['tablespace'] = null;
		if (!isset($_POST['newschema'])) $_POST['newschema'] = null;

		$status = $data->alterTable($_POST['table'], $_POST['name'], $_POST['owner'], $_POST['newschema'], $_POST['comment'], $_POST['tablespace']);
		if ($status == 0) {
			// If table has been renamed, need to change to the new name and
			// reload the browser frame.
			if ($_POST['table'] != $_POST['name']) {
				// Jump them to the new table name
				$_REQUEST['table'] = $_POST['name'];
				// Force a browser reload
				$_reload_browser = true;
			}
			// If schema has changed, need to change to the new schema and reload the browser
			if (!empty($_POST['newschema']) && ($_POST['newschema'] != $data->_schema)) {
				// Jump them to the new sequence schema
				$misc->setCurrentSchema($_POST['newschema']);
				$_reload_browser = true;
			 }
			doDefault($lang['strtablealtered']);
		}
		else
			doAlter($lang['strtablealteredbad']);
	}

	/**
	 * Function to allow altering of a table
	 */
	function doAlter($msg = '') {
		global $data, $misc;
		global $lang;

		$misc->printTrail('table');
		$misc->printTitle($lang['stralter'], 'pg.table.alter');
		$misc->printMsg($msg);

		// Fetch table info
		$table = $data->getTable($_REQUEST['table']);
		// Fetch all users
		$users = $data->getUsers();
		// Fetch all tablespaces from the database
		if ($data->hasTablespaces()) $tablespaces = $data->getTablespaces(true);

		if ($table->recordCount() > 0) {

			if (!isset($_POST['name'])) $_POST['name'] = $table->fields['relname'];
			if (!isset($_POST['owner'])) $_POST['owner'] = $table->fields['relowner'];
			if (!isset($_POST['newschema'])) $_POST['newschema'] = $table->fields['nspname'];
			if (!isset($_POST['comment'])) $_POST['comment'] = $table->fields['relcomment'];
			if ($data->hasTablespaces() && !isset($_POST['tablespace'])) $_POST['tablespace'] = $table->fields['tablespace'];

			echo "<form action=\"tblproperties.php\" method=\"post\">\n";
			echo "<table>\n";
			echo "<tr><th class=\"data left required\">{$lang['strname']}</th>\n";
			echo "<td class=\"data1\">";
			echo "<input name=\"name\" size=\"32\" maxlength=\"{$data->_maxNameLen}\" value=\"",
				htmlspecialchars($_POST['name'], ENT_QUOTES), "\" /></td></tr>\n";

			if ($data->isSuperUser()) {
				echo "<tr><th class=\"data left required\">{$lang['strowner']}</th>\n";
				echo "<td class=\"data1\"><select name=\"owner\">";
				while (!$users->EOF) {
					$uname = $users->fields['usename'];
					echo "<option value=\"", htmlspecialchars($uname), "\"",
						($uname == $_POST['owner']) ? ' selected="selected"' : '', ">", htmlspecialchars($uname), "</option>\n";
					$users->moveNext();
				}
				echo "</select></td></tr>\n";
			}

			if ($data->hasAlterTableSchema()) {
				$schemas = $data->getSchemas();
				echo "<tr><th class=\"data left required\">{$lang['strschema']}</th>\n";
				echo "<td class=\"data1\"><select name=\"newschema\">";
				while (!$schemas->EOF) {
					$schema = $schemas->fields['nspname'];
					echo "<option value=\"", htmlspecialchars($schema), "\"",
						($schema == $_POST['newschema']) ? ' selected="selected"' : '', ">", htmlspecialchars($schema), "</option>\n";
					$schemas->moveNext();
				}
			    echo "</select></td></tr>\n";
			}

			// Tablespace (if there are any)
			if ($data->hasTablespaces() && $tablespaces->recordCount() > 0) {
				echo "\t<tr>\n\t\t<th class=\"data left\">{$lang['strtablespace']}</th>\n";
				echo "\t\t<td class=\"data1\">\n\t\t\t<select name=\"tablespace\">\n";
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
				echo "\t\t\t</select>\n\t\t</td>\n\t</tr>\n";
			}

			echo "<tr><th class=\"data left\">{$lang['strcomment']}</th>\n";
			echo "<td class=\"data1\">";
			echo "<textarea rows=\"3\" cols=\"32\" name=\"comment\">",
				htmlspecialchars($_POST['comment']), "</textarea></td></tr>\n";
			echo "</table>\n";
			echo "<p><input type=\"hidden\" name=\"action\" value=\"alter\" />\n";
			echo "<input type=\"hidden\" name=\"table\" value=\"", htmlspecialchars($_REQUEST['table']), "\" />\n";
			echo $misc->form;
			echo "<input type=\"submit\" name=\"alter\" value=\"{$lang['stralter']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
			echo "</form>\n";
		}
		else echo "<p>{$lang['strnodata']}</p>\n";
	}

	function doExport($msg = '') {
		global $data, $misc;
		global $lang;

		// Determine whether or not the table has an object ID (Always false if version>=12)
		$hasID = $data->hasObjectID($_REQUEST['table']);

		$misc->printTrail('table');
		$misc->printTabs('table','export');
		$misc->printMsg($msg);

		echo "<form action=\"dataexport.php\" method=\"post\">\n";
		echo "<table>\n";
		echo "<tr><th class=\"data\">{$lang['strformat']}</th><th class=\"data\" colspan=\"2\">{$lang['stroptions']}</th></tr>\n";
		// Data only
		echo "<tr><th class=\"data left\" rowspan=\"", ($hasID) ? 2 : 1, "\">";
		echo "<input type=\"radio\" id=\"what1\" name=\"what\" value=\"dataonly\" checked=\"checked\" /><label for=\"what1\">{$lang['strdataonly']}</label></th>\n";
		echo "<td>{$lang['strformat']}</td>\n";
		echo "<td><select name=\"d_format\">\n";
		echo "<option value=\"copy\">COPY</option>\n";
		echo "<option value=\"sql\">SQL</option>\n";
		echo "<option value=\"csv\">CSV</option>\n";
		echo "<option value=\"tab\">{$lang['strtabbed']}</option>\n";
		echo "<option value=\"html\">XHTML</option>\n";
		echo "<option value=\"xml\">XML</option>\n";
		echo "</select>\n</td>\n</tr>\n";
		if ($hasID) {
			echo "<tr><td><label for=\"d_oids\">{$lang['stroids']}</td><td><input type=\"checkbox\" id=\"d_oids\" name=\"d_oids\" /></td>\n</tr>\n";
		}
		// Structure only
		echo "<tr><th class=\"data left\"><input type=\"radio\" id=\"what2\" name=\"what\" value=\"structureonly\" /><label for=\"what2\">{$lang['strstructureonly']}</label></th>\n";
		echo "<td><label for=\"s_clean\">{$lang['strdrop']}</label></td><td><input type=\"checkbox\" id=\"s_clean\" name=\"s_clean\" /></td>\n</tr>\n";
		// Structure and data
		echo "<tr><th class=\"data left\" rowspan=\"", ($hasID) ? 3 : 2, "\">";
		echo "<input type=\"radio\" id=\"what3\" name=\"what\" value=\"structureanddata\" /><label for=\"what3\">{$lang['strstructureanddata']}</label></th>\n";
		echo "<td>{$lang['strformat']}</td>\n";
		echo "<td><select name=\"sd_format\">\n";
		echo "<option value=\"copy\">COPY</option>\n";
		echo "<option value=\"sql\">SQL</option>\n";
		echo "</select>\n</td>\n</tr>\n";
		echo "<tr><td><label for=\"sd_clean\">{$lang['strdrop']}</label></td><td><input type=\"checkbox\" id=\"sd_clean\" name=\"sd_clean\" /></td>\n</tr>\n";
		if ($hasID) {
			echo "<tr><td><label for=\"sd_oids\">{$lang['stroids']}</label></td><td><input type=\"checkbox\" id=\"sd_oids\" name=\"sd_oids\" /></td>\n</tr>\n";
		}
		echo "</table>\n";

		echo "<h3>{$lang['stroptions']}</h3>\n";
		echo "<p><input type=\"radio\" id=\"output1\" name=\"output\" value=\"show\" checked=\"checked\" /><label for=\"output1\">{$lang['strshow']}</label>\n";
		echo "<br/><input type=\"radio\" id=\"output2\" name=\"output\" value=\"download\" /><label for=\"output2\">{$lang['strdownload']}</label></p>\n";

		echo "<p><input type=\"hidden\" name=\"action\" value=\"export\" />\n";
		echo $misc->form;
		echo "<input type=\"hidden\" name=\"subject\" value=\"table\" />\n";
		echo "<input type=\"hidden\" name=\"table\" value=\"", htmlspecialchars($_REQUEST['table']), "\" />\n";
		echo "<input type=\"submit\" value=\"{$lang['strexport']}\" /></p>\n";
		echo "</form>\n";
	}

	function doImport($msg = '') {
		global $data, $misc;
		global $lang;

		$misc->printTrail('table');
		$misc->printTabs('table','import');
		$misc->printMsg($msg);

		// Check that file uploads are enabled
		if (ini_get('file_uploads')) {
			// Don't show upload option if max size of uploads is zero
			$max_size = $misc->inisizeToBytes(ini_get('upload_max_filesize'));
			if (is_double($max_size) && $max_size > 0) {
				echo "<form action=\"dataimport.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
				echo "<table>\n";
				echo "\t<tr>\n\t\t<th class=\"data left required\">{$lang['strformat']}</th>\n";
				echo "\t\t<td><select name=\"format\">\n";
				echo "\t\t\t<option value=\"auto\">{$lang['strauto']}</option>\n";
				echo "\t\t\t<option value=\"csv\">CSV</option>\n";
				echo "\t\t\t<option value=\"tab\">{$lang['strtabbed']}</option>\n";
				if (function_exists('xml_parser_create')) {
					echo "\t\t\t<option value=\"xml\">XML</option>\n";
				}
				echo "\t\t</select></td>\n\t</tr>\n";
				echo "\t<tr>\n\t\t<th class=\"data left required\">{$lang['strallowednulls']}</th>\n";
				echo "\t\t<td><label><input type=\"checkbox\" name=\"allowednulls[0]\" value=\"\\N\" checked=\"checked\" />{$lang['strbackslashn']}</label><br />\n";
				echo "\t\t<label><input type=\"checkbox\" name=\"allowednulls[1]\" value=\"NULL\" />NULL</label><br />\n";
				echo "\t\t<label><input type=\"checkbox\" name=\"allowednulls[2]\" value=\"\" />{$lang['stremptystring'] }</label></td>\n\t</tr>\n";
				echo "\t<tr>\n\t\t<th class=\"data left required\">{$lang['strfile']}</th>\n";
				echo "\t\t<td><input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"{$max_size}\" />";
				echo "<input type=\"file\" name=\"source\" /></td>\n\t</tr>\n";
				echo "</table>\n";
				echo "<p><input type=\"hidden\" name=\"action\" value=\"import\" />\n";
				echo $misc->form;
				echo "<input type=\"hidden\" name=\"table\" value=\"", htmlspecialchars($_REQUEST['table']), "\" />\n";
				echo "<input type=\"submit\" value=\"{$lang['strimport']}\" /></p>\n";
				echo "</form>\n";
			}
		}
		else echo "<p>{$lang['strnouploads']}</p>\n";
	}

	/**
	 * Displays a screen where they can add a column
	 */
	function doAddColumn($msg = '') {
		global $data, $misc, $_reload_browser;
		global $lang;

		if (!isset($_REQUEST['stage'])) $_REQUEST['stage'] = 1;

		switch ($_REQUEST['stage']) {
			case 1:
				// Set variable defaults
				if (!isset($_POST['field'])) $_POST['field'] = '';
				if (!isset($_POST['type'])) $_POST['type'] = '';
				if (!isset($_POST['array'])) $_POST['array'] = '';
				if (!isset($_POST['length'])) $_POST['length'] = '';
				if (!isset($_POST['default'])) $_POST['default'] = '';
				if (!isset($_POST['comment'])) $_POST['comment'] = '';

				// Fetch all available types
				$types = $data->getTypes(true, false, true);
				$types_for_js = array();

				$misc->printTrail('table');
				$misc->printTitle($lang['straddcolumn'], 'pg.column.add');
				$misc->printMsg($msg);

				echo "<script src=\"tables.js\" type=\"text/javascript\"></script>";
				echo "<form action=\"tblproperties.php\" method=\"post\">\n";

				// Output table header
				echo "<table>\n";
				echo "<tr><th class=\"data required\">{$lang['strname']}</th>\n<th colspan=\"2\" class=\"data required\">{$lang['strtype']}</th>\n";
				echo "<th class=\"data\">{$lang['strlength']}</th>\n";
				if ($data->hasCreateFieldWithConstraints())
					echo "<th class=\"data\">{$lang['strnotnull']}</th>\n<th class=\"data\">{$lang['strdefault']}</th>\n";
				echo "<th class=\"data\">{$lang['strcomment']}</th></tr>\n";

				echo "<tr><td><input name=\"field\" size=\"16\" maxlength=\"{$data->_maxNameLen}\" value=\"",
					htmlspecialchars($_POST['field']), "\" /></td>\n";
				echo "<td><select name=\"type\" id=\"type\" onchange=\"checkLengths(document.getElementById('type').value,'');\">\n";
				// Output any "magic" types.  This came in with the alter column type so we'll check that
				if ($data->hasMagicTypes()) {
					foreach ($data->extraTypes as $v) {
						$types_for_js[] = strtolower($v);
						echo "\t<option value=\"", htmlspecialchars($v), "\"",
						($v == $_POST['type']) ? ' selected="selected"' : '', ">",
							$misc->printVal($v), "</option>\n";
					}
				}
				while (!$types->EOF) {
					$typname = $types->fields['typname'];
					$types_for_js[] = $typname;
					echo "\t<option value=\"", htmlspecialchars($typname), "\"", ($typname == $_POST['type']) ? ' selected="selected"' : '', ">",
						$misc->printVal($typname), "</option>\n";
					$types->moveNext();
				}
				echo "</select></td>\n";

				// Output array type selector
				echo "<td><select name=\"array\">\n";
				echo "\t<option value=\"\"", ($_POST['array'] == '') ? ' selected="selected"' : '', "></option>\n";
				echo "\t<option value=\"[]\"", ($_POST['array'] == '[]') ? ' selected="selected"' : '', ">[ ]</option>\n";
				echo "</select></td>\n";
				$predefined_size_types = array_intersect($data->predefined_size_types, $types_for_js);
				$escaped_predef_types = array(); // the JS escaped array elements
				foreach($predefined_size_types as $value) {
					$escaped_predef_types[] = "'{$value}'";
				}

				echo "<td><input name=\"length\" id=\"lengths\" size=\"8\" value=\"",
					htmlspecialchars($_POST['length']), "\" /></td>\n";
				// Support for adding column with not null and default
				if ($data->hasCreateFieldWithConstraints()) {
					echo "<td><input type=\"checkbox\" name=\"notnull\"",
						(isset($_REQUEST['notnull'])) ? ' checked="checked"' : '', " /></td>\n";
					echo "<td><input name=\"default\" size=\"20\" value=\"",
						htmlspecialchars($_POST['default']), "\" /></td>\n";
				}
				echo "<td><input name=\"comment\" size=\"40\" value=\"",
					htmlspecialchars($_POST['comment']), "\" /></td></tr>\n";
				echo "</table>\n";
				echo "<p><input type=\"hidden\" name=\"action\" value=\"add_column\" />\n";
				echo "<input type=\"hidden\" name=\"stage\" value=\"2\" />\n";
				echo $misc->form;
				echo "<input type=\"hidden\" name=\"table\" value=\"", htmlspecialchars($_REQUEST['table']), "\" />\n";
				if (!$data->hasCreateFieldWithConstraints()) {
					echo "<input type=\"hidden\" name=\"default\" value=\"\" />\n";
				}
				echo "<input type=\"submit\" value=\"{$lang['stradd']}\" />\n";
				echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
				echo "</form>\n";
				echo "<script type=\"text/javascript\">predefined_lengths = new Array(". implode(",",$escaped_predef_types) .");checkLengths(document.getElementById('type').value,'');</script>\n";
				break;
			case 2:
				// Check inputs
				if (trim($_POST['field']) == '') {
					$_REQUEST['stage'] = 1;
					doAddColumn($lang['strcolneedsname']);
					return;
				}
				if (!isset($_POST['length'])) $_POST['length'] = '';
				$status = $data->addColumn($_POST['table'], $_POST['field'],
							   $_POST['type'], $_POST['array'] != '', $_POST['length'], isset($_POST['notnull']),
							   $_POST['default'], $_POST['comment']);
				if ($status == 0) {
					$_reload_browser = true;
					doDefault($lang['strcolumnadded']);
				}
				else {
					$_REQUEST['stage'] = 1;
					doAddColumn($lang['strcolumnaddedbad']);
					return;
				}
				break;
			default:
				echo "<p>{$lang['strinvalidparam']}</p>\n";
		}
	}

	/**
	 * Show confirmation of drop column and perform actual drop
	 */
	function doDrop($confirm) {
		global $data, $database, $misc, $_reload_browser;
		global $lang;

		if ($confirm) {
			$misc->printTrail('column');
			$misc->printTitle($lang['strdrop'], 'pg.column.drop');

            echo "<p>", sprintf($lang['strconfdropcolumn'], $misc->printVal($_REQUEST['column']),
                    $misc->printVal($_REQUEST['table'])), "</p>\n";


			echo "<form action=\"tblproperties.php\" method=\"post\">\n";
			echo "<input type=\"hidden\" name=\"action\" value=\"drop\" />\n";
			echo "<input type=\"hidden\" name=\"table\" value=\"", htmlspecialchars($_REQUEST['table']), "\" />\n";
			echo "<input type=\"hidden\" name=\"column\" value=\"", htmlspecialchars($_REQUEST['column']), "\" />\n";
			echo $misc->form;
			echo "<p><input type=\"checkbox\" id=\"cascade\" name=\"cascade\"> <label for=\"cascade\">{$lang['strcascade']}</label></p>\n";
			echo "<input type=\"submit\" name=\"drop\" value=\"{$lang['strdrop']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" />\n";
			echo "</form>\n";
		}
		else {
			$status = $data->dropColumn($_POST['table'], $_POST['column'], isset($_POST['cascade']));
			if ($status == 0) {
				$_reload_browser = true;
				doDefault($lang['strcolumndropped']);
			}
			else
				doDefault($lang['strcolumndroppedbad']);
		}

	}

	function doTree() {
		global $misc, $data;

		$columns = $data->getTableAttributes($_REQUEST['table']);
		$reqvars = $misc->getRequestVars('column');

		$attrs = array (
			'text'   => field('attname'),
			'action' => url('colproperties.php',
							$reqvars,
							array(
								'table'		=> $_REQUEST['table'],
								'column'	=> field('attname')
							)
						),
			'icon'   => 'Column',
			'iconAction' => url('display.php',
								$reqvars,
								array(
									'table'		=> $_REQUEST['table'],
									'column'	=> field('attname'),
									'query'		=> replace(
														'SELECT "%column%", count(*) AS "count" FROM "%table%" GROUP BY "%column%" ORDER BY "%column%"',
														array (
															'%column%' => field('attname'),
															'%table%' => $_REQUEST['table']
														)
													)
								)
							),
			'toolTip'=> field('comment')
		);

		$misc->printTree($columns, $attrs, 'tblcolumns');

		exit;
	}

	if ($action == 'tree') doTree();

	/**
	 * Show default list of columns in the table
	 */
	function doDefault($msg = '') {
		global $data, $conf, $misc;
		global $lang;

		function attPre(&$rowdata, $actions) {
			global $data;
			$rowdata->fields['+type'] = $data->formatType($rowdata->fields['type'], $rowdata->fields['atttypmod']);
			$attname = $rowdata->fields['attname'];
			$table = $_REQUEST['table'];
			$data->fieldClean($attname);
			$data->fieldClean($table);

			$actions['browse']['attr']['href']['urlvars']['query'] = "SELECT \"{$attname}\", count(*) AS \"count\"
				FROM \"{$table}\" GROUP BY \"{$attname}\" ORDER BY \"{$attname}\"";

			return $actions;
		}

		$misc->printTrail('table');
		$misc->printTabs('table','columns');
		$misc->printMsg($msg);

		// Get table
		$tdata = $data->getTable($_REQUEST['table']);
		// Get columns
		$attrs = $data->getTableAttributes($_REQUEST['table']);
		// Get constraints keys
		$ck = $data->getConstraintsWithFields($_REQUEST['table']);

		// Show comment if any
		if ($tdata->fields['relcomment'] !== null)
			echo '<p class="comment">', $misc->printVal($tdata->fields['relcomment']), "</p>\n";

		$columns = array(
			'column' => array(
				'title' => $lang['strcolumn'],
				'field' => field('attname'),
				'url'   => "colproperties.php?subject=column&amp;{$misc->href}&amp;table=".urlencode($_REQUEST['table'])."&amp;",
				'vars'  => array('column' => 'attname'),
			),
			'type' => array(
				'title' => $lang['strtype'],
				'field' => field('+type'),
			),
			'notnull' => array(
				'title' => $lang['strnotnull'],
				'field' => field('attnotnull'),
				'type'  => 'bool',
				'params'=> array('true' => 'NOT NULL', 'false' => ''),
			),
			'default' => array(
				'title' => $lang['strdefault'],
				'field' => field('adsrc'),
			),
			'keyprop' => array(
				'title' => $lang['strconstraints'],
				'class' => 'constraint_cell',
				'field' => field('attname'),
				'type'  => 'callback',
				'params'=> array(
					'function' => 'cstrRender',
					'keys' => $ck->getArray()
				)
			),
			'actions' => array(
				'title' => $lang['stractions'],
			),
			'comment' => array(
				'title' => $lang['strcomment'],
				'field' => field('comment')
			),
		);

		function cstrRender($s, $p) {
			global $misc, $data;

			$str ='';
			foreach ($p['keys'] as $k => $c) {

				if (is_null($p['keys'][$k]['consrc'])) {
					$atts = $data->getAttributeNames($_REQUEST['table'], explode(' ', $p['keys'][$k]['indkey']));
					$c['consrc'] = ($c['contype'] == 'u' ? "UNIQUE (" : "PRIMARY KEY (") . join(',', $atts) . ')';
				}

				if ($c['p_field'] == $s)
					switch ($c['contype']) {
						case 'p':
							$str .= '<a href="constraints.php?'. $misc->href ."&amp;table=". urlencode($c['p_table']) ."&amp;schema=". urlencode($c['p_schema']) ."\"><img src=\"".
								$misc->icon('PrimaryKey') .'" alt="[pk]" title="'. htmlentities($c['consrc'], ENT_QUOTES, 'UTF-8') .'" /></a>';
						break;
						case 'f':
							$str .= '<a href="tblproperties.php?'. $misc->href ."&amp;table=". urlencode($c['f_table']) ."&amp;schema=". urlencode($c['f_schema']) ."\"><img src=\"".
								$misc->icon('ForeignKey') .'" alt="[fk]" title="'. htmlentities($c['consrc'], ENT_QUOTES, 'UTF-8') .'" /></a>';
						break;
						case 'u':
							$str .= '<a href="constraints.php?'. $misc->href ."&amp;table=". urlencode($c['p_table']) ."&amp;schema=". urlencode($c['p_schema']) ."\"><img src=\"".
								$misc->icon('UniqueConstraint') .'" alt="[uniq]" title="'. htmlentities($c['consrc'], ENT_QUOTES, 'UTF-8') .'" /></a>';
						break;
						case 'c':
							$str .= '<a href="constraints.php?'. $misc->href ."&amp;table=". urlencode($c['p_table']) ."&amp;schema=". urlencode($c['p_schema']) ."\"><img src=\"".
								$misc->icon('CheckConstraint') .'" alt="[check]" title="'. htmlentities($c['consrc'], ENT_QUOTES, 'UTF-8') .'" /></a>';
					}
			}

			return $str;
		}

		$actions = array(
			'browse' => array(
				'title' => $lang['strbrowse'],
				'url' => "display.php?{$misc->href}&amp;subject=column&amp;return=table&amp;table=".urlencode($_REQUEST['table']).'&amp;',
				'vars' => array('column' => 'attname'),
			),
			'alter' => array(
				'title' => $lang['stralter'],
				'url'   => "colproperties.php?action=properties&amp;{$misc->href}&amp;table=".urlencode($_REQUEST['table'])."&amp;",
				'vars'  => array('column' => 'attname'),
			),
			'privileges' => array(
				'title' => $lang['strprivileges'],
				'url'   => "privileges.php?subject=column&amp;{$misc->href}&amp;table=".urlencode($_REQUEST['table'])."&amp;",
				'vars'  => array('column' => 'attname'),
			),
			'drop' => array(
				'title' => $lang['strdrop'],
				'url'   => "tblproperties.php?action=confirm_drop&amp;{$misc->href}&amp;table=".urlencode($_REQUEST['table'])."&amp;",
				'vars'  => array('column' => 'attname'),
			),
		);

		$actions = array(
			'browse' => array(
				'content' => $lang['strbrowse'],
				'attr'=> array (
					'href' => array (
						'url' => 'display.php',
						'urlvars' => array (
							'table' => $_REQUEST['table'],
							'subject' => 'column',
							'return' => 'table',
							'column' => field('attname')
						)
					)
				)
			),
			'alter' => array(
				'content' => $lang['stralter'],
				'attr'=> array (
					'href' => array (
						'url' => 'colproperties.php',
						'urlvars' => array (
							'subject' => 'column',
							'action' => 'properties',
							'table' => $_REQUEST['table'],
							'column' => field('attname')
						)
					)
				)
			),
			'privileges' => array(
				'content' => $lang['strprivileges'],
				'attr'=> array (
					'href' => array (
						'url' => 'privileges.php',
						'urlvars' => array (
							'subject' => 'column',
							'table' => $_REQUEST['table'],
							'column' => field('attname')
						)
					)
				)
			),
			'drop' => array(
				'content' => $lang['strdrop'],
				'attr'=> array (
					'href' => array (
						'url' => 'tblproperties.php',
						'urlvars' => array (
							'subject' => 'column',
							'action' => 'confirm_drop',
							'table' => $_REQUEST['table'],
							'column' => field('attname')
						)
					)
				)
			),
		);

		$misc->printTable($attrs, $columns, $actions, 'tblproperties-tblproperties', null, 'attPre');

		$navlinks = array (
			'browse' => array (
				'attr'=> array (
					'href' => array (
						'url' => 'display.php',
						'urlvars' => array (
							'server' => $_REQUEST['server'],
							'database' => $_REQUEST['database'],
							'schema' => $_REQUEST['schema'],
							'table' => $_REQUEST['table'],
							'subject' => 'table',
							'return' => 'table'
						)
					)
				),
				'content' => $lang['strbrowse']
			),
			'select' => array (
				'attr'=> array (
					'href' => array (
						'url' => 'tables.php',
						'urlvars' => array (
							'action' => 'confselectrows',
							'server' => $_REQUEST['server'],
							'database' => $_REQUEST['database'],
							'schema' => $_REQUEST['schema'],
							'table' => $_REQUEST['table']
						)
					)
				),
				'content' => $lang['strselect']
			),
			'insert' => array (
				'attr'=> array (
					'href' => array (
					'url' => 'tables.php',
						'urlvars' => array (
							'action' => 'confinsertrow',
							'server' => $_REQUEST['server'],
							'database' => $_REQUEST['database'],
							'schema' => $_REQUEST['schema'],
							'table' => $_REQUEST['table']
						)
					)
				),
				'content' => $lang['strinsert']
			),
			'empty' => array (
				'attr'=> array (
					'href' => array (
					'url' => 'tables.php',
						'urlvars' => array (
							'action' => 'confirm_empty',
							'server' => $_REQUEST['server'],
							'database' => $_REQUEST['database'],
							'schema' => $_REQUEST['schema'],
							'table' => $_REQUEST['table']
						)
					)
				),
				'content' => $lang['strempty']
			),
			'drop' => array (
				'attr'=> array (
					'href' => array (
						'url' => 'tables.php',
						'urlvars' => array (
							'action' => 'confirm_drop',
							'server' => $_REQUEST['server'],
							'database' => $_REQUEST['database'],
							'schema' => $_REQUEST['schema'],
							'table' => $_REQUEST['table'],
						)
					)
				),
				'content' => $lang['strdrop']
			),
			'addcolumn' => array (
				'attr'=> array (
					'href' => array (
						'url' => 'tblproperties.php',
						'urlvars' => array (
							'action' => 'add_column',
							'server' => $_REQUEST['server'],
							'database' => $_REQUEST['database'],
							'schema' => $_REQUEST['schema'],
							'table' => $_REQUEST['table']
						)
					)
				),
				'content' => $lang['straddcolumn']
			),
			'alter' => array (
				'attr'=> array (
					'href' => array (
						'url' => 'tblproperties.php',
						'urlvars' => array (
							'action' => 'confirm_alter',
							'server' => $_REQUEST['server'],
							'database' => $_REQUEST['database'],
							'schema' => $_REQUEST['schema'],
							'table' => $_REQUEST['table']
						)
					)
				),
				'content' => $lang['stralter']
			)
		);
		$misc->printNavLinks($navlinks,
			'tblproperties-tblproperties'
			, get_defined_vars()
		);

	}

	$misc->printHeader($lang['strtables'] . ' - ' . $_REQUEST['table']);
	$misc->printBody();

	switch ($action) {
		case 'alter':
			if (isset($_POST['alter'])) doSaveAlter();
			else doDefault();
			break;
		case 'confirm_alter':
			doAlter();
			break;
		case 'import':
			doImport();
			break;
		case 'export':
			doExport();
			break;
		case 'add_column':
			if (isset($_POST['cancel'])) doDefault();
			else doAddColumn();
			break;
		case 'properties':
			if (isset($_POST['cancel'])) doDefault();
			else doProperties();
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
