<?php

	/**
	 * List rules on a table OR view
	 *
	 * $Id: rules.php,v 1.33 2007/08/31 18:30:11 ioguix Exp $
	 */

	// Include application functions
	include_once('./libraries/lib.inc.php');

	$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';

	/**
	 * Confirm and then actually create a rule
	 */
	function createRule($confirm, $msg = '') {
		global $data, $misc;
		global $lang;

		if (!isset($_POST['name'])) $_POST['name'] = '';
		if (!isset($_POST['event'])) $_POST['event'] = '';
		if (!isset($_POST['where'])) $_POST['where'] = '';
		if (!isset($_POST['type'])) $_POST['type'] = 'SOMETHING';
		if (!isset($_POST['raction'])) $_POST['raction'] = '';

		if ($confirm) {
			$misc->printTrail($_REQUEST['subject']);
			$misc->printTitle($lang['strcreaterule'],'pg.rule.create');
			$misc->printMsg($msg);

			echo "<form action=\"rules.php\" method=\"post\">\n";
			echo "<table>\n";
			echo "<tr><th class=\"data left required\">{$lang['strname']}</th>\n";
			echo "<td class=\"data1\"><input name=\"name\" size=\"16\" maxlength=\"{$data->_maxNameLen}\" value=\"",
				htmlspecialchars($_POST['name']), "\" /></td></tr>\n";
			echo "<tr><th class=\"data left required\">{$lang['strevent']}</th>\n";
			echo "<td class=\"data1\"><select name=\"event\">\n";
			foreach ($data->rule_events as $v) {
				echo "<option value=\"{$v}\"", ($v == $_POST['event']) ? ' selected="selected"' : '',
				">{$v}</option>\n";
			}
			echo "</select></td></tr>\n";
			echo "<tr><th class=\"data left\">{$lang['strwhere']}</th>\n";
			echo "<td class=\"data1\"><input name=\"where\" size=\"32\" value=\"",
				htmlspecialchars($_POST['where']), "\" /></td></tr>\n";
			echo "<tr><th class=\"data left\"><label for=\"instead\">{$lang['strinstead']}</label></th>\n";
			echo "<td class=\"data1\">";
			echo "<input type=\"checkbox\" id=\"instead\" name=\"instead\" ", (isset($_POST['instead'])) ? ' checked="checked"' : '', " />\n";
			echo "</td></tr>\n";
			echo "<tr><th class=\"data left required\">{$lang['straction']}</th>\n";
			echo "<td class=\"data1\">";
			echo "<input type=\"radio\" id=\"type1\" name=\"type\" value=\"NOTHING\"", ($_POST['type'] == 'NOTHING') ? ' checked="checked"' : '', " /> <label for=\"type1\">NOTHING</label><br />\n";
			echo "<input type=\"radio\" name=\"type\" value=\"SOMETHING\"", ($_POST['type'] == 'SOMETHING') ? ' checked="checked"' : '', " />\n";
			echo "(<input name=\"raction\" size=\"32\" value=\"",
				htmlspecialchars($_POST['raction']), "\" />)</td></tr>\n";
			echo "</table>\n";

			echo "<input type=\"hidden\" name=\"action\" value=\"save_create_rule\" />\n";
			echo "<input type=\"hidden\" name=\"subject\" value=\"", htmlspecialchars($_REQUEST['subject']), "\" />\n";
			echo "<input type=\"hidden\" name=\"", htmlspecialchars($_REQUEST['subject']),
					"\" value=\"", htmlspecialchars($_REQUEST[$_REQUEST['subject']]), "\" />\n";
			echo $misc->form;
			echo "<p><input type=\"submit\" name=\"ok\" value=\"{$lang['strcreate']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
			echo "</form>\n";

		}
		else {
			if (trim($_POST['name']) == '')
				createRule(true, $lang['strruleneedsname']);
			else {
				$status = $data->createRule($_POST['name'],
					$_POST['event'], $_POST[$_POST['subject']], $_POST['where'],
					isset($_POST['instead']), $_POST['type'], $_POST['raction']);
				if ($status == 0)
					doDefault($lang['strrulecreated']);
				else
					createRule(true, $lang['strrulecreatedbad']);
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
			$misc->printTrail($_REQUEST['subject']);
			$misc->printTitle($lang['strdrop'],'pg.rule.drop');
			
			echo "<p>", sprintf($lang['strconfdroprule'], $misc->printVal($_REQUEST['rule']),
				$misc->printVal($_REQUEST[$_REQUEST['reltype']])), "</p>\n";

			echo "<form action=\"rules.php\" method=\"post\">\n";
			echo "<input type=\"hidden\" name=\"action\" value=\"drop\" />\n";
			echo "<input type=\"hidden\" name=\"subject\" value=\"", htmlspecialchars($_REQUEST['reltype']), "\" />\n";
			echo "<input type=\"hidden\" name=\"", htmlspecialchars($_REQUEST['reltype']),
					"\" value=\"", htmlspecialchars($_REQUEST[$_REQUEST['reltype']]), "\" />\n";
			echo "<input type=\"hidden\" name=\"rule\" value=\"", htmlspecialchars($_REQUEST['rule']), "\" />\n";
			echo $misc->form;
			echo "<p><input type=\"checkbox\" id=\"cascade\" name=\"cascade\" /> <label for=\"cascade\">{$lang['strcascade']}</label></p>\n";
			echo "<input type=\"submit\" name=\"yes\" value=\"{$lang['stryes']}\" />\n";
			echo "<input type=\"submit\" name=\"no\" value=\"{$lang['strno']}\" />\n";
			echo "</form>\n";
		}
		else {
			$status = $data->dropRule($_POST['rule'], $_POST[$_POST['subject']], isset($_POST['cascade']));
			if ($status == 0)
				doDefault($lang['strruledropped']);
			else
				doDefault($lang['strruledroppedbad']);
		}

	}

	/**
	 * List all the rules on the table
	 */
	function doDefault($msg = '') {
		global $data, $misc;
		global $lang;

		$misc->printTrail($_REQUEST['subject']);
		$misc->printTabs($_REQUEST['subject'], 'rules');
		$misc->printMsg($msg);

		$rules = $data->getRules($_REQUEST[$_REQUEST['subject']]);

		$columns = array(
			'rule' => array(
				'title' => $lang['strname'],
				'field' => field('rulename'),
			),
			'definition' => array(
				'title' => $lang['strdefinition'],
				'field' => field('definition'),
			),
			'actions' => array(
				'title' => $lang['stractions'],
			),
		);

		$subject = urlencode($_REQUEST['subject']);
		$object = urlencode($_REQUEST[$_REQUEST['subject']]);

		$actions = array(
			'drop' => array(
				'content' => $lang['strdrop'],
				'attr'=> array (
					'href' => array (
						'url' => 'rules.php',
						'urlvars' => array (
							'action' => 'confirm_drop',
							'reltype' => $subject,
							$subject => $object,
							'subject' => 'rule',
							'rule' => field('rulename')
						)
					)
				)
			),
		);

		$misc->printTable($rules, $columns, $actions, 'rules-rules', $lang['strnorules']);

		$misc->printNavLinks(array ('create' => array (
				'attr'=> array (
					'href' => array (
						'url' => 'rules.php',
						'urlvars' => array (
							'action' => 'create_rule',
							'server' => $_REQUEST['server'],
							'database' => $_REQUEST['database'],
							'schema' => $_REQUEST['schema'],
							$subject => $object,
							'subject' => $subject
						)
					)
				),
				'content' => $lang['strcreaterule']
			)), 'rules-rules', get_defined_vars());
	}

	function doTree() {
		global $misc, $data;

		$rules = $data->getRules($_REQUEST[$_REQUEST['subject']]);

		$reqvars = $misc->getRequestVars($_REQUEST['subject']);

		$attrs = array(
			'text'   => field('rulename'),
			'icon'   => 'Rule',
		);

		$misc->printTree($rules, $attrs, 'rules');
		exit;
	}

	if ($action == 'tree') doTree();
	
	// Different header if we're view rules or table rules
	$misc->printHeader($_REQUEST[$_REQUEST['subject']] . ' - ' . $lang['strrules']);
	$misc->printBody();

	switch ($action) {
		case 'create_rule':
			createRule(true);
			break;
		case 'save_create_rule':
			if (isset($_POST['cancel'])) doDefault();
			else createRule(false);
			break;
		case 'drop':
			if (isset($_POST['yes'])) doDrop(false);
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
