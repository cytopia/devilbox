<?php

	/**
	 * List extra information on a table
	 *
	 * $Id: info.php,v 1.14 2007/05/28 17:30:32 ioguix Exp $
	 */

	// Include application functions
	include_once('./libraries/lib.inc.php');

	$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';

	/**
	 * List all the information on the table
	 */
	function doDefault($msg = '') {
		global $data, $misc;
		global $lang;

		$misc->printTrail('table');
		$misc->printTabs('table','info');
		$misc->printMsg($msg);

		// common params for printVal
		$shownull = array('null' => true);

		// Fetch info
		$referrers = $data->getReferrers($_REQUEST['table']);
		$parents = $data->getTableParents($_REQUEST['table']);
		$children = $data->getTableChildren($_REQUEST['table']);
		$tablestatstups = $data->getStatsTableTuples($_REQUEST['table']);
		$tablestatsio = $data->getStatsTableIO($_REQUEST['table']);
		$indexstatstups = $data->getStatsIndexTuples($_REQUEST['table']);
		$indexstatsio = $data->getStatsIndexIO($_REQUEST['table']);
        
		// Check that there is some info
		if (($referrers === -99 || ($referrers !== -99 && $referrers->recordCount() == 0)) 
				&& $parents->recordCount() == 0 && $children->recordCount() == 0
				&& ($tablestatstups->recordCount() == 0 && $tablestatsio->recordCount() == 0
				&& $indexstatstups->recordCount() == 0 && $indexstatsio->recordCount() == 0)) {
			$misc->printMsg($lang['strnoinfo']);
		}
		else {
			// Referring foreign tables
			if ($referrers !== -99 && $referrers->recordCount() > 0) {
				echo "<h3>{$lang['strreferringtables']}</h3>\n";

				$columns = array (
					'schema' => array (
						'title' => $lang['strschema'],
						'field' => field('nspname')
					),
					'table' => array (
						'title' => $lang['strtable'],
						'field' => field('relname'),
					),
					'name' => array (
						'title' => $lang['strname'],
						'field' => field('conname'),
					),
					'definition' => array (
						'title' => $lang['strdefinition'],
						'field' => field('consrc'),
					),
					'actions' => array (
						'title' => $lang['stractions'],
					)
				);

				$actions = array (
					'properties' => array (
						'content' => $lang['strproperties'],
						'attr'=> array (
							'href' => array (
								'url' => 'constraints.php',
								'urlvars' => array (
									'schema' => field('nspname'),
									'table' => field('relname')
								)
							)
						)
					)
				);

				$misc->printTable($referrers, $columns, $actions, 'info-referrers', $lang['strnodata']);
			}
			
			// Parent tables
			if ($parents->recordCount() > 0) {
				echo "<h3>{$lang['strparenttables']}</h3>\n";

				$columns = array (
					'schema' => array (
						'title' => $lang['strschema'],
						'field' => field('nspname')
					),
					'table' => array (
						'title' => $lang['strtable'],
						'field' => field('relname'),
					),
						'actions' => array (
						'title' => $lang['stractions'],
					)
				);

				$actions = array (
					'properties' => array (
						'content' => $lang['strproperties'],
						'attr'=> array (
							'href' => array (
								'url' => 'tblproperties.php',
								'urlvars' => array (
									'schema' => field('nspname'),
									'table' => field('relname')
								)
							)
						)
					)
				);

				$misc->printTable($parents, $columns, $actions, 'info-parents', $lang['strnodata']);
			}
	
			// Child tables
			if ($children->recordCount() > 0) {
				echo "<h3>{$lang['strchildtables']}</h3>\n";

				$columns = array (
					'schema' => array (
						'title' => $lang['strschema'],
						'field' => field('nspname')
					),
					'table' => array (
						'title' => $lang['strtable'],
						'field' => field('relname'),
					),
					'actions' => array (
						'title' => $lang['stractions'],
					)
				);
				
				$actions = array (
					'properties' => array (
						'content' => $lang['strproperties'],
						'attr'=> array (
							'href' => array (
								'url' => 'tblproperties.php',
								'urlvars' => array (
									'schema' => field('nspname'),
									'table' => field('relname')
								)
							)
						)
					)
				);
				
				$misc->printTable($children, $columns, $actions, 'info-children', $lang['strnodata']);

			}

			// Row performance
			if ($tablestatstups->recordCount() > 0) {
				echo "<h3>{$lang['strrowperf']}</h3>\n";

				echo "<table>\n";
				echo "\t<tr>\n";
				echo "\t\t<th class=\"data\" colspan=\"2\">{$lang['strsequential']}</th>\n";
				echo "\t\t<th class=\"data\" colspan=\"2\">{$lang['strindex']}</th>\n";
				echo "\t\t<th class=\"data\" colspan=\"3\">{$lang['strrows2']}</th>\n";
				echo "\t</tr>\n";
				echo "\t<tr>\n";
				echo "\t\t<th class=\"data\">{$lang['strscan']}</th>\n";
				echo "\t\t<th class=\"data\">{$lang['strread']}</th>\n";
				echo "\t\t<th class=\"data\">{$lang['strscan']}</th>\n";
				echo "\t\t<th class=\"data\">{$lang['strfetch']}</th>\n";
				echo "\t\t<th class=\"data\">{$lang['strinsert']}</th>\n";
				echo "\t\t<th class=\"data\">{$lang['strupdate']}</th>\n";
				echo "\t\t<th class=\"data\">{$lang['strdelete']}</th>\n";
				echo "\t</tr>\n";
				$i = 0;
				
				while (!$tablestatstups->EOF) {
					$id = ( ($i % 2 ) == 0 ? '1' : '2' );
					echo "\t<tr class=\"data{$id}\">\n";
					echo "\t\t<td>", $misc->printVal($tablestatstups->fields['seq_scan'], 'int4', $shownull), "</td>\n";
					echo "\t\t<td>", $misc->printVal($tablestatstups->fields['seq_tup_read'], 'int4', $shownull), "</td>\n";
					echo "\t\t<td>", $misc->printVal($tablestatstups->fields['idx_scan'], 'int4', $shownull), "</td>\n";
					echo "\t\t<td>", $misc->printVal($tablestatstups->fields['idx_tup_fetch'], 'int4', $shownull), "</td>\n";
					echo "\t\t<td>", $misc->printVal($tablestatstups->fields['n_tup_ins'], 'int4', $shownull), "</td>\n";
					echo "\t\t<td>", $misc->printVal($tablestatstups->fields['n_tup_upd'], 'int4', $shownull), "</td>\n";
					echo "\t\t<td>", $misc->printVal($tablestatstups->fields['n_tup_del'], 'int4', $shownull), "</td>\n";
					echo "\t</tr>\n";
					$tablestatstups->movenext();
					$i++;
				}
	
				echo "</table>\n";
			}

			// I/O performance
			if ($tablestatsio->recordCount() > 0) {
				echo "<h3>{$lang['strioperf']}</h3>\n";

				echo "<table>\n";
				echo "\t<tr>\n";
				echo "\t\t<th class=\"data\" colspan=\"3\">{$lang['strheap']}</th>\n";
				echo "\t\t<th class=\"data\" colspan=\"3\">{$lang['strindex']}</th>\n";
				echo "\t\t<th class=\"data\" colspan=\"3\">{$lang['strtoast']}</th>\n";
				echo "\t\t<th class=\"data\" colspan=\"3\">{$lang['strtoastindex']}</th>\n";
				echo "\t</tr>\n";
				echo "\t<tr>\n";
				echo "\t\t<th class=\"data\">{$lang['strdisk']}</th>\n";
				echo "\t\t<th class=\"data\">{$lang['strcache']}</th>\n";
				echo "\t\t<th class=\"data\">{$lang['strpercent']}</th>\n";
				echo "\t\t<th class=\"data\">{$lang['strdisk']}</th>\n";
				echo "\t\t<th class=\"data\">{$lang['strcache']}</th>\n";
				echo "\t\t<th class=\"data\">{$lang['strpercent']}</th>\n";
				echo "\t\t<th class=\"data\">{$lang['strdisk']}</th>\n";
				echo "\t\t<th class=\"data\">{$lang['strcache']}</th>\n";
				echo "\t\t<th class=\"data\">{$lang['strpercent']}</th>\n";
				echo "\t\t<th class=\"data\">{$lang['strdisk']}</th>\n";
				echo "\t\t<th class=\"data\">{$lang['strcache']}</th>\n";
				echo "\t\t<th class=\"data\">{$lang['strpercent']}</th>\n";
				echo "\t</tr>\n";
				$i = 0;
				
				while (!$tablestatsio->EOF) {
					$id = ( ($i % 2 ) == 0 ? '1' : '2' );
					echo "\t<tr class=\"data{$id}\">\n";

					$total = $tablestatsio->fields['heap_blks_hit'] + $tablestatsio->fields['heap_blks_read'];
					if ($total > 0)	$percentage = round(($tablestatsio->fields['heap_blks_hit'] / $total) * 100);
					else $percentage = 0;
					echo "\t\t<td>", $misc->printVal($tablestatsio->fields['heap_blks_read'], 'int4', $shownull), "</td>\n";
					echo "\t\t<td>", $misc->printVal($tablestatsio->fields['heap_blks_hit'], 'int4', $shownull), "</td>\n";
					echo "\t\t<td>({$percentage}{$lang['strpercent']})</td>\n";

					$total = $tablestatsio->fields['idx_blks_hit'] + $tablestatsio->fields['idx_blks_read'];
					if ($total > 0)	$percentage = round(($tablestatsio->fields['idx_blks_hit'] / $total) * 100);
					else $percentage = 0;
					echo "\t\t<td>", $misc->printVal($tablestatsio->fields['idx_blks_read'], 'int4', $shownull), "</td>\n";
					echo "\t\t<td>", $misc->printVal($tablestatsio->fields['idx_blks_hit'], 'int4', $shownull), "</td>\n";
					echo "\t\t<td>({$percentage}{$lang['strpercent']})</td>\n";

					$total = $tablestatsio->fields['toast_blks_hit'] + $tablestatsio->fields['toast_blks_read'];
					if ($total > 0)	$percentage = round(($tablestatsio->fields['toast_blks_hit'] / $total) * 100);
					else $percentage = 0;
					echo "\t\t<td>", $misc->printVal($tablestatsio->fields['toast_blks_read'], 'int4', $shownull), "</td>\n";
					echo "\t\t<td>", $misc->printVal($tablestatsio->fields['toast_blks_hit'], 'int4', $shownull), "</td>\n";
					echo "\t\t<td>({$percentage}{$lang['strpercent']})</td>\n";

					$total = $tablestatsio->fields['tidx_blks_hit'] + $tablestatsio->fields['tidx_blks_read'];
					if ($total > 0)	$percentage = round(($tablestatsio->fields['tidx_blks_hit'] / $total) * 100);
					else $percentage = 0;
					echo "\t\t<td>", $misc->printVal($tablestatsio->fields['tidx_blks_read'], 'int4', $shownull), "</td>\n";
					echo "\t\t<td>", $misc->printVal($tablestatsio->fields['tidx_blks_hit'], 'int4', $shownull), "</td>\n";
					echo "\t\t<td>({$percentage}{$lang['strpercent']})</td>\n";
					echo "\t</tr>\n";
					$tablestatsio->movenext();
					$i++;
				}
	
				echo "</table>\n";
			}

			// Index row performance
			if ($indexstatstups->recordCount() > 0) {
				echo "<h3>{$lang['stridxrowperf']}</h3>\n";

				echo "<table>\n";
				echo "\t<tr>\n";
				echo "\t\t<th class=\"data\">{$lang['strindex']}</th>\n";
				echo "\t\t<th class=\"data\">{$lang['strscan']}</th>\n";
				echo "\t\t<th class=\"data\">{$lang['strread']}</th>\n";
				echo "\t\t<th class=\"data\">{$lang['strfetch']}</th>\n";
				echo "\t</tr>\n";
				$i = 0;
				
				while (!$indexstatstups->EOF) {
					$id = ( ($i % 2 ) == 0 ? '1' : '2' );
					echo "\t<tr class=\"data{$id}\">\n";
					echo "\t\t<td>", $misc->printVal($indexstatstups->fields['indexrelname']), "</td>\n";
					echo "\t\t<td>", $misc->printVal($indexstatstups->fields['idx_scan'], 'int4', $shownull), "</td>\n";
					echo "\t\t<td>", $misc->printVal($indexstatstups->fields['idx_tup_read'], 'int4', $shownull), "</td>\n";
					echo "\t\t<td>", $misc->printVal($indexstatstups->fields['idx_tup_fetch'], 'int4', $shownull), "</td>\n";
					echo "\t</tr>\n";
					$indexstatstups->movenext();
					$i++;
				}
	
				echo "</table>\n";
			}

			// Index I/0 performance
			if ($indexstatsio->recordCount() > 0) {
				echo "<h3>{$lang['stridxioperf']}</h3>\n";

				echo "<table>\n";
				echo "\t<tr>\n";
				echo "\t\t<th class=\"data\">{$lang['strindex']}</th>\n";
				echo "\t\t<th class=\"data\">{$lang['strdisk']}</th>\n";
				echo "\t\t<th class=\"data\">{$lang['strcache']}</th>\n";
				echo "\t\t<th class=\"data\">{$lang['strpercent']}</th>\n";
				echo "\t</tr>\n";
				$i = 0;
				
				while (!$indexstatsio->EOF) {
					$id = ( ($i % 2 ) == 0 ? '1' : '2' );
					echo "\t<tr class=\"data{$id}\">\n";
					$total = $indexstatsio->fields['idx_blks_hit'] + $indexstatsio->fields['idx_blks_read'];
					if ($total > 0)	$percentage = round(($indexstatsio->fields['idx_blks_hit'] / $total) * 100);
					else $percentage = 0;
					echo "\t\t<td>", $misc->printVal($indexstatsio->fields['indexrelname']), "</td>\n";
					echo "\t\t<td>", $misc->printVal($indexstatsio->fields['idx_blks_read'], 'int4', $shownull), "</td>\n";
					echo "\t\t<td>", $misc->printVal($indexstatsio->fields['idx_blks_hit'], 'int4', $shownull), "</td>\n";
					echo "\t\t<td>({$percentage}{$lang['strpercent']})</td>\n";
					echo "\t</tr>\n";
					$indexstatsio->movenext();
					$i++;
				}
	
				echo "</table>\n";
			}
		}
	}

	$misc->printHeader($lang['strtables'] . ' - ' . $_REQUEST['table'] . ' - ' . $lang['strinfo']);
	$misc->printBody();
	
	switch ($action) {
		default:
			doDefault();
			break;
	}
	
	$misc->printFooter();

?>
