<?php

	/**
	 * Manage types in a database
	 *
	 * $Id: types.php,v 1.42 2007/11/30 15:25:23 soranzo Exp $
	 */

	// Include application functions
	include_once('./libraries/lib.inc.php');
	
	$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';
	if (!isset($msg)) $msg = '';

	/**
	 * Show read only properties for a type
	 */
	function doProperties($msg = '') {
		global $data, $misc;
		global $lang;

		// Get type (using base name)
		$typedata = $data->getType($_REQUEST['type']);

		$misc->printTrail('type');
		$misc->printTitle($lang['strproperties'], 'pg.type');
		$misc->printMsg($msg);
		
		function attPre(&$rowdata) {
			global $data;
			$rowdata->fields['+type'] = $data->formatType($rowdata->fields['type'], $rowdata->fields['atttypmod']);
		}
		
		if ($typedata->recordCount() > 0) {
			$vals = false;
			switch ($typedata->fields['typtype']) {
			case 'c':
				$attrs = $data->getTableAttributes($_REQUEST['type']);
				
				$columns = array(
					'field' => array(
						'title' => $lang['strfield'],
						'field' => field('attname'),
					),
					'type' => array(
						'title' => $lang['strtype'],
						'field' => field('+type'),
					),
					'comment' => array(
						'title' => $lang['strcomment'],
						'field' => field('comment'),
					)
				);
				
				$actions = array();
				
				$misc->printTable($attrs, $columns, $actions, 'types-properties', null, 'attPre');
				
				break;
			case 'e':
				$vals = $data->getEnumValues($typedata->fields['typname']);
			default:
				$byval = $data->phpBool($typedata->fields['typbyval']);
				echo "<table>\n";
				echo "<tr><th class=\"data left\">{$lang['strname']}</th>\n";
				echo "<td class=\"data1\">", $misc->printVal($typedata->fields['typname']), "</td></tr>\n";
				echo "<tr><th class=\"data left\">{$lang['strinputfn']}</th>\n";
				echo "<td class=\"data1\">", $misc->printVal($typedata->fields['typin']), "</td></tr>\n";
				echo "<tr><th class=\"data left\">{$lang['stroutputfn']}</th>\n";
				echo "<td class=\"data1\">", $misc->printVal($typedata->fields['typout']), "</td></tr>\n";
				echo "<tr><th class=\"data left\">{$lang['strlength']}</th>\n";
				echo "<td class=\"data1\">", $misc->printVal($typedata->fields['typlen']), "</td></tr>\n";
				echo "<tr><th class=\"data left\">{$lang['strpassbyval']}</th>\n";
				echo "<td class=\"data1\">", ($byval) ? $lang['stryes'] : $lang['strno'], "</td></tr>\n";
				echo "<tr><th class=\"data left\">{$lang['stralignment']}</th>\n";
				echo "<td class=\"data1\">", $misc->printVal($typedata->fields['typalign']), "</td></tr>\n";
				if ($data->hasEnumTypes() && $vals) {
					$vals = $vals->getArray();
					$nbVals = count($vals);
					echo "<tr>\n\t<th class=\"data left\" rowspan=\"$nbVals\">{$lang['strenumvalues']}</th>\n";
					echo "<td class=\"data2\">{$vals[0]['enumval']}</td></tr>\n";
					for ($i=1; $i < $nbVals; $i++)
						echo "<td class=\"data", 2-($i%2), "\">{$vals[$i]['enumval']}</td></tr>\n";
				}
				echo "</table>\n";
			}

			$misc->printNavLinks(array ('showall' => array (
					'attr'=> array (
						'href' => array (
						'url' => 'types.php',
							'urlvars' => array (
								'server' => $_REQUEST['server'],
								'database' => $_REQUEST['database'],
								'schema' => $_REQUEST['schema'],
							)
						)
					),
					'content' => $lang['strshowalltypes']
				)), 'types-properties', get_defined_vars());
		} else
			doDefault($lang['strinvalidparam']);
	}
	
	/**
	 * Show confirmation of drop and perform actual drop
	 */
	function doDrop($confirm) {
		global $data, $misc;
		global $lang;

		if ($confirm) {
			$misc->printTrail('type');
			$misc->printTitle($lang['strdrop'], 'pg.type.drop');

			echo "<p>", sprintf($lang['strconfdroptype'], $misc->printVal($_REQUEST['type'])), "</p>\n";

			echo "<form action=\"types.php\" method=\"post\">\n";
			echo "<p><input type=\"checkbox\" id=\"cascade\" name=\"cascade\" /> <label for=\"cascade\">{$lang['strcascade']}</label></p>\n";
			echo "<p><input type=\"hidden\" name=\"action\" value=\"drop\" />\n";
			echo "<input type=\"hidden\" name=\"type\" value=\"", htmlspecialchars($_REQUEST['type']), "\" />\n";
			echo $misc->form;
			echo "<input type=\"submit\" name=\"drop\" value=\"{$lang['strdrop']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
			echo "</form>\n";
		}
		else {
			$status = $data->dropType($_POST['type'], isset($_POST['cascade']));
			if ($status == 0)
				doDefault($lang['strtypedropped']);
			else
				doDefault($lang['strtypedroppedbad']);
		}
		
	}

	/**
	 * Displays a screen where they can enter a new composite type
	 */
	function doCreateComposite($msg = '') {
		global $data, $misc;
		global $lang;

		if (!isset($_REQUEST['stage'])) $_REQUEST['stage'] = 1;
		if (!isset($_REQUEST['name'])) $_REQUEST['name'] = '';
		if (!isset($_REQUEST['fields'])) $_REQUEST['fields'] = '';
		if (!isset($_REQUEST['typcomment'])) $_REQUEST['typcomment'] = '';

		switch ($_REQUEST['stage']) {
			case 1:
				$misc->printTrail('type');
				$misc->printTitle($lang['strcreatecomptype'], 'pg.type.create');
				$misc->printMsg($msg);
				
				echo "<form action=\"types.php\" method=\"post\">\n";
				echo "<table>\n";
				echo "\t<tr>\n\t\t<th class=\"data left required\">{$lang['strname']}</th>\n";
				echo "\t\t<td class=\"data\"><input name=\"name\" size=\"32\" maxlength=\"{$data->_maxNameLen}\" value=\"", 
					htmlspecialchars($_REQUEST['name']), "\" /></td>\n\t</tr>\n";
				echo "\t<tr>\n\t\t<th class=\"data left required\">{$lang['strnumfields']}</th>\n";
				echo "\t\t<td class=\"data\"><input name=\"fields\" size=\"5\" maxlength=\"{$data->_maxNameLen}\" value=\"", 
					htmlspecialchars($_REQUEST['fields']), "\" /></td>\n\t</tr>\n";

				echo "\t<tr>\n\t\t<th class=\"data left\">{$lang['strcomment']}</th>\n";
				echo "\t\t<td><textarea name=\"typcomment\" rows=\"3\" cols=\"32\">", 
					htmlspecialchars($_REQUEST['typcomment']), "</textarea></td>\n\t</tr>\n";

				echo "</table>\n";
				echo "<p><input type=\"hidden\" name=\"action\" value=\"create_comp\" />\n";
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
					doCreateComposite($lang['strtypeneedsname']);
					return;
				}
				elseif ($fields == '' || !is_numeric($fields) || $fields != (int)$fields || $fields < 1)  {
					$_REQUEST['stage'] = 1;
					doCreateComposite($lang['strtypeneedscols']);
					return;
				}

				$types = $data->getTypes(true, false, true);

				$misc->printTrail('schema');
				$misc->printTitle($lang['strcreatecomptype'], 'pg.type.create');
				$misc->printMsg($msg);

				echo "<form action=\"types.php\" method=\"post\">\n";

				// Output table header
				echo "<table>\n";
				echo "\t<tr><th colspan=\"2\" class=\"data required\">{$lang['strfield']}</th><th colspan=\"2\" class=\"data required\">{$lang['strtype']}</th>";
				echo"<th class=\"data\">{$lang['strlength']}</th><th class=\"data\">{$lang['strcomment']}</th></tr>\n";
				
				for ($i = 0; $i < $_REQUEST['fields']; $i++) {
					if (!isset($_REQUEST['field'][$i])) $_REQUEST['field'][$i] = '';
					if (!isset($_REQUEST['length'][$i])) $_REQUEST['length'][$i] = '';
					if (!isset($_REQUEST['colcomment'][$i])) $_REQUEST['colcomment'][$i] = '';

					echo "\t<tr>\n\t\t<td>", $i + 1, ".&nbsp;</td>\n";
					echo "\t\t<td><input name=\"field[{$i}]\" size=\"16\" maxlength=\"{$data->_maxNameLen}\" value=\"",
						htmlspecialchars($_REQUEST['field'][$i]), "\" /></td>\n";
					echo "\t\t<td>\n\t\t\t<select name=\"type[{$i}]\">\n";
					$types->moveFirst();
					while (!$types->EOF) {
						$typname = $types->fields['typname'];
						echo "\t\t\t\t<option value=\"", htmlspecialchars($typname), "\"",
						(isset($_REQUEST['type'][$i]) && $typname == $_REQUEST['type'][$i]) ? ' selected="selected"' : '', ">",
							$misc->printVal($typname), "</option>\n";
						$types->moveNext();
					}
					echo "\t\t\t</select>\n\t\t</td>\n";
					
					// Output array type selector
					echo "\t\t<td>\n\t\t\t<select name=\"array[{$i}]\">\n";
					echo "\t\t\t\t<option value=\"\"", (isset($_REQUEST['array'][$i]) && $_REQUEST['array'][$i] == '') ? ' selected="selected"' : '', "></option>\n";
					echo "\t\t\t\t<option value=\"[]\"", (isset($_REQUEST['array'][$i]) && $_REQUEST['array'][$i] == '[]') ? ' selected="selected"' : '', ">[ ]</option>\n";
					echo "\t\t\t</select>\n\t\t</td>\n";
					
					echo "\t\t<td><input name=\"length[{$i}]\" size=\"10\" value=\"", 
						htmlspecialchars($_REQUEST['length'][$i]), "\" /></td>\n";
					echo "\t\t<td><input name=\"colcomment[{$i}]\" size=\"40\" value=\"", 
						htmlspecialchars($_REQUEST['colcomment'][$i]), "\" /></td>\n\t</tr>\n";
				}	
				echo "</table>\n";
				echo "<p><input type=\"hidden\" name=\"action\" value=\"create_comp\" />\n";
				echo "<input type=\"hidden\" name=\"stage\" value=\"3\" />\n";
				echo $misc->form;
				echo "<input type=\"hidden\" name=\"name\" value=\"", htmlspecialchars($_REQUEST['name']), "\" />\n";
				echo "<input type=\"hidden\" name=\"fields\" value=\"", htmlspecialchars($_REQUEST['fields']), "\" />\n";
				echo "<input type=\"hidden\" name=\"typcomment\" value=\"", htmlspecialchars($_REQUEST['typcomment']), "\" />\n";
				echo "<input type=\"submit\" value=\"{$lang['strcreate']}\" />\n";
				echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
				echo "</form>\n";
								
				break;
			case 3:
				global $data, $lang;

				// Check inputs
				$fields = trim($_REQUEST['fields']);
				if (trim($_REQUEST['name']) == '') {
					$_REQUEST['stage'] = 1;
					doCreateComposite($lang['strtypeneedsname']);
					return;
				}
				elseif ($fields == '' || !is_numeric($fields) || $fields != (int)$fields || $fields <= 0)  {
					$_REQUEST['stage'] = 1;
					doCreateComposite($lang['strtypeneedscols']);	
					return;
				}
				
				$status = $data->createCompositeType($_REQUEST['name'], $_REQUEST['fields'], $_REQUEST['field'],
								$_REQUEST['type'], $_REQUEST['array'], $_REQUEST['length'], $_REQUEST['colcomment'], 
								$_REQUEST['typcomment']);

				if ($status == 0)
					doDefault($lang['strtypecreated']);
				elseif ($status == -1) {
					$_REQUEST['stage'] = 2;
					doCreateComposite($lang['strtypeneedsfield']);
					return;
				}
				else {
					$_REQUEST['stage'] = 2;
					doCreateComposite($lang['strtypecreatedbad']);
					return;
				}
				break;
			default:
				echo "<p>{$lang['strinvalidparam']}</p>\n";
		}
	}

	/**
	 * Displays a screen where they can enter a new enum type
	 */
	function doCreateEnum($msg = '') {
		global $data, $misc;
		global $lang;

		if (!isset($_REQUEST['stage'])) $_REQUEST['stage'] = 1;
		if (!isset($_REQUEST['name'])) $_REQUEST['name'] = '';
		if (!isset($_REQUEST['values'])) $_REQUEST['values'] = '';
		if (!isset($_REQUEST['typcomment'])) $_REQUEST['typcomment'] = '';

		switch ($_REQUEST['stage']) {
			case 1:
				$misc->printTrail('type');
				$misc->printTitle($lang['strcreateenumtype'], 'pg.type.create');
				$misc->printMsg($msg);
				
				echo "<form action=\"types.php\" method=\"post\">\n";
				echo "<table>\n";
				echo "\t<tr>\n\t\t<th class=\"data left required\">{$lang['strname']}</th>\n";
				echo "\t\t<td class=\"data\"><input name=\"name\" size=\"32\" maxlength=\"{$data->_maxNameLen}\" value=\"", 
					htmlspecialchars($_REQUEST['name']), "\" /></td>\n\t</tr>\n";
				echo "\t<tr>\n\t\t<th class=\"data left required\">{$lang['strnumvalues']}</th>\n";
				echo "\t\t<td class=\"data\"><input name=\"values\" size=\"5\" maxlength=\"{$data->_maxNameLen}\" value=\"", 
					htmlspecialchars($_REQUEST['values']), "\" /></td>\n\t</tr>\n";

				echo "\t<tr>\n\t\t<th class=\"data left\">{$lang['strcomment']}</th>\n";
				echo "\t\t<td><textarea name=\"typcomment\" rows=\"3\" cols=\"32\">", 
					htmlspecialchars($_REQUEST['typcomment']), "</textarea></td>\n\t</tr>\n";

				echo "</table>\n";
				echo "<p><input type=\"hidden\" name=\"action\" value=\"create_enum\" />\n";
				echo "<input type=\"hidden\" name=\"stage\" value=\"2\" />\n";
				echo $misc->form;
				echo "<input type=\"submit\" value=\"{$lang['strnext']}\" />\n";
				echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
				echo "</form>\n";
				break;
			case 2:
				global $lang;

				// Check inputs
				$values = trim($_REQUEST['values']);
				if (trim($_REQUEST['name']) == '') {
					$_REQUEST['stage'] = 1;
					doCreateEnum($lang['strtypeneedsname']);
					return;
				}
				elseif ($values == '' || !is_numeric($values) || $values != (int)$values || $values < 1)  {
					$_REQUEST['stage'] = 1;
					doCreateEnum($lang['strtypeneedsvals']);
					return;
				}

				$misc->printTrail('schema');
				$misc->printTitle($lang['strcreateenumtype'], 'pg.type.create');
				$misc->printMsg($msg);

				echo "<form action=\"types.php\" method=\"post\">\n";

				// Output table header
				echo "<table>\n";
				echo "\t<tr><th colspan=\"2\" class=\"data required\">{$lang['strvalue']}</th></tr>\n";
				
				for ($i = 0; $i < $_REQUEST['values']; $i++) {
					if (!isset($_REQUEST['value'][$i])) $_REQUEST['value'][$i] = '';

					echo "\t<tr>\n\t\t<td>", $i + 1, ".&nbsp;</td>\n";
					echo "\t\t<td><input name=\"value[{$i}]\" size=\"16\" maxlength=\"{$data->_maxNameLen}\" value=\"",
						htmlspecialchars($_REQUEST['value'][$i]), "\" /></td>\n\t</tr>\n";
				}	
				echo "</table>\n";
				echo "<p><input type=\"hidden\" name=\"action\" value=\"create_enum\" />\n";
				echo "<input type=\"hidden\" name=\"stage\" value=\"3\" />\n";
				echo $misc->form;
				echo "<input type=\"hidden\" name=\"name\" value=\"", htmlspecialchars($_REQUEST['name']), "\" />\n";
				echo "<input type=\"hidden\" name=\"values\" value=\"", htmlspecialchars($_REQUEST['values']), "\" />\n";
				echo "<input type=\"hidden\" name=\"typcomment\" value=\"", htmlspecialchars($_REQUEST['typcomment']), "\" />\n";
				echo "<input type=\"submit\" value=\"{$lang['strcreate']}\" />\n";
				echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
				echo "</form>\n";

				break;
			case 3:
				global $data, $lang;

				// Check inputs
				$values = trim($_REQUEST['values']);
				if (trim($_REQUEST['name']) == '') {
					$_REQUEST['stage'] = 1;
					doCreateEnum($lang['strtypeneedsname']);
					return;
				}
				elseif ($values == '' || !is_numeric($values) || $values != (int)$values || $values <= 0)  {
					$_REQUEST['stage'] = 1;
					doCreateEnum($lang['strtypeneedsvals']);	
					return;
				}

				$status = $data->createEnumType($_REQUEST['name'], $_REQUEST['value'], $_REQUEST['typcomment']);

				if ($status == 0)
					doDefault($lang['strtypecreated']);
				elseif ($status == -1) {
					$_REQUEST['stage'] = 2;
					doCreateEnum($lang['strtypeneedsvalue']);
					return;
				}
				else {
					$_REQUEST['stage'] = 2;
					doCreateEnum($lang['strtypecreatedbad']);
					return;
				}
				break;
			default:
				echo "<p>{$lang['strinvalidparam']}</p>\n";
		}
	}

	/**
	 * Displays a screen where they can enter a new type
	 */
	function doCreate($msg = '') {
		global $data, $misc;
		global $lang;

		if (!isset($_POST['typname'])) $_POST['typname'] = '';
		if (!isset($_POST['typin'])) $_POST['typin'] = '';
		if (!isset($_POST['typout'])) $_POST['typout'] = '';
		if (!isset($_POST['typlen'])) $_POST['typlen'] = '';
		if (!isset($_POST['typdef'])) $_POST['typdef'] = '';
		if (!isset($_POST['typelem'])) $_POST['typelem'] = '';
		if (!isset($_POST['typdelim'])) $_POST['typdelim'] = '';
		if (!isset($_POST['typalign'])) $_POST['typalign'] = $data->typAlignDef;
		if (!isset($_POST['typstorage'])) $_POST['typstorage'] = $data->typStorageDef;

		// Retrieve all functions and types in the database
		$funcs = $data->getFunctions(true);
		$types = $data->getTypes(true);

		$misc->printTrail('schema');
		$misc->printTitle($lang['strcreatetype'], 'pg.type.create');
		$misc->printMsg($msg);

		echo "<form action=\"types.php\" method=\"post\">\n";
		echo "<table>\n";
		echo "<tr><th class=\"data left required\">{$lang['strname']}</th>\n";
		echo "<td class=\"data1\"><input name=\"typname\" size=\"32\" maxlength=\"{$data->_maxNameLen}\" value=\"",
			htmlspecialchars($_POST['typname']), "\" /></td></tr>\n";
		echo "<tr><th class=\"data left required\">{$lang['strinputfn']}</th>\n";
		echo "<td class=\"data1\"><select name=\"typin\">";
		while (!$funcs->EOF) {
			$proname = htmlspecialchars($funcs->fields['proname']);
			echo "<option value=\"{$proname}\"",
				($proname == $_POST['typin']) ? ' selected="selected"' : '', ">{$proname}</option>\n";
			$funcs->moveNext();
		}
		echo "</select></td></tr>\n";
		echo "<tr><th class=\"data left required\">{$lang['stroutputfn']}</th>\n";
		echo "<td class=\"data1\"><select name=\"typout\">";
		$funcs->moveFirst();
		while (!$funcs->EOF) {
			$proname = htmlspecialchars($funcs->fields['proname']);
			echo "<option value=\"{$proname}\"",
				($proname == $_POST['typout']) ? ' selected="selected"' : '', ">{$proname}</option>\n";
			$funcs->moveNext();
		}
		echo "</select></td></tr>\n";
		echo "<tr><th class=\"data left" . (version_compare($data->major_version, '7.4', '<') ? ' required' : '') . "\">{$lang['strlength']}</th>\n";
		echo "<td class=\"data1\"><input name=\"typlen\" size=\"8\" value=\"",
			htmlspecialchars($_POST['typlen']), "\" /></td></tr>";
		echo "<tr><th class=\"data left\">{$lang['strdefault']}</th>\n";
		echo "<td class=\"data1\"><input name=\"typdef\" size=\"8\" value=\"",
			htmlspecialchars($_POST['typdef']), "\" /></td></tr>";
		echo "<tr><th class=\"data left\">{$lang['strelement']}</th>\n";
		echo "<td class=\"data1\"><select name=\"typelem\">";
		echo "<option value=\"\"></option>\n";
		while (!$types->EOF) {
			$currname = htmlspecialchars($types->fields['typname']);
			echo "<option value=\"{$currname}\"",
				($currname == $_POST['typelem']) ? ' selected="selected"' : '', ">{$currname}</option>\n";
			$types->moveNext();
		}
		echo "</select></td></tr>\n";
		echo "<tr><th class=\"data left\">{$lang['strdelimiter']}</th>\n";
		echo "<td class=\"data1\"><input name=\"typdelim\" size=\"1\" maxlength=\"1\" value=\"",
			htmlspecialchars($_POST['typdelim']), "\" /></td></tr>";
		echo "<tr><th class=\"data left\"><label for=\"typbyval\">{$lang['strpassbyval']}</label></th>\n";
		echo "<td class=\"data1\"><input type=\"checkbox\" id=\"typbyval\" name=\"typbyval\"", 
			isset($_POST['typbyval']) ? ' checked="checked"' : '', " /></td></tr>";
		echo "<tr><th class=\"data left\">{$lang['stralignment']}</th>\n";
		echo "<td class=\"data1\"><select name=\"typalign\">";
		foreach ($data->typAligns as $v) {
			echo "<option value=\"{$v}\"",
				($v == $_POST['typalign']) ? ' selected="selected"' : '', ">{$v}</option>\n";
		}
		echo "</select></td></tr>\n";
		echo "<tr><th class=\"data left\">{$lang['strstorage']}</th>\n";
		echo "<td class=\"data1\"><select name=\"typstorage\">";
		foreach ($data->typStorages as $v) {
			echo "<option value=\"{$v}\"",
				($v == $_POST['typstorage']) ? ' selected="selected"' : '', ">{$v}</option>\n";
		}
		echo "</select></td></tr>\n";
		echo "</table>\n";
		echo "<p><input type=\"hidden\" name=\"action\" value=\"save_create\" />\n";
		echo $misc->form;
		echo "<input type=\"submit\" value=\"{$lang['strcreate']}\" />\n";
		echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
		echo "</form>\n";
	}
	
	/**
	 * Actually creates the new type in the database
	 */
	function doSaveCreate() {
		global $data;
		global $lang;

		// Check that they've given a name and a length.
		// Note: We're assuming they've given in and out functions here
		// which might be unwise...
		if ($_POST['typname'] == '') doCreate($lang['strtypeneedsname']);
		elseif ($_POST['typlen'] == '') doCreate($lang['strtypeneedslen']);
		else {		 
			$status = $data->createType(
				$_POST['typname'],
				$_POST['typin'],
				$_POST['typout'],
				$_POST['typlen'],
				$_POST['typdef'],
				$_POST['typelem'],
				$_POST['typdelim'],
				isset($_POST['typbyval']),
				$_POST['typalign'],
				$_POST['typstorage']
			);
			if ($status == 0)
				doDefault($lang['strtypecreated']);
			else
				doCreate($lang['strtypecreatedbad']);
		}
	}	

	/**
	 * Show default list of types in the database
	 */
	function doDefault($msg = '') {
		global $data, $conf, $misc;
		global $lang;

		$misc->printTrail('schema');
		$misc->printTabs('schema','types');
		$misc->printMsg($msg);
		
		$types = $data->getTypes();

		$columns = array(
			'type' => array(
				'title' => $lang['strtype'],
				'field' => field('typname'),
				'url'   => "types.php?action=properties&amp;{$misc->href}&amp;",
				'vars'  => array('type' => 'basename'),
			),
			'owner' => array(
				'title' => $lang['strowner'],
				'field' => field('typowner'),
			),
			'flavour' => array(
				'title' => $lang['strflavor'],
				'field' => field('typtype'),
				'type'  => 'verbatim',
				'params'=> array(
					'map' => array(
						'b' => $lang['strbasetype'],
						'c' => $lang['strcompositetype'],
						'd' => $lang['strdomain'],
						'p' => $lang['strpseudotype'],
						'e' => $lang['strenum'],
					),
					'align' => 'center',
				),
			),
			'actions' => array(
				'title' => $lang['stractions'],
			),
			'comment' => array(
				'title' => $lang['strcomment'],
				'field' => field('typcomment'),
			),
		);
		
		if (!isset($types->fields['typtype'])) unset($columns['flavour']);

		$actions = array(
			'drop' => array(
				'content' => $lang['strdrop'],
				'attr'=> array (
					'href' => array (
						'url' => 'types.php',
						'urlvars' => array (
							'action' => 'confirm_drop',
							'type' => field('basename')
						)
					)
				)
			),
		);
		
		$misc->printTable($types, $columns, $actions, 'types-types', $lang['strnotypes']);

		$navlinks = array (
			'create' => array (
				'attr'=> array (
					'href' => array (
						'url' => 'types.php',
						'urlvars' => array (
							'action' => 'create',
							'server' => $_REQUEST['server'],
							'database' => $_REQUEST['database'],
							'schema' => $_REQUEST['schema']
						)
					)
				),
				'content' => $lang['strcreatetype']
			),
			'createcomp' => array (
				'attr'=> array (
					'href' => array (
						'url' => 'types.php',
						'urlvars' => array (
							'action' => 'create_comp',
							'server' => $_REQUEST['server'],
							'database' => $_REQUEST['database'],
							'schema' => $_REQUEST['schema']
						)
					)
				),
				'content' => $lang['strcreatecomptype']
			),
			'createenum' => array (
				'attr'=> array (
					'href' => array(
						'url' => 'types.php',
						'urlvars' => array (
							'action' => 'create_enum',
							'server' => $_REQUEST['server'],
							'database' => $_REQUEST['database'],
							'schema' => $_REQUEST['schema']
						)
					)
				),
				'content' => $lang['strcreateenumtype']
			)
		);

		if (! $data->hasEnumTypes()) {
			unset($navlinks['enum']);
		}

		$misc->printNavLinks($navlinks, 'types-types', get_defined_vars());
	}
	
	/**
	 * Generate XML for the browser tree.
	 */
	function doTree() {
		global $misc, $data;
		
		$types = $data->getTypes();
		
		$reqvars = $misc->getRequestVars('type');
		
		$attrs = array(
			'text'   => field('typname'),
			'icon'   => 'Type',
			'toolTip'=> field('typcomment'),
			'action' => url('types.php',
							$reqvars,
							array(
								'action' => 'properties',
								'type'   => field('basename')
							)
						)
		);
		
		$misc->printTree($types, $attrs, 'types');
		exit;
	}
	
	if ($action == 'tree') doTree();
	
	$misc->printHeader($lang['strtypes']);
	$misc->printBody();

	switch ($action) {
		case 'create_comp':
			if (isset($_POST['cancel'])) doDefault();
			else doCreateComposite();
			break;
		case 'create_enum':
			if (isset($_POST['cancel'])) doDefault();
			else doCreateEnum();
			break;
		case 'save_create':
			if (isset($_POST['cancel'])) doDefault();
			else doSaveCreate();
			break;
		case 'create':
			doCreate();
			break;
		case 'drop':
			if (isset($_POST['cancel'])) doDefault();
			else doDrop(false);
			break;
		case 'confirm_drop':
			doDrop(true);
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
