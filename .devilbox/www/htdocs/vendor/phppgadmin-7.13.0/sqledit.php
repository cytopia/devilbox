<?php

	/**
	 * Alternative SQL editing window
	 *
	 * $Id: sqledit.php,v 1.40 2008/01/10 19:37:07 xzilla Exp $
	 */

	// Include application functions
	include_once('./libraries/lib.inc.php');

	$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';
	if (!isset($msg)) $msg = '';

	/**
	 * Private function to display server and list of databases
	 */
	function _printConnection() {
		global $data, $action, $misc;
		
		// The javascript action on the select box reloads the
		// popup whenever the server or database is changed.
		// This ensures that the correct page encoding is used.
		$onchange = "onchange=\"location.href='sqledit.php?action=" . 
				urlencode($action) . "&amp;server=' + encodeURI(server.options[server.selectedIndex].value) + '&amp;database=' + encodeURI(database.options[database.selectedIndex].value) + ";
		
		// The exact URL to reload to is different between SQL and Find mode, however.
		if ($action == 'find') {
			$onchange .= "'&amp;term=' + encodeURI(term.value) + '&amp;filter=' + encodeURI(filter.value) + '&amp;'\"";
		} else {
			$onchange .= "'&amp;query=' + encodeURI(query.value) + '&amp;search_path=' + encodeURI(search_path.value) + (paginate.checked ? '&amp;paginate=on' : '')  + '&amp;'\"";
		}
		
		$misc->printConnection($onchange);
	}
	
	/**
	 * Searches for a named database object
	 */
	function doFind() {
		global $data, $misc;
		global $lang, $conf;
		
		if (!isset($_REQUEST['term'])) $_REQUEST['term'] = '';
		if (!isset($_REQUEST['filter'])) $_REQUEST['filter'] = '';
		
		$misc->printHeader($lang['strfind']);
		
		// Bring to the front always
		echo "<body onload=\"window.focus();\">\n";
		
		$misc->printTabs($misc->getNavTabs('popup'), 'find');
		
		echo "<form action=\"database.php\" method=\"post\" target=\"detail\">\n";
		_printConnection();
		echo "<p><input name=\"term\" value=\"", htmlspecialchars($_REQUEST['term']), 
			"\" size=\"32\" maxlength=\"{$data->_maxNameLen}\" />\n";
			
		// Output list of filters.  This is complex due to all the 'has' and 'conf' feature possibilities
		echo "<select name=\"filter\">\n";
		echo "\t<option value=\"\"", ($_REQUEST['filter'] == '') ? ' selected="selected"' : '', ">{$lang['strallobjects']}</option>\n";
		echo "\t<option value=\"SCHEMA\"", ($_REQUEST['filter'] == 'SCHEMA') ? ' selected="selected"' : '', ">{$lang['strschemas']}</option>\n";
		echo "\t<option value=\"TABLE\"", ($_REQUEST['filter'] == 'TABLE') ? ' selected="selected"' : '', ">{$lang['strtables']}</option>\n";
		echo "\t<option value=\"VIEW\"", ($_REQUEST['filter'] == 'VIEW') ? ' selected="selected"' : '', ">{$lang['strviews']}</option>\n";
		echo "\t<option value=\"SEQUENCE\"", ($_REQUEST['filter'] == 'SEQUENCE') ? ' selected="selected"' : '', ">{$lang['strsequences']}</option>\n";
		echo "\t<option value=\"COLUMN\"", ($_REQUEST['filter'] == 'COLUMN') ? ' selected="selected"' : '', ">{$lang['strcolumns']}</option>\n";
		echo "\t<option value=\"RULE\"", ($_REQUEST['filter'] == 'RULE') ? ' selected="selected"' : '', ">{$lang['strrules']}</option>\n";
		echo "\t<option value=\"INDEX\"", ($_REQUEST['filter'] == 'INDEX') ? ' selected="selected"' : '', ">{$lang['strindexes']}</option>\n";
		echo "\t<option value=\"TRIGGER\"", ($_REQUEST['filter'] == 'TRIGGER') ? ' selected="selected"' : '', ">{$lang['strtriggers']}</option>\n";
		echo "\t<option value=\"CONSTRAINT\"", ($_REQUEST['filter'] == 'CONSTRAINT') ? ' selected="selected"' : '', ">{$lang['strconstraints']}</option>\n";
		echo "\t<option value=\"FUNCTION\"", ($_REQUEST['filter'] == 'FUNCTION') ? ' selected="selected"' : '', ">{$lang['strfunctions']}</option>\n";
		echo "\t<option value=\"DOMAIN\"", ($_REQUEST['filter'] == 'DOMAIN') ? ' selected="selected"' : '', ">{$lang['strdomains']}</option>\n";
		if ($conf['show_advanced']) {
			echo "\t<option value=\"AGGREGATE\"", ($_REQUEST['filter'] == 'AGGREGATE') ? ' selected="selected"' : '', ">{$lang['straggregates']}</option>\n";
			echo "\t<option value=\"TYPE\"", ($_REQUEST['filter'] == 'TYPE') ? ' selected="selected"' : '', ">{$lang['strtypes']}</option>\n";
			echo "\t<option value=\"OPERATOR\"", ($_REQUEST['filter'] == 'OPERATOR') ? ' selected="selected"' : '', ">{$lang['stroperators']}</option>\n";
			echo "\t<option value=\"OPCLASS\"", ($_REQUEST['filter'] == 'OPCLASS') ? ' selected="selected"' : '', ">{$lang['stropclasses']}</option>\n";
			echo "\t<option value=\"CONVERSION\"", ($_REQUEST['filter'] == 'CONVERSION') ? ' selected="selected"' : '', ">{$lang['strconversions']}</option>\n";
			echo "\t<option value=\"LANGUAGE\"", ($_REQUEST['filter'] == 'LANGUAGE') ? ' selected="selected"' : '', ">{$lang['strlanguages']}</option>\n";
		}
		echo "</select>\n";
					
		echo "<input type=\"submit\" value=\"{$lang['strfind']}\" />\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"find\" /></p>\n";
		echo "</form>\n";

		// Default focus
		$misc->setFocus('forms[0].term');
	}

	/**
	 * Allow execution of arbitrary SQL statements on a database
	 */
	function doDefault() {
		global $data, $misc;
		global $lang; 
		
		if (!isset($_SESSION['sqlquery'])) $_SESSION['sqlquery'] = '';
		
		$misc->printHeader($lang['strsql']);
		
		// Bring to the front always
		echo "<body onload=\"window.focus();\">\n";
		
		$misc->printTabs($misc->getNavTabs('popup'), 'sql');
		
		echo "<form action=\"sql.php\" method=\"post\" enctype=\"multipart/form-data\" target=\"detail\">\n";
		_printConnection();
		echo "\n";
		if (!isset($_REQUEST['search_path']))
			$_REQUEST['search_path'] = implode(',',$data->getSearchPath());
		
		echo "<p><label>";
		$misc->printHelp($lang['strsearchpath'], 'pg.schema.search_path');
		echo ": <input type=\"text\" name=\"search_path\" size=\"50\" value=\"",
			htmlspecialchars($_REQUEST['search_path']), "\" /></label></p>\n";
		
		echo "<textarea style=\"width:98%;\" rows=\"10\" cols=\"50\" name=\"query\">",
			htmlspecialchars($_SESSION['sqlquery']), "</textarea>\n";

		// Check that file uploads are enabled
		if (ini_get('file_uploads')) {
			// Don't show upload option if max size of uploads is zero
			$max_size = $misc->inisizeToBytes(ini_get('upload_max_filesize'));
			if (is_double($max_size) && $max_size > 0) {
				echo "<p><input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"{$max_size}\" />\n";
				echo "<label for=\"script\">{$lang['struploadscript']}</label> <input id=\"script\" name=\"script\" type=\"file\" /></p>\n";
			}
		}

		echo "<p><label for=\"paginate\"><input type=\"checkbox\" id=\"paginate\" name=\"paginate\"", (isset($_REQUEST['paginate']) ? ' checked="checked"' : ''), " />&nbsp;{$lang['strpaginate']}</label></p>\n";
		
		echo "<p><input type=\"submit\" name=\"execute\" accesskey=\"r\" value=\"{$lang['strexecute']}\" />\n";
		echo "<input type=\"reset\" accesskey=\"q\" value=\"{$lang['strreset']}\" /></p>\n";
		echo "</form>\n";
		
		// Default focus
		$misc->setFocus('forms[0].query');
	}

	switch ($action) {
		case 'find':
			doFind();
			break;
		case 'sql':
		default:
			doDefault();
			break;
	}
	
	// Set the name of the window
	$misc->setWindowName('sqledit');
	
	$misc->printFooter();
	
?>
