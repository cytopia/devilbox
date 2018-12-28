<?php

/*
 * Parent class of all ADODB objects.
 *
 * $Id: ADODB_base.php,v 1.24 2008/02/20 20:43:10 ioguix Exp $
 */

include_once('./libraries/errorhandler.inc.php');
include_once('./libraries/adodb/adodb.inc.php');

class ADODB_base {

	var $conn;
	
	// The backend platform.  Set to UNKNOWN by default.
	var $platform = 'UNKNOWN';

	/**
	 * Base constructor
	 * @param &$conn The connection object
	 */
	function ADODB_base(&$conn) {
		$this->conn = $conn;
	}

	/**
	 * Turns on or off query debugging
	 * @param $debug True to turn on debugging, false otherwise
	 */
	function setDebug($debug) {
		$this->conn->debug = $debug;
	}

	/**
	 * Cleans (escapes) a string
	 * @param $str The string to clean, by reference
	 * @return The cleaned string
	 */
	function clean(&$str) {
		$str = addslashes($str);
		return $str;
	}
	
	/**
	 * Cleans (escapes) an object name (eg. table, field)
	 * @param $str The string to clean, by reference
	 * @return The cleaned string
	 */
	function fieldClean(&$str) {
		$str = str_replace('"', '""', $str);
		return $str;
	}

	/**
	 * Cleans (escapes) an array
	 * @param $arr The array to clean, by reference
	 * @return The cleaned array
	 */
	function arrayClean(&$arr) {
		reset($arr);
		while(list($k, $v) = each($arr))
			$arr[$k] = addslashes($v);
		return $arr;
	}
	
	/**
	 * Executes a query on the underlying connection
	 * @param $sql The SQL query to execute
	 * @return A recordset
	 */
	function execute($sql) {
		// Execute the statement
		$rs = $this->conn->Execute($sql);

		// If failure, return error value
		return $this->conn->ErrorNo();
	}

	/**
	 * Closes the connection the database class
	 * relies on.
	 */
	function close() {
		$this->conn->close();
	}

	/**
	 * Retrieves a ResultSet from a query
	 * @param $sql The SQL statement to be executed
	 * @return A recordset
	 */
	function selectSet($sql) {
		// Execute the statement
		$rs = $this->conn->Execute($sql);
		
		if (!$rs) return $this->conn->ErrorNo();
 
 		return $rs;	
 	}
 	
	/**
	 * Retrieves a single value from a query
	 *
	 * @@ assumes that the query will return only one row - returns field value in the first row
	 *
	 * @param $sql The SQL statement to be executed
	 * @param $field The field name to be returned
	 * @return A single field value
	 * @return -1 No rows were found
	 */
	function selectField($sql, $field) {
		// Execute the statement
		$rs = $this->conn->Execute($sql);

		// If failure, or no rows returned, return error value
		if (!$rs) return $this->conn->ErrorNo();
		elseif ($rs->RecordCount() == 0) return -1;

		return $rs->fields[$field];
	}

	/**
	 * Delete from the database
	 * @param $table The name of the table
	 * @param $conditions (array) A map of field names to conditions
	 * @param $schema (optional) The table's schema
	 * @return 0 success
	 * @return -1 on referential integrity violation
	 * @return -2 on no rows deleted
	 */
	function delete($table, $conditions, $schema = '') {
		$this->fieldClean($table);

		reset($conditions);

		if (!empty($schema)) {
			$this->fieldClean($schema);
			$schema = "\"{$schema}\".";
		}

		// Build clause
		$sql = '';
		while(list($key, $value) = each($conditions)) {
			$this->clean($key);
			$this->clean($value);
			if ($sql) $sql .= " AND \"{$key}\"='{$value}'";
			else $sql = "DELETE FROM {$schema}\"{$table}\" WHERE \"{$key}\"='{$value}'";
		}

		// Check for failures
		if (!$this->conn->Execute($sql)) {
			// Check for referential integrity failure
			if (stristr($this->conn->ErrorMsg(), 'referential'))
				return -1;
		}

		// Check for no rows modified
		if ($this->conn->Affected_Rows() == 0) return -2;

		return $this->conn->ErrorNo();
	}

	/**
	 * Insert a set of values into the database
	 * @param $table The table to insert into
	 * @param $vars (array) A mapping of the field names to the values to be inserted
	 * @return 0 success
	 * @return -1 if a unique constraint is violated
	 * @return -2 if a referential constraint is violated
	 */
	function insert($table, $vars) {
		$this->fieldClean($table);

		// Build clause
		if (sizeof($vars) > 0) {
			$fields = '';
			$values = '';
			foreach($vars as $key => $value) {
				$this->clean($key);
				$this->clean($value);

				if ($fields) $fields .= ", \"{$key}\"";
				else $fields = "INSERT INTO \"{$table}\" (\"{$key}\"";

				if ($values) $values .= ", '{$value}'";
				else $values = ") VALUES ('{$value}'";
			}
			$sql = $fields . $values . ')';
		}

		// Check for failures
		if (!$this->conn->Execute($sql)) {
			// Check for unique constraint failure
			if (stristr($this->conn->ErrorMsg(), 'unique'))
				return -1;
			// Check for referential integrity failure
			elseif (stristr($this->conn->ErrorMsg(), 'referential'))
				return -2;
		}

		return $this->conn->ErrorNo();
	}

