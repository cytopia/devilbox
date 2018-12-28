<?php

	/**
	 * Does an import to a particular table from a text file
	 *
	 * $Id: dataimport.php,v 1.11 2007/01/22 16:33:01 soranzo Exp $
	 */

	// Prevent timeouts on large exports (non-safe mode only)
	if (!ini_get('safe_mode')) set_time_limit(0);

	// Include application functions
	include_once('./libraries/lib.inc.php');

	// Default state for XML parser
	$state = 'XML';
	$curr_col_name = null;
	$curr_col_val = null;
	$curr_col_null = false;
	$curr_row = array();

	/**
	 * Open tag handler for XML import feature
	 */
	function _startElement($parser, $name, $attrs) {
		global $data, $misc, $lang;
		global $state, $curr_row, $curr_col_name, $curr_col_val, $curr_col_null;

		switch ($name) {
			case 'DATA':
				if ($state != 'XML') {
					$data->rollbackTransaction();
					$misc->printMsg($lang['strimporterror']);
					exit;
				}
				$state = 'DATA';
				break;
			case 'HEADER':
				if ($state != 'DATA') {
					$data->rollbackTransaction();
					$misc->printMsg($lang['strimporterror']);
					exit;
				}
				$state = 'HEADER';
				break;
			case 'RECORDS':
				if ($state != 'READ_HEADER') {
					$data->rollbackTransaction();
					$misc->printMsg($lang['strimporterror']);
					exit;
				}
				$state = 'RECORDS';
				break;
			case 'ROW':
				if ($state != 'RECORDS') {
					$data->rollbackTransaction();
					$misc->printMsg($lang['strimporterror']);
					exit;
				}
				$state = 'ROW';
				$curr_row = array();
				break;
			case 'COLUMN':
				// We handle columns in rows
				if ($state == 'ROW') {
					$state = 'COLUMN';
					$curr_col_name = $attrs['NAME'];
					$curr_col_null = isset($attrs['NULL']);
				}
				// And we ignore columns in headers and fail in any other context				
				elseif ($state != 'HEADER') {
					$data->rollbackTransaction();
					$misc->printMsg($lang['strimporterror']);
					exit;
				}
				break;
			default:
				// An unrecognised tag means failure
				$data->rollbackTransaction();
				$misc->printMsg($lang['strimporterror']);
				exit;			
		}
	}
	
	/**
	 * Close tag handler for XML import feature
	 */
	function _endElement($parser, $name) {
		global $data, $misc, $lang;
		global $state, $curr_row, $curr_col_name, $curr_col_val, $curr_col_null;

		switch ($name) {
			case 'DATA':
				$state = 'READ_DATA';
				break;
			case 'HEADER':
				$state = 'READ_HEADER';
				break;
			case 'RECORDS':
				$state = 'READ_RECORDS';
				break;
			case 'ROW':
				// Build value map in order to insert row into table
				$fields = array();
				$vars = array();
				$nulls = array();
				$format = array();		
				$types = array();
				$i = 0;			
				foreach ($curr_row as $k => $v) {
					$fields[$i] = $k;
					// Check for nulls
					if ($v === null) $nulls[$i] = 'on';
					// Add to value array
					$vars[$i] = $v;
					// Format is always VALUE
					$format[$i] = 'VALUE';
					// Type is always text
					$types[$i] = 'text';
					$i++;
				}
				$status = $data->insertRow($_REQUEST['table'], $fields, $vars, $nulls, $format, $types);
				if ($status != 0) {
					$data->rollbackTransaction();
					$misc->printMsg($lang['strimporterror']);
					exit;
				}
				$curr_row = array();
				$state = 'RECORDS';
				break;
			case 'COLUMN':
				$curr_row[$curr_col_name] = ($curr_col_null ? null : $curr_col_val);
				$curr_col_name = null;
				$curr_col_val = null;
				$curr_col_null = false;
				$state = 'ROW';
				break;
			default:
				// An unrecognised tag means failure
				$data->rollbackTransaction();
				$misc->printMsg($lang['strimporterror']);
				exit;
		}
	}

	/**
	 * Character data handler for XML import feature
	 */
	function _charHandler($parser, $cdata) {
		global $data, $misc, $lang;
		global $state, $curr_col_val;

		if ($state == 'COLUMN') {
			$curr_col_val .= $cdata;
		} 
	}

	function loadNULLArray() {
		$array = array();
		if (isset($_POST['allowednulls'])) {
			foreach ($_POST['allowednulls'] as $null_char)
				$array[] = $null_char;
		}
		return $array;
	}

	function determineNull($field, $null_array) {
		return in_array($field, $null_array);
	}

	$misc->printHeader($lang['strimport']);
	$misc->printTrail('table');
	$misc->printTabs('table','import');

	// Check that file is specified and is an uploaded file
	if (isset($_FILES['source']) && is_uploaded_file($_FILES['source']['tmp_name']) && is_readable($_FILES['source']['tmp_name'])) {
		
		$fd = fopen($_FILES['source']['tmp_name'], 'r');
		// Check that file was opened successfully
		if ($fd !== false) {		
			$null_array = loadNULLArray();
			$status = $data->beginTransaction();
			if ($status != 0) {
				$misc->printMsg($lang['strimporterror']);
				exit;
			}

			// If format is set to 'auto', then determine format automatically from file name
			if ($_REQUEST['format'] == 'auto') {
				$extension = substr(strrchr($_FILES['source']['name'], '.'), 1);
				switch ($extension) {
					case 'csv':
						$_REQUEST['format'] = 'csv';
						break;
					case 'txt':
						$_REQUEST['format'] = 'tab';
						break;
					case 'xml':
						$_REQUEST['format'] = 'xml';
						break;
					default:
						$data->rollbackTransaction();
						$misc->printMsg($lang['strimporterror-fileformat']);
						exit;			
				}
			}

			// Do different import technique depending on file format
			switch ($_REQUEST['format']) {
				case 'csv':
				case 'tab':
					// XXX: Length of CSV lines limited to 100k
					$csv_max_line = 100000;
					// Set delimiter to tabs or commas
					if ($_REQUEST['format'] == 'csv') $csv_delimiter = ',';
					else $csv_delimiter = "\t";
					// Get first line of field names
					$fields = fgetcsv($fd, $csv_max_line, $csv_delimiter);
					$row = 2; //We start on the line AFTER the field names
					while ($line = fgetcsv($fd, $csv_max_line, $csv_delimiter)) {
						// Build value map
						$t_fields = array();
						$vars = array();
						$nulls = array();
						$format = array();
						$types = array();
						$i = 0;
						foreach ($fields as $f) {
							// Check that there is a column
							if (!isset($line[$i])) {
								$misc->printMsg(sprintf($lang['strimporterrorline-badcolumnnum'], $row));
								exit;
							}
							$t_fields[$i] = $f;
							
							// Check for nulls
							if (determineNull($line[$i], $null_array)) {
								$nulls[$i] = 'on';
							}
							// Add to value array
							$vars[$i] = $line[$i];
							// Format is always VALUE
							$format[$i] = 'VALUE';
							// Type is always text
							$types[$i] = 'text';
							$i++;
						}

						$status = $data->insertRow($_REQUEST['table'], $t_fields, $vars, $nulls, $format, $types);
						if ($status != 0) {
							$data->rollbackTransaction();
							$misc->printMsg(sprintf($lang['strimporterrorline'], $row));
							exit;
						}
						$row++;
					}
					break;
				case 'xml':
					$parser = xml_parser_create();
					xml_set_element_handler($parser, '_startElement', '_endElement');
					xml_set_character_data_handler($parser, '_charHandler');
					
					while (!feof($fd)) {
						$line = fgets($fd, 4096);
						xml_parse($parser, $line);
					}
					
					xml_parser_free($parser);
					break;
				default:
					// Unknown type
					$data->rollbackTransaction();
					$misc->printMsg($lang['strinvalidparam']);
					exit;
			}
	
			$status = $data->endTransaction();
			if ($status != 0) {
				$misc->printMsg($lang['strimporterror']);
				exit;
			}
			fclose($fd);

			$misc->printMsg($lang['strfileimported']);
		}
		else {
			// File could not be opened
			$misc->printMsg($lang['strimporterror']);
		}
	}
	else {
		// Upload went wrong
		$misc->printMsg($lang['strimporterror-uploadedfile']);
	}
	
	$misc->printFooter();

?>
