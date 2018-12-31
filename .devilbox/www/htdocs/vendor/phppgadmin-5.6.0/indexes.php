<?php

	/**
	 * List indexes on a table
	 *
	 * $Id: indexes.php,v 1.46 2008/01/08 22:50:29 xzilla Exp $
	 */

	// Include application functions
	include_once('./libraries/lib.inc.php');
	include_once('./classes/class.select.php');
		
	$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';

	/**
	 * Show confirmation of cluster index and perform actual cluster
	 */
	function doClusterIndex($confirm) {
		global $data, $misc, $action;
		global $lang;

		if ($confirm) {
			// Default analyze to on
			$_REQUEST['analyze'] = true;
			
			$misc->printTrail('index');
			$misc->printTitle($lang['strclusterindex'],'pg.index.cluster');

			echo "<p>", sprintf($lang['strconfcluster'], $misc->printVal($_REQUEST['index'])), "</p>\n";

			echo "<form action=\"indexes.php\" method=\"post\">\n";
			echo "<p><input type=\"checkbox\" id=\"analyze\" name=\"analyze\"", (isset($_REQUEST['analyze']) ? ' checked="checked"' : ''), " /><label for=\"analyze\">{$lang['stranalyze']}</label></p>\n";
			echo "<input type=\"hidden\" name=\"action\" value=\"cluster_index\" />\n";
			echo "<input type=\"hidden\" name=\"table\" value=\"", htmlspecialchars($_REQUEST['table']), "\" />\n";
			echo "<input type=\"hidden\" name=\"index\" value=\"", htmlspecialchars($_REQUEST['index']), "\" />\n";
			echo $misc->form;
			echo "<input type=\"submit\" name=\"cluster\" value=\"{$lang['strclusterindex']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" />\n";
			echo "</form>\n";
		}
		else {
			$status = $data->clusterIndex($_POST['table'], $_POST['index']);
			if ($status == 0)
				if (isset($_POST['analyze'])){
					$status = $data->analyzeDB($_POST['table']);
					if ($status == 0)
						doDefault($lang['strclusteredgood'] . ' ' . $lang['stranalyzegood']);
					else
						doDefault($lang['stranalyzebad']);
				} else
					doDefault($lang['strclusteredgood']);
			else
				doDefault($lang['strclusteredbad']);
		}

	}

	function doReindex() {
		global $data, $lang;

		$status = $data->reindex('INDEX', $_REQUEST['index']);
		if ($status == 0)
			doDefault($lang['strreindexgood']);
		else
			doDefault($lang['strreindexbad']);
	}

	/**
	 * Displays a screen where they can enter a new index
	 */
	function doCreateIndex($msg = '') {
		global $data, $misc;
		global $lang;

		if (!isset($_POST['formIndexName'])) $_POST['formIndexName'] = '';
		if (!isset($_POST['formIndexType'])) $_POST['formIndexType'] = null;
		if (!isset($_POST['formCols'])) $_POST['formCols'] = '';
		if (!isset($_POST['formWhere'])) $_POST['formWhere'] = '';
		if (!isset($_POST['formSpc'])) $_POST['formSpc'] = '';

		$attrs = $data->getTableAttributes($_REQUEST['table']);
		// Fetch all tablespaces from the database
		if ($data->hasTablespaces()) $tablespaces = $data->getTablespaces();
		
		$misc->printTrail('table');
		$misc->printTitle($lang['strcreateindex'],'pg.index.create');
		$misc->printMsg($msg);

		$selColumns = new XHTML_select("TableColumnList",true,10);
		$selColumns->set_style("width: 10em;");

		if ($attrs->recordCount() > 0) {
			while (!$attrs->EOF) {
				$selColumns->add(new XHTML_Option($attrs->fields['attname']));
				$attrs->moveNext();
			}
		}

		$selIndex = new XHTML_select("IndexColumnList[]", true, 10);
		$selIndex->set_style("width: 10em;");
		$selIndex->set_attribute("id", "IndexColumnList");
		$buttonAdd    = new XHTML_Button("add", ">>");
		$buttonAdd->set_attribute("onclick", "buttonPressed(this);");
		$buttonAdd->set_attribute("type", "button");

		$buttonRemove = new XHTML_Button("remove", "<<");
		$buttonRemove->set_attribute("onclick", "buttonPressed(this);");		
		$buttonRemove->set_attribute("type", "button");

		echo "<form onsubmit=\"doSelectAll();\" name=\"formIndex\" action=\"indexes.php\" method=\"post\">\n";


		echo "<table>\n";
		echo "<tr><th class=\"data required\" colspan=\"3\">{$lang['strindexname']}</th></tr>";
		echo "<tr>";
		echo "<td class=\"data1\" colspan=\"3\"><input type=\"text\" name=\"formIndexName\" size=\"32\" maxlength=\"{$data->_maxNameLen}\" value=\"", 
			htmlspecialchars($_POST['formIndexName']), "\" /></td></tr>";
		echo "<tr><th class=\"data\">{$lang['strtablecolumnlist']}</th><th class=\"data\">&nbsp;</th>";
		echo "<th class=\"data required\">{$lang['strindexcolumnlist']}</th></tr>\n";
		echo "<tr><td class=\"data1\">" . $selColumns->fetch() . "</td>\n";
		echo "<td class=\"data1\">" . $buttonRemove->fetch() . $buttonAdd->fetch() . "</td>";
		echo "<td class=\"data1\">" . $selIndex->fetch() . "</td></tr>\n";
		echo "</table>\n";

		echo "<table> \n";
		echo "<tr>";
		echo "<th class=\"data left required\" scope=\"row\">{$lang['strindextype']}</th>";
		echo "<td class=\"data1\"><select name=\"formIndexType\">";
		foreach ($data->typIndexes as $v) {
			echo "<option value=\"", htmlspecialchars($v), "\"",
				($v == $_POST['formIndexType']) ? ' selected="selected"' : '', ">", htmlspecialchars($v), "</option>\n";
		}
		echo "</select></td></tr>\n";				
		echo "<tr>";
		echo "<th class=\"data left\" scope=\"row\"><label for=\"formUnique\">{$lang['strunique']}</label></th>";
		echo "<td class=\"data1\"><input type=\"checkbox\" id=\"formUnique\" name=\"formUnique\"", (isset($_POST['formUnique']) ? 'checked="checked"' : ''), " /></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<th class=\"data left\" scope=\"row\">{$lang['strwhere']}</th>";
		echo "<td class=\"data1\">(<input name=\"formWhere\" size=\"32\" maxlength=\"{$data->_maxNameLen}\" value=\"", 
			htmlspecialchars($_POST['formWhere']), "\" />)</td>";
		echo "</tr>";
		
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

		if ($data->hasConcurrentIndexBuild()) {
			echo "<tr>";
			echo "<th class=\"data left\" scope=\"row\"><label for=\"formConcur\">{$lang['strconcurrently']}</label></th>";
			echo "<td class=\"data1\"><input type=\"checkbox\" id=\"formConcur\" name=\"formConcur\"", (isset($_POST['formConcur']) ? 'checked="checked"' : ''), " /></td>";
			echo "</tr>";
		}	

		echo "</table>";

		echo "<p><input type=\"hidden\" name=\"action\" value=\"save_create_index\" />\n";
		echo $misc->form;
		echo "<input type=\"hidden\" name=\"table\" value=\"", htmlspecialchars($_REQUEST['table']), "\" />\n";
		echo "<input type=\"submit\" value=\"{$lang['strcreate']}\" />\n";
		echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
		echo "</form>\n";
	}

	/**
	 * Actually creates the new index in the database
	 * @@ Note: this function can't handle columns with commas in them
	 */
	function doSaveCreateIndex() {
		global $data;
		global $lang;
		
		// Handle databases that don't have partial indexes
		if (!isset($_POST['formWhere'])) $_POST['formWhere'] = '';
		// Default tablespace to null if it isn't set
		if (!isset($_POST['formSpc'])) $_POST['formSpc'] = null;
		
		// Check that they've given a name and at least one column
		if ($_POST['formIndexName'] == '') doCreateIndex($lang['strindexneedsname']);
		elseif (!isset($_POST['IndexColumnList']) || $_POST['IndexColumnList'] == '') doCreateIndex($lang['strindexneedscols']);
		else {
			$status = $data->createIndex($_POST['formIndexName'], $_POST['table'], $_POST['IndexColumnList'], 
				$_POST['formIndexType'], isset($_POST['formUnique']), $_POST['formWhere'], $_POST['formSpc'], 
				isset($_POST['formConcur']));
			if ($status == 0)
				doDefault($lang['strindexcreated']);
			else
				doCreateIndex($lang['strindexcreatedbad']);
		}
	}

	/**
	 * Show confirmation of drop index and perform actual drop
	 */
	function doDropIndex($confirm) {
		global $data, $misc;
		global $lang;

		if ($confirm) {
			$misc->printTrail('index');
			$misc->printTitle($lang['strdrop'],'pg.index.drop');

			echo "<p>", sprintf($lang['strconfdropindex'], $misc->printVal($_REQUEST['index'])), "</p>\n";

			echo "<form action=\"indexes.php\" method=\"post\">\n";
			echo "<input type=\"hidden\" name=\"action\" value=\"drop_index\" />\n";
			echo "<input type=\"hidden\" name=\"table\" value=\"", htmlspecialchars($_REQUEST['table']), "\" />\n";
			echo "<input type=\"hidden\" name=\"index\" value=\"", htmlspecialchars($_REQUEST['index']), "\" />\n";
			echo $misc->form;
			echo "<p><input type=\"checkbox\" id=\"cascade\" name=\"cascade\" /> <label for=\"cascade\">{$lang['strcascade']}</label></p>\n";
			echo "<input type=\"submit\" name=\"drop\" value=\"{$lang['strdrop']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" />\n";
			echo "</form>\n";
		}
		else {
			$status = $data->dropIndex($_POST['index'], isset($_POST['cascade']));
			if ($status == 0)
				doDefault($lang['strindexdropped']);
			else
				doDefault($lang['strindexdroppedbad']);
		}

	}

	function doDefault($msg = '') {
		global $data, $misc;
		global $lang;
		
		function indPre(&$rowdata, $actions) {
			global $data, $lang;
			
			if ($data->phpBool($rowdata->fields['indisprimary'])) {
				$rowdata->fields['+constraints'] = $lang['strprimarykey'];
				$actions['drop']['disable'] = true;
			}
			elseif ($data->phpBool($rowdata->fields['indisunique'])) {
				$rowdata->fields['+constraints'] = $lang['struniquekey'];
				$actions['drop']['disable'] = true;
			}
			else
				$rowdata->fields['+constraints'] = '';
			
			return $actions;
		}
		
		$misc->printTrail('table');
		$misc->printTabs('table','indexes');
		$misc->printMsg($msg);

		$indexes = $data->getIndexes($_REQUEST['table']);
		
		$columns = array(
			'index' => array(
				'title' => $lang['strname'],
				'field' => field('indname'),
			),
			'definition' => array(
				'title' => $lang['strdefinition'],
				'field' => field('inddef'),
			),
			'constraints' => array(
				'title' => $lang['strconstraints'],
				'field' => field('+constraints'),
				'type'  => 'verbatim',
				'params'=> array('align' => 'center'),
			),
			'clustered' => array(
				'title' => $lang['strclustered'],
				'field' => field('indisclustered'),
				'type'  => 'yesno',
			),
			'actions' => array(
				'title' => $lang['stractions'],
			),
			'comment' => array(
				'title' => $lang['strcomment'],
				'field' => field('idxcomment'),
			),
		);

		$actions = array(
			'cluster' => array(
				'content' => $lang['strclusterindex'],
				'attr'=> array (
					'href' => array (
						'url' => 'indexes.php',
						'urlvars' => array (
							'action' => 'confirm_cluster_index',
							'table' => $_REQUEST['table'],
							'index' => field('indname')
						)
					)
				)
			),
			'reindex' => array(
				'content' => $lang['strreindex'],
				'attr'=> array (
					'href' => array (
						'url' => 'indexes.php',
						'urlvars' => array (
							'action' => 'reindex',
							'table' => $_REQUEST['table'],
							'index' => field('indname')
						)
					)
				)
			),
			'drop' => array(
				'content' => $lang['strdrop'],
				'attr'=> array (
					'href' => array (
						'url' => 'indexes.php',
						'urlvars' => array (
							'action' => 'confirm_drop_index',
							'table' => $_REQUEST['table'],
							'index' => field('indname')
						)
					)
				)
			)
		);
		
		$misc->printTable($indexes, $columns, $actions, 'indexes-indexes', $lang['strnoindexes'], 'indPre');
		
		$misc->printNavLinks(array (
			'create' => array (
				'attr'=> array (
					'href' => array (
						'url' => 'indexes.php',
						'urlvars' => array (
							'action' => 'create_index',
							'server' => $_REQUEST['server'],
							'database' => $_REQUEST['database'],
							'schema' => $_REQUEST['schema'],
							'table' => $_REQUEST['table']
						)
					)
				),
				'content' => $lang['strcreateindex']
			)
		), 'indexes-indexes', get_defined_vars());
	}

	function doTree() {
		global $misc, $data;

		$indexes = $data->getIndexes($_REQUEST['table']);

		$reqvars = $misc->getRequestVars('table');

		function getIcon($f) {
			if ($f['indisprimary'] == 't')
				return 'PrimaryKey';
			if ($f['indisunique'] == 't')
				return 'UniqueConstraint';
			return 'Index';
		}

		$attrs = array(
			'text'   => field('indname'),
			'icon'   => callback('getIcon'),
		);

		$misc->printTree($indexes, $attrs, 'indexes');
		exit;
	}

	if ($action == 'tree') doTree();

	$misc->printHeader($lang['strindexes'], "<script src=\"indexes.js\" type=\"text/javascript\"></script>");

	if ($action == 'create_index' || $action == 'save_create_index')
		echo "<body onload=\"init();\">";
	else
		$misc->printBody();

	switch ($action) {
		case 'cluster_index':
			if (isset($_POST['cluster'])) doClusterIndex(false);
			else doDefault();
			break;
		case 'confirm_cluster_index':
			doClusterIndex(true);
			break;
		case 'reindex':
			doReindex();
			break;
		case 'save_create_index':
			if (isset($_POST['cancel'])) doDefault();
			else doSaveCreateIndex();
			break;
		case 'create_index':
			doCreateIndex();
			break;
		case 'drop_index':
			if (isset($_POST['drop'])) doDropIndex(false);
			else doDefault();
			break;
		case 'confirm_drop_index':
			doDropIndex(true);
			break;
		default:
			doDefault();
			break;
	}

	$misc->printFooter();

?>