	/**
	 * Update a row in the database
	 * @param $table The table that is to be updated
	 * @param $vars (array) A mapping of the field names to the values to be updated
	 * @param $where (array) A mapping of field names to values for the where clause
	 * @param $nulls (array, optional) An array of fields to be set null
	 * @return 0 success
	 * @return -1 if a unique constraint is violated
	 * @return -2 if a referential constraint is violated
	 * @return -3 on no rows deleted
	 */
	function update($table, $vars, $where, $nulls = array()) {
		$this->fieldClean($table);

		$setClause = '';
		$whereClause = '';

		// Populate the syntax arrays
		reset($vars);
		while(list($key, $value) = each($vars)) {
			$this->fieldClean($key);
			$this->clean($value);
			if ($setClause) $setClause .= ", \"{$key}\"='{$value}'";
			else $setClause = "UPDATE \"{$table}\" SET \"{$key}\"='{$value}'";
		}

		reset($nulls);
		while(list(, $value) = each($nulls)) {
			$this->fieldClean($value);
			if ($setClause) $setClause .= ", \"{$value}\"=NULL";
			else $setClause = "UPDATE \"{$table}\" SET \"{$value}\"=NULL";
		}

		reset($where);
		while(list($key, $value) = each($where)) {
			$this->fieldClean($key);
			$this->clean($value);
			if ($whereClause) $whereClause .= " AND \"{$key}\"='{$value}'";
			else $whereClause = " WHERE \"{$key}\"='{$value}'";
		}

		// Check for failures
		if (!$this->conn->Execute($setClause . $whereClause)) {
			// Check for unique constraint failure
			if (stristr($this->conn->ErrorMsg(), 'unique'))
				return -1;
			// Check for referential integrity failure
			elseif (stristr($this->conn->ErrorMsg(), 'referential'))
				return -2;
		}

		// Check for no rows modified
		if ($this->conn->Affected_Rows() == 0) return -3;

		return $this->conn->ErrorNo();
	}

	/**
	 * Begin a transaction
	 * @return 0 success
	 */
	function beginTransaction() {
		return !$this->conn->BeginTrans();
	}

	/**
	 * End a transaction
	 * @return 0 success
	 */
	function endTransaction() {
		return !$this->conn->CommitTrans();
	}

	/**
	 * Roll back a transaction
	 * @return 0 success
	 */
	function rollbackTransaction() {
		return !$this->conn->RollbackTrans();
	}

	/**
	 * Get the backend platform
	 * @return The backend platform
	 */
	function getPlatform() {
		//return $this->conn->platform;
		return "UNKNOWN";
	}

	// Type conversion routines

	/**
	 * Change the value of a parameter to database representation depending on whether it evaluates to true or false
	 * @param $parameter the parameter
	 */
	function dbBool(&$parameter) {
		return $parameter;
	}

	/**
	 * Change a parameter from database representation to a boolean, (others evaluate to false)
	 * @param $parameter the parameter
	 */
	function phpBool($parameter) {
		return $parameter;
	}

	/**
	 * Change a db array into a PHP array
	 * @param $arr String representing the DB array
	 * @return A PHP array
	 */
	function phpArray($dbarr) {
		// Take off the first and last characters (the braces)
		$arr = substr($dbarr, 1, strlen($dbarr) - 2);

		// Pick out array entries by carefully parsing.  This is necessary in order
		// to cope with double quotes and commas, etc.
		$elements = array();
		$i = $j = 0;		
		$in_quotes = false;
		while ($i < strlen($arr)) {
			// If current char is a double quote and it's not escaped, then
			// enter quoted bit
			$char = substr($arr, $i, 1);
			if ($char == '"' && ($i == 0 || substr($arr, $i - 1, 1) != '\\')) 
				$in_quotes = !$in_quotes;
			elseif ($char == ',' && !$in_quotes) {
				// Add text so far to the array
				$elements[] = substr($arr, $j, $i - $j);
				$j = $i + 1;
			}
			$i++;
		}
		// Add final text to the array
		$elements[] = substr($arr, $j);

		// Do one further loop over the elements array to remote double quoting
		// and escaping of double quotes and backslashes
		for ($i = 0; $i < sizeof($elements); $i++) {
			$v = $elements[$i];
			if (strpos($v, '"') === 0) {
				$v = substr($v, 1, strlen($v) - 2);
				$v = str_replace('\\"', '"', $v);
				$v = str_replace('\\\\', '\\', $v);
				$elements[$i] = $v;
			}
		}

		return $elements;
	}
}

?>
