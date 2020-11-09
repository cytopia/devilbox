<?php

	/**
	 * Manage operators in a database
	 *
	 * $Id: operators.php,v 1.29 2007/08/31 18:30:11 ioguix Exp $
	 */

	// Include application functions
	include_once('./libraries/lib.inc.php');
	
	$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';
	if (!isset($msg)) $msg = '';

	/**
	 * Show read only properties for an operator
	 */
	function doProperties($msg = '') {
		global $data, $misc;
		global $lang;

		$misc->printTrail('operator');
		$misc->printTitle($lang['strproperties'],'pg.operator');
		$misc->printMsg($msg);
		
		$oprdata = $data->getOperator($_REQUEST['operator_oid']);
		$oprdata->fields['oprcanhash'] = $data->phpBool($oprdata->fields['oprcanhash']);

		if ($oprdata->recordCount() > 0) {
			echo "<table>\n";
			echo "<tr><th class=\"data left\">{$lang['strname']}</th>\n";
			echo "<td class=\"data1\">", $misc->printVal($oprdata->fields['oprname']), "</td></tr>\n";
			echo "<tr><th class=\"data left\">{$lang['strleftarg']}</th>\n";
			echo "<td class=\"data1\">", $misc->printVal($oprdata->fields['oprleftname']), "</td></tr>\n";
			echo "<tr><th class=\"data left\">{$lang['strrightarg']}</th>\n";
			echo "<td class=\"data1\">", $misc->printVal($oprdata->fields['oprrightname']), "</td></tr>\n";
			echo "<tr><th class=\"data left\">{$lang['strcommutator']}</th>\n";
			echo "<td class=\"data1\">", $misc->printVal($oprdata->fields['oprcom']), "</td></tr>\n";
			echo "<tr><th class=\"data left\">{$lang['strnegator']}</th>\n";
			echo "<td class=\"data1\">", $misc->printVal($oprdata->fields['oprnegate']), "</td></tr>\n";
			echo "<tr><th class=\"data left\">{$lang['strjoin']}</th>\n";
			echo "<td class=\"data1\">", $misc->printVal($oprdata->fields['oprjoin']), "</td></tr>\n";
			echo "<tr><th class=\"data left\">{$lang['strhashes']}</th>\n";
			echo "<td class=\"data1\">", ($oprdata->fields['oprcanhash']) ? $lang['stryes'] : $lang['strno'], "</td></tr>\n";

			/* these field only exists in 8.2 and before in pg_catalog */
			if (isset($oprdata->fields['oprlsortop'])) {
				echo "<tr><th class=\"data left\">{$lang['strmerges']}</th>\n";
				echo "<td class=\"data1\">", ($oprdata->fields['oprlsortop'] !== '0' && $oprdata->fields['oprrsortop'] !== '0') ? $lang['stryes'] : $lang['strno'], "</td></tr>\n";
				echo "<tr><th class=\"data left\">{$lang['strrestrict']}</th>\n";
				echo "<td class=\"data1\">", $misc->printVal($oprdata->fields['oprrest']), "</td></tr>\n";
				echo "<tr><th class=\"data left\">{$lang['strleftsort']}</th>\n";
				echo "<td class=\"data1\">", $misc->printVal($oprdata->fields['oprlsortop']), "</td></tr>\n";
				echo "<tr><th class=\"data left\">{$lang['strrightsort']}</th>\n";
				echo "<td class=\"data1\">", $misc->printVal($oprdata->fields['oprrsortop']), "</td></tr>\n";
				echo "<tr><th class=\"data left\">{$lang['strlessthan']}</th>\n";
				echo "<td class=\"data1\">", $misc->printVal($oprdata->fields['oprltcmpop']), "</td></tr>\n";
				echo "<tr><th class=\"data left\">{$lang['strgreaterthan']}</th>\n";
				echo "<td class=\"data1\">", $misc->printVal($oprdata->fields['oprgtcmpop']), "</td></tr>\n";
			}
			else {
				echo "<tr><th class=\"data left\">{$lang['strmerges']}</th>\n";
				echo "<td class=\"data1\">", $data->phpBool($oprdata->fields['oprcanmerge']) ? $lang['stryes'] : $lang['strno'], "</td></tr>\n";
			}
			echo "</table>\n";

			$misc->printNavLinks(array (
				'showall' => array (
					'attr'=> array (
						'href' => array (
							'url' => 'operators.php',
							'urlvars' => array (
								'server' => $_REQUEST['server'],
								'database' => $_REQUEST['database'],
								'schema' => $_REQUEST['schema']
							)
						)
					),
					'content' => $lang['strshowalloperators']
				)), 'operators-properties', get_defined_vars()
			);
		}
		else
			doDefault($lang['strinvalidparam']);
	}

	/**
	 * Show confirmation of drop and perform actual drop
	 */
	function doDrop($confirm) {
		global $data, $misc;
		global $lang;

		if ($confirm) {
			$misc->printTrail('operator');
			$misc->printTitle($lang['strdrop'], 'pg.operator.drop');
			
			echo "<p>", sprintf($lang['strconfdropoperator'], $misc->printVal($_REQUEST['operator'])), "</p>\n";	
			
			echo "<form action=\"operators.php\" method=\"post\">\n";
			echo "<p><input type=\"checkbox\" id=\"cascade\" name=\"cascade\" /> <label for=\"cascade\">{$lang['strcascade']}</label></p>\n";
			echo "<p><input type=\"hidden\" name=\"action\" value=\"drop\" />\n";
			echo "<input type=\"hidden\" name=\"operator\" value=\"", htmlspecialchars($_REQUEST['operator']), "\" />\n";
			echo "<input type=\"hidden\" name=\"operator_oid\" value=\"", htmlspecialchars($_REQUEST['operator_oid']), "\" />\n";
			echo $misc->form;
			echo "<input type=\"submit\" name=\"drop\" value=\"{$lang['strdrop']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
			echo "</form>\n";
		}
		else {
			$status = $data->dropOperator($_POST['operator_oid'], isset($_POST['cascade']));
			if ($status == 0)
				doDefault($lang['stroperatordropped']);
			else
				doDefault($lang['stroperatordroppedbad']);
		}
		
	}
	
	/**
	 * Show default list of operators in the database
	 */
	function doDefault($msg = '') {
		global $data, $conf, $misc;
		global $lang;
		
		$misc->printTrail('schema');
		$misc->printTabs('schema','operators');
		$misc->printMsg($msg);
		
		$operators = $data->getOperators();

		$columns = array(
			'operator' => array(
				'title' => $lang['stroperator'],
				'field' => field('oprname'),
				'url'   => "operators.php?action=properties&amp;{$misc->href}&amp;",
				'vars'  => array('operator' => 'oprname', 'operator_oid' => 'oid'),
			),
			'leftarg' => array(
				'title' => $lang['strleftarg'],
				'field' => field('oprleftname'),
			),
			'rightarg' => array(
				'title' => $lang['strrightarg'],
				'field' => field('oprrightname'),
			),
			'returns' => array(
				'title' => $lang['strreturns'],
				'field' => field('resultname'),
			),
			'actions' => array(
				'title' => $lang['stractions'],
			),
			'comment' => array(
				'title' => $lang['strcomment'],
				'field' => field('oprcomment'),
			),
		);

		$actions = array(
			'drop' => array(
				// 'title' => $lang['strdrop'],
				// 'url'   => "operators.php?action=confirm_drop&amp;{$misc->href}&amp;",
				// 'vars'  => array('operator' => 'oprname', 'operator_oid' => 'oid'),
				'content' => $lang['strdrop'],
				'attr'=> array (
					'href' => array (
						'url' => 'operators.php',
						'urlvars' => array (
							'action' => 'confirm_drop',
							'operator' => field('oprname'),
							'operator_oid' => field('oid')
						)
					)
				)
			)
		);
		
		$misc->printTable($operators, $columns, $actions, 'operators-operators', $lang['strnooperators']);
		
//		TODO operators.php action=create $lang['strcreateoperator']
	}

	/**
	 * Generate XML for the browser tree.
	 */
	function doTree() {
		global $misc, $data;
		
		$operators = $data->getOperators();
		
		// Operator prototype: "type operator type"
		$proto = concat(field('oprleftname'), ' ', field('oprname'), ' ', field('oprrightname'));
		
		$reqvars = $misc->getRequestVars('operator');
		
		$attrs = array(
			'text'   => $proto,
			'icon'   => 'Operator',
			'toolTip'=> field('oprcomment'),
			'action' => url('operators.php',
							$reqvars,
							array(
								'action'  => 'properties',
								'operator' => $proto,
								'operator_oid' => field('oid')
							)
						)
		);
		
		$misc->printTree($operators, $attrs, 'operators');
		exit;
	}
	
	if ($action == 'tree') doTree();
	
	$misc->printHeader($lang['stroperators']);
	$misc->printBody();

	switch ($action) {
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
