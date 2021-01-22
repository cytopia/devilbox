<?php

	/**
	 * Help page redirection/browsing.
	 *
	 * $Id: help.php,v 1.3 2006/12/31 16:21:26 soranzo Exp $
	 */

	 
	// Include application functions
	include_once('./libraries/lib.inc.php');
	
	$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';

	function doDefault() {
		global $data, $lang;
		
		if (isset($_REQUEST['help'])) {
			$url = $data->getHelp($_REQUEST['help']);
			
			if (is_array($url)) {
				doChoosePage($url);
				return;
			}
			
			if ($url) {
				header("Location: $url");
				exit;
			}
		}
		
		doBrowse($lang['strinvalidhelppage']);
	}
	
	function doBrowse($msg = '') {
		global $misc, $data, $lang;
		
		$misc->printHeader($lang['strhelppagebrowser']);
		$misc->printBody();
		
		$misc->printTitle($lang['strselecthelppage']);
		
		echo $misc->printMsg($msg);
		
		echo "<dl>\n";
		
		$pages = $data->getHelpPages();
		foreach ($pages as $page => $dummy) {
			echo "<dt>{$page}</dt>\n";
			
			$urls = $data->getHelp($page);
			if (!is_array($urls)) $urls = array($urls);
			foreach ($urls as $url) {
				echo "<dd><a href=\"{$url}\">{$url}</a></dd>\n";
			}
		}
		
		echo "</dl>\n";
		
		$misc->printFooter();
	}
	
	function doChoosePage($urls) {
		global $misc, $lang;
		
		$misc->printHeader($lang['strhelppagebrowser']);
		$misc->printBody();
		
		$misc->printTitle($lang['strselecthelppage']);
		
		echo "<ul>\n";
		foreach($urls as $url) {
			echo "<li><a href=\"{$url}\">{$url}</a></li>\n";
		}
		echo "</ul>\n";

		$misc->printFooter();
	}
	
	switch ($action) {
		case 'browse':
			doBrowse();
			break;
		default:
			doDefault();
			break;
	}
?>
