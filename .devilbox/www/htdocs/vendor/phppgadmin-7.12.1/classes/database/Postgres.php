<?php

/**
 * A Class that implements the DB Interface for Postgres
 * Note: This Class uses ADODB and returns RecordSets.
 *
 * $Id: Postgres.php,v 1.320 2008/02/20 20:43:09 ioguix Exp $
 */

include_once('./classes/database/ADODB_base.php');

class Postgres extends ADODB_base {

	var $major_version = 12;
	// Max object name length
	var $_maxNameLen = 63;
	// Store the current schema
	var $_schema;
	// Map of database encoding names to HTTP encoding names.  If a
	// database encoding does not appear in this list, then its HTTP
	// encoding name is the same as its database encoding name.
	var $codemap = array(
		'BIG5' => 'BIG5',
		'EUC_CN' => 'GB2312',
		'EUC_JP' => 'EUC-JP',
		'EUC_KR' => 'EUC-KR',
		'EUC_TW' => 'EUC-TW',
		'GB18030' => 'GB18030',
		'GBK' => 'GB2312',
		'ISO_8859_5' => 'ISO-8859-5',
		'ISO_8859_6' => 'ISO-8859-6',
		'ISO_8859_7' => 'ISO-8859-7',
		'ISO_8859_8' => 'ISO-8859-8',
		'JOHAB' => 'CP1361',
		'KOI8' => 'KOI8-R',
		'LATIN1' => 'ISO-8859-1',
		'LATIN2' => 'ISO-8859-2',
		'LATIN3' => 'ISO-8859-3',
		'LATIN4' => 'ISO-8859-4',
		'LATIN5' => 'ISO-8859-9',
		'LATIN6' => 'ISO-8859-10',
		'LATIN7' => 'ISO-8859-13',
		'LATIN8' => 'ISO-8859-14',
		'LATIN9' => 'ISO-8859-15',
		'LATIN10' => 'ISO-8859-16',
		'SJIS' => 'SHIFT_JIS',
		'SQL_ASCII' => 'US-ASCII',
		'UHC' => 'WIN949',
		'UTF8' => 'UTF-8',
		'WIN866' => 'CP866',
		'WIN874' => 'CP874',
		'WIN1250' => 'CP1250',
		'WIN1251' => 'CP1251',
		'WIN1252' => 'CP1252',
		'WIN1256' => 'CP1256',
		'WIN1258' => 'CP1258'
	);
	var $defaultprops = array('', '', '');
	// Extra "magic" types.  BIGSERIAL was added in PostgreSQL 7.2.
	var $extraTypes = array('SERIAL', 'BIGSERIAL');
	// Foreign key stuff.  First element MUST be the default.
	var $fkactions = array('NO ACTION', 'RESTRICT', 'CASCADE', 'SET NULL', 'SET DEFAULT');
	var $fkdeferrable = array('NOT DEFERRABLE', 'DEFERRABLE');
	var $fkinitial = array('INITIALLY IMMEDIATE', 'INITIALLY DEFERRED');
	var $fkmatches = array('MATCH SIMPLE', 'MATCH FULL');
	// Function properties
	var $funcprops = array( array('', 'VOLATILE', 'IMMUTABLE', 'STABLE'),
							array('', 'CALLED ON NULL INPUT', 'RETURNS NULL ON NULL INPUT'),
							array('', 'SECURITY INVOKER', 'SECURITY DEFINER'));
	// Default help URL
	var $help_base;
	// Help sub pages
	var $help_page;
	// Name of id column
	var $id = 'oid';
	// Supported join operations for use with view wizard
	var $joinOps = array('INNER JOIN' => 'INNER JOIN', 'LEFT JOIN' => 'LEFT JOIN', 'RIGHT JOIN' => 'RIGHT JOIN', 'FULL JOIN' => 'FULL JOIN');
	// Map of internal language name to syntax highlighting name
	var $langmap = array(
		'sql' => 'SQL',
		'plpgsql' => 'SQL',
		'php' => 'PHP',
		'phpu' => 'PHP',
		'plphp' => 'PHP',
		'plphpu' => 'PHP',
		'perl' => 'Perl',
		'perlu' => 'Perl',
		'plperl' => 'Perl',
		'plperlu' => 'Perl',
		'java' => 'Java',
		'javau' => 'Java',
		'pljava' => 'Java',
		'pljavau' => 'Java',
		'plj' => 'Java',
		'plju' => 'Java',
		'python' => 'Python',
		'pythonu' => 'Python',
		'plpython' => 'Python',
		'plpythonu' => 'Python',
		'ruby' => 'Ruby',
		'rubyu' => 'Ruby',
		'plruby' => 'Ruby',
		'plrubyu' => 'Ruby'
	);
	// Predefined size types
	var $predefined_size_types = array('abstime','aclitem','bigserial','boolean','bytea','cid','cidr','circle','date','float4','float8','gtsvector','inet','int2','int4','int8','macaddr','money','oid','path','polygon','refcursor','regclass','regoper','regoperator','regproc','regprocedure','regtype','reltime','serial','smgr','text','tid','tinterval','tsquery','tsvector','varbit','void','xid');
	// List of all legal privileges that can be applied to different types
	// of objects.
	var $privlist = array(
  		'table' => array('SELECT', 'INSERT', 'UPDATE', 'DELETE', 'REFERENCES', 'TRIGGER', 'ALL PRIVILEGES'),
  		'view' => array('SELECT', 'INSERT', 'UPDATE', 'DELETE', 'REFERENCES', 'TRIGGER', 'ALL PRIVILEGES'),
  		'sequence' => array('USAGE', 'SELECT', 'UPDATE', 'ALL PRIVILEGES'),
  		'database' => array('CREATE', 'TEMPORARY', 'CONNECT', 'ALL PRIVILEGES'),
  		'function' => array('EXECUTE', 'ALL PRIVILEGES'),
  		'language' => array('USAGE', 'ALL PRIVILEGES'),
  		'schema' => array('CREATE', 'USAGE', 'ALL PRIVILEGES'),
  		'tablespace' => array('CREATE', 'ALL PRIVILEGES'),
		'column' => array('SELECT', 'INSERT', 'UPDATE', 'REFERENCES','ALL PRIVILEGES')
	);
	// List of characters in acl lists and the privileges they
	// refer to.
	var $privmap = array(
		'r' => 'SELECT',
		'w' => 'UPDATE',
		'a' => 'INSERT',
  		'd' => 'DELETE',
		'D' => 'TRUNCATE',
  		'R' => 'RULE',
  		'x' => 'REFERENCES',
  		't' => 'TRIGGER',
  		'X' => 'EXECUTE',
  		'U' => 'USAGE',
  		'C' => 'CREATE',
  		'T' => 'TEMPORARY',
  		'c' => 'CONNECT'
	);
	// Rule action types
	var $rule_events = array('SELECT', 'INSERT', 'UPDATE', 'DELETE');
	// Select operators
	var $selectOps = array('=' => 'i', '!=' => 'i', '<' => 'i', '>' => 'i', '<=' => 'i', '>=' => 'i',
		'<<' => 'i', '>>' => 'i', '<<=' => 'i', '>>=' => 'i',
		'LIKE' => 'i', 'NOT LIKE' => 'i', 'ILIKE' => 'i', 'NOT ILIKE' => 'i', 'SIMILAR TO' => 'i',
		'NOT SIMILAR TO' => 'i', '~' => 'i', '!~' => 'i', '~*' => 'i', '!~*' => 'i',
		'IS NULL' => 'p', 'IS NOT NULL' => 'p', 'IN' => 'x', 'NOT IN' => 'x',
		'@@' => 'i', '@@@' => 'i', '@>' => 'i', '<@' => 'i',
		'@@ to_tsquery' => 't', '@@@ to_tsquery' => 't', '@> to_tsquery' => 't', '<@ to_tsquery' => 't',
		'@@ plainto_tsquery' => 't', '@@@ plainto_tsquery' => 't', '@> plainto_tsquery' => 't', '<@ plainto_tsquery' => 't');
	// Array of allowed trigger events
	var $triggerEvents= array('INSERT', 'UPDATE', 'DELETE', 'INSERT OR UPDATE', 'INSERT OR DELETE',
		'DELETE OR UPDATE', 'INSERT OR DELETE OR UPDATE');
	// When to execute the trigger
	var $triggerExecTimes = array('BEFORE', 'AFTER');
	// How often to execute the trigger
	var $triggerFrequency = array('ROW','STATEMENT');
	// Array of allowed type alignments
	var $typAligns = array('char', 'int2', 'int4', 'double');
	// The default type alignment
	var $typAlignDef = 'int4';
	// Default index type
	var $typIndexDef = 'BTREE';
	// Array of allowed index types
	var $typIndexes = array('BTREE', 'RTREE', 'GIST', 'GIN', 'HASH');
	// Array of allowed type storage attributes
	var $typStorages = array('plain', 'external', 'extended', 'main');
	// The default type storage
	var $typStorageDef = 'plain';

	/**
	 * Constructor
	 * @param $conn The database connection
	 */
 	function __construct($conn) {
 		parent::__construct($conn);
	}

	// Formatting functions

	/**
	 * Cleans (escapes) a string
	 * @param $str The string to clean, by reference
	 * @return The cleaned string
	 */
	function clean(&$str) {
		if ($str === null) return null;
		$str = str_replace("\r\n","\n",$str);
		$str = pg_escape_string($str);
		return $str;
	}

	/**
	 * Cleans (escapes) an object name (eg. table, field)
	 * @param $str The string to clean, by reference
	 * @return The cleaned string
	 */
	function fieldClean(&$str) {
		if ($str === null) return null;
		$str = str_replace('"', '""', $str);
		return $str;
	}

	/**
	 * Cleans (escapes) an array of field names
	 * @param $arr The array to clean, by reference
	 * @return The cleaned array
	 */
	function fieldArrayClean(&$arr) {
		foreach ($arr as $k => $v) {
			if ($v === null) continue;
			$arr[$k] = str_replace('"', '""', $v);
		}
		return $arr;
	}

	/**
	 * Cleans (escapes) an array
	 * @param $arr The array to clean, by reference
	 * @return The cleaned array
	 */
	function arrayClean(&$arr) {
		foreach ($arr as $k => $v) {
			if ($v === null) continue;
			$arr[$k] = pg_escape_string($v);
		}
		return $arr;
	}

	/**
	 * Escapes bytea data for display on the screen
	 * @param $data The bytea data
	 * @return Data formatted for on-screen display
	 */
	function escapeBytea($data) {
		return htmlentities($data, ENT_QUOTES, 'UTF-8');
	}

	/**
	 * Outputs the HTML code for a particular field
	 * @param $name The name to give the field
	 * @param $value The value of the field.  Note this could be 'numeric(7,2)' sort of thing...
	 * @param $type The database type of the field
	 * @param $extras An array of attributes name as key and attributes' values as value
	 */
	function printField($name, $value, $type, $extras = array()) {
		global $lang;

		// Determine actions string
		$extra_str = '';
		foreach ($extras as $k => $v) {
			$extra_str .= " {$k}=\"" . htmlspecialchars($v) . "\"";
		}

		switch (substr($type,0,9)) {
			case 'bool':
			case 'boolean':
				if ($value !== null && $value == '') $value = null;
				elseif ($value == 'true') $value = 't';
				elseif ($value == 'false') $value = 'f';

				// If value is null, 't' or 'f'...
				if ($value === null || $value == 't' || $value == 'f') {
					echo "<select name=\"", htmlspecialchars($name), "\"{$extra_str}>\n";
					echo "<option value=\"\"", ($value === null) ? ' selected="selected"' : '', "></option>\n";
					echo "<option value=\"t\"", ($value == 't') ? ' selected="selected"' : '', ">{$lang['strtrue']}</option>\n";
					echo "<option value=\"f\"", ($value == 'f') ? ' selected="selected"' : '', ">{$lang['strfalse']}</option>\n";
					echo "</select>\n";
				}
				else {
					echo "<input name=\"", htmlspecialchars($name), "\" value=\"", htmlspecialchars($value), "\" size=\"35\"{$extra_str} />\n";
				}
				break;
			case 'bytea':
			case 'bytea[]':
                if (!is_null($value)) {
				    $value = $this->escapeBytea($value);
                }
			case 'text':
			case 'text[]':
			case 'json':
			case 'jsonb': 
			case 'xml':
			case 'xml[]':
				$n = substr_count($value, "\n");
				$n = $n < 5 ? 5 : $n;
				$n = $n > 20 ? 20 : $n;
				echo "<textarea name=\"", htmlspecialchars($name), "\" rows=\"{$n}\" cols=\"75\"{$extra_str}>\n";
				echo htmlspecialchars($value);
				echo "</textarea>\n";
				break;
			case 'character':
			case 'character[]':
				$n = substr_count($value, "\n");
				$n = $n < 5 ? 5 : $n;
				$n = $n > 20 ? 20 : $n;
				echo "<textarea name=\"", htmlspecialchars($name), "\" rows=\"{$n}\" cols=\"35\"{$extra_str}>\n";
				echo htmlspecialchars($value);
				echo "</textarea>\n";
				break;
			default:
				echo "<input name=\"", htmlspecialchars($name), "\" value=\"", htmlspecialchars($value), "\" size=\"35\"{$extra_str} />\n";
				break;
		}
	}

	/**
	 * Formats a value or expression for sql purposes
	 * @param $type The type of the field
	 * @param $format VALUE or EXPRESSION
	 * @param $value The actual value entered in the field.  Can be NULL
	 * @return The suitably quoted and escaped value.
	 */
	function formatValue($type, $format, $value) {
		switch ($type) {
			case 'bool':
			case 'boolean':
				if ($value == 't')
					return 'TRUE';
				elseif ($value == 'f')
					return 'FALSE';
				elseif ($value == '')
					return 'NULL';
				else
					return $value;
				break;
			default:
				// Checking variable fields is difficult as there might be a size
				// attribute...
				if (strpos($type, 'time') === 0) {
					// Assume it's one of the time types...
					if ($value == '') return "''";
					elseif (strcasecmp($value, 'CURRENT_TIMESTAMP') == 0
							|| strcasecmp($value, 'CURRENT_TIME') == 0
							|| strcasecmp($value, 'CURRENT_DATE') == 0
							|| strcasecmp($value, 'LOCALTIME') == 0
							|| strcasecmp($value, 'LOCALTIMESTAMP') == 0) {
						return $value;
					}
					elseif ($format == 'EXPRESSION')
						return $value;
					else {
						$this->clean($value);
						return "'{$value}'";
					}
				}
				else {
					if ($format == 'VALUE') {
						$this->clean($value);
						return "'{$value}'";
					}
					return $value;
				}
		}
	}

	/**
	 * Formats a type correctly for display.  Postgres 7.0 had no 'format_type'
	 * built-in function, and hence we need to do it manually.
	 * @param $typname The name of the type
	 * @param $typmod The contents of the typmod field
	 */
	function formatType($typname, $typmod) {
		// This is a specific constant in the 7.0 source
		$varhdrsz = 4;

		// If the first character is an underscore, it's an array type
		$is_array = false;
		if (substr($typname, 0, 1) == '_') {
			$is_array = true;
			$typname = substr($typname, 1);
		}

		// Show lengths on bpchar and varchar
		if ($typname == 'bpchar') {
			$len = $typmod - $varhdrsz;
			$temp = 'character';
			if ($len > 1)
				$temp .= "({$len})";
		}
		elseif ($typname == 'varchar') {
			$temp = 'character varying';
			if ($typmod != -1)
				$temp .= "(" . ($typmod - $varhdrsz) . ")";
		}
		elseif ($typname == 'numeric') {
			$temp = 'numeric';
			if ($typmod != -1) {
				$tmp_typmod = $typmod - $varhdrsz;
				$precision = ($tmp_typmod >> 16) & 0xffff;
				$scale = $tmp_typmod & 0xffff;
				$temp .= "({$precision}, {$scale})";
			}
		}
		else $temp = $typname;

		// Add array qualifier if it's an array
		if ($is_array) $temp .= '[]';

		return $temp;
	}

	// Help functions

	/**
	 * Fetch a URL (or array of URLs) for a given help page.
	 */
	function getHelp($help) {
		$this->getHelpPages();

		if (isset($this->help_page[$help])) {
			if (is_array($this->help_page[$help])) {
				$urls = array();
				foreach ($this->help_page[$help] as $link) {
					$urls[] = $this->help_base . $link;
				}
				return $urls;
			} else
				return $this->help_base . $this->help_page[$help];
		} else
			return null;
	}

	function getHelpPages() {
		include_once('./help/PostgresDoc95.php');
		return $this->help_page;
	}

	// Database functions

	/**
	 * Return all information about a particular database
	 * @param $database The name of the database to retrieve
	 * @return The database info
	 */
	function getDatabase($database) {
		$this->clean($database);
		$sql = "SELECT * FROM pg_database WHERE datname='{$database}'";
		return $this->selectSet($sql);
	}

	/**
	 * Return all database available on the server
	 * @param $currentdatabase database name that should be on top of the resultset
	 * 
	 * @return A list of databases, sorted alphabetically
	 */
	function getDatabases($currentdatabase = NULL) {
		global $conf, $misc;

		$server_info = $misc->getServerInfo();

		if (isset($conf['owned_only']) && $conf['owned_only'] && !$this->isSuperUser()) {
			$username = $server_info['username'];
			$this->clean($username);
			$clause = " AND pr.rolname='{$username}'";
		}
		else $clause = '';

		if ($currentdatabase != NULL) {
			$this->clean($currentdatabase);
			$orderby = "ORDER BY pdb.datname = '{$currentdatabase}' DESC, pdb.datname";
		} 
		else
			$orderby = "ORDER BY pdb.datname";

		if (!$conf['show_system'])
			$where = ' AND NOT pdb.datistemplate';
		else
			$where = ' AND pdb.datallowconn';

		$sql = "
			SELECT pdb.datname AS datname, pr.rolname AS datowner, pg_encoding_to_char(encoding) AS datencoding,
				(SELECT description FROM pg_catalog.pg_shdescription pd WHERE pdb.oid=pd.objoid AND pd.classoid='pg_database'::regclass) AS datcomment,
				(SELECT spcname FROM pg_catalog.pg_tablespace pt WHERE pt.oid=pdb.dattablespace) AS tablespace,
				CASE WHEN pg_catalog.has_database_privilege(current_user, pdb.oid, 'CONNECT') 
					THEN pg_catalog.pg_database_size(pdb.oid) 
					ELSE -1 -- set this magic value, which we will convert to no access later  
				END as dbsize, pdb.datcollate, pdb.datctype
			FROM pg_catalog.pg_database pdb
				LEFT JOIN pg_catalog.pg_roles pr ON (pdb.datdba = pr.oid)
			WHERE true
				{$where}
				{$clause}
			{$orderby}";

		return $this->selectSet($sql);
	}

	/**
	 * Return the database comment of a db from the shared description table
	 * @param string $database the name of the database to get the comment for
	 * @return recordset of the db comment info
	 */
	function getDatabaseComment($database) {
		$this->clean($database);
		$sql = "SELECT description FROM pg_catalog.pg_database JOIN pg_catalog.pg_shdescription ON (oid=objoid AND classoid='pg_database'::regclass) WHERE pg_database.datname = '{$database}' ";
		return $this->selectSet($sql);
	}

	/**
	 * Return the database owner of a db
	 * @param string $database the name of the database to get the owner for
	 * @return recordset of the db owner info
	 */
	function getDatabaseOwner($database) {
		$this->clean($database);
		$sql = "SELECT usename FROM pg_user, pg_database WHERE pg_user.usesysid = pg_database.datdba AND pg_database.datname = '{$database}' ";
		return $this->selectSet($sql);
	}

	/**
	 * Returns the current database encoding
	 * @return The encoding.  eg. SQL_ASCII, UTF-8, etc.
	 */
	function getDatabaseEncoding() {
		return pg_parameter_status($this->conn->_connectionID, 'server_encoding');
	}

	/**
	 * Returns the current default_with_oids setting
	 * @return default_with_oids setting
	 */
	function getDefaultWithOid() {
		// OID support was removed in PG12
		// But this function is referenced when browsing data
		return false;
	}

	/**
	 * Creates a database
	 * @param $database The name of the database to create
	 * @param $encoding Encoding of the database
	 * @param $tablespace (optional) The tablespace name
	 * @return 0 success
	 * @return -1 tablespace error
	 * @return -2 comment error
	 */
	function createDatabase($database, $encoding, $tablespace = '', $comment = '', $template = 'template1',
		$lc_collate = '', $lc_ctype = '')
	{
		$this->fieldClean($database);
		$this->clean($encoding);
		$this->fieldClean($tablespace);
		$this->fieldClean($template);
		$this->clean($lc_collate);
		$this->clean($lc_ctype);

		$sql = "CREATE DATABASE \"{$database}\" WITH TEMPLATE=\"{$template}\"";

		if ($encoding != '') $sql .= " ENCODING='{$encoding}'";
		if ($lc_collate != '') $sql .= " LC_COLLATE='{$lc_collate}'";
		if ($lc_ctype != '') $sql .= " LC_CTYPE='{$lc_ctype}'";

		if ($tablespace != '' && $this->hasTablespaces()) $sql .= " TABLESPACE \"{$tablespace}\"";

		$status = $this->execute($sql);
		if ($status != 0) return -1;

		if ($comment != '' && $this->hasSharedComments()) {
			$status = $this->setComment('DATABASE',$database,'',$comment);
			if ($status != 0) return -2;
		}

		return 0;
	}

	/**
	 * Renames a database, note that this operation cannot be
	 * performed on a database that is currently being connected to
	 * @param string $oldName name of database to rename
	 * @param string $newName new name of database
	 * @return int 0 on success
	 */
	function alterDatabaseRename($oldName, $newName) {
		$this->fieldClean($oldName);
		$this->fieldClean($newName);

		if ($oldName != $newName) {
			$sql = "ALTER DATABASE \"{$oldName}\" RENAME TO \"{$newName}\"";
			return $this->execute($sql);
		}
		else //just return success, we're not going to do anything
			return 0;
	}

	/**
	 * Drops a database
	 * @param $database The name of the database to drop
	 * @return 0 success
	 */
	function dropDatabase($database) {
		$this->fieldClean($database);
		$sql = "DROP DATABASE \"{$database}\"";
		return $this->execute($sql);
	}

	/**
	 * Changes ownership of a database
	 * This can only be done by a superuser or the owner of the database
	 * @param string $dbName database to change ownership of
	 * @param string $newOwner user that will own the database
	 * @return int 0 on success
	 */
	function alterDatabaseOwner($dbName, $newOwner) {
		$this->fieldClean($dbName);
		$this->fieldClean($newOwner);

		$sql = "ALTER DATABASE \"{$dbName}\" OWNER TO \"{$newOwner}\"";
		return $this->execute($sql);
	}

	/**
	 * Alters a database
	 * the multiple return vals are for postgres 8+ which support more functionality in alter database
	 * @param $dbName The name of the database
	 * @param $newName new name for the database
	 * @param $newOwner The new owner for the database
	 * @return 0 success
	 * @return -1 transaction error
	 * @return -2 owner error
	 * @return -3 rename error
	 * @return -4 comment error
	 */
	function alterDatabase($dbName, $newName, $newOwner = '', $comment = '') {

		$status = $this->beginTransaction();
		if ($status != 0) {
			$this->rollbackTransaction();
			return -1;
		}

		if ($dbName != $newName) {
			$status = $this->alterDatabaseRename($dbName, $newName);
			if ($status != 0) {
				$this->rollbackTransaction();
				return -3;
			}
			$dbName = $newName;
		}

		if ($newOwner != '') {
			$status = $this->alterDatabaseOwner($newName, $newOwner);
			if ($status != 0) {
				$this->rollbackTransaction();
				return -2;
			}
		}
		
		$this->fieldClean($dbName);
		$status = $this->setComment('DATABASE', $dbName, '', $comment);
		if ($status != 0) {
			$this->rollbackTransaction();
			return -4;
		}
		return $this->endTransaction();
	}

	/**
	 * Returns prepared transactions information
	 * @param $database (optional) Find only prepared transactions executed in a specific database
	 * @return A recordset
	 */
	function getPreparedXacts($database = null) {
		if ($database === null)
			$sql = "SELECT * FROM pg_prepared_xacts";
		else {
			$this->clean($database);
			$sql = "SELECT transaction, gid, prepared, owner FROM pg_prepared_xacts
				WHERE database='{$database}' ORDER BY owner";
		}

		return $this->selectSet($sql);
	}

	/**
	 * Searches all system catalogs to find objects that match a certain name.
	 * @param $term The search term
	 * @param $filter The object type to restrict to ('' means no restriction)
	 * @return A recordset
	 */
	function findObject($term, $filter) {
		global $conf;

		/*about escaping:
		 * SET standard_conforming_string is not available before 8.2
		 * So we must use PostgreSQL specific notation :/
		 * E'' notation is not available before 8.1
		 * $$ is available since 8.0
		 * Nothing specific from 7.4
		 **/

		// Escape search term for ILIKE match
		$this->clean($term);
		$this->clean($filter);
		$term = str_replace('_', '\_', $term);
		$term = str_replace('%', '\%', $term);

		// Exclude system relations if necessary
		if (!$conf['show_system']) {
			// XXX: The mention of information_schema here is in the wrong place, but
			// it's the quickest fix to exclude the info schema from 7.4
			$where = " AND pn.nspname NOT LIKE \$_PATTERN_\$pg\_%\$_PATTERN_\$ AND pn.nspname != 'information_schema'";
			$lan_where = "AND pl.lanispl";
		}
		else {
			$where = '';
			$lan_where = '';
		}

		// Apply outer filter
		$sql = '';
		if ($filter != '') {
			$sql = "SELECT * FROM (";
		}

		$term = "\$_PATTERN_\$%{$term}%\$_PATTERN_\$";

		$sql .= "
			SELECT 'SCHEMA' AS type, oid, NULL AS schemaname, NULL AS relname, nspname AS name
				FROM pg_catalog.pg_namespace pn WHERE nspname ILIKE {$term} {$where}
			UNION ALL
			SELECT CASE WHEN relkind='r' THEN 'TABLE' WHEN relkind='v' THEN 'VIEW' WHEN relkind='S' THEN 'SEQUENCE' END, pc.oid,
				pn.nspname, NULL, pc.relname FROM pg_catalog.pg_class pc, pg_catalog.pg_namespace pn
				WHERE pc.relnamespace=pn.oid AND relkind IN ('r', 'v', 'S') AND relname ILIKE {$term} {$where}
			UNION ALL
			SELECT CASE WHEN pc.relkind='r' THEN 'COLUMNTABLE' ELSE 'COLUMNVIEW' END, NULL, pn.nspname, pc.relname, pa.attname FROM pg_catalog.pg_class pc, pg_catalog.pg_namespace pn,
				pg_catalog.pg_attribute pa WHERE pc.relnamespace=pn.oid AND pc.oid=pa.attrelid
				AND pa.attname ILIKE {$term} AND pa.attnum > 0 AND NOT pa.attisdropped AND pc.relkind IN ('r', 'v') {$where}
			UNION ALL
			SELECT 'FUNCTION', pp.oid, pn.nspname, NULL, pp.proname || '(' || pg_catalog.oidvectortypes(pp.proargtypes) || ')' FROM pg_catalog.pg_proc pp, pg_catalog.pg_namespace pn
				WHERE pp.pronamespace=pn.oid AND NOT pp.prokind = 'a' AND pp.proname ILIKE {$term} {$where}
			UNION ALL
			SELECT 'INDEX', NULL, pn.nspname, pc.relname, pc2.relname FROM pg_catalog.pg_class pc, pg_catalog.pg_namespace pn,
				pg_catalog.pg_index pi, pg_catalog.pg_class pc2 WHERE pc.relnamespace=pn.oid AND pc.oid=pi.indrelid
				AND pi.indexrelid=pc2.oid
				AND NOT EXISTS (
					SELECT 1 FROM pg_catalog.pg_depend d JOIN pg_catalog.pg_constraint c
					ON (d.refclassid = c.tableoid AND d.refobjid = c.oid)
					WHERE d.classid = pc2.tableoid AND d.objid = pc2.oid AND d.deptype = 'i' AND c.contype IN ('u', 'p')
				)
				AND pc2.relname ILIKE {$term} {$where}
			UNION ALL
			SELECT 'CONSTRAINTTABLE', NULL, pn.nspname, pc.relname, pc2.conname FROM pg_catalog.pg_class pc, pg_catalog.pg_namespace pn,
				pg_catalog.pg_constraint pc2 WHERE pc.relnamespace=pn.oid AND pc.oid=pc2.conrelid AND pc2.conrelid != 0
				AND CASE WHEN pc2.contype IN ('f', 'c') THEN TRUE ELSE NOT EXISTS (
					SELECT 1 FROM pg_catalog.pg_depend d JOIN pg_catalog.pg_constraint c
					ON (d.refclassid = c.tableoid AND d.refobjid = c.oid)
					WHERE d.classid = pc2.tableoid AND d.objid = pc2.oid AND d.deptype = 'i' AND c.contype IN ('u', 'p')
				) END
				AND pc2.conname ILIKE {$term} {$where}
			UNION ALL
			SELECT 'CONSTRAINTDOMAIN', pt.oid, pn.nspname, pt.typname, pc.conname FROM pg_catalog.pg_type pt, pg_catalog.pg_namespace pn,
				pg_catalog.pg_constraint pc WHERE pt.typnamespace=pn.oid AND pt.oid=pc.contypid AND pc.contypid != 0
				AND pc.conname ILIKE {$term} {$where}
			UNION ALL
			SELECT 'TRIGGER', NULL, pn.nspname, pc.relname, pt.tgname FROM pg_catalog.pg_class pc, pg_catalog.pg_namespace pn,
				pg_catalog.pg_trigger pt WHERE pc.relnamespace=pn.oid AND pc.oid=pt.tgrelid
					AND ( pt.tgconstraint = 0 OR NOT EXISTS
					(SELECT 1 FROM pg_catalog.pg_depend d JOIN pg_catalog.pg_constraint c
					ON (d.refclassid = c.tableoid AND d.refobjid = c.oid)
					WHERE d.classid = pt.tableoid AND d.objid = pt.oid AND d.deptype = 'i' AND c.contype = 'f'))
				AND pt.tgname ILIKE {$term} {$where}
			UNION ALL
			SELECT 'RULETABLE', NULL, pn.nspname AS schemaname, c.relname AS tablename, r.rulename FROM pg_catalog.pg_rewrite r
				JOIN pg_catalog.pg_class c ON c.oid = r.ev_class
				LEFT JOIN pg_catalog.pg_namespace pn ON pn.oid = c.relnamespace
				WHERE c.relkind='r' AND r.rulename != '_RETURN' AND r.rulename ILIKE {$term} {$where}
			UNION ALL
			SELECT 'RULEVIEW', NULL, pn.nspname AS schemaname, c.relname AS tablename, r.rulename FROM pg_catalog.pg_rewrite r
				JOIN pg_catalog.pg_class c ON c.oid = r.ev_class
				LEFT JOIN pg_catalog.pg_namespace pn ON pn.oid = c.relnamespace
				WHERE c.relkind='v' AND r.rulename != '_RETURN' AND r.rulename ILIKE {$term} {$where}
		";

		// Add advanced objects if show_advanced is set
		if ($conf['show_advanced']) {
			$sql .= "
				UNION ALL
				SELECT CASE WHEN pt.typtype='d' THEN 'DOMAIN' ELSE 'TYPE' END, pt.oid, pn.nspname, NULL,
					pt.typname FROM pg_catalog.pg_type pt, pg_catalog.pg_namespace pn
					WHERE pt.typnamespace=pn.oid AND typname ILIKE {$term}
					AND (pt.typrelid = 0 OR (SELECT c.relkind = 'c' FROM pg_catalog.pg_class c WHERE c.oid = pt.typrelid))
					{$where}
			 	UNION ALL
				SELECT 'OPERATOR', po.oid, pn.nspname, NULL, po.oprname FROM pg_catalog.pg_operator po, pg_catalog.pg_namespace pn
					WHERE po.oprnamespace=pn.oid AND oprname ILIKE {$term} {$where}
				UNION ALL
				SELECT 'CONVERSION', pc.oid, pn.nspname, NULL, pc.conname FROM pg_catalog.pg_conversion pc,
					pg_catalog.pg_namespace pn WHERE pc.connamespace=pn.oid AND conname ILIKE {$term} {$where}
				UNION ALL
				SELECT 'LANGUAGE', pl.oid, NULL, NULL, pl.lanname FROM pg_catalog.pg_language pl
					WHERE lanname ILIKE {$term} {$lan_where}
				UNION ALL
				SELECT DISTINCT ON (p.proname) 'AGGREGATE', p.oid, pn.nspname, NULL, p.proname FROM pg_catalog.pg_proc p
					LEFT JOIN pg_catalog.pg_namespace pn ON p.pronamespace=pn.oid
					WHERE p.prokind = 'a' AND p.proname ILIKE {$term} {$where}
				UNION ALL
				SELECT DISTINCT ON (po.opcname) 'OPCLASS', po.oid, pn.nspname, NULL, po.opcname FROM pg_catalog.pg_opclass po,
					pg_catalog.pg_namespace pn WHERE po.opcnamespace=pn.oid
					AND po.opcname ILIKE {$term} {$where}
			";
		}
		// Otherwise just add domains
		else {
			$sql .= "
				UNION ALL
				SELECT 'DOMAIN', pt.oid, pn.nspname, NULL,
					pt.typname FROM pg_catalog.pg_type pt, pg_catalog.pg_namespace pn
					WHERE pt.typnamespace=pn.oid AND pt.typtype='d' AND typname ILIKE {$term}
					AND (pt.typrelid = 0 OR (SELECT c.relkind = 'c' FROM pg_catalog.pg_class c WHERE c.oid = pt.typrelid))
					{$where}
			";
		}

		if ($filter != '') {
			// We use like to make RULE, CONSTRAINT and COLUMN searches work
			$sql .= ") AS sub WHERE type LIKE '{$filter}%' ";
		}

		$sql .= "ORDER BY type, schemaname, relname, name";

		return $this->selectSet($sql);
	}

	/**
	 * Returns all available variable information.
	 * @return A recordset
	 */
	function getVariables() {
		$sql = "SHOW ALL";

		return $this->selectSet($sql);
	}

	// Schema functions

	/**
	 * Return all schemas in the current database.
	 * @return All schemas, sorted alphabetically
	 */
	function getSchemas() {
		global $conf;

		if (!$conf['show_system']) {
			$where = "WHERE nspname NOT LIKE 'pg@_%' ESCAPE '@' AND nspname != 'information_schema'";

		}
		else $where = "WHERE nspname !~ '^pg_t(emp_[0-9]+|oast)$'";
		$sql = "
			SELECT pn.nspname, pu.rolname AS nspowner,
				pg_catalog.obj_description(pn.oid, 'pg_namespace') AS nspcomment
			FROM pg_catalog.pg_namespace pn
				LEFT JOIN pg_catalog.pg_roles pu ON (pn.nspowner = pu.oid)
			{$where}
			ORDER BY nspname";

		return $this->selectSet($sql);
	}

	/**
	 * Return all information relating to a schema
	 * @param $schema The name of the schema
	 * @return Schema information
	 */
	function getSchemaByName($schema) {
		$this->clean($schema);
		$sql = "
			SELECT nspname, nspowner, r.rolname AS ownername, nspacl,
				pg_catalog.obj_description(pn.oid, 'pg_namespace') as nspcomment
			FROM pg_catalog.pg_namespace pn
				LEFT JOIN pg_roles as r ON pn.nspowner = r.oid
			WHERE nspname='{$schema}'";
		return $this->selectSet($sql);
	}

	/**
	 * Sets the current working schema.  Will also set Class variable.
	 * @param $schema The the name of the schema to work in
	 * @return 0 success
	 */
	function setSchema($schema) {
		// Get the current schema search path, including 'pg_catalog'.
		$search_path = $this->getSearchPath();
		// Prepend $schema to search path
		array_unshift($search_path, $schema);
		$status = $this->setSearchPath($search_path);
		if ($status == 0) {
			$this->_schema = $schema;
			return 0;
		}
		else return $status;
	}

	/**
	 * Sets the current schema search path
	 * @param $paths An array of schemas in required search order
	 * @return 0 success
	 * @return -1 Array not passed
	 * @return -2 Array must contain at least one item
	 */
	function setSearchPath($paths) {
		if (!is_array($paths)) return -1;
		elseif (sizeof($paths) == 0) return -2;
		elseif (sizeof($paths) == 1 && $paths[0] == '') {
			// Need to handle empty paths in some cases
			$paths[0] = 'pg_catalog';
		}

		// Loop over all the paths to check that none are empty
		$temp = array();
		foreach ($paths as $schema) {
			if ($schema != '') $temp[] = $schema;
		}
		$this->fieldArrayClean($temp);

		$sql = 'SET SEARCH_PATH TO "' . implode('","', $temp) . '"';

		return $this->execute($sql);
 		}

	/**
	 * Creates a new schema.
	 * @param $schemaname The name of the schema to create
	 * @param $authorization (optional) The username to create the schema for.
	 * @param $comment (optional) If omitted, defaults to nothing
	 * @return 0 success
	 */
	function createSchema($schemaname, $authorization = '', $comment = '') {
		$this->fieldClean($schemaname);
		$this->fieldClean($authorization);

		$sql = "CREATE SCHEMA \"{$schemaname}\"";
		if ($authorization != '') $sql .= " AUTHORIZATION \"{$authorization}\"";

		if ($comment != '') {
			$status = $this->beginTransaction();
			if ($status != 0) return -1;
		}

		// Create the new schema
		$status = $this->execute($sql);
		if ($status != 0) {
			$this->rollbackTransaction();
			return -1;
		}

		// Set the comment
		if ($comment != '') {
			$status = $this->setComment('SCHEMA', $schemaname, '', $comment);
			if ($status != 0) {
				$this->rollbackTransaction();
				return -1;
			}

		return $this->endTransaction();
		}

		return 0;
	}

	/**
	 * Updates a schema.
	 * @param $schemaname The name of the schema to drop
	 * @param $comment The new comment for this schema
	 * @param $owner The new owner for this schema
	 * @return 0 success
	 */
	function updateSchema($schemaname, $comment, $name, $owner) {
		$this->fieldClean($schemaname);
		$this->fieldClean($name);
		$this->fieldClean($owner);

		$status = $this->beginTransaction();
		if ($status != 0) {
			$this->rollbackTransaction();
			return -1;
		}

		$status = $this->setComment('SCHEMA', $schemaname, '', $comment);
		if ($status != 0) {
			$this->rollbackTransaction();
			return -1;
		}

		$schema_rs = $this->getSchemaByName($schemaname);
		/* Only if the owner change */
		if ($schema_rs->fields['ownername'] != $owner) {
			$sql = "ALTER SCHEMA \"{$schemaname}\" OWNER TO \"{$owner}\"";
			$status = $this->execute($sql);
			if ($status != 0) {
				$this->rollbackTransaction();
				return -1;
			}
		}

		// Only if the name has changed
		if ($name != $schemaname) {
			$sql = "ALTER SCHEMA \"{$schemaname}\" RENAME TO \"{$name}\"";
			$status = $this->execute($sql);
			if ($status != 0) {
				$this->rollbackTransaction();
				return -1;
			}
		}

		return $this->endTransaction();
	}

	/**
	 * Drops a schema.
	 * @param $schemaname The name of the schema to drop
	 * @param $cascade True to cascade drop, false to restrict
	 * @return 0 success
	 */
	function dropSchema($schemaname, $cascade) {
		$this->fieldClean($schemaname);

		$sql = "DROP SCHEMA \"{$schemaname}\"";
		if ($cascade) $sql .= " CASCADE";

		return $this->execute($sql);
		}

	/**
	 * Return the current schema search path
	 * @return Array of schema names
	 */
	function getSearchPath() {
		$sql = 'SELECT current_schemas(false) AS search_path';

		return $this->phpArray($this->selectField($sql, 'search_path'));
		}

	// Table functions

    /**
	 * Checks to see whether or not a table has a unique id column
	 * @param $table The table name
	 * @return True if it has a unique id, false otherwise
	 * @return null error
	 **/
	function hasObjectID($table) {
		// OID support is gone since PG12
		// But that function is required by table exports
		return false;
	}

	/**
	 * Returns table information
	 * @param $table The name of the table
	 * @return A recordset
	 */
	function getTable($table) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($table);

		$sql = "
			SELECT
			  c.relname, n.nspname, u.usename AS relowner,
			  pg_catalog.obj_description(c.oid, 'pg_class') AS relcomment,
			  (SELECT spcname FROM pg_catalog.pg_tablespace pt WHERE pt.oid=c.reltablespace) AS tablespace
			FROM pg_catalog.pg_class c
			     LEFT JOIN pg_catalog.pg_user u ON u.usesysid = c.relowner
			     LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
			WHERE c.relkind = 'r'
			      AND n.nspname = '{$c_schema}'
			      AND n.oid = c.relnamespace
			      AND c.relname = '{$table}'";

		return $this->selectSet($sql);
	}

	/**
	 * Return all tables in current database (and schema)
	 * @param $all True to fetch all tables, false for just in current schema
	 * @return All tables, sorted alphabetically
	 */
	function getTables($all = false) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		if ($all) {
			// Exclude pg_catalog and information_schema tables
			$sql = "SELECT schemaname AS nspname, tablename AS relname, tableowner AS relowner
					FROM pg_catalog.pg_tables
					WHERE schemaname NOT IN ('pg_catalog', 'information_schema', 'pg_toast')
					ORDER BY schemaname, tablename";
		} else {
			$sql = "SELECT c.relname, pg_catalog.pg_get_userbyid(c.relowner) AS relowner,
						pg_catalog.obj_description(c.oid, 'pg_class') AS relcomment,
						reltuples::bigint,
						(SELECT spcname FROM pg_catalog.pg_tablespace pt WHERE pt.oid=c.reltablespace) AS tablespace
					FROM pg_catalog.pg_class c
					LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
					WHERE c.relkind = 'r'
					AND nspname='{$c_schema}'
					ORDER BY c.relname";
		}

		return $this->selectSet($sql);
	}

	/**
	 * Retrieve the attribute definition of a table
	 * @param $table The name of the table
	 * @param $field (optional) The name of a field to return
	 * @return All attributes in order
	 */
	function getTableAttributes($table, $field = '') {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($table);
		$this->clean($field);

		if ($field == '') {
			// This query is made much more complex by the addition of the 'attisserial' field.
			// The subquery to get that field checks to see if there is an internally dependent
			// sequence on the field.
			$sql = "
				SELECT
					a.attname, a.attnum,
					pg_catalog.format_type(a.atttypid, a.atttypmod) as type,
					a.atttypmod,
					a.attnotnull, a.atthasdef, pg_catalog.pg_get_expr(adef.adbin, adef.adrelid, true) as adsrc,
					a.attstattarget, a.attstorage, t.typstorage,
					(
						SELECT 1 FROM pg_catalog.pg_depend pd, pg_catalog.pg_class pc
						WHERE pd.objid=pc.oid
						AND pd.classid=pc.tableoid
						AND pd.refclassid=pc.tableoid
						AND pd.refobjid=a.attrelid
						AND pd.refobjsubid=a.attnum
						AND pd.deptype='i'
						AND pc.relkind='S'
					) IS NOT NULL AS attisserial,
					pg_catalog.col_description(a.attrelid, a.attnum) AS comment
				FROM
					pg_catalog.pg_attribute a LEFT JOIN pg_catalog.pg_attrdef adef
					ON a.attrelid=adef.adrelid
					AND a.attnum=adef.adnum
					LEFT JOIN pg_catalog.pg_type t ON a.atttypid=t.oid
				WHERE
					a.attrelid = (SELECT oid FROM pg_catalog.pg_class WHERE relname='{$table}'
						AND relnamespace = (SELECT oid FROM pg_catalog.pg_namespace WHERE
						nspname = '{$c_schema}'))
					AND a.attnum > 0 AND NOT a.attisdropped
				ORDER BY a.attnum";
		}
		else {
			$sql = "
				SELECT
					a.attname, a.attnum,
					pg_catalog.format_type(a.atttypid, a.atttypmod) as type,
					pg_catalog.format_type(a.atttypid, NULL) as base_type,
					a.atttypmod,
					a.attnotnull, a.atthasdef, pg_catalog.pg_get_expr(adef.adbin, adef.adrelid, true) as adsrc,
					a.attstattarget, a.attstorage, t.typstorage,
					pg_catalog.col_description(a.attrelid, a.attnum) AS comment
				FROM
					pg_catalog.pg_attribute a LEFT JOIN pg_catalog.pg_attrdef adef
					ON a.attrelid=adef.adrelid
					AND a.attnum=adef.adnum
					LEFT JOIN pg_catalog.pg_type t ON a.atttypid=t.oid
				WHERE
					a.attrelid = (SELECT oid FROM pg_catalog.pg_class WHERE relname='{$table}'
						AND relnamespace = (SELECT oid FROM pg_catalog.pg_namespace WHERE
						nspname = '{$c_schema}'))
					AND a.attname = '{$field}'";
		}

		return $this->selectSet($sql);
	}

	/**
	 * Finds the names and schemas of parent tables (in order)
	 * @param $table The table to find the parents for
	 * @return A recordset
	 */
	function getTableParents($table) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($table);

		$sql = "
			SELECT
				pn.nspname, relname
			FROM
				pg_catalog.pg_class pc, pg_catalog.pg_inherits pi, pg_catalog.pg_namespace pn
			WHERE
				pc.oid=pi.inhparent
				AND pc.relnamespace=pn.oid
				AND pi.inhrelid = (SELECT oid from pg_catalog.pg_class WHERE relname='{$table}'
					AND relnamespace = (SELECT oid FROM pg_catalog.pg_namespace WHERE nspname = '{$c_schema}'))
			ORDER BY
				pi.inhseqno
		";

		return $this->selectSet($sql);
	}

	/**
	 * Finds the names and schemas of child tables
	 * @param $table The table to find the children for
	 * @return A recordset
	 */
	function getTableChildren($table) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($table);

		$sql = "
			SELECT
				pn.nspname, relname
			FROM
				pg_catalog.pg_class pc, pg_catalog.pg_inherits pi, pg_catalog.pg_namespace pn
			WHERE
				pc.oid=pi.inhrelid
				AND pc.relnamespace=pn.oid
				AND pi.inhparent = (SELECT oid from pg_catalog.pg_class WHERE relname='{$table}'
					AND relnamespace = (SELECT oid FROM pg_catalog.pg_namespace WHERE nspname = '{$c_schema}'))
		";

		return $this->selectSet($sql);
	}

	/**
	 * Returns the SQL definition for the table.
	 * @pre MUST be run within a transaction
	 * @param $table The table to define
	 * @param $clean True to issue drop command, false otherwise
	 * @return A string containing the formatted SQL code
	 * @return null On error
	 */
	function getTableDefPrefix($table, $clean = false) {
		// Fetch table
		$t = $this->getTable($table);
		if (!is_object($t) || $t->recordCount() != 1) {
			$this->rollbackTransaction();
			return null;
		}
		$this->fieldClean($t->fields['relname']);
		$this->fieldClean($t->fields['nspname']);

		// Fetch attributes
		$atts = $this->getTableAttributes($table);
		if (!is_object($atts)) {
			$this->rollbackTransaction();
			return null;
		}

		// Fetch constraints
		$cons = $this->getConstraints($table);
		if (!is_object($cons)) {
			$this->rollbackTransaction();
			return null;
		}

		// Output a reconnect command to create the table as the correct user
		$sql = $this->getChangeUserSQL($t->fields['relowner']) . "\n\n";

		// Set schema search path
		$sql .= "SET search_path = \"{$t->fields['nspname']}\", pg_catalog;\n\n";

		// Begin CREATE TABLE definition
		$sql .= "-- Definition\n\n";
		// DROP TABLE must be fully qualified in case a table with the same name exists
		// in pg_catalog.
		if (!$clean) $sql .= "-- ";
		$sql .= "DROP TABLE ";
		$sql .= "\"{$t->fields['nspname']}\".\"{$t->fields['relname']}\";\n";
		$sql .= "CREATE TABLE \"{$t->fields['nspname']}\".\"{$t->fields['relname']}\" (\n";

		// Output all table columns
		$col_comments_sql = '';   // Accumulate comments on columns
		$num = $atts->recordCount() + $cons->recordCount();
		$i = 1;
		while (!$atts->EOF) {
			$this->fieldClean($atts->fields['attname']);
			$sql .= "    \"{$atts->fields['attname']}\"";
			// Dump SERIAL and BIGSERIAL columns correctly
			if ($this->phpBool($atts->fields['attisserial']) &&
					($atts->fields['type'] == 'integer' || $atts->fields['type'] == 'bigint')) {
				if ($atts->fields['type'] == 'integer')
					$sql .= " SERIAL";
				else
					$sql .= " BIGSERIAL";
			}
			else {
				$sql .= " " . $this->formatType($atts->fields['type'], $atts->fields['atttypmod']);

				// Add NOT NULL if necessary
				if ($this->phpBool($atts->fields['attnotnull']))
					$sql .= " NOT NULL";
				// Add default if necessary
				if ($atts->fields['adsrc'] !== null)
					$sql .= " DEFAULT {$atts->fields['adsrc']}";
			}

			// Output comma or not
			if ($i < $num) $sql .= ",\n";
			else $sql .= "\n";

			// Does this column have a comment?
			if ($atts->fields['comment'] !== null) {
				$this->clean($atts->fields['comment']);
				$col_comments_sql .= "COMMENT ON COLUMN \"{$t->fields['relname']}\".\"{$atts->fields['attname']}\"  IS '{$atts->fields['comment']}';\n";
			}

			$atts->moveNext();
			$i++;
		}
		// Output all table constraints
		while (!$cons->EOF) {
			$this->fieldClean($cons->fields['conname']);
			$sql .= "    CONSTRAINT \"{$cons->fields['conname']}\" ";
			// Nasty hack to support pre-7.4 PostgreSQL
			if ($cons->fields['consrc'] !== null)
				$sql .= $cons->fields['consrc'];
			else {
				switch ($cons->fields['contype']) {
					case 'p':
						$keys = $this->getAttributeNames($table, explode(' ', $cons->fields['indkey']));
						$sql .= "PRIMARY KEY (" . join(',', $keys) . ")";
						break;
					case 'u':
						$keys = $this->getAttributeNames($table, explode(' ', $cons->fields['indkey']));
						$sql .= "UNIQUE (" . join(',', $keys) . ")";
						break;
					default:
						// Unrecognised constraint
						$this->rollbackTransaction();
						return null;
				}
			}

			// Output comma or not
			if ($i < $num) $sql .= ",\n";
			else $sql .= "\n";

			$cons->moveNext();
			$i++;
		}

		$sql .= ")";

		// @@@@ DUMP CLUSTERING INFORMATION

		// Inherits
		/*
		 * XXX: This is currently commented out as handling inheritance isn't this simple.
		 * You also need to make sure you don't dump inherited columns and defaults, as well
		 * as inherited NOT NULL and CHECK constraints.  So for the time being, we just do
		 * not claim to support inheritance.
		$parents = $this->getTableParents($table);
		if ($parents->recordCount() > 0) {
			$sql .= " INHERITS (";
			while (!$parents->EOF) {
				$this->fieldClean($parents->fields['relname']);
				// Qualify the parent table if it's in another schema
				if ($parents->fields['schemaname'] != $this->_schema) {
					$this->fieldClean($parents->fields['schemaname']);
					$sql .= "\"{$parents->fields['schemaname']}\".";
				}
				$sql .= "\"{$parents->fields['relname']}\"";

				$parents->moveNext();
				if (!$parents->EOF) $sql .= ', ';
			}
			$sql .= ")";
		}
		*/

		// Handle WITHOUT OIDS
		if ($this->hasObjectID($table))
			$sql .= " WITH OIDS";
		else
			$sql .= " WITHOUT OIDS";

		$sql .= ";\n";

		// Column storage and statistics
		$atts->moveFirst();
		$first = true;
		while (!$atts->EOF) {
			$this->fieldClean($atts->fields['attname']);
			// Statistics first
			if ($atts->fields['attstattarget'] >= 0) {
				if ($first) {
					$sql .= "\n";
					$first = false;
				}
				$sql .= "ALTER TABLE ONLY \"{$t->fields['nspname']}\".\"{$t->fields['relname']}\" ALTER COLUMN \"{$atts->fields['attname']}\" SET STATISTICS {$atts->fields['attstattarget']};\n";
			}
			// Then storage
			if ($atts->fields['attstorage'] != $atts->fields['typstorage']) {
				switch ($atts->fields['attstorage']) {
					case 'p':
						$storage = 'PLAIN';
						break;
					case 'e':
						$storage = 'EXTERNAL';
						break;
					case 'm':
						$storage = 'MAIN';
						break;
					case 'x':
						$storage = 'EXTENDED';
						break;
					default:
						// Unknown storage type
						$this->rollbackTransaction();
						return null;
				}
				$sql .= "ALTER TABLE ONLY \"{$t->fields['nspname']}\".\"{$t->fields['relname']}\" ALTER COLUMN \"{$atts->fields['attname']}\" SET STORAGE {$storage};\n";
			}

			$atts->moveNext();
		}

		// Comment
		if ($t->fields['relcomment'] !== null) {
			$this->clean($t->fields['relcomment']);
			$sql .= "\n-- Comment\n\n";
			$sql .= "COMMENT ON TABLE \"{$t->fields['nspname']}\".\"{$t->fields['relname']}\" IS '{$t->fields['relcomment']}';\n";
		}

		// Add comments on columns, if any
		if ($col_comments_sql != '') $sql .= $col_comments_sql;

		// Privileges
		$privs = $this->getPrivileges($table, 'table');
		if (!is_array($privs)) {
			$this->rollbackTransaction();
			return null;
		}

		if (sizeof($privs) > 0) {
			$sql .= "\n-- Privileges\n\n";
			/*
			 * Always start with REVOKE ALL FROM PUBLIC, so that we don't have to
			 * wire-in knowledge about the default public privileges for different
			 * kinds of objects.
			 */
			$sql .= "REVOKE ALL ON TABLE \"{$t->fields['nspname']}\".\"{$t->fields['relname']}\" FROM PUBLIC;\n";
			foreach ($privs as $v) {
				// Get non-GRANT OPTION privs
				$nongrant = array_diff($v[2], $v[4]);

				// Skip empty or owner ACEs
				if (sizeof($v[2]) == 0 || ($v[0] == 'user' && $v[1] == $t->fields['relowner'])) continue;

				// Change user if necessary
				if ($this->hasGrantOption() && $v[3] != $t->fields['relowner']) {
					$grantor = $v[3];
					$this->clean($grantor);
					$sql .= "SET SESSION AUTHORIZATION '{$grantor}';\n";
				}

				// Output privileges with no GRANT OPTION
				$sql .= "GRANT " . join(', ', $nongrant) . " ON TABLE \"{$t->fields['relname']}\" TO ";
				switch ($v[0]) {
					case 'public':
						$sql .= "PUBLIC;\n";
						break;
					case 'user':
						$this->fieldClean($v[1]);
						$sql .= "\"{$v[1]}\";\n";
						break;
					case 'group':
						$this->fieldClean($v[1]);
						$sql .= "GROUP \"{$v[1]}\";\n";
						break;
					default:
						// Unknown privilege type - fail
						$this->rollbackTransaction();
						return null;
				}

				// Reset user if necessary
				if ($this->hasGrantOption() && $v[3] != $t->fields['relowner']) {
					$sql .= "RESET SESSION AUTHORIZATION;\n";
				}

				// Output privileges with GRANT OPTION

				// Skip empty or owner ACEs
				if (!$this->hasGrantOption() || sizeof($v[4]) == 0) continue;

				// Change user if necessary
				if ($this->hasGrantOption() && $v[3] != $t->fields['relowner']) {
					$grantor = $v[3];
					$this->clean($grantor);
					$sql .= "SET SESSION AUTHORIZATION '{$grantor}';\n";
				}

				$sql .= "GRANT " . join(', ', $v[4]) . " ON \"{$t->fields['relname']}\" TO ";
				switch ($v[0]) {
					case 'public':
						$sql .= "PUBLIC";
						break;
					case 'user':
						$this->fieldClean($v[1]);
						$sql .= "\"{$v[1]}\"";
						break;
					case 'group':
						$this->fieldClean($v[1]);
						$sql .= "GROUP \"{$v[1]}\"";
						break;
					default:
						// Unknown privilege type - fail
						return null;
				}
				$sql .= " WITH GRANT OPTION;\n";

				// Reset user if necessary
				if ($this->hasGrantOption() && $v[3] != $t->fields['relowner']) {
					$sql .= "RESET SESSION AUTHORIZATION;\n";
				}

			}
		}

		// Add a newline to separate data that follows (if any)
		$sql .= "\n";

		return $sql;
	}

	/**
	 * Returns extra table definition information that is most usefully
	 * dumped after the table contents for speed and efficiency reasons
	 * @param $table The table to define
	 * @return A string containing the formatted SQL code
	 * @return null On error
	 */
	function getTableDefSuffix($table) {
		$sql = '';

		// Indexes
		$indexes = $this->getIndexes($table);
		if (!is_object($indexes)) {
			$this->rollbackTransaction();
			return null;
		}

		if ($indexes->recordCount() > 0) {
			$sql .= "\n-- Indexes\n\n";
			while (!$indexes->EOF) {
				$sql .= $indexes->fields['inddef'] . ";\n";

				$indexes->moveNext();
			}
		}

		// Triggers
		$triggers = $this->getTriggers($table);
		if (!is_object($triggers)) {
			$this->rollbackTransaction();
			return null;
		}

		if ($triggers->recordCount() > 0) {
			$sql .= "\n-- Triggers\n\n";
			while (!$triggers->EOF) {

				$sql .= $triggers->fields['tgdef'];
				$sql .= ";\n";

				$triggers->moveNext();
			}
		}

		// Rules
		$rules = $this->getRules($table);
		if (!is_object($rules)) {
			$this->rollbackTransaction();
			return null;
		}

		if ($rules->recordCount() > 0) {
			$sql .= "\n-- Rules\n\n";
			while (!$rules->EOF) {
				$sql .= $rules->fields['definition'] . "\n";

				$rules->moveNext();
			}
		}

		return $sql;
	}

	/**
	 * Creates a new table in the database
	 * @param $name The name of the table
	 * @param $fields The number of fields
	 * @param $field An array of field names
	 * @param $type An array of field types
	 * @param $array An array of '' or '[]' for each type if it's an array or not
	 * @param $length An array of field lengths
	 * @param $notnull An array of not null
	 * @param $default An array of default values
	 * @param $withoutoids True if WITHOUT OIDS, false otherwise
	 * @param $colcomment An array of comments
	 * @param $comment Table comment
	 * @param $tablespace The tablespace name ('' means none/default)
 	 * @param $uniquekey An Array indicating the fields that are unique (those indexes that are set)
 	 * @param $primarykey An Array indicating the field used for the primarykey (those indexes that are set)
	 * @return 0 success
	 * @return -1 no fields supplied
	 */
	function createTable($name, $fields, $field, $type, $array, $length, $notnull,
				$default, $withoutoids, $colcomment, $tblcomment, $tablespace,
				$uniquekey, $primarykey) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($name);

		$status = $this->beginTransaction();
		if ($status != 0) return -1;

		$found = false;
		$first = true;
		$comment_sql = ''; //Accumulate comments for the columns
		$sql = "CREATE TABLE \"{$f_schema}\".\"{$name}\" (";
		for ($i = 0; $i < $fields; $i++) {
			$this->fieldClean($field[$i]);
			$this->clean($type[$i]);
			$this->clean($length[$i]);
			$this->clean($colcomment[$i]);

			// Skip blank columns - for user convenience
			if ($field[$i] == '' || $type[$i] == '') continue;
			// If not the first column, add a comma
			if (!$first) $sql .= ", ";
			else $first = false;

			switch ($type[$i]) {
				// Have to account for weird placing of length for with/without
				// time zone types
				case 'timestamp with time zone':
				case 'timestamp without time zone':
					$qual = substr($type[$i], 9);
					$sql .= "\"{$field[$i]}\" timestamp";
					if ($length[$i] != '') $sql .= "({$length[$i]})";
					$sql .= $qual;
					break;
				case 'time with time zone':
				case 'time without time zone':
					$qual = substr($type[$i], 4);
					$sql .= "\"{$field[$i]}\" time";
					if ($length[$i] != '') $sql .= "({$length[$i]})";
					$sql .= $qual;
					break;
				default:
					$sql .= "\"{$field[$i]}\" {$type[$i]}";
					if ($length[$i] != '') $sql .= "({$length[$i]})";
			}
			// Add array qualifier if necessary
			if ($array[$i] == '[]') $sql .= '[]';
			// Add other qualifiers
			if (!isset($primarykey[$i])) {
 				if (isset($uniquekey[$i])) $sql .= " UNIQUE";
 				if (isset($notnull[$i])) $sql .= " NOT NULL";
			}
			if ($default[$i] != '') $sql .= " DEFAULT {$default[$i]}";

			if ($colcomment[$i] != '') $comment_sql .= "COMMENT ON COLUMN \"{$name}\".\"{$field[$i]}\" IS '{$colcomment[$i]}';\n";

			$found = true;
		}

		if (!$found) return -1;

		// PRIMARY KEY
 		$primarykeycolumns = array();
 		for ($i = 0; $i < $fields; $i++) {
 			if (isset($primarykey[$i])) {
 				$primarykeycolumns[] = "\"{$field[$i]}\"";
			}
		}
 		if (count($primarykeycolumns) > 0) {
 			$sql .= ", PRIMARY KEY (" . implode(", ", $primarykeycolumns) . ")";
		}

		$sql .= ")";

		// WITHOUT OIDS
		if ($withoutoids)
			$sql .= ' WITHOUT OIDS';
		else
			$sql .= ' WITH OIDS';

		// Tablespace
		if ($this->hasTablespaces() && $tablespace != '') {
			$this->fieldClean($tablespace);
			$sql .= " TABLESPACE \"{$tablespace}\"";
		}

		$status = $this->execute($sql);
		if ($status) {
			$this->rollbackTransaction();
			return -1;
		}

		if ($tblcomment != '') {
			$status = $this->setComment('TABLE', '', $name, $tblcomment, true);
			if ($status) {
				$this->rollbackTransaction();
				return -1;
			}
		}

		if ($comment_sql != '') {
			$status = $this->execute($comment_sql);
			if ($status) {
				$this->rollbackTransaction();
				return -1;
			}
		}
		return $this->endTransaction();
	}

	/**
	 * Creates a new table in the database copying attribs and other properties from another table
	 * @param $name The name of the table
	 * @param $like an array giving the schema and the name of the table from which attribs are copying from:
	 *		array(
	 *			'table' => table name,
	 *			'schema' => the schema name,
	 *		)
	 * @param $defaults if true, copy the default values as well
	 * @param $constraints if true, copy the constraints as well (CHECK on table & attr)
	 * @param $tablespace The tablespace name ('' means none/default)
	 */
	function createTableLike($name, $like, $defaults = false, $constraints = false, $idx = false, $tablespace = '') {

		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($name);
		$this->fieldClean($like['schema']);
		$this->fieldClean($like['table']);
		$like = "\"{$like['schema']}\".\"{$like['table']}\"";

		$status = $this->beginTransaction();
		if ($status != 0) return -1;

		$sql = "CREATE TABLE \"{$f_schema}\".\"{$name}\" (LIKE {$like}";

		if ($defaults) $sql .= " INCLUDING DEFAULTS";
		if ($this->hasCreateTableLikeWithConstraints() && $constraints) $sql .= " INCLUDING CONSTRAINTS";
		if ($this->hasCreateTableLikeWithIndexes() && $idx) $sql .= " INCLUDING INDEXES";

		$sql .= ")";

		if ($this->hasTablespaces() && $tablespace != '') {
			$this->fieldClean($tablespace);
			$sql .= " TABLESPACE \"{$tablespace}\"";
		}

		$status = $this->execute($sql);
		if ($status) {
			$this->rollbackTransaction();
			return -1;
		}

		return $this->endTransaction();
	}

	/**
	 * Alter a table's name
	 * /!\ this function is called from _alterTable which take care of escaping fields
	 * @param $tblrs The table RecordSet returned by getTable()
	 * @param $name The new table's name
	 * @return 0 success
	 */
	function alterTableName($tblrs, $name = null) {
		/* vars cleaned in _alterTable */
		// Rename (only if name has changed)
		if (!empty($name) && ($name != $tblrs->fields['relname'])) {
			$f_schema = $this->_schema;
			$this->fieldClean($f_schema);
			
			$sql = "ALTER TABLE \"{$f_schema}\".\"{$tblrs->fields['relname']}\" RENAME TO \"{$name}\"";
			$status =  $this->execute($sql);
			if ($status == 0)
				$tblrs->fields['relname'] = $name;
			else
				return $status;
		}
		return 0;
	}

	/**
	 * Alter a table's owner
	 * /!\ this function is called from _alterTable which take care of escaping fields
	 * @param $tblrs The table RecordSet returned by getTable()
	 * @param $name The new table's owner
	 * @return 0 success
	 */
	function alterTableOwner($tblrs, $owner = null) {
		/* vars cleaned in _alterTable */
		if (!empty($owner) && ($tblrs->fields['relowner'] != $owner)) {
			$f_schema = $this->_schema;
			$this->fieldClean($f_schema);
			// If owner has been changed, then do the alteration.  We are
			// careful to avoid this generally as changing owner is a
			// superuser only function.
			$sql = "ALTER TABLE \"{$f_schema}\".\"{$tblrs->fields['relname']}\" OWNER TO \"{$owner}\"";

			return $this->execute($sql);
		}
		return 0;
	}

	/**
	 * Alter a table's tablespace
	 * /!\ this function is called from _alterTable which take care of escaping fields
	 * @param $tblrs The table RecordSet returned by getTable()
	 * @param $name The new table's tablespace
	 * @return 0 success
	 */
	function alterTableTablespace($tblrs, $tablespace = null) {
		/* vars cleaned in _alterTable */
		if (!empty($tablespace) && ($tblrs->fields['tablespace'] != $tablespace)) {
			$f_schema = $this->_schema;
			$this->fieldClean($f_schema);
			
			// If tablespace has been changed, then do the alteration.  We
			// don't want to do this unnecessarily.
			$sql = "ALTER TABLE \"{$f_schema}\".\"{$tblrs->fields['relname']}\" SET TABLESPACE \"{$tablespace}\"";

			return $this->execute($sql);
		}
		return 0;
	}

	/**
	 * Alter a table's schema
	 * /!\ this function is called from _alterTable which take care of escaping fields
	 * @param $tblrs The table RecordSet returned by getTable()
	 * @param $name The new table's schema
	 * @return 0 success
	 */
	function alterTableSchema($tblrs, $schema = null) {
		/* vars cleaned in _alterTable */
		if (!empty($schema) && ($tblrs->fields['nspname'] != $schema)) {
			$f_schema = $this->_schema;
			$this->fieldClean($f_schema);
			// If tablespace has been changed, then do the alteration.  We
			// don't want to do this unnecessarily.
			$sql = "ALTER TABLE \"{$f_schema}\".\"{$tblrs->fields['relname']}\" SET SCHEMA \"{$schema}\"";

			return $this->execute($sql);
			}
		return 0;
		}

	/**
	 * Protected method which alter a table
	 * SHOULDN'T BE CALLED OUTSIDE OF A TRANSACTION
	 * @param $tblrs The table recordSet returned by getTable()
	 * @param $name The new name for the table
	 * @param $owner The new owner for the table
	 * @param $schema The new schema for the table
	 * @param $comment The comment on the table
	 * @param $tablespace The new tablespace for the table ('' means leave as is)
	 * @return 0 success
	 * @return -3 rename error
	 * @return -4 comment error
	 * @return -5 owner error
	 * @return -6 tablespace error
	 * @return -7 schema error
	 */
	protected
	function _alterTable($tblrs, $name, $owner, $schema, $comment, $tablespace) {

		$this->fieldArrayClean($tblrs->fields);

		// Comment
		$status = $this->setComment('TABLE', '', $tblrs->fields['relname'], $comment);
		if ($status != 0) return -4;

		// Owner
		$this->fieldClean($owner);
		$status = $this->alterTableOwner($tblrs, $owner);
		if ($status != 0) return -5;

		// Tablespace
		$this->fieldClean($tablespace);
		$status = $this->alterTableTablespace($tblrs, $tablespace);
		if ($status != 0) return -6;

		// Rename
		$this->fieldClean($name);
		$status = $this->alterTableName($tblrs, $name);
		if ($status != 0) return -3;

		// Schema
		$this->fieldClean($schema);
		$status = $this->alterTableSchema($tblrs, $schema);
		if ($status != 0) return -7;

		return 0;
	}

	/**
	 * Alter table properties
	 * @param $table The name of the table
	 * @param $name The new name for the table
	 * @param $owner The new owner for the table
	 * @param $schema The new schema for the table
	 * @param $comment The comment on the table
	 * @param $tablespace The new tablespace for the table ('' means leave as is)
	 * @return 0 success
	 * @return -1 transaction error
	 * @return -2 get existing table error
	 * @return $this->_alterTable error code
	 */
	function alterTable($table, $name, $owner, $schema, $comment, $tablespace) {

		$data = $this->getTable($table);

		if ($data->recordCount() != 1)
			return -2;

		$status = $this->beginTransaction();
		if ($status != 0) {
			$this->rollbackTransaction();
			return -1;
		}

		$status = $this->_alterTable($data, $name, $owner, $schema, $comment, $tablespace);

		if ($status != 0) {
			$this->rollbackTransaction();
			return $status;
		}

		return $this->endTransaction();
	}

	/**
	 * Returns the SQL for changing the current user
	 * @param $user The user to change to
	 * @return The SQL
	 */
	function getChangeUserSQL($user) {
		$this->clean($user);
		return "SET SESSION AUTHORIZATION '{$user}';";
	}

	/**
	 * Given an array of attnums and a relation, returns an array mapping
	 * attribute number to attribute name.
	 * @param $table The table to get attributes for
	 * @param $atts An array of attribute numbers
	 * @return An array mapping attnum to attname
	 * @return -1 $atts must be an array
	 * @return -2 wrong number of attributes found
	 */
	function getAttributeNames($table, $atts) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($table);
		$this->arrayClean($atts);

		if (!is_array($atts)) return -1;

		if (sizeof($atts) == 0) return array();

		$sql = "SELECT attnum, attname FROM pg_catalog.pg_attribute WHERE
			attrelid=(SELECT oid FROM pg_catalog.pg_class WHERE relname='{$table}' AND
			relnamespace=(SELECT oid FROM pg_catalog.pg_namespace WHERE nspname='{$c_schema}'))
			AND attnum IN ('" . join("','", $atts) . "')";

		$rs = $this->selectSet($sql);
		if ($rs->recordCount() != sizeof($atts)) {
				return -2;
			}
		else {
			$temp = array();
			while (!$rs->EOF) {
				$temp[$rs->fields['attnum']] = $rs->fields['attname'];
				$rs->moveNext();
			}
			return $temp;
		}
	}

	/**
	 * Empties a table in the database
	 * @param $table The table to be emptied
	 * @return 0 success
	 */
	function emptyTable($table) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($table);

		$sql = "DELETE FROM \"{$f_schema}\".\"{$table}\"";

		return $this->execute($sql);
	}

	/**
	 * Removes a table from the database
	 * @param $table The table to drop
	 * @param $cascade True to cascade drop, false to restrict
	 * @return 0 success
	 */
	function dropTable($table, $cascade) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($table);

		$sql = "DROP TABLE \"{$f_schema}\".\"{$table}\"";
		if ($cascade) $sql .= " CASCADE";

			return $this->execute($sql);
		}

	/**
	 * Add a new column to a table
	 * @param $table The table to add to
	 * @param $column The name of the new column
	 * @param $type The type of the column
	 * @param $array True if array type, false otherwise
	 * @param $notnull True if NOT NULL, false otherwise
	 * @param $default The default for the column.  '' for none.
	 * @param $length The optional size of the column (ie. 30 for varchar(30))
	 * @return 0 success
	 */
	function addColumn($table, $column, $type, $array, $length, $notnull, $default, $comment) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($table);
		$this->fieldClean($column);
		$this->clean($type);
		$this->clean($length);

		if ($length == '')
			$sql = "ALTER TABLE \"{$f_schema}\".\"{$table}\" ADD COLUMN \"{$column}\" {$type}";
		else {
			switch ($type) {
				// Have to account for weird placing of length for with/without
				// time zone types
				case 'timestamp with time zone':
				case 'timestamp without time zone':
					$qual = substr($type, 9);
					$sql = "ALTER TABLE \"{$f_schema}\".\"{$table}\" ADD COLUMN \"{$column}\" timestamp({$length}){$qual}";
					break;
				case 'time with time zone':
				case 'time without time zone':
					$qual = substr($type, 4);
					$sql = "ALTER TABLE \"{$f_schema}\".\"{$table}\" ADD COLUMN \"{$column}\" time({$length}){$qual}";
					break;
				default:
					$sql = "ALTER TABLE \"{$f_schema}\".\"{$table}\" ADD COLUMN \"{$column}\" {$type}({$length})";
			}
		}

		// Add array qualifier, if requested
		if ($array) $sql .= '[]';

		// If we have advanced column adding, add the extra qualifiers
		if ($this->hasCreateFieldWithConstraints()) {
			// NOT NULL clause
			if ($notnull) $sql .= ' NOT NULL';

			// DEFAULT clause
			if ($default != '') $sql .= ' DEFAULT ' . $default;
		}

		$status = $this->beginTransaction();
		if ($status != 0) return -1;

		$status = $this->execute($sql);
		if ($status != 0) {
				$this->rollbackTransaction();
				return -1;
			}

		$status = $this->setComment('COLUMN', $column, $table, $comment);
		if ($status != 0) {
			$this->rollbackTransaction();
			return -1;
	}

		return $this->endTransaction();
	}

	/**
	 * Alters a column in a table
	 * @param $table The table in which the column resides
	 * @param $column The column to alter
	 * @param $name The new name for the column
	 * @param $notnull (boolean) True if not null, false otherwise
	 * @param $oldnotnull (boolean) True if column is already not null, false otherwise
	 * @param $default The new default for the column
	 * @param $olddefault The old default for the column
	 * @param $type The new type for the column
	 * @param $array True if array type, false otherwise
	 * @param $length The optional size of the column (ie. 30 for varchar(30))
	 * @param $oldtype The old type for the column
	 * @param $comment Comment for the column
	 * @return 0 success
	 * @return -1 batch alteration failed
	 * @return -4 rename column error
	 * @return -5 comment error
	 * @return -6 transaction error
	 */
	function alterColumn($table, $column, $name, $notnull, $oldnotnull, $default, $olddefault,
		$type, $length, $array, $oldtype, $comment)
	{
		// Begin transaction
		$status = $this->beginTransaction();
		if ($status != 0) {
			$this->rollbackTransaction();
			return -6;
		}

		// Rename the column, if it has been changed
		if ($column != $name) {
			$status = $this->renameColumn($table, $column, $name);
			if ($status != 0) {
				$this->rollbackTransaction();
				return -4;
			}
		}

		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($name);
		$this->fieldClean($table);
		$this->fieldClean($column);

		$toAlter = array();
		// Create the command for changing nullability
		if ($notnull != $oldnotnull) {
			$toAlter[] = "ALTER COLUMN \"{$name}\" ". (($notnull) ? 'SET' : 'DROP') . " NOT NULL";
		}

		// Add default, if it has changed
		if ($default != $olddefault) {
			if ($default == '') {
				$toAlter[] = "ALTER COLUMN \"{$name}\" DROP DEFAULT";
			}
			else {
				$toAlter[] = "ALTER COLUMN \"{$name}\" SET DEFAULT {$default}";
			}
		}

		// Add type, if it has changed
		if ($length == '')
			$ftype = $type;
		else {
			switch ($type) {
				// Have to account for weird placing of length for with/without
				// time zone types
				case 'timestamp with time zone':
				case 'timestamp without time zone':
					$qual = substr($type, 9);
					$ftype = "timestamp({$length}){$qual}";
					break;
				case 'time with time zone':
				case 'time without time zone':
					$qual = substr($type, 4);
					$ftype = "time({$length}){$qual}";
					break;
				default:
					$ftype = "{$type}({$length})";
			}
		}

		// Add array qualifier, if requested
		if ($array) $ftype .= '[]';

		if ($ftype != $oldtype) {
			$toAlter[] = "ALTER COLUMN \"{$name}\" TYPE {$ftype}";
		}

		// Attempt to process the batch alteration, if anything has been changed
		if (!empty($toAlter)) {
			// Initialise an empty SQL string
			$sql = "ALTER TABLE \"{$f_schema}\".\"{$table}\" "
				. implode(',', $toAlter);
	
			$status = $this->execute($sql);
			if ($status != 0) {
				$this->rollbackTransaction();
				return -1;
			}
		}

		// Update the comment on the column
		$status = $this->setComment('COLUMN', $name, $table, $comment);
		if ($status != 0) {
			$this->rollbackTransaction();
			return -5;
		}

		return $this->endTransaction();
	}

	/**
	 * Renames a column in a table
	 * @param $table The table containing the column to be renamed
	 * @param $column The column to be renamed
	 * @param $newName The new name for the column
	 * @return 0 success
	 */
	function renameColumn($table, $column, $newName) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($table);
		$this->fieldClean($column);
		$this->fieldClean($newName);

		$sql = "ALTER TABLE \"{$f_schema}\".\"{$table}\" RENAME COLUMN \"{$column}\" TO \"{$newName}\"";

		return $this->execute($sql);
	}

	/**
	 * Sets default value of a column
	 * @param $table The table from which to drop
	 * @param $column The column name to set
	 * @param $default The new default value
	 * @return 0 success
	 */
	function setColumnDefault($table, $column, $default) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($table);
		$this->fieldClean($column);

		$sql = "ALTER TABLE \"{$f_schema}\".\"{$table}\" ALTER COLUMN \"{$column}\" SET DEFAULT {$default}";

		return $this->execute($sql);
	}

	/**
	 * Sets whether or not a column can contain NULLs
	 * @param $table The table that contains the column
	 * @param $column The column to alter
	 * @param $state True to set null, false to set not null
	 * @return 0 success
	 */
	function setColumnNull($table, $column, $state) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($table);
		$this->fieldClean($column);

		$sql = "ALTER TABLE \"{$f_schema}\".\"{$table}\" ALTER COLUMN \"{$column}\" " . (($state) ? 'DROP' : 'SET') . " NOT NULL";

		return $this->execute($sql);
	}

	/**
	 * Drops a column from a table
	 * @param $table The table from which to drop a column
	 * @param $column The column to be dropped
	 * @param $cascade True to cascade drop, false to restrict
	 * @return 0 success
	 */
	function dropColumn($table, $column, $cascade) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($table);
		$this->fieldClean($column);

		$sql = "ALTER TABLE \"{$f_schema}\".\"{$table}\" DROP COLUMN \"{$column}\"";
		if ($cascade) $sql .= " CASCADE";

		return $this->execute($sql);
	}

	/**
	 * Drops default value of a column
	 * @param $table The table from which to drop
	 * @param $column The column name to drop default
	 * @return 0 success
	 */
	function dropColumnDefault($table, $column) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($table);
		$this->fieldClean($column);

		$sql = "ALTER TABLE \"{$f_schema}\".\"{$table}\" ALTER COLUMN \"{$column}\" DROP DEFAULT";

		return $this->execute($sql);
	}

	/**
	 * Sets up the data object for a dump.  eg. Starts the appropriate
	 * transaction, sets variables, etc.
	 * @return 0 success
	 */
	function beginDump() {
		// Begin serializable transaction (to dump consistent data)
		$status = $this->beginTransaction();
		if ($status != 0) return -1;

		// Set serializable
		$sql = "SET TRANSACTION ISOLATION LEVEL SERIALIZABLE";
		$status = $this->execute($sql);
		if ($status != 0) {
			$this->rollbackTransaction();
			return -1;
		}

		// Set datestyle to ISO
		$sql = "SET DATESTYLE = ISO";
		$status = $this->execute($sql);
		if ($status != 0) {
			$this->rollbackTransaction();
			return -1;
		}
		
		// Set extra_float_digits to 2
		$sql = "SET extra_float_digits TO 2";
		$status = $this->execute($sql);
		if ($status != 0) {
			$this->rollbackTransaction();
			return -1;
		}
		
		return 0;
	}

	/**
	 * Ends the data object for a dump.
	 * @return 0 success
	 */
	function endDump() {
		return $this->endTransaction();
	}

	/**
	 * Returns a recordset of all columns in a relation.  Used for data export.
	 * @@ Note: Really needs to use a cursor
	 * @param $relation The name of a relation
	 * @return A recordset on success
	 * @return -1 Failed to set datestyle
	 */
	function dumpRelation($relation, $oids) {
		$this->fieldClean($relation);

		// Actually retrieve the rows
		if ($oids) $oid_str = $this->id . ', ';
		else $oid_str = '';

		return $this->selectSet("SELECT {$oid_str}* FROM \"{$relation}\"");
	}
	
	/**
	 * Returns all available autovacuum per table information.
	 * @param $table if given, return autovacuum info for the given table or return all information for all tables
	 *   
	 * @return A recordset
	 */
	function getTableAutovacuum($table='') {

		$sql = '';

		if ($table !== '') {
			$this->clean($table);
			$c_schema = $this->_schema;
			$this->clean($c_schema);

			$sql = "SELECT c.oid, nspname, relname, pg_catalog.array_to_string(reloptions, E',') AS reloptions
				FROM pg_class c
					LEFT JOIN pg_namespace n ON n.oid = c.relnamespace
				WHERE c.relkind = 'r'::\"char\"
					AND n.nspname NOT IN ('pg_catalog','information_schema')
					AND c.reloptions IS NOT NULL
					AND c.relname = '{$table}' AND n.nspname = '{$c_schema}'
				ORDER BY nspname, relname";
		}
		else {
			$sql = "SELECT c.oid, nspname, relname, pg_catalog.array_to_string(reloptions, E',') AS reloptions
				FROM pg_class c
					LEFT JOIN pg_namespace n ON n.oid = c.relnamespace
				WHERE c.relkind = 'r'::\"char\"
					AND n.nspname NOT IN ('pg_catalog','information_schema')
					AND c.reloptions IS NOT NULL
				ORDER BY nspname, relname";

		}

		/* tmp var to parse the results */
		$_autovacs = $this->selectSet($sql);

		/* result array to return as RS */
		$autovacs = array();
		while (!$_autovacs->EOF) {
			$_ = array(
				'nspname' => $_autovacs->fields['nspname'],
				'relname' => $_autovacs->fields['relname']
			);

			foreach (explode(',', $_autovacs->fields['reloptions']) as $var) {
				list($o, $v) = explode('=', $var);
				$_[$o] = $v; 
			}

			$autovacs[] = $_;
			
			$_autovacs->moveNext();
		}

		include_once('./classes/ArrayRecordSet.php');
		return new ArrayRecordSet($autovacs);
	}

	// Row functions

	/**
	 * Get the fields for uniquely identifying a row in a table
	 * @param $table The table for which to retrieve the identifier
	 * @return An array mapping attribute number to attribute name, empty for no identifiers
	 * @return -1 error
	 */
	function getRowIdentifier($table) {
		$oldtable = $table;
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($table);

		$status = $this->beginTransaction();
		if ($status != 0) return -1;

		// Get the first primary or unique index (sorting primary keys first) that
		// is NOT a partial index.
		$sql = "
			SELECT indrelid, indkey
			FROM pg_catalog.pg_index
			WHERE indisunique AND indrelid=(
				SELECT oid FROM pg_catalog.pg_class
				WHERE relname='{$table}' AND relnamespace=(
					SELECT oid FROM pg_catalog.pg_namespace
					WHERE nspname='{$c_schema}'
				)
			) AND indpred IS NULL AND indexprs IS NULL
			ORDER BY indisprimary DESC LIMIT 1";
		$rs = $this->selectSet($sql);

		// If none, check for an OID column.  Even though OIDs can be duplicated, the edit and delete row
		// functions check that they're only modiying a single row.  Otherwise, return empty array.
		if ($rs->recordCount() == 0) {
			// Check for OID column
			$temp = array();
			if ($this->hasObjectID($table)) {
				$temp = array('oid');
			}
			$this->endTransaction();
			return $temp;
		}
		// Otherwise find the names of the keys
		else {
			$attnames = $this->getAttributeNames($oldtable, explode(' ', $rs->fields['indkey']));
			if (!is_array($attnames)) {
				$this->rollbackTransaction();
				return -1;
			}
			else {
				$this->endTransaction();
				return $attnames;
			}
		}
	}

	/**
	 * Adds a new row to a table
	 * @param $table The table in which to insert
	 * @param $fields Array of given field in values
	 * @param $values Array of new values for the row
	 * @param $nulls An array mapping column => something if it is to be null
	 * @param $format An array of the data type (VALUE or EXPRESSION)
	 * @param $types An array of field types
	 * @return 0 success
	 * @return -1 invalid parameters
	 */
	function insertRow($table, $fields, $values, $nulls, $format, $types) {

		if (!is_array($fields) || !is_array($values) || !is_array($nulls)
			|| !is_array($format) || !is_array($types)
			|| (count($fields) != count($values))
		) {
			return -1;
		}
		else {
			// Build clause
			if (count($values) > 0) {
				// Escape all field names
				$fields = array_map(array('Postgres','fieldClean'), $fields);
				$f_schema = $this->_schema;
				$this->fieldClean($table);
				$this->fieldClean($f_schema);

				$sql = '';
				foreach($values as $i => $value) {

					// Handle NULL values
					if (isset($nulls[$i]))
						$sql .= ',NULL';
					else
						$sql .= ',' . $this->formatValue($types[$i], $format[$i], $value);
				}

				$sql = "INSERT INTO \"{$f_schema}\".\"{$table}\" (\"". implode('","', $fields) ."\")
					VALUES (". substr($sql, 1) .")";

				return $this->execute($sql);
			}
		}

		return -1;
	}

	/**
	 * Updates a row in a table
	 * @param $table The table in which to update
	 * @param $vars An array mapping new values for the row
	 * @param $nulls An array mapping column => something if it is to be null
	 * @param $format An array of the data type (VALUE or EXPRESSION)
	 * @param $types An array of field types
	 * @param $keyarr An array mapping column => value to update
	 * @return 0 success
	 * @return -1 invalid parameters
	 */
	function editRow($table, $vars, $nulls, $format, $types, $keyarr) {
		if (!is_array($vars) || !is_array($nulls) || !is_array($format) || !is_array($types))
			return -1;
		else {
			$f_schema = $this->_schema;
			$this->fieldClean($f_schema);
			$this->fieldClean($table);

			// Build clause
			if (sizeof($vars) > 0) {

				foreach($vars as $key => $value) {
					$this->fieldClean($key);

					// Handle NULL values
					if (isset($nulls[$key])) $tmp = 'NULL';
					else $tmp = $this->formatValue($types[$key], $format[$key], $value);

					if (isset($sql)) $sql .= ", \"{$key}\"={$tmp}";
					else $sql = "UPDATE \"{$f_schema}\".\"{$table}\" SET \"{$key}\"={$tmp}";
				}
				$first = true;
				foreach ($keyarr as $k => $v) {
					$this->fieldClean($k);
					$this->clean($v);
					if ($first) {
						$sql .= " WHERE \"{$k}\"='{$v}'";
						$first = false;
					}
					else $sql .= " AND \"{$k}\"='{$v}'";
				}
		}

			// Begin transaction.  We do this so that we can ensure only one row is
			// edited
			$status = $this->beginTransaction();
		if ($status != 0) {
			$this->rollbackTransaction();
				return -1;
		}

	   	$status = $this->execute($sql);
			if ($status != 0) { // update failed
			$this->rollbackTransaction();
				return -1;
			} elseif ($this->conn->Affected_Rows() != 1) { // more than one row could be updated
				$this->rollbackTransaction();
				return -2;
		}

			// End transaction
		return $this->endTransaction();
	}
	}

	/**
	 * Delete a row from a table
	 * @param $table The table from which to delete
	 * @param $key An array mapping column => value to delete
	 * @return 0 success
	 */
	function deleteRow($table, $key, $schema=false) {
		if (!is_array($key)) return -1;
		else {
			// Begin transaction.  We do this so that we can ensure only one row is
			// deleted
			$status = $this->beginTransaction();
			if ($status != 0) {
				$this->rollbackTransaction();
				return -1;
			}
			
			if ($schema === false) $schema = $this->_schema;

			$status = $this->delete($table, $key, $schema);
			if ($status != 0 || $this->conn->Affected_Rows() != 1) {
				$this->rollbackTransaction();
				return -2;
			}

			// End transaction
			return $this->endTransaction();
		}
	}

	// Sequence functions

	/**
	 * Determines whether or not the current user can directly access sequence information 
	 * @param $sequence Sequence Name 
	 * @return t/f based on user permissions 
	*/ 
	function hasSequencePrivilege($sequence) {
		/* This double-cleaning is deliberate */
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->clean($f_schema);
		$this->fieldClean($sequence);
		$this->clean($sequence);

		$sql = "SELECT pg_catalog.has_sequence_privilege('{$f_schema}.{$sequence}','SELECT,USAGE')";

		return $this->execute($sql);
	}

	/**
	 * Returns properties of a single sequence
	 * @param $sequence Sequence name
	 * @return A recordset
	 */
	function getSequence($sequence) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$c_sequence = $sequence;
		$this->fieldClean($sequence);
		$this->clean($c_sequence);

		$join = ''; 
		if ($this->hasSequencePrivilege($sequence) == 't') {
			$join = "CROSS JOIN \"{$c_schema}\".\"{$c_sequence}\" AS s";
		} else {
			$join = 'CROSS JOIN ( values (null, null, null) ) AS s (last_value, log_cnt, is_called) ';
		}; 

        $sql = "
            SELECT
                c.relname AS seqname, s.*, 
                m.seqstart AS start_value, m.seqincrement AS increment_by, m.seqmax AS max_value, m.seqmin AS min_value, 
                m.seqcache AS cache_value, m.seqcycle AS is_cycled,  
			    pg_catalog.obj_description(m.seqrelid, 'pg_class') AS seqcomment,
				u.usename AS seqowner, n.nspname
            FROM
                \"{$sequence}\" AS s, pg_catalog.pg_sequence m,  
                pg_catalog.pg_class c, pg_catalog.pg_user u, pg_catalog.pg_namespace n                       
            WHERE
                c.relowner=u.usesysid AND c.relnamespace=n.oid 
                AND c.oid = m.seqrelid AND c.relname = '{$c_sequence}' AND c.relkind = 'S' AND n.nspname='{$c_schema}' 
                AND n.oid = c.relnamespace"; 

		$sql = "
			SELECT
                c.relname AS seqname,
				s.last_value, s.log_cnt, s.is_called, 
                m.seqstart AS start_value, m.seqincrement AS increment_by, m.seqmax AS max_value, m.seqmin AS min_value, 
                m.seqcache AS cache_value, m.seqcycle AS is_cycled,  
				pg_catalog.obj_description(c.oid, 'pg_class') as seqcomment, 
				pg_catalog.pg_get_userbyid(c.relowner) as seqowner,
				n.nspname
			FROM 
				pg_catalog.pg_class c
     			JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
				JOIN pg_catalog.pg_sequence m ON m.seqrelid = c.oid
				{$join} 
			WHERE 
				c.relkind IN ('S')
				AND c.relname = '{$c_sequence}' 
				AND n.nspname = '{$c_schema}' 
			"; 

		return $this->selectSet( $sql );
	}

	/**
	 * Returns all sequences in the current database
	 * @return A recordset
	 */
	function getSequences($all = false) {
		if ($all) {
			// Exclude pg_catalog and information_schema tables
			$sql = "
					SELECT
						n.nspname, 
						c.relname AS seqname, 
						pg_catalog.pg_get_userbyid(c.relowner) as seqowner
					FROM
						pg_catalog.pg_class c 
						JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
					WHERE 
						c.relkind IN ('S')
						AND n.nspname NOT IN ('pg_catalog','information_schema')
						AND n.nspname !~ '^pg_toast'
						AND pg_catalog.pg_table_is_visible(c.oid)
					ORDER BY 
						nspname, seqname;";
		} else {
			$c_schema = $this->_schema;
			$this->clean($c_schema);
			$sql = "
					SELECT
						n.nspname, 
						c.relname AS seqname, 
						pg_catalog.obj_description(c.oid, 'pg_class') AS seqcomment,
						(SELECT spcname FROM pg_catalog.pg_tablespace pt WHERE pt.oid=c.reltablespace) AS tablespace, 
						pg_catalog.pg_get_userbyid(c.relowner) as seqowner
					FROM
						pg_catalog.pg_class c 
						JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
					WHERE 
						c.relkind IN ('S')
						AND n.nspname = '{$c_schema}' 
						AND pg_catalog.pg_table_is_visible(c.oid)
					ORDER BY 
						nspname, seqname;";
		}

		return $this->selectSet( $sql );
	}

	/**
	 * Execute nextval on a given sequence
	 * @param $sequence Sequence name
	 * @return 0 success
	 * @return -1 sequence not found
	 */
	function nextvalSequence($sequence) {
		/* This double-cleaning is deliberate */
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->clean($f_schema);
		$this->fieldClean($sequence);
		$this->clean($sequence);

		$sql = "SELECT pg_catalog.NEXTVAL('\"{$f_schema}\".\"{$sequence}\"')";

		return $this->execute($sql);
	}

	/**
	 * Execute setval on a given sequence
	 * @param $sequence Sequence name
	 * @param $nextvalue The next value
	 * @return 0 success
	 * @return -1 sequence not found
	 */
	function setvalSequence($sequence, $nextvalue) {
		/* This double-cleaning is deliberate */
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->clean($f_schema);
		$this->fieldClean($sequence);
		$this->clean($sequence);
		$this->clean($nextvalue);

		$sql = "SELECT pg_catalog.SETVAL('\"{$f_schema}\".\"{$sequence}\"', '{$nextvalue}')";

		return $this->execute($sql);
	}

	/**
	 * Restart a given sequence to its start value
	 * @param $sequence Sequence name
	 * @return 0 success
	 * @return -1 sequence not found
	 */
	function restartSequence($sequence) {

		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($sequence);

		$sql = "ALTER SEQUENCE \"{$f_schema}\".\"{$sequence}\" RESTART;";

		return $this->execute($sql);
	}

	/**
	 * Resets a given sequence to min value of sequence
	 * @param $sequence Sequence name
	 * @return 0 success
	 * @return -1 sequence not found
	 */
	function resetSequence($sequence) {
		// Get the minimum value of the sequence
		$seq = $this->getSequence($sequence);
		if ($seq->recordCount() != 1) return -1;
		$minvalue = $seq->fields['min_value'];

		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		/* This double-cleaning is deliberate */
		$this->fieldClean($sequence);
		$this->clean($sequence);

		$sql = "SELECT pg_catalog.SETVAL('\"{$f_schema}\".\"{$sequence}\"', {$minvalue})";

		return $this->execute($sql);
	}

	/**
	 * Creates a new sequence
	 * @param $sequence Sequence name
	 * @param $increment The increment
	 * @param $minvalue The min value
	 * @param $maxvalue The max value
	 * @param $startvalue The starting value
	 * @param $cachevalue The cache value
	 * @param $cycledvalue True if cycled, false otherwise
	 * @return 0 success
	 */
	function createSequence($sequence, $increment, $minvalue, $maxvalue,
								$startvalue, $cachevalue, $cycledvalue) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($sequence);
		$this->clean($increment);
		$this->clean($minvalue);
		$this->clean($maxvalue);
		$this->clean($startvalue);
		$this->clean($cachevalue);

		$sql = "CREATE SEQUENCE \"{$f_schema}\".\"{$sequence}\"";
		if ($increment != '') $sql .= " INCREMENT {$increment}";
		if ($minvalue != '') $sql .= " MINVALUE {$minvalue}";
		if ($maxvalue != '') $sql .= " MAXVALUE {$maxvalue}";
		if ($startvalue != '') $sql .= " START {$startvalue}";
		if ($cachevalue != '') $sql .= " CACHE {$cachevalue}";
		if ($cycledvalue) $sql .= " CYCLE";

		return $this->execute($sql);
	}

	/**
	 * Rename a sequence
	 * @param $seqrs The sequence RecordSet returned by getSequence()
	 * @param $name The new name for the sequence
	 * @return 0 success
	 */
	function alterSequenceName($seqrs, $name) {
		/* vars are cleaned in _alterSequence */
		if (!empty($name) && ($seqrs->fields['seqname'] != $name)) {
			$f_schema = $this->_schema;
			$this->fieldClean($f_schema);
			$sql = "ALTER SEQUENCE \"{$f_schema}\".\"{$seqrs->fields['seqname']}\" RENAME TO \"{$name}\"";
			$status = $this->execute($sql);
			if ($status == 0)
				$seqrs->fields['seqname'] = $name;
			else
				return $status;
		}
		return 0;
	}

	/**
	 * Alter a sequence's owner
	 * @param $seqrs The sequence RecordSet returned by getSequence()
	 * @param $name The new owner for the sequence
	 * @return 0 success
	 */
	function alterSequenceOwner($seqrs, $owner) {
		// If owner has been changed, then do the alteration.  We are
		// careful to avoid this generally as changing owner is a
		// superuser only function.
		/* vars are cleaned in _alterSequence */
		if (!empty($owner) && ($seqrs->fields['seqowner'] != $owner)) {
			$f_schema = $this->_schema;
			$this->fieldClean($f_schema);
			$sql = "ALTER SEQUENCE \"{$f_schema}\".\"{$seqrs->fields['seqname']}\" OWNER TO \"{$owner}\"";
			return $this->execute($sql);
		}
		return 0;
	}

	/**
	 * Alter a sequence's schema
	 * @param $seqrs The sequence RecordSet returned by getSequence()
	 * @param $name The new schema for the sequence
	 * @return 0 success
	 */
	function alterSequenceSchema($seqrs, $schema) {
		/* vars are cleaned in _alterSequence */
		if (!empty($schema) && ($seqrs->fields['nspname'] != $schema)) {
			$f_schema = $this->_schema;
			$this->fieldClean($f_schema);
			$sql = "ALTER SEQUENCE \"{$f_schema}\".\"{$seqrs->fields['seqname']}\" SET SCHEMA {$schema}";
			return $this->execute($sql);
		}
		return 0;
	}

	/**
	 * Alter a sequence's properties
	 * @param $seqrs The sequence RecordSet returned by getSequence()
	 * @param $increment The sequence incremental value
	 * @param $minvalue The sequence minimum value
	 * @param $maxvalue The sequence maximum value
	 * @param $restartvalue The sequence current value
	 * @param $cachevalue The sequence cache value
	 * @param $cycledvalue Sequence can cycle ?
	 * @param $startvalue The sequence start value when issuing a restart
	 * @return 0 success
	 */
	function alterSequenceProps($seqrs, $increment,	$minvalue, $maxvalue,
								$restartvalue, $cachevalue, $cycledvalue, $startvalue) {

		$sql = '';
		/* vars are cleaned in _alterSequence */
		if (!empty($increment) && ($increment != $seqrs->fields['increment_by'])) $sql .= " INCREMENT {$increment}";
		if (!empty($minvalue) && ($minvalue != $seqrs->fields['min_value'])) $sql .= " MINVALUE {$minvalue}";
		if (!empty($maxvalue) && ($maxvalue != $seqrs->fields['max_value'])) $sql .= " MAXVALUE {$maxvalue}";
		if (!empty($restartvalue) && ($restartvalue != $seqrs->fields['last_value'])) $sql .= " RESTART {$restartvalue}";
		if (!empty($cachevalue) && ($cachevalue != $seqrs->fields['cache_value'])) $sql .= " CACHE {$cachevalue}";
		if (!empty($startvalue) && ($startvalue != $seqrs->fields['start_value'])) $sql .= " START {$startvalue}";
		// toggle cycle yes/no
		if (!is_null($cycledvalue))	$sql .= (!$cycledvalue ? ' NO ' : '') . " CYCLE";
		if ($sql != '') {
			$f_schema = $this->_schema;
			$this->fieldClean($f_schema);
			$sql = "ALTER SEQUENCE \"{$f_schema}\".\"{$seqrs->fields['seqname']}\" {$sql}";
			return $this->execute($sql);
		}
		return 0;
	}

	/**
	 * Protected method which alter a sequence
	 * SHOULDN'T BE CALLED OUTSIDE OF A TRANSACTION
	 * @param $seqrs The sequence recordSet returned by getSequence()
	 * @param $name The new name for the sequence
	 * @param $comment The comment on the sequence
	 * @param $owner The new owner for the sequence
	 * @param $schema The new schema for the sequence
	 * @param $increment The increment
	 * @param $minvalue The min value
	 * @param $maxvalue The max value
	 * @param $restartvalue The starting value
	 * @param $cachevalue The cache value
	 * @param $cycledvalue True if cycled, false otherwise
	 * @param $startvalue The sequence start value when issuing a restart
	 * @return 0 success
	 * @return -3 rename error
	 * @return -4 comment error
	 * @return -5 owner error
	 * @return -6 get sequence props error
	 * @return -7 schema error
	 */
	protected
	function _alterSequence($seqrs, $name, $comment, $owner, $schema, $increment,
	$minvalue, $maxvalue, $restartvalue, $cachevalue, $cycledvalue, $startvalue) {

		$this->fieldArrayClean($seqrs->fields);

		// Comment
		$status = $this->setComment('SEQUENCE', $seqrs->fields['seqname'], '', $comment);
		if ($status != 0)
			return -4;

		// Owner
		$this->fieldClean($owner);
		$status = $this->alterSequenceOwner($seqrs, $owner);
		if ($status != 0)
			return -5;

		// Props
		$this->clean($increment);
		$this->clean($minvalue);
		$this->clean($maxvalue);
		$this->clean($restartvalue);
		$this->clean($cachevalue);
		$this->clean($cycledvalue);
		$this->clean($startvalue);
		$status = $this->alterSequenceProps($seqrs, $increment,	$minvalue,
			$maxvalue, $restartvalue, $cachevalue, $cycledvalue, $startvalue);
		if ($status != 0)
			return -6;

		// Rename
		$this->fieldClean($name);
		$status = $this->alterSequenceName($seqrs, $name);
		if ($status != 0)
			return -3;

		// Schema
		$this->clean($schema);
		$status = $this->alterSequenceSchema($seqrs, $schema);
		if ($status != 0)
			return -7;

		return 0;
	}

	/**
	 * Alters a sequence
	 * @param $sequence The name of the sequence
	 * @param $name The new name for the sequence
	 * @param $comment The comment on the sequence
	 * @param $owner The new owner for the sequence
	 * @param $schema The new schema for the sequence
	 * @param $increment The increment
	 * @param $minvalue The min value
	 * @param $maxvalue The max value
	 * @param $restartvalue The starting value
	 * @param $cachevalue The cache value
	 * @param $cycledvalue True if cycled, false otherwise
	 * @param $startvalue The sequence start value when issuing a restart
	 * @return 0 success
	 * @return -1 transaction error
	 * @return -2 get existing sequence error
	 * @return $this->_alterSequence error code
	 */
    function alterSequence($sequence, $name, $comment, $owner=null, $schema=null, $increment=null,
	$minvalue=null, $maxvalue=null, $restartvalue=null, $cachevalue=null, $cycledvalue=null, $startvalue=null) {

		$this->fieldClean($sequence);

		$data = $this->getSequence($sequence);

		if ($data->recordCount() != 1)
			return -2;

		$status = $this->beginTransaction();
		if ($status != 0) {
			$this->rollbackTransaction();
			return -1;
		}

		$status = $this->_alterSequence($data, $name, $comment, $owner, $schema, $increment,
				$minvalue, $maxvalue, $restartvalue, $cachevalue, $cycledvalue, $startvalue);

		if ($status != 0) {
			$this->rollbackTransaction();
			return $status;
		}

		return $this->endTransaction();
	}

	/**
	 * Drops a given sequence
	 * @param $sequence Sequence name
	 * @param $cascade True to cascade drop, false to restrict
	 * @return 0 success
	 */
	function dropSequence($sequence, $cascade) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($sequence);

		$sql = "DROP SEQUENCE \"{$f_schema}\".\"{$sequence}\"";
		if ($cascade) $sql .= " CASCADE";

		return $this->execute($sql);
	}

	// View functions

	/**
	 * Returns all details for a particular view
	 * @param $view The name of the view to retrieve
	 * @return View info
	 */
	function getView($view) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($view);

		$sql = "
			SELECT c.relname, n.nspname, pg_catalog.pg_get_userbyid(c.relowner) AS relowner,
				pg_catalog.pg_get_viewdef(c.oid, true) AS vwdefinition,
				pg_catalog.obj_description(c.oid, 'pg_class') AS relcomment
			FROM pg_catalog.pg_class c
				LEFT JOIN pg_catalog.pg_namespace n ON (n.oid = c.relnamespace)
			WHERE (c.relname = '{$view}') AND n.nspname='{$c_schema}'";

		return $this->selectSet($sql);
	}

	/**
	 * Returns a list of all views in the database
	 * @return All views
	 */
	function getViews() {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$sql = "
			SELECT c.relname, pg_catalog.pg_get_userbyid(c.relowner) AS relowner,
				pg_catalog.obj_description(c.oid, 'pg_class') AS relcomment
			FROM pg_catalog.pg_class c
				LEFT JOIN pg_catalog.pg_namespace n ON (n.oid = c.relnamespace)
			WHERE (n.nspname='{$c_schema}') AND (c.relkind = 'v'::\"char\")
			ORDER BY relname";

		return $this->selectSet($sql);
	}

	/**
	 * Updates a view.
	 * @param $viewname The name of the view to update
	 * @param $definition The new definition for the view
	 * @return 0 success
	 * @return -1 transaction error
	 * @return -2 drop view error
	 * @return -3 create view error
	 */
	function setView($viewname, $definition,$comment) {
		return $this->createView($viewname, $definition, true, $comment);
	}

	/**
	 * Creates a new view.
	 * @param $viewname The name of the view to create
	 * @param $definition The definition for the new view
	 * @param $replace True to replace the view, false otherwise
	 * @return 0 success
	 */
	function createView($viewname, $definition, $replace, $comment) {
		$status = $this->beginTransaction();
		if ($status != 0) return -1;

		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($viewname);

		// Note: $definition not cleaned

		$sql = "CREATE ";
		if ($replace) $sql .= "OR REPLACE ";
		$sql .= "VIEW \"{$f_schema}\".\"{$viewname}\" AS {$definition}";

		$status = $this->execute($sql);
		if ($status) {
			$this->rollbackTransaction();
			return -1;
		}

		if ($comment != '') {
			$status = $this->setComment('VIEW', $viewname, '', $comment);
			if ($status) {
				$this->rollbackTransaction();
			return -1;
			}
		}

		return $this->endTransaction();
	}

	/**
	 * Rename a view
	 * @param $vwrs The view recordSet returned by getView()
	 * @param $name The new view's name
	 * @return 0 success
	 */
	function alterViewName($vwrs, $name) {
		// Rename (only if name has changed)
		/* $vwrs and $name are cleaned in _alterView */
		if (!empty($name) && ($name != $vwrs->fields['relname'])) {
			$f_schema = $this->_schema;
			$this->fieldClean($f_schema);
			$sql = "ALTER VIEW \"{$f_schema}\".\"{$vwrs->fields['relname']}\" RENAME TO \"{$name}\"";
			$status =  $this->execute($sql);
			if ($status == 0)
				$vwrs->fields['relname'] = $name;
			else
				return $status;
		}
		return 0;
	}

	/**
	 * Alter a view's owner
	 * @param $vwrs The view recordSet returned by getView()
	 * @param $name The new view's owner
	 * @return 0 success
	 */
	function alterViewOwner($vwrs, $owner = null) {
		/* $vwrs and $owner are cleaned in _alterView */
		if ((!empty($owner)) && ($vwrs->fields['relowner'] != $owner)) {
			$f_schema = $this->_schema;
			$this->fieldClean($f_schema);
			// If owner has been changed, then do the alteration.  We are
			// careful to avoid this generally as changing owner is a
			// superuser only function.
			$sql = "ALTER TABLE \"{$f_schema}\".\"{$vwrs->fields['relname']}\" OWNER TO \"{$owner}\"";
			return $this->execute($sql);
		}
		return 0;
		}

	/**
	 * Alter a view's schema
	 * @param $vwrs The view recordSet returned by getView()
	 * @param $name The new view's schema
	 * @return 0 success
	 */
	function alterViewSchema($vwrs, $schema) {
		/* $vwrs and $schema are cleaned in _alterView */
		if (!empty($schema) && ($vwrs->fields['nspname'] != $schema)) {
			$f_schema = $this->_schema;
			$this->fieldClean($f_schema);
			// If tablespace has been changed, then do the alteration.  We
			// don't want to do this unnecessarily.
			$sql = "ALTER TABLE \"{$f_schema}\".\"{$vwrs->fields['relname']}\" SET SCHEMA \"{$schema}\"";
			return $this->execute($sql);
		}
		return 0;
	}

	 /**
	  * Protected method which alter a view
	  * SHOULDN'T BE CALLED OUTSIDE OF A TRANSACTION
	  * @param $vwrs The view recordSet returned by getView()
	  * @param $name The new name for the view
	  * @param $owner The new owner for the view
	  * @param $comment The comment on the view
	  * @return 0 success
	  * @return -3 rename error
	  * @return -4 comment error
	  * @return -5 owner error
	  * @return -6 schema error
	  */
	protected
    function _alterView($vwrs, $name, $owner, $schema, $comment) {

    	$this->fieldArrayClean($vwrs->fields);

		// Comment
		if ($this->setComment('VIEW', $vwrs->fields['relname'], '', $comment) != 0)
			return -4;

		// Owner
		$this->fieldClean($owner);
		$status = $this->alterViewOwner($vwrs, $owner);
		if ($status != 0) return -5;

		// Rename
		$this->fieldClean($name);
		$status = $this->alterViewName($vwrs, $name);
		if ($status != 0) return -3;

		// Schema
		$this->fieldClean($schema);
		$status = $this->alterViewSchema($vwrs, $schema);
		if ($status != 0) return -6;

		return 0;
	}

	/**
	 * Alter view properties
	 * @param $view The name of the view
	 * @param $name The new name for the view
	 * @param $owner The new owner for the view
	 * @param $schema The new schema for the view
	 * @param $comment The comment on the view
	 * @return 0 success
	 * @return -1 transaction error
	 * @return -2 get existing view error
	 * @return $this->_alterView error code
	 */
	function alterView($view, $name, $owner, $schema, $comment) {

		$data = $this->getView($view);
		if ($data->recordCount() != 1)
			return -2;

		$status = $this->beginTransaction();
		if ($status != 0) {
			$this->rollbackTransaction();
			return -1;
		}

		$status = $this->_alterView($data, $name, $owner, $schema, $comment);

		if ($status != 0) {
			$this->rollbackTransaction();
			return $status;
		}

		return $this->endTransaction();
	}

	/**
	 * Drops a view.
	 * @param $viewname The name of the view to drop
	 * @param $cascade True to cascade drop, false to restrict
	 * @return 0 success
	 */
	function dropView($viewname, $cascade) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($viewname);

		$sql = "DROP VIEW \"{$f_schema}\".\"{$viewname}\"";
		if ($cascade) $sql .= " CASCADE";

		return $this->execute($sql);
	}

	// Index functions

	/**
	 * Grabs a list of indexes for a table
	 * @param $table The name of a table whose indexes to retrieve
	 * @param $unique Only get unique/pk indexes
	 * @return A recordset
	 */
	function getIndexes($table = '', $unique = false) {
		$this->clean($table);

		$sql = "
			SELECT c2.relname AS indname, i.indisprimary, i.indisunique, i.indisclustered,
				pg_catalog.pg_get_indexdef(i.indexrelid, 0, true) AS inddef
			FROM pg_catalog.pg_class c, pg_catalog.pg_class c2, pg_catalog.pg_index i
			WHERE c.relname = '{$table}' AND pg_catalog.pg_table_is_visible(c.oid)
				AND c.oid = i.indrelid AND i.indexrelid = c2.oid
		";
		if ($unique) $sql .= " AND i.indisunique ";
		$sql .= " ORDER BY c2.relname";

		return $this->selectSet($sql);
	}

	/** 
	 * test if a table has been clustered on an index
	 * @param $table The table to test
	 * 
	 * @return true if the table has been already clustered
	 */
	function alreadyClustered($table) {
		
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($table);

		$sql = "SELECT i.indisclustered
			FROM pg_catalog.pg_class c, pg_catalog.pg_index i
			WHERE c.relname = '{$table}'
				AND c.oid = i.indrelid AND i.indisclustered
				AND c.relnamespace = (SELECT oid FROM pg_catalog.pg_namespace
					WHERE nspname='{$c_schema}')
				";
		
		$v = $this->selectSet($sql);
		
		if ($v->recordCount() == 0)
			return false;
			
		return true;
	}
	
	/**
	 * Creates an index
	 * @param $name The index name
	 * @param $table The table on which to add the index
	 * @param $columns An array of columns that form the index
	 *                 or a string expression for a functional index
	 * @param $type The index type
	 * @param $unique True if unique, false otherwise
	 * @param $where Index predicate ('' for none)
	 * @param $tablespace The tablespaces ('' means none/default)
	 * @return 0 success
	 */
	function createIndex($name, $table, $columns, $type, $unique, $where, $tablespace, $concurrently) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($name);
		$this->fieldClean($table);

		$sql = "CREATE";
		if ($unique) $sql .= " UNIQUE";
		$sql .= " INDEX";
		if ($concurrently) $sql .= " CONCURRENTLY";
		$sql .= " \"{$name}\" ON \"{$f_schema}\".\"{$table}\" USING {$type} ";

		if (is_array($columns)) {
			$this->arrayClean($columns);
			$sql .= "(\"" . implode('","', $columns) . "\")";
		} else {
			$sql .= "(" . $columns .")";
		}

		// Tablespace
		if ($this->hasTablespaces() && $tablespace != '') {
			$this->fieldClean($tablespace);
			$sql .= " TABLESPACE \"{$tablespace}\"";
		}

		// Predicate
		if (trim($where) != '') {
			$sql .= " WHERE ({$where})";
		}

		return $this->execute($sql);
	}

	/**
	 * Removes an index from the database
	 * @param $index The index to drop
	 * @param $cascade True to cascade drop, false to restrict
	 * @return 0 success
	 */
	function dropIndex($index, $cascade) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($index);

		$sql = "DROP INDEX \"{$f_schema}\".\"{$index}\"";
		if ($cascade) $sql .= " CASCADE";

		return $this->execute($sql);
	}

	/**
	 * Rebuild indexes
	 * @param $type 'DATABASE' or 'TABLE' or 'INDEX'
	 * @param $name The name of the specific database, table, or index to be reindexed
	 * @param $force If true, recreates indexes forcedly in PostgreSQL 7.0-7.1, forces rebuild of system indexes in 7.2-7.3, ignored in >=7.4
	 */
	function reindex($type, $name, $force = false) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($name);
		switch($type) {
			case 'DATABASE':
				$sql = "REINDEX {$type} \"{$name}\"";
				if ($force) $sql .= ' FORCE';
				break;
			case 'TABLE':
			case 'INDEX':
				$sql = "REINDEX {$type} \"{$f_schema}\".\"{$name}\"";
				if ($force) $sql .= ' FORCE';
				break;
			default:
				return -1;
	}

		return $this->execute($sql);
	}

	/**
	 * Clusters an index
	 * @param $index The name of the index
	 * @param $table The table the index is on
	 * @return 0 success
	 */
	function clusterIndex($table='', $index='') {
		
		$sql = 'CLUSTER';
		
		// We don't bother with a transaction here, as there's no point rolling
		// back an expensive cluster if a cheap analyze fails for whatever reason
		
		if (!empty($table)) {
			$f_schema = $this->_schema;
			$this->fieldClean($f_schema);
			$this->fieldClean($table);
			$sql .= " \"{$f_schema}\".\"{$table}\"";
			
			if (!empty($index)) {
				$this->fieldClean($index);
				$sql .= " USING \"{$index}\"";
			}
		}

		return $this->execute($sql);
	}

	// Constraint functions

	/**
	 * Returns a list of all constraints on a table
	 * @param $table The table to find rules for
	 * @return A recordset
	 */
	function getConstraints($table) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($table);

		// This SQL is greatly complicated by the need to retrieve
		// index clustering information for primary and unique constraints
		$sql = "SELECT
				pc.conname,
				pg_catalog.pg_get_constraintdef(pc.oid, true) AS consrc,
				pc.contype,
				CASE WHEN pc.contype='u' OR pc.contype='p' THEN (
					SELECT
						indisclustered
					FROM
						pg_catalog.pg_depend pd,
						pg_catalog.pg_class pl,
						pg_catalog.pg_index pi
					WHERE
						pd.refclassid=pc.tableoid
						AND pd.refobjid=pc.oid
						AND pd.objid=pl.oid
						AND pl.oid=pi.indexrelid
				) ELSE
					NULL
				END AS indisclustered
			FROM
				pg_catalog.pg_constraint pc
			WHERE
				pc.conrelid = (SELECT oid FROM pg_catalog.pg_class WHERE relname='{$table}'
					AND relnamespace = (SELECT oid FROM pg_catalog.pg_namespace
					WHERE nspname='{$c_schema}'))
			ORDER BY
				1
		";

		return $this->selectSet($sql);
	}

	/**
	 * Returns a list of all constraints on a table,
	 * including constraint name, definition, related col and referenced namespace,
	 * table and col if needed
	 * @param $table the table where we are looking for fk
	 * @return a recordset
	 */
	function getConstraintsWithFields($table) {

		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($table);

		// get the max number of col used in a constraint for the table
		$sql = "SELECT DISTINCT
			max(SUBSTRING(array_dims(c.conkey) FROM  \$pattern\$^\\[.*:(.*)\\]$\$pattern\$)) as nb
		FROM pg_catalog.pg_constraint AS c
			JOIN pg_catalog.pg_class AS r ON (c.conrelid=r.oid)
			JOIN pg_catalog.pg_namespace AS ns ON (r.relnamespace=ns.oid)
		WHERE
			r.relname = '{$table}' AND ns.nspname='{$c_schema}'";

		$rs = $this->selectSet($sql);

		if ($rs->EOF) $max_col = 0;
		else $max_col = $rs->fields['nb'];

		$sql = '
			SELECT
				c.oid AS conid, c.contype, c.conname, pg_catalog.pg_get_constraintdef(c.oid, true) AS consrc,
				ns1.nspname as p_schema, r1.relname as p_table, ns2.nspname as f_schema,
				r2.relname as f_table, f1.attname as p_field, f1.attnum AS p_attnum, f2.attname as f_field,
				f2.attnum AS f_attnum, pg_catalog.obj_description(c.oid, \'pg_constraint\') AS constcomment,
				c.conrelid, c.confrelid
			FROM
				pg_catalog.pg_constraint AS c
				JOIN pg_catalog.pg_class AS r1 ON (c.conrelid=r1.oid)
				JOIN pg_catalog.pg_attribute AS f1 ON (f1.attrelid=r1.oid AND (f1.attnum=c.conkey[1]';
		for ($i = 2; $i <= $rs->fields['nb']; $i++) {
			$sql.= " OR f1.attnum=c.conkey[$i]";
		}
		$sql.= '))
				JOIN pg_catalog.pg_namespace AS ns1 ON r1.relnamespace=ns1.oid
				LEFT JOIN (
					pg_catalog.pg_class AS r2 JOIN pg_catalog.pg_namespace AS ns2 ON (r2.relnamespace=ns2.oid)
				) ON (c.confrelid=r2.oid)
				LEFT JOIN pg_catalog.pg_attribute AS f2 ON
					(f2.attrelid=r2.oid AND ((c.confkey[1]=f2.attnum AND c.conkey[1]=f1.attnum)';
		for ($i = 2; $i <= $rs->fields['nb']; $i++)
			$sql.= " OR (c.confkey[$i]=f2.attnum AND c.conkey[$i]=f1.attnum)";

		$sql .= sprintf("))
			WHERE
				r1.relname = '%s' AND ns1.nspname='%s'
			ORDER BY 1", $table, $c_schema);

		return $this->selectSet($sql);
	}

	/**
	 * Adds a primary key constraint to a table
	 * @param $table The table to which to add the primery key
	 * @param $fields (array) An array of fields over which to add the primary key
	 * @param $name (optional) The name to give the key, otherwise default name is assigned
	 * @param $tablespace (optional) The tablespace for the schema, '' indicates default.
	 * @return 0 success
	 * @return -1 no fields given
	 */
	function addPrimaryKey($table, $fields, $name = '', $tablespace = '') {
		if (!is_array($fields) || sizeof($fields) == 0) return -1;
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($table);
		$this->fieldArrayClean($fields);
		$this->fieldClean($name);
		$this->fieldClean($tablespace);

		$sql = "ALTER TABLE \"{$f_schema}\".\"{$table}\" ADD ";
		if ($name != '') $sql .= "CONSTRAINT \"{$name}\" ";
		$sql .= "PRIMARY KEY (\"" . join('","', $fields) . "\")";

		if ($tablespace != '' && $this->hasTablespaces())
			$sql .= " USING INDEX TABLESPACE \"{$tablespace}\"";

		return $this->execute($sql);
	}

	/**
	 * Adds a unique constraint to a table
	 * @param $table The table to which to add the unique key
	 * @param $fields (array) An array of fields over which to add the unique key
	 * @param $name (optional) The name to give the key, otherwise default name is assigned
	 * @param $tablespace (optional) The tablespace for the schema, '' indicates default.
	 * @return 0 success
	 * @return -1 no fields given
	 */
	function addUniqueKey($table, $fields, $name = '', $tablespace = '') {
		if (!is_array($fields) || sizeof($fields) == 0) return -1;
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($table);
		$this->fieldArrayClean($fields);
		$this->fieldClean($name);
		$this->fieldClean($tablespace);

		$sql = "ALTER TABLE \"{$f_schema}\".\"{$table}\" ADD ";
		if ($name != '') $sql .= "CONSTRAINT \"{$name}\" ";
		$sql .= "UNIQUE (\"" . join('","', $fields) . "\")";

		if ($tablespace != '' && $this->hasTablespaces())
			$sql .= " USING INDEX TABLESPACE \"{$tablespace}\"";

		return $this->execute($sql);
	}

	/**
	 * Adds a check constraint to a table
	 * @param $table The table to which to add the check
	 * @param $definition The definition of the check
	 * @param $name (optional) The name to give the check, otherwise default name is assigned
	 * @return 0 success
	 */
	function addCheckConstraint($table, $definition, $name = '') {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($table);
		$this->fieldClean($name);
		// @@ How the heck do you clean a definition???

		$sql = "ALTER TABLE \"{$f_schema}\".\"{$table}\" ADD ";
		if ($name != '') $sql .= "CONSTRAINT \"{$name}\" ";
		$sql .= "CHECK ({$definition})";

		return $this->execute($sql);
	}

	/**
	 * Drops a check constraint from a table
	 * @param $table The table from which to drop the check
	 * @param $name The name of the check to be dropped
	 * @return 0 success
	 * @return -2 transaction error
	 * @return -3 lock error
	 * @return -4 check drop error
	 */
	function dropCheckConstraint($table, $name) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$c_table = $table;
		$this->fieldClean($table);
		$this->clean($c_table);
		$this->clean($name);

		// Begin transaction
		$status = $this->beginTransaction();
		if ($status != 0) return -2;

		// Properly lock the table
		$sql = "LOCK TABLE \"{$f_schema}\".\"{$table}\" IN ACCESS EXCLUSIVE MODE";
		$status = $this->execute($sql);
		if ($status != 0) {
			$this->rollbackTransaction();
			return -3;
		}

		// Delete the check constraint
		$sql = "DELETE FROM pg_relcheck WHERE rcrelid=(SELECT oid FROM pg_catalog.pg_class WHERE relname='{$c_table}'
			AND relnamespace = (SELECT oid FROM pg_catalog.pg_namespace WHERE
			nspname = '{$c_schema}')) AND rcname='{$name}'";
	   	$status = $this->execute($sql);
		if ($status != 0) {
			$this->rollbackTransaction();
			return -4;
		}

		// Update the pg_class catalog to reflect the new number of checks
		$sql = "UPDATE pg_class SET relchecks=(SELECT COUNT(*) FROM pg_relcheck WHERE
					rcrelid=(SELECT oid FROM pg_catalog.pg_class WHERE relname='{$c_table}'
						AND relnamespace = (SELECT oid FROM pg_catalog.pg_namespace WHERE
						nspname = '{$c_schema}')))
					WHERE relname='{$c_table}'";
	   	$status = $this->execute($sql);
		if ($status != 0) {
			$this->rollbackTransaction();
			return -4;
		}

		// Otherwise, close the transaction
		return $this->endTransaction();
	}

	/**
	 * Adds a foreign key constraint to a table
	 * @param $targschema The schema that houses the target table to which to add the foreign key
	 * @param $targtable The table to which to add the foreign key
	 * @param $target The table that contains the target columns
	 * @param $sfields (array) An array of source fields over which to add the foreign key
	 * @param $tfields (array) An array of target fields over which to add the foreign key
	 * @param $upd_action The action for updates (eg. RESTRICT)
	 * @param $del_action The action for deletes (eg. RESTRICT)
	 * @param $match The match type (eg. MATCH FULL)
	 * @param $deferrable The deferrability (eg. NOT DEFERRABLE)
	 * @param $initially The initial deferrability (eg. INITIALLY IMMEDIATE)
	 * @param $name (optional) The name to give the key, otherwise default name is assigned
	 * @return 0 success
	 * @return -1 no fields given
	 */
	function addForeignKey($table, $targschema, $targtable, $sfields, $tfields, $upd_action, $del_action,
	$match, $deferrable, $initially, $name = '') {
		if (!is_array($sfields) || sizeof($sfields) == 0 ||
			!is_array($tfields) || sizeof($tfields) == 0) return -1;
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($table);
		$this->fieldClean($targschema);
		$this->fieldClean($targtable);
		$this->fieldArrayClean($sfields);
		$this->fieldArrayClean($tfields);
		$this->fieldClean($name);

		$sql = "ALTER TABLE \"{$f_schema}\".\"{$table}\" ADD ";
		if ($name != '') $sql .= "CONSTRAINT \"{$name}\" ";
		$sql .= "FOREIGN KEY (\"" . join('","', $sfields) . "\") ";
		// Target table needs to be fully qualified
		$sql .= "REFERENCES \"{$targschema}\".\"{$targtable}\"(\"" . join('","', $tfields) . "\") ";
		if ($match != $this->fkmatches[0]) $sql .= " {$match}";
		if ($upd_action != $this->fkactions[0]) $sql .= " ON UPDATE {$upd_action}";
		if ($del_action != $this->fkactions[0]) $sql .= " ON DELETE {$del_action}";
		if ($deferrable != $this->fkdeferrable[0]) $sql .= " {$deferrable}";
		if ($initially != $this->fkinitial[0]) $sql .= " {$initially}";

		return $this->execute($sql);
	}

	/**
	 * Removes a constraint from a relation
	 * @param $constraint The constraint to drop
	 * @param $relation The relation from which to drop
	 * @param $type The type of constraint (c, f, u or p)
	 * @param $cascade True to cascade drop, false to restrict
	 * @return 0 success
	 */
	function dropConstraint($constraint, $relation, $type, $cascade) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($constraint);
		$this->fieldClean($relation);

		$sql = "ALTER TABLE \"{$f_schema}\".\"{$relation}\" DROP CONSTRAINT \"{$constraint}\"";
		if ($cascade) $sql .= " CASCADE";

		return $this->execute($sql);
	}

	/**
	 * A function for getting all columns linked by foreign keys given a group of tables
	 * @param $tables multi dimensional assoc array that holds schema and table name
	 * @return A recordset of linked tables and columns
	 * @return -1 $tables isn't an array
	 */
	function getLinkingKeys($tables) {
		if (!is_array($tables)) return -1;
		
		$this->clean($tables[0]['tablename']);
		$this->clean($tables[0]['schemaname']);
		$tables_list = "'{$tables[0]['tablename']}'";
		$schema_list = "'{$tables[0]['schemaname']}'";
		$schema_tables_list = "'{$tables[0]['schemaname']}.{$tables[0]['tablename']}'";

		for ($i = 1; $i < sizeof($tables); $i++) {
			$this->clean($tables[$i]['tablename']);
			$this->clean($tables[$i]['schemaname']);
			$tables_list .= ", '{$tables[$i]['tablename']}'";
			$schema_list .= ", '{$tables[$i]['schemaname']}'";
			$schema_tables_list .= ", '{$tables[$i]['schemaname']}.{$tables[$i]['tablename']}'";
		}

		$maxDimension = 1;

		$sql = "
			SELECT DISTINCT
				array_dims(pc.conkey) AS arr_dim,
				pgc1.relname AS p_table
			FROM
				pg_catalog.pg_constraint AS pc,
				pg_catalog.pg_class AS pgc1
			WHERE
				pc.contype = 'f'
				AND (pc.conrelid = pgc1.relfilenode OR pc.confrelid = pgc1.relfilenode)
				AND pgc1.relname IN ($tables_list)
			";

		//parse our output to find the highest dimension of foreign keys since pc.conkey is stored in an array
		$rs = $this->selectSet($sql);
		while (!$rs->EOF) {
			$arrData = explode(':', $rs->fields['arr_dim']);
			$tmpDimension = intval(substr($arrData[1], 0, strlen($arrData[1] - 1)));
			$maxDimension = $tmpDimension > $maxDimension ? $tmpDimension : $maxDimension;
			$rs->MoveNext();
		}

		//we know the highest index for foreign keys that conkey goes up to, expand for us in an IN query
		$cons_str = '( (pfield.attnum = conkey[1] AND cfield.attnum = confkey[1]) ';
		for ($i = 2; $i <= $maxDimension; $i++) {
			$cons_str .= "OR (pfield.attnum = conkey[{$i}] AND cfield.attnum = confkey[{$i}]) ";
		}
		$cons_str .= ') ';

		$sql = "
			SELECT
				pgc1.relname AS p_table,
				pgc2.relname AS f_table,
				pfield.attname AS p_field,
				cfield.attname AS f_field,
				pgns1.nspname AS p_schema,
				pgns2.nspname AS f_schema
			FROM
				pg_catalog.pg_constraint AS pc,
				pg_catalog.pg_class AS pgc1,
				pg_catalog.pg_class AS pgc2,
				pg_catalog.pg_attribute AS pfield,
				pg_catalog.pg_attribute AS cfield,
				(SELECT oid AS ns_id, nspname FROM pg_catalog.pg_namespace WHERE nspname IN ($schema_list) ) AS pgns1,
 				(SELECT oid AS ns_id, nspname FROM pg_catalog.pg_namespace WHERE nspname IN ($schema_list) ) AS pgns2
			WHERE
				pc.contype = 'f'
				AND pgc1.relnamespace = pgns1.ns_id
 				AND pgc2.relnamespace = pgns2.ns_id
				AND pc.conrelid = pgc1.relfilenode
				AND pc.confrelid = pgc2.relfilenode
				AND pfield.attrelid = pc.conrelid
				AND cfield.attrelid = pc.confrelid
				AND $cons_str
				AND pgns1.nspname || '.' || pgc1.relname IN ($schema_tables_list)
				AND pgns2.nspname || '.' || pgc2.relname IN ($schema_tables_list)
		";
		return $this->selectSet($sql);
	}

	/**
	 * Finds the foreign keys that refer to the specified table
	 * @param $table The table to find referrers for
	 * @return A recordset
	 */
	function getReferrers($table) {
		$this->clean($table);

		$status = $this->beginTransaction();
		if ($status != 0) return -1;

		$c_schema = $this->_schema;
		$this->clean($c_schema);

		$sql = "
			SELECT
				pn.nspname,
				pl.relname,
				pc.conname,
				pg_catalog.pg_get_constraintdef(pc.oid) AS consrc
			FROM
				pg_catalog.pg_constraint pc,
				pg_catalog.pg_namespace pn,
				pg_catalog.pg_class pl
			WHERE
				pc.connamespace = pn.oid
				AND pc.conrelid = pl.oid
				AND pc.contype = 'f'
				AND confrelid = (SELECT oid FROM pg_catalog.pg_class WHERE relname='{$table}'
					AND relnamespace = (SELECT oid FROM pg_catalog.pg_namespace
					WHERE nspname='{$c_schema}'))
			ORDER BY 1,2,3
		";

		return $this->selectSet($sql);
		}

	// Domain functions

	/**
	 * Gets all information for a single domain
	 * @param $domain The name of the domain to fetch
	 * @return A recordset
	 */
	function getDomain($domain) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($domain);

		$sql = "
			SELECT
				t.typname AS domname,
				pg_catalog.format_type(t.typbasetype, t.typtypmod) AS domtype,
				t.typnotnull AS domnotnull,
				t.typdefault AS domdef,
				pg_catalog.pg_get_userbyid(t.typowner) AS domowner,
				pg_catalog.obj_description(t.oid, 'pg_type') AS domcomment
			FROM
				pg_catalog.pg_type t
			WHERE
				t.typtype = 'd'
				AND t.typname = '{$domain}'
				AND t.typnamespace = (SELECT oid FROM pg_catalog.pg_namespace
					WHERE nspname = '{$c_schema}')";

		return $this->selectSet($sql);
		}

	/**
	 * Return all domains in current schema.  Excludes domain constraints.
	 * @return All tables, sorted alphabetically
	 */
	function getDomains() {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		
		$sql = "
			SELECT
				t.typname AS domname,
				pg_catalog.format_type(t.typbasetype, t.typtypmod) AS domtype,
				t.typnotnull AS domnotnull,
				t.typdefault AS domdef,
				pg_catalog.pg_get_userbyid(t.typowner) AS domowner,
				pg_catalog.obj_description(t.oid, 'pg_type') AS domcomment
			FROM
				pg_catalog.pg_type t
			WHERE
				t.typtype = 'd'
				AND t.typnamespace = (SELECT oid FROM pg_catalog.pg_namespace
					WHERE nspname='{$c_schema}')
			ORDER BY t.typname";

		return $this->selectSet($sql);
	}

	/**
	 * Get domain constraints
	 * @param $domain The name of the domain whose constraints to fetch
	 * @return A recordset
	 */
	function getDomainConstraints($domain) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($domain);

		$sql = "
			SELECT
				conname,
				contype,
				pg_catalog.pg_get_constraintdef(oid, true) AS consrc
			FROM
				pg_catalog.pg_constraint
			WHERE
				contypid = (
					SELECT oid FROM pg_catalog.pg_type
					WHERE typname='{$domain}'
						AND typnamespace = (
							SELECT oid FROM pg_catalog.pg_namespace
							WHERE nspname = '{$c_schema}')
				)
			ORDER BY conname";

		return $this->selectSet($sql);
	}

	/**
	 * Creates a domain
	 * @param $domain The name of the domain to create
	 * @param $type The base type for the domain
	 * @param $length Optional type length
	 * @param $array True for array type, false otherwise
	 * @param $notnull True for NOT NULL, false otherwise
	 * @param $default Default value for domain
	 * @param $check A CHECK constraint if there is one
	 * @return 0 success
	 */
	function createDomain($domain, $type, $length, $array, $notnull, $default, $check) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($domain);

		$sql = "CREATE DOMAIN \"{$f_schema}\".\"{$domain}\" AS ";

		if ($length == '')
			$sql .= $type;
		else {
			switch ($type) {
				// Have to account for weird placing of length for with/without
				// time zone types
				case 'timestamp with time zone':
				case 'timestamp without time zone':
					$qual = substr($type, 9);
					$sql .= "timestamp({$length}){$qual}";
					break;
				case 'time with time zone':
				case 'time without time zone':
					$qual = substr($type, 4);
					$sql .= "time({$length}){$qual}";
					break;
				default:
					$sql .= "{$type}({$length})";
			}
		}

		// Add array qualifier, if requested
		if ($array) $sql .= '[]';

		if ($notnull) $sql .= ' NOT NULL';
		if ($default != '') $sql .= " DEFAULT {$default}";
		if ($this->hasDomainConstraints() && $check != '') $sql .= " CHECK ({$check})";

		return $this->execute($sql);
	}

	/**
	 * Alters a domain
	 * @param $domain The domain to alter
	 * @param $domdefault The domain default
	 * @param $domnotnull True for NOT NULL, false otherwise
	 * @param $domowner The domain owner
	 * @return 0 success
	 * @return -1 transaction error
	 * @return -2 default error
	 * @return -3 not null error
	 * @return -4 owner error
	 */
	function alterDomain($domain, $domdefault, $domnotnull, $domowner) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($domain);
		$this->fieldClean($domowner);

		$status = $this->beginTransaction();
		if ($status != 0) {
			$this->rollbackTransaction();
			return -1;
		}

		// Default
		if ($domdefault == '')
			$sql = "ALTER DOMAIN \"{$f_schema}\".\"{$domain}\" DROP DEFAULT";
		else
			$sql = "ALTER DOMAIN \"{$f_schema}\".\"{$domain}\" SET DEFAULT {$domdefault}";

		$status = $this->execute($sql);
		if ($status != 0) {
			$this->rollbackTransaction();
			return -2;
		}

		// NOT NULL
		if ($domnotnull)
			$sql = "ALTER DOMAIN \"{$f_schema}\".\"{$domain}\" SET NOT NULL";
		else
			$sql = "ALTER DOMAIN \"{$f_schema}\".\"{$domain}\" DROP NOT NULL";

		$status = $this->execute($sql);
		if ($status != 0) {
			$this->rollbackTransaction();
			return -3;
		}

		// Owner
		$sql = "ALTER DOMAIN \"{$f_schema}\".\"{$domain}\" OWNER TO \"{$domowner}\"";

		$status = $this->execute($sql);
		if ($status != 0) {
			$this->rollbackTransaction();
			return -4;
		}

		return $this->endTransaction();
	}

	/**
	 * Drops a domain.
	 * @param $domain The name of the domain to drop
	 * @param $cascade True to cascade drop, false to restrict
	 * @return 0 success
	 */
	function dropDomain($domain, $cascade) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($domain);

		$sql = "DROP DOMAIN \"{$f_schema}\".\"{$domain}\"";
		if ($cascade) $sql .= " CASCADE";

		return $this->execute($sql);
	}

	/**
	 * Adds a check constraint to a domain
	 * @param $domain The domain to which to add the check
	 * @param $definition The definition of the check
	 * @param $name (optional) The name to give the check, otherwise default name is assigned
	 * @return 0 success
	 */
	function addDomainCheckConstraint($domain, $definition, $name = '') {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($domain);
		$this->fieldClean($name);

		$sql = "ALTER DOMAIN \"{$f_schema}\".\"{$domain}\" ADD ";
		if ($name != '') $sql .= "CONSTRAINT \"{$name}\" ";
		$sql .= "CHECK ({$definition})";

		return $this->execute($sql);
	}

	/**
	 * Drops a domain constraint
	 * @param $domain The domain from which to remove the constraint
	 * @param $constraint The constraint to remove
	 * @param $cascade True to cascade, false otherwise
	 * @return 0 success
	 */
	function dropDomainConstraint($domain, $constraint, $cascade) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($domain);
		$this->fieldClean($constraint);

		$sql = "ALTER DOMAIN \"{$f_schema}\".\"{$domain}\" DROP CONSTRAINT \"{$constraint}\"";
		if ($cascade) $sql .= " CASCADE";

		return $this->execute($sql);
	}

	// Function functions

	/**
	 * Returns all details for a particular function
	 * @param $func The name of the function to retrieve
	 * @return Function info
	 */
	function getFunction($function_oid) {
		$this->clean($function_oid);

		$sql = "
			SELECT
				pc.oid AS prooid, proname, 
				pg_catalog.pg_get_userbyid(proowner) AS proowner,
				nspname as proschema, lanname as prolanguage, procost, prorows,
				pg_catalog.format_type(prorettype, NULL) as proresult, prosrc,
				probin, proretset, proisstrict, provolatile, prosecdef,
				pg_catalog.oidvectortypes(pc.proargtypes) AS proarguments,
				proargnames AS proargnames,
				pg_catalog.obj_description(pc.oid, 'pg_proc') AS procomment,
				proconfig,
				(select array_agg( (select typname from pg_type pt
					where pt.oid = p.oid) ) from unnest(proallargtypes) p)
				AS proallarguments,
				proargmodes
			FROM
				pg_catalog.pg_proc pc, pg_catalog.pg_language pl,
				pg_catalog.pg_namespace pn
			WHERE
				pc.oid = '{$function_oid}'::oid AND pc.prolang = pl.oid
				AND pc.pronamespace = pn.oid
			";

		return $this->selectSet($sql);
	}

	/**
	 * Returns a list of all functions in the database
	 * @param $all If true, will find all available functions, if false just those in search path
	 * @param $type If not null, will find all functions with return value = type
	 *
  	 * @return All functions
	 */
	function getFunctions($all = false, $type = null) {
		if ($all) {
			$where = 'pg_catalog.pg_function_is_visible(p.oid)';
			$distinct = 'DISTINCT ON (p.proname)';

			if ($type) {
				$where .= " AND p.prorettype = (select oid from pg_catalog.pg_type p where p.typname = 'trigger') ";
			}
		}
		else {
			$c_schema = $this->_schema;
			$this->clean($c_schema);
			$where = "n.nspname = '{$c_schema}'";
			$distinct = '';
		}

		$sql = "
			SELECT
				{$distinct}
				p.oid AS prooid,
				p.proname,
				p.proretset,
				pg_catalog.format_type(p.prorettype, NULL) AS proresult,
				pg_catalog.oidvectortypes(p.proargtypes) AS proarguments,
				pl.lanname AS prolanguage,
				pg_catalog.obj_description(p.oid, 'pg_proc') AS procomment,
				p.proname || ' (' || pg_catalog.oidvectortypes(p.proargtypes) || ')' AS proproto,
				CASE WHEN p.proretset THEN 'setof ' ELSE '' END || pg_catalog.format_type(p.prorettype, NULL) AS proreturns,
				u.usename AS proowner,
				CASE p.prokind
  					WHEN 'a' THEN 'agg'
  					WHEN 'w' THEN 'window'
  					WHEN 'p' THEN 'proc'
  					ELSE 'func'
 				END as protype
			FROM pg_catalog.pg_proc p
				INNER JOIN pg_catalog.pg_namespace n ON n.oid = p.pronamespace
				INNER JOIN pg_catalog.pg_language pl ON pl.oid = p.prolang
				LEFT JOIN pg_catalog.pg_user u ON u.usesysid = p.proowner
			WHERE NOT p.prokind = 'a' 
				AND {$where}
			ORDER BY p.proname, proresult
			";

		return $this->selectSet($sql);
	}

	/**
	 * Returns an array containing a function's properties
	 * @param $f The array of data for the function
	 * @return An array containing the properties
	 */
	function getFunctionProperties($f) {
		$temp = array();

		// Volatility
		if ($f['provolatile'] == 'v')
			$temp[] = 'VOLATILE';
		elseif ($f['provolatile'] == 'i')
			$temp[] = 'IMMUTABLE';
		elseif ($f['provolatile'] == 's')
			$temp[] = 'STABLE';
		else
			return -1;

		// Null handling
		$f['proisstrict'] = $this->phpBool($f['proisstrict']);
		if ($f['proisstrict'])
			$temp[] = 'RETURNS NULL ON NULL INPUT';
		else
			$temp[] = 'CALLED ON NULL INPUT';

		// Security
		$f['prosecdef'] = $this->phpBool($f['prosecdef']);
		if ($f['prosecdef'])
			$temp[] = 'SECURITY DEFINER';
		else
			$temp[] = 'SECURITY INVOKER';

		return $temp;
	}

	/**
	 * Updates (replaces) a function.
	 * @param $function_oid The OID of the function
	 * @param $funcname The name of the function to create
	 * @param $newname The new name for the function
	 * @param $args The array of argument types
	 * @param $returns The return type
	 * @param $definition The definition for the new function
	 * @param $language The language the function is written for
	 * @param $flags An array of optional flags
	 * @param $setof True if returns a set, false otherwise
	 * @param $comment The comment on the function
	 * @return 0 success
	 * @return -1 transaction error
	 * @return -3 create function error
	 * @return -4 comment error
	 * @return -5 rename function error
	 * @return -6 alter owner error
	 * @return -7 alter schema error
	 */
	function setFunction($function_oid, $funcname, $newname, $args, $returns, $definition, $language, $flags, $setof, $funcown, $newown, $funcschema, $newschema, $cost, $rows, $comment) {
		// Begin a transaction
		$status = $this->beginTransaction();
		if ($status != 0) {
			$this->rollbackTransaction();
			return -1;
		}

		// Replace the existing function
		$status = $this->createFunction($funcname, $args, $returns, $definition, $language, $flags, $setof, $cost, $rows, $comment, true);
		if ($status != 0) {
			$this->rollbackTransaction();
			return $status;
		}

		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);

		// Rename the function, if necessary
		$this->fieldClean($newname);
		/* $funcname is escaped in createFunction */
		if ($funcname != $newname) {
			$sql = "ALTER FUNCTION \"{$f_schema}\".\"{$funcname}\"({$args}) RENAME TO \"{$newname}\"";
			$status = $this->execute($sql);
			if ($status != 0) {
				$this->rollbackTransaction();
				return -5;
			}

            $funcname = $newname;
		}

		// Alter the owner, if necessary
		if ($this->hasFunctionAlterOwner()) {
			$this->fieldClean($newown);
		    if ($funcown != $newown) {
				$sql = "ALTER FUNCTION \"{$f_schema}\".\"{$funcname}\"({$args}) OWNER TO \"{$newown}\"";
				$status = $this->execute($sql);
				if ($status != 0) {
					$this->rollbackTransaction();
					return -6;
				}
		    }

		}

		// Alter the schema, if necessary
		if ($this->hasFunctionAlterSchema()) {
		    $this->fieldClean($newschema);
		    /* $funcschema is escaped in createFunction */
		    if ($funcschema != $newschema) { 
				$sql = "ALTER FUNCTION \"{$f_schema}\".\"{$funcname}\"({$args}) SET SCHEMA \"{$newschema}\"";
				$status = $this->execute($sql);
				if ($status != 0) {
					$this->rollbackTransaction();
					return -7;
				}
		    }
		}

		return $this->endTransaction();
	}

	/**
	 * Creates a new function.
	 * @param $funcname The name of the function to create
	 * @param $args A comma separated string of types
	 * @param $returns The return type
	 * @param $definition The definition for the new function
	 * @param $language The language the function is written for
	 * @param $flags An array of optional flags
	 * @param $setof True if it returns a set, false otherwise
	 * @param $rows number of rows planner should estimate will be returned
     * @param $cost cost the planner should use in the function execution step
     * @param $comment Comment for the function
	 * @param $replace (optional) True if OR REPLACE, false for normal
	 * @return 0 success
	 * @return -3 create function failed
	 * @return -4 set comment failed
	 */
	function createFunction($funcname, $args, $returns, $definition, $language, $flags, $setof, $cost, $rows, $comment, $replace = false) {
		
		// Begin a transaction
		$status = $this->beginTransaction();
		if ($status != 0) {
			$this->rollbackTransaction();
			return -1;
		}
		
		$this->fieldClean($funcname);
		$this->clean($args);
		$this->fieldClean($language);
		$this->arrayClean($flags);
		$this->clean($cost);
		$this->clean($rows);
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);

		$sql = "CREATE";
		if ($replace) $sql .= " OR REPLACE";
		$sql .= " FUNCTION \"{$f_schema}\".\"{$funcname}\" (";

		if ($args != '')
			$sql .= $args;

		// For some reason, the returns field cannot have quotes...
		$sql .= ") RETURNS ";
		if ($setof) $sql .= "SETOF ";
		$sql .= "{$returns} AS ";

		if (is_array($definition)) {
			$this->arrayClean($definition);
			$sql .= "'" . $definition[0] . "'";
			if ($definition[1]) {
				$sql .= ",'" . $definition[1] . "'";
			}
		} else {
			$this->clean($definition);
			$sql .= "'" . $definition . "'";
		}

		$sql .= " LANGUAGE \"{$language}\"";

		// Add costs
		if (!empty($cost))
			$sql .= " COST {$cost}";

		if ($rows <> 0 ){
			$sql .= " ROWS {$rows}";
		}

		// Add flags
		foreach ($flags as  $v) {
			// Skip default flags
			if ($v == '') continue;
			else $sql .= "\n{$v}";
		}

		$status = $this->execute($sql);
		if ($status != 0) {
			$this->rollbackTransaction();
			return -3;
		}

		/* set the comment */
		$status = $this->setComment('FUNCTION', "\"{$funcname}\"({$args})", null, $comment);
		if ($status != 0) {
			$this->rollbackTransaction();
			return -4;
		}

		return $this->endTransaction();
	}

	/**
	 * Drops a function.
	 * @param $function_oid The OID of the function to drop
	 * @param $cascade True to cascade drop, false to restrict
	 * @return 0 success
	 */
	function dropFunction($function_oid, $cascade) {
		// Function comes in with $object as function OID
		$fn = $this->getFunction($function_oid);
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($fn->fields['proname']);

		$sql = "DROP FUNCTION \"{$f_schema}\".\"{$fn->fields['proname']}\"({$fn->fields['proarguments']})";
		if ($cascade) $sql .= " CASCADE";

		return $this->execute($sql);
	}

	// Type functions

	/**
	 * Returns all details for a particular type
	 * @param $typname The name of the view to retrieve
	 * @return Type info
	 */
	function getType($typname) {
		$this->clean($typname);

		$sql = "SELECT typtype, typbyval, typname, typinput AS typin, typoutput AS typout, typlen, typalign
			FROM pg_type WHERE typname='{$typname}'";

		return $this->selectSet($sql);
	}

	/**
	 * Returns a list of all types in the database
	 * @param $all If true, will find all available types, if false just those in search path
	 * @param $tabletypes If true, will include table types
	 * @param $domains If true, will include domains
	 * @return A recordet
	 */
	function getTypes($all = false, $tabletypes = false, $domains = false) {
		if ($all)
			$where = '1 = 1';
		else {
			$c_schema = $this->_schema;
			$this->clean($c_schema);
			$where = "n.nspname = '{$c_schema}'";
		}
		// Never show system table types
		$where2 = "AND c.relnamespace NOT IN (SELECT oid FROM pg_catalog.pg_namespace WHERE nspname LIKE 'pg@_%' ESCAPE '@')";

		// Create type filter
		$tqry = "'c'";
		if ($tabletypes)
			$tqry .= ", 'r', 'v'";

		// Create domain filter
		if (!$domains)
			$where .= " AND t.typtype != 'd'";

		$sql = "SELECT
				t.typname AS basename,
				pg_catalog.format_type(t.oid, NULL) AS typname,
				pu.usename AS typowner,
				t.typtype,
				pg_catalog.obj_description(t.oid, 'pg_type') AS typcomment
			FROM (pg_catalog.pg_type t
				LEFT JOIN pg_catalog.pg_namespace n ON n.oid = t.typnamespace)
				LEFT JOIN pg_catalog.pg_user pu ON t.typowner = pu.usesysid
			WHERE (t.typrelid = 0 OR (SELECT c.relkind IN ({$tqry}) FROM pg_catalog.pg_class c WHERE c.oid = t.typrelid {$where2}))
			AND t.typname !~ '^_'
			AND {$where}
			ORDER BY typname
		";

		return $this->selectSet($sql);
	}

	/**
	 * Creates a new type
	 * @param ...
	 * @return 0 success
	 */
	function createType($typname, $typin, $typout, $typlen, $typdef,
						$typelem, $typdelim, $typbyval, $typalign, $typstorage) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($typname);
		$this->fieldClean($typin);
		$this->fieldClean($typout);

		$sql = "
			CREATE TYPE \"{$f_schema}\".\"{$typname}\" (
				INPUT = \"{$typin}\",
				OUTPUT = \"{$typout}\",
				INTERNALLENGTH = {$typlen}";
		if ($typdef != '') $sql .= ", DEFAULT = {$typdef}";
		if ($typelem != '') $sql .= ", ELEMENT = {$typelem}";
		if ($typdelim != '') $sql .= ", DELIMITER = {$typdelim}";
		if ($typbyval) $sql .= ", PASSEDBYVALUE, ";
		if ($typalign != '') $sql .= ", ALIGNMENT = {$typalign}";
		if ($typstorage != '') $sql .= ", STORAGE = {$typstorage}";

		$sql .= ")";

		return $this->execute($sql);
	}

	/**
	 * Drops a type.
	 * @param $typname The name of the type to drop
	 * @param $cascade True to cascade drop, false to restrict
	 * @return 0 success
	 */
	function dropType($typname, $cascade) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($typname);

		$sql = "DROP TYPE \"{$f_schema}\".\"{$typname}\"";
		if ($cascade) $sql .= " CASCADE";

		return $this->execute($sql);
	}

	/**
	 * Creates a new enum type in the database
	 * @param $name The name of the type
	 * @param $values An array of values
	 * @param $typcomment Type comment
	 * @return 0 success
	 * @return -1 transaction error
	 * @return -2 no values supplied
	 */
	function createEnumType($name, $values, $typcomment) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($name);

		if (empty($values)) return -2;

		$status = $this->beginTransaction();
		if ($status != 0) return -1;

		$values = array_unique($values);

		$nbval = count($values);

		for ($i = 0; $i < $nbval; $i++)
			$this->clean($values[$i]);

		$sql = "CREATE TYPE \"{$f_schema}\".\"{$name}\" AS ENUM ('";
		$sql.= implode("','", $values);
		$sql .= "')";

		$status = $this->execute($sql);
		if ($status) {
			$this->rollbackTransaction();
			return -1;
		}

		if ($typcomment != '') {
			$status = $this->setComment('TYPE', $name, '', $typcomment, true);
			if ($status) {
				$this->rollbackTransaction();
				return -1;
			}
		}

		return $this->endTransaction();

	}

	/**
	 * Get defined values for a given enum
	 * @return A recordset
	 */
	function getEnumValues($name) {
		$this->clean($name);

		$sql = "SELECT enumlabel AS enumval
		FROM pg_catalog.pg_type t JOIN pg_catalog.pg_enum e ON (t.oid=e.enumtypid)
		WHERE t.typname = '{$name}' ORDER BY e.oid";
		return $this->selectSet($sql);
	}

	/**
	 * Creates a new composite type in the database
	 * @param $name The name of the type
	 * @param $fields The number of fields
	 * @param $field An array of field names
	 * @param $type An array of field types
	 * @param $array An array of '' or '[]' for each type if it's an array or not
	 * @param $length An array of field lengths
	 * @param $colcomment An array of comments
	 * @param $typcomment Type comment
	 * @return 0 success
	 * @return -1 no fields supplied
	 */
	function createCompositeType($name, $fields, $field, $type, $array, $length, $colcomment, $typcomment) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($name);

		$status = $this->beginTransaction();
		if ($status != 0) return -1;

		$found = false;
		$first = true;
		$comment_sql = ''; // Accumulate comments for the columns
		$sql = "CREATE TYPE \"{$f_schema}\".\"{$name}\" AS (";
		for ($i = 0; $i < $fields; $i++) {
			$this->fieldClean($field[$i]);
			$this->clean($type[$i]);
			$this->clean($length[$i]);
			$this->clean($colcomment[$i]);

			// Skip blank columns - for user convenience
			if ($field[$i] == '' || $type[$i] == '') continue;
			// If not the first column, add a comma
			if (!$first) $sql .= ", ";
			else $first = false;

			switch ($type[$i]) {
				// Have to account for weird placing of length for with/without
				// time zone types
				case 'timestamp with time zone':
				case 'timestamp without time zone':
					$qual = substr($type[$i], 9);
					$sql .= "\"{$field[$i]}\" timestamp";
					if ($length[$i] != '') $sql .= "({$length[$i]})";
					$sql .= $qual;
					break;
				case 'time with time zone':
				case 'time without time zone':
					$qual = substr($type[$i], 4);
					$sql .= "\"{$field[$i]}\" time";
					if ($length[$i] != '') $sql .= "({$length[$i]})";
					$sql .= $qual;
					break;
				default:
					$sql .= "\"{$field[$i]}\" {$type[$i]}";
					if ($length[$i] != '') $sql .= "({$length[$i]})";
			}
			// Add array qualifier if necessary
			if ($array[$i] == '[]') $sql .= '[]';

			if ($colcomment[$i] != '') $comment_sql .= "COMMENT ON COLUMN \"{$f_schema}\".\"{$name}\".\"{$field[$i]}\" IS '{$colcomment[$i]}';\n";

			$found = true;
		}

		if (!$found) return -1;

		$sql .= ")";

		$status = $this->execute($sql);
		if ($status) {
			$this->rollbackTransaction();
			return -1;
		}

		if ($typcomment != '') {
			$status = $this->setComment('TYPE', $name, '', $typcomment, true);
			if ($status) {
				$this->rollbackTransaction();
				return -1;
			}
		}

		if ($comment_sql != '') {
			$status = $this->execute($comment_sql);
			if ($status) {
				$this->rollbackTransaction();
				return -1;
			}
		}
		return $this->endTransaction();
	}

	/**
	 * Returns a list of all casts in the database
	 * @return All casts
	 */
	function getCasts() {
		global $conf;

		if ($conf['show_system'])
			$where = '';
		else
			$where = '
				AND n1.nspname NOT LIKE $$pg\_%$$
				AND n2.nspname NOT LIKE $$pg\_%$$
				AND n3.nspname NOT LIKE $$pg\_%$$
			';

		$sql = "
			SELECT
				c.castsource::pg_catalog.regtype AS castsource,
				c.casttarget::pg_catalog.regtype AS casttarget,
				CASE WHEN c.castfunc=0 THEN NULL
				ELSE c.castfunc::pg_catalog.regprocedure END AS castfunc,
				c.castcontext,
				obj_description(c.oid, 'pg_cast') as castcomment
			FROM
				(pg_catalog.pg_cast c LEFT JOIN pg_catalog.pg_proc p ON c.castfunc=p.oid JOIN pg_catalog.pg_namespace n3 ON p.pronamespace=n3.oid),
				pg_catalog.pg_type t1,
				pg_catalog.pg_type t2,
				pg_catalog.pg_namespace n1,
				pg_catalog.pg_namespace n2
			WHERE
				c.castsource=t1.oid
				AND c.casttarget=t2.oid
				AND t1.typnamespace=n1.oid
				AND t2.typnamespace=n2.oid
				{$where}
			ORDER BY 1, 2
		";

		return $this->selectSet($sql);
	}

	/**
	 * Returns a list of all conversions in the database
	 * @return All conversions
	 */
	function getConversions() {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$sql = "
			SELECT
			       c.conname,
			       pg_catalog.pg_encoding_to_char(c.conforencoding) AS conforencoding,
			       pg_catalog.pg_encoding_to_char(c.contoencoding) AS contoencoding,
			       c.condefault,
			       pg_catalog.obj_description(c.oid, 'pg_conversion') AS concomment
			FROM pg_catalog.pg_conversion c, pg_catalog.pg_namespace n
			WHERE n.oid = c.connamespace
			      AND n.nspname='{$c_schema}'
			ORDER BY 1;
		";

		return $this->selectSet($sql);
	}

	// Rule functions

	/**
	 * Returns a list of all rules on a table OR view
	 * @param $table The table to find rules for
	 * @return A recordset
	 */
	function getRules($table) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($table);

		$sql = "
			SELECT *
			FROM pg_catalog.pg_rules
			WHERE
				schemaname='{$c_schema}' AND tablename='{$table}'
			ORDER BY rulename
		";

		return $this->selectSet($sql);
	}

	/**
	 * Edits a rule on a table OR view
	 * @param $name The name of the new rule
	 * @param $event SELECT, INSERT, UPDATE or DELETE
	 * @param $table Table on which to create the rule
	 * @param $where When to execute the rule, '' indicates always
	 * @param $instead True if an INSTEAD rule, false otherwise
	 * @param $type NOTHING for a do nothing rule, SOMETHING to use given action
	 * @param $action The action to take
	 * @return 0 success
	 * @return -1 invalid event
	 */
	function setRule($name, $event, $table, $where, $instead, $type, $action) {
		return $this->createRule($name, $event, $table, $where, $instead, $type, $action, true);
	}

	/**
	 * Creates a rule
	 * @param $name The name of the new rule
	 * @param $event SELECT, INSERT, UPDATE or DELETE
	 * @param $table Table on which to create the rule
	 * @param $where When to execute the rule, '' indicates always
	 * @param $instead True if an INSTEAD rule, false otherwise
	 * @param $type NOTHING for a do nothing rule, SOMETHING to use given action
	 * @param $action The action to take
	 * @param $replace (optional) True to replace existing rule, false otherwise
	 * @return 0 success
	 * @return -1 invalid event
	 */
	function createRule($name, $event, $table, $where, $instead, $type, $action, $replace = false) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($name);
		$this->fieldClean($table);
		if (!in_array($event, $this->rule_events)) return -1;

		$sql = "CREATE";
		if ($replace) $sql .= " OR REPLACE";
		$sql .= " RULE \"{$name}\" AS ON {$event} TO \"{$f_schema}\".\"{$table}\"";
		// Can't escape WHERE clause
		if ($where != '') $sql .= " WHERE {$where}";
		$sql .= " DO";
		if ($instead) $sql .= " INSTEAD";
		if ($type == 'NOTHING')
			$sql .= " NOTHING";
		else $sql .= " ({$action})";

		return $this->execute($sql);
	}

	/**
	 * Removes a rule from a table OR view
	 * @param $rule The rule to drop
	 * @param $relation The relation from which to drop
	 * @param $cascade True to cascade drop, false to restrict
	 * @return 0 success
	 */
	function dropRule($rule, $relation, $cascade) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($rule);
		$this->fieldClean($relation);

		$sql = "DROP RULE \"{$rule}\" ON \"{$f_schema}\".\"{$relation}\"";
		if ($cascade) $sql .= " CASCADE";

		return $this->execute($sql);
	}

	// Trigger functions

	/**
	 * Grabs a single trigger
	 * @param $table The name of a table whose triggers to retrieve
	 * @param $trigger The name of the trigger to retrieve
	 * @return A recordset
	 */
	function getTrigger($table, $trigger) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($table);
		$this->clean($trigger);

		$sql = "
			SELECT * FROM pg_catalog.pg_trigger t, pg_catalog.pg_class c
			WHERE t.tgrelid=c.oid AND c.relname='{$table}' AND t.tgname='{$trigger}'
				AND c.relnamespace=(
					SELECT oid FROM pg_catalog.pg_namespace
					WHERE nspname='{$c_schema}')";

		return $this->selectSet($sql);
	}

	/**
	 * Grabs a list of triggers on a table
	 * @param $table The name of a table whose triggers to retrieve
	 * @return A recordset
	 */
	function getTriggers($table = '') {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($table);

		$sql = "SELECT
				t.tgname, pg_catalog.pg_get_triggerdef(t.oid) AS tgdef,
				CASE WHEN t.tgenabled = 'D' THEN FALSE ELSE TRUE END AS tgenabled, p.oid AS prooid,
				p.proname || ' (' || pg_catalog.oidvectortypes(p.proargtypes) || ')' AS proproto,
				ns.nspname AS pronamespace
			FROM pg_catalog.pg_trigger t, pg_catalog.pg_proc p, pg_catalog.pg_namespace ns
			WHERE t.tgrelid = (SELECT oid FROM pg_catalog.pg_class WHERE relname='{$table}'
				AND relnamespace=(SELECT oid FROM pg_catalog.pg_namespace WHERE nspname='{$c_schema}'))
				AND ( tgconstraint = 0 OR NOT EXISTS
						(SELECT 1 FROM pg_catalog.pg_depend d    JOIN pg_catalog.pg_constraint c
							ON (d.refclassid = c.tableoid AND d.refobjid = c.oid)
						WHERE d.classid = t.tableoid AND d.objid = t.oid AND d.deptype = 'i' AND c.contype = 'f'))
				AND p.oid=t.tgfoid
				AND p.pronamespace = ns.oid";

		return $this->selectSet($sql);
	}

	/**
	 * A helper function for getTriggers that translates
	 * an array of attribute numbers to an array of field names.
	 * Note: Only needed for pre-7.4 servers, this function is deprecated 
	 * @param $trigger An array containing fields from the trigger table
	 * @return The trigger definition string
	 */
	function getTriggerDef($trigger) {

		$this->fieldArrayClean($trigger);
		// Constants to figure out tgtype
		if (!defined('TRIGGER_TYPE_ROW')) define ('TRIGGER_TYPE_ROW', (1 << 0));
		if (!defined('TRIGGER_TYPE_BEFORE')) define ('TRIGGER_TYPE_BEFORE', (1 << 1));
		if (!defined('TRIGGER_TYPE_INSERT')) define ('TRIGGER_TYPE_INSERT', (1 << 2));
		if (!defined('TRIGGER_TYPE_DELETE')) define ('TRIGGER_TYPE_DELETE', (1 << 3));
		if (!defined('TRIGGER_TYPE_UPDATE')) define ('TRIGGER_TYPE_UPDATE', (1 << 4));

		$trigger['tgisconstraint'] = $this->phpBool($trigger['tgisconstraint']);
		$trigger['tgdeferrable'] = $this->phpBool($trigger['tgdeferrable']);
		$trigger['tginitdeferred'] = $this->phpBool($trigger['tginitdeferred']);

		// Constraint trigger or normal trigger
		if ($trigger['tgisconstraint'])
			$tgdef = 'CREATE CONSTRAINT TRIGGER ';
		else
			$tgdef = 'CREATE TRIGGER ';

		$tgdef .= "\"{$trigger['tgname']}\" ";

		// Trigger type
		$findx = 0;
		if (($trigger['tgtype'] & TRIGGER_TYPE_BEFORE) == TRIGGER_TYPE_BEFORE)
			$tgdef .= 'BEFORE';
		else
			$tgdef .= 'AFTER';

		if (($trigger['tgtype'] & TRIGGER_TYPE_INSERT) == TRIGGER_TYPE_INSERT) {
			$tgdef .= ' INSERT';
			$findx++;
		}
		if (($trigger['tgtype'] & TRIGGER_TYPE_DELETE) == TRIGGER_TYPE_DELETE) {
			if ($findx > 0)
				$tgdef .= ' OR DELETE';
			else {
				$tgdef .= ' DELETE';
				$findx++;
			}
		}
		if (($trigger['tgtype'] & TRIGGER_TYPE_UPDATE) == TRIGGER_TYPE_UPDATE) {
			if ($findx > 0)
				$tgdef .= ' OR UPDATE';
			else
				$tgdef .= ' UPDATE';
		}

		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		// Table name
		$tgdef .= " ON \"{$f_schema}\".\"{$trigger['relname']}\" ";

		// Deferrability
		if ($trigger['tgisconstraint']) {
			if ($trigger['tgconstrrelid'] != 0) {
				// Assume constrelname is not null
				$tgdef .= " FROM \"{$trigger['tgconstrrelname']}\" ";
			}
			if (!$trigger['tgdeferrable'])
				$tgdef .= 'NOT ';
			$tgdef .= 'DEFERRABLE INITIALLY ';
			if ($trigger['tginitdeferred'])
				$tgdef .= 'DEFERRED ';
			else
				$tgdef .= 'IMMEDIATE ';
		}

		// Row or statement
		if ($trigger['tgtype'] & TRIGGER_TYPE_ROW == TRIGGER_TYPE_ROW)
			$tgdef .= 'FOR EACH ROW ';
		else
			$tgdef .= 'FOR EACH STATEMENT ';

		// Execute procedure
		$tgdef .= "EXECUTE PROCEDURE \"{$trigger['tgfname']}\"(";

		// Parameters
		// Escape null characters
		$v = addCSlashes($trigger['tgargs'], "\0");
		// Split on escaped null characters
		$params = explode('\\000', $v);
		for ($findx = 0; $findx < $trigger['tgnargs']; $findx++) {
			$param = "'" . str_replace('\'', '\\\'', $params[$findx]) . "'";
			$tgdef .= $param;
			if ($findx < ($trigger['tgnargs'] - 1))
				$tgdef .= ', ';
		}

		// Finish it off
		$tgdef .= ')';

		return $tgdef;
	}

	/**
	 * Returns a list of all functions that can be used in triggers
	 */
	function getTriggerFunctions() {
		return $this->getFunctions(true, 'trigger');
	}

	/**
	 * Creates a trigger
	 * @param $tgname The name of the trigger to create
	 * @param $table The name of the table
	 * @param $tgproc The function to execute
	 * @param $tgtime BEFORE or AFTER
	 * @param $tgevent Event
	 * @param $tgargs The function arguments
	 * @return 0 success
	 */
	function createTrigger($tgname, $table, $tgproc, $tgtime, $tgevent, $tgfrequency, $tgargs) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($tgname);
		$this->fieldClean($table);
		$this->fieldClean($tgproc);

		/* No Statement Level Triggers in PostgreSQL (by now) */
		$sql = "CREATE TRIGGER \"{$tgname}\" {$tgtime}
				{$tgevent} ON \"{$f_schema}\".\"{$table}\"
				FOR EACH {$tgfrequency} EXECUTE PROCEDURE \"{$tgproc}\"({$tgargs})";

		return $this->execute($sql);
	}

	/**
	 * Alters a trigger
	 * @param $table The name of the table containing the trigger
	 * @param $trigger The name of the trigger to alter
	 * @param $name The new name for the trigger
	 * @return 0 success
	 */
	function alterTrigger($table, $trigger, $name) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($table);
		$this->fieldClean($trigger);
		$this->fieldClean($name);

		$sql = "ALTER TRIGGER \"{$trigger}\" ON \"{$f_schema}\".\"{$table}\" RENAME TO \"{$name}\"";

		return $this->execute($sql);
	}

	/**
	 * Drops a trigger
	 * @param $tgname The name of the trigger to drop
	 * @param $table The table from which to drop the trigger
	 * @param $cascade True to cascade drop, false to restrict
	 * @return 0 success
	 */
	function dropTrigger($tgname, $table, $cascade) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($tgname);
		$this->fieldClean($table);

		$sql = "DROP TRIGGER \"{$tgname}\" ON \"{$f_schema}\".\"{$table}\"";
		if ($cascade) $sql .= " CASCADE";

		return $this->execute($sql);
	}

	/**
	 * Enables a trigger
	 * @param $tgname The name of the trigger to enable
	 * @param $table The table in which to enable the trigger
	 * @return 0 success
	 */
	function enableTrigger($tgname, $table) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($tgname);
		$this->fieldClean($table);

		$sql = "ALTER TABLE \"{$f_schema}\".\"{$table}\" ENABLE TRIGGER \"{$tgname}\"";

		return $this->execute($sql);
	}

	/**
	 * Disables a trigger
	 * @param $tgname The name of the trigger to disable
	 * @param $table The table in which to disable the trigger
	 * @return 0 success
	 */
	function disableTrigger($tgname, $table) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($tgname);
		$this->fieldClean($table);

		$sql = "ALTER TABLE \"{$f_schema}\".\"{$table}\" DISABLE TRIGGER \"{$tgname}\"";

		return $this->execute($sql);
	}

	// Operator functions

	/**
	 * Returns a list of all operators in the database
	 * @return All operators
	 */
	function getOperators() {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		// We stick with the subselects here, as you cannot ORDER BY a regtype
		$sql = "
			SELECT
            	po.oid,	po.oprname,
				(SELECT pg_catalog.format_type(oid, NULL) FROM pg_catalog.pg_type pt WHERE pt.oid=po.oprleft) AS oprleftname,
				(SELECT pg_catalog.format_type(oid, NULL) FROM pg_catalog.pg_type pt WHERE pt.oid=po.oprright) AS oprrightname,
				po.oprresult::pg_catalog.regtype AS resultname,
		        pg_catalog.obj_description(po.oid, 'pg_operator') AS oprcomment
			FROM
				pg_catalog.pg_operator po
			WHERE
				po.oprnamespace = (SELECT oid FROM pg_catalog.pg_namespace WHERE nspname='{$c_schema}')
			ORDER BY
				po.oprname, oprleftname, oprrightname
		";

		return $this->selectSet($sql);
	}

	/**
	 * Returns all details for a particular operator
	 * @param $operator_oid The oid of the operator
	 * @return Function info
	 */
	function getOperator($operator_oid) {
		$this->clean($operator_oid);

		$sql = "
			SELECT
            	po.oid, po.oprname,
				oprleft::pg_catalog.regtype AS oprleftname,
				oprright::pg_catalog.regtype AS oprrightname,
				oprresult::pg_catalog.regtype AS resultname,
				po.oprcanhash,
				oprcanmerge,
				oprcom::pg_catalog.regoperator AS oprcom,
				oprnegate::pg_catalog.regoperator AS oprnegate,
				po.oprcode::pg_catalog.regproc AS oprcode,
				po.oprrest::pg_catalog.regproc AS oprrest,
				po.oprjoin::pg_catalog.regproc AS oprjoin
			FROM
				pg_catalog.pg_operator po
			WHERE
				po.oid='{$operator_oid}'
		";

		return $this->selectSet($sql);
	}

	/**
	 * Drops an operator
	 * @param $operator_oid The OID of the operator to drop
	 * @param $cascade True to cascade drop, false to restrict
	 * @return 0 success
	 */
	function dropOperator($operator_oid, $cascade) {
		// Function comes in with $object as operator OID
		$opr = $this->getOperator($operator_oid);
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($opr->fields['oprname']);

		$sql = "DROP OPERATOR \"{$f_schema}\".{$opr->fields['oprname']} (";
		// Quoting or formatting here???
		if ($opr->fields['oprleftname'] !== null) $sql .= $opr->fields['oprleftname'] . ', ';
		else $sql .= "NONE, ";
		if ($opr->fields['oprrightname'] !== null) $sql .= $opr->fields['oprrightname'] . ')';
		else $sql .= "NONE)";

		if ($cascade) $sql .= " CASCADE";

		return $this->execute($sql);
	}

	// Operator Class functions

	/**
	 *  Gets all opclasses
	 *
	 * @return A recordset
	 */

	function getOpClasses() {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$sql = "
			SELECT
				pa.amname, po.opcname,
				po.opcintype::pg_catalog.regtype AS opcintype,
				po.opcdefault,
				pg_catalog.obj_description(po.oid, 'pg_opclass') AS opccomment
			FROM
				pg_catalog.pg_opclass po, pg_catalog.pg_am pa, pg_catalog.pg_namespace pn
			WHERE
				po.opcmethod=pa.oid
				AND po.opcnamespace=pn.oid
				AND pn.nspname='{$c_schema}'
			ORDER BY 1,2
			";

		return $this->selectSet($sql);
	}

	// FTS functions

 	/**
 	 * Creates a new FTS configuration.
 	 * @param string $cfgname The name of the FTS configuration to create
 	 * @param string $parser The parser to be used in new FTS configuration
 	 * @param string $locale Locale of the FTS configuration
 	 * @param string $template The existing FTS configuration to be used as template for the new one
 	 * @param string $withmap Should we copy whole map of existing FTS configuration to the new one
 	 * @param string $makeDefault Should this configuration be the default for locale given
 	 * @param string $comment If omitted, defaults to nothing
	 * 
 	 * @return 0 success
 	 */
 	function createFtsConfiguration($cfgname, $parser = '', $template = '', $comment = '') {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
 		$this->fieldClean($cfgname);

 		$sql = "CREATE TEXT SEARCH CONFIGURATION \"{$f_schema}\".\"{$cfgname}\" (";
 		if ($parser != '') {
			$this->fieldClean($parser['schema']);
			$this->fieldClean($parser['parser']);
			$parser = "\"{$parser['schema']}\".\"{$parser['parser']}\"";
			$sql .= " PARSER = {$parser}";
		}
 		if ($template != '') {
			$this->fieldClean($template['schema']);
			$this->fieldClean($template['name']);
 			$sql .= " COPY = \"{$template['schema']}\".\"{$template['name']}\"";
 		}
		$sql .= ")";

 		if ($comment != '') {
 			$status = $this->beginTransaction();
 			if ($status != 0) return -1;
 		}

 		// Create the FTS configuration
 		$status =  $this->execute($sql);
 		if ($status != 0) {
 			$this->rollbackTransaction();
 			return -1;
 		}

 		// Set the comment
 		if ($comment != '') {
 			$status = $this->setComment('TEXT SEARCH CONFIGURATION', $cfgname, '', $comment);
 			if ($status != 0) {
 				$this->rollbackTransaction();
 				return -1;
 			}

 			return $this->endTransaction();
 		}

 		return 0;
 	}

 	/**
 	 * Returns available FTS configurations
	 * @param $all if false, returns schema qualified FTS confs
	 * 
	 * @return A recordset
 	 */
 	function getFtsConfigurations($all = true) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$sql = "
			SELECT
				n.nspname as schema,
				c.cfgname as name,
				pg_catalog.obj_description(c.oid, 'pg_ts_config') as comment
			FROM
				pg_catalog.pg_ts_config c
				JOIN pg_catalog.pg_namespace n ON n.oid = c.cfgnamespace
			WHERE
				pg_catalog.pg_ts_config_is_visible(c.oid)";

		if (!$all)
			$sql.= " AND  n.nspname='{$c_schema}'\n";
		
		$sql.= "ORDER BY name";

 		return $this->selectSet($sql);
 	}

 	/**
 	 * Return all information related to a FTS configuration
 	 * @param $ftscfg The name of the FTS configuration
	 * 
 	 * @return FTS configuration information
 	 */
 	function getFtsConfigurationByName($ftscfg) {
 		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($ftscfg);
 		$sql = "
			SELECT
				n.nspname as schema,
				c.cfgname as name,
				p.prsname as parser,
				c.cfgparser as parser_id,
				pg_catalog.obj_description(c.oid, 'pg_ts_config') as comment
			FROM pg_catalog.pg_ts_config c
				LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.cfgnamespace
				LEFT JOIN pg_catalog.pg_ts_parser p ON p.oid = c.cfgparser
			WHERE pg_catalog.pg_ts_config_is_visible(c.oid)
				AND c.cfgname = '{$ftscfg}'
				AND n.nspname='{$c_schema}'";

 		return $this->selectSet($sql);
 	}

 	/**
 	 * Returns the map of FTS configuration given
	 * (list of mappings (tokens) and their processing dictionaries)
 	 * @param string $ftscfg Name of the FTS configuration
	 * 
	 * @return RecordSet
 	 */
 	function getFtsConfigurationMap($ftscfg) {

 		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->fieldClean($ftscfg);

 		$oidSet = $this->selectSet("SELECT c.oid
			FROM pg_catalog.pg_ts_config AS c
				LEFT JOIN pg_catalog.pg_namespace n ON (n.oid = c.cfgnamespace)
			WHERE c.cfgname = '{$ftscfg}'
				AND n.nspname='{$c_schema}'");

 		$oid = $oidSet->fields['oid'];

 		$sql = "
 			SELECT
    			(SELECT t.alias FROM pg_catalog.ts_token_type(c.cfgparser) AS t WHERE t.tokid = m.maptokentype) AS name,
        		(SELECT t.description FROM pg_catalog.ts_token_type(c.cfgparser) AS t WHERE t.tokid = m.maptokentype) AS description,
				c.cfgname AS cfgname, n.nspname ||'.'|| d.dictname as dictionaries
			FROM
				pg_catalog.pg_ts_config AS c, pg_catalog.pg_ts_config_map AS m, pg_catalog.pg_ts_dict d,
				pg_catalog.pg_namespace n
			WHERE
				c.oid = {$oid}
				AND m.mapcfg = c.oid
				AND m.mapdict = d.oid
				AND d.dictnamespace = n.oid
			ORDER BY name
			";
 		return $this->selectSet($sql);
 	}

 	/**
 	 * Returns FTS parsers available
	 * @param $all if false, return only Parsers from the current schema
	 *
	 * @return RecordSet
 	 */
 	function getFtsParsers($all = true) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
 		$sql = "
			SELECT
			   n.nspname as schema,
			   p.prsname as name,
			   pg_catalog.obj_description(p.oid, 'pg_ts_parser') as comment
			FROM pg_catalog.pg_ts_parser p
				LEFT JOIN pg_catalog.pg_namespace n ON (n.oid = p.prsnamespace)
			WHERE pg_catalog.pg_ts_parser_is_visible(p.oid)";

		if (!$all)
			$sql.= " AND n.nspname='{$c_schema}'\n";

		$sql.= "ORDER BY name";

 		return $this->selectSet($sql);
 	}

 	/**
 	 * Returns FTS dictionaries available
	 * @param $all if false, return only Dics from the current schema
	 *
	 * @returns RecordSet
 	 */
 	function getFtsDictionaries($all = true) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
 		$sql = "
 			SELECT
				n.nspname as schema, d.dictname as name,
				pg_catalog.obj_description(d.oid, 'pg_ts_dict') as comment
			FROM pg_catalog.pg_ts_dict d
				LEFT JOIN pg_catalog.pg_namespace n ON n.oid = d.dictnamespace
			WHERE pg_catalog.pg_ts_dict_is_visible(d.oid)";

		if (!$all)
			$sql.= " AND n.nspname='{$c_schema}'\n";

		$sql.= "ORDER BY name;";

 		return $this->selectSet($sql);
 	}

 	/**
 	 * Returns all FTS dictionary templates available
 	 */
 	function getFtsDictionaryTemplates() {
		
 		$sql = "
 			SELECT
				n.nspname as schema,
				t.tmplname as name,
				( SELECT COALESCE(np.nspname, '(null)')::pg_catalog.text || '.' || p.proname
					FROM pg_catalog.pg_proc p
					LEFT JOIN pg_catalog.pg_namespace np ON np.oid = p.pronamespace
					WHERE t.tmplinit = p.oid ) AS  init,
				( SELECT COALESCE(np.nspname, '(null)')::pg_catalog.text || '.' || p.proname
					FROM pg_catalog.pg_proc p
					LEFT JOIN pg_catalog.pg_namespace np ON np.oid = p.pronamespace
					WHERE t.tmpllexize = p.oid ) AS  lexize,
				pg_catalog.obj_description(t.oid, 'pg_ts_template') as comment
			FROM pg_catalog.pg_ts_template t
				LEFT JOIN pg_catalog.pg_namespace n ON n.oid = t.tmplnamespace
			WHERE pg_catalog.pg_ts_template_is_visible(t.oid)
			ORDER BY name;";

 		return $this->selectSet($sql);
 	}

 	/**
 	 * Drops FTS coniguration
	 * @param $ftscfg The configuration's name
	 * @param $cascade Cascade to dependenced objects
	 *
	 * @return 0 on success
 	 */
 	function dropFtsConfiguration($ftscfg, $cascade) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
 		$this->fieldClean($ftscfg);

 		$sql = "DROP TEXT SEARCH CONFIGURATION \"{$f_schema}\".\"{$ftscfg}\"";
 		if ($cascade) $sql .=  ' CASCADE';

 		return $this->execute($sql);
 	}

 	/**
 	 * Drops FTS dictionary
	 * @param $ftsdict The dico's name
	 * @param $cascade Cascade to dependenced objects
 	 *
 	 * @todo Support of dictionary templates dropping
	 * @return 0 on success
 	 */
 	function dropFtsDictionary($ftsdict, $cascade) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
 		$this->fieldClean($ftsdict);

 		$sql = "DROP TEXT SEARCH DICTIONARY";
 		$sql .= " \"{$f_schema}\".\"{$ftsdict}\"";
 		if ($cascade) $sql .= ' CASCADE';

 		return $this->execute($sql);
 	}

 	/**
 	 * Alters FTS configuration
	 * @param $cfgname The conf's name
	 * @param $comment A comment on for the conf
	 * @param $name The new conf name
	 *
	 * @return 0 on success
 	 */
 	function updateFtsConfiguration($cfgname, $comment, $name) {

 		$status = $this->beginTransaction();
 		if ($status != 0) {
 			$this->rollbackTransaction();
 			return -1;
 		}

		$this->fieldClean($cfgname);

 		$status = $this->setComment('TEXT SEARCH CONFIGURATION', $cfgname, '', $comment);
 		if ($status != 0) {
 			$this->rollbackTransaction();
 			return -1;
 		}

 		// Only if the name has changed
 		if ($name != $cfgname) {
			$f_schema = $this->_schema;
			$this->fieldClean($f_schema);
			$this->fieldClean($name);

 			$sql = "ALTER TEXT SEARCH CONFIGURATION \"{$f_schema}\".\"{$cfgname}\" RENAME TO \"{$name}\"";
 			$status = $this->execute($sql);
 			if ($status != 0) {
 				$this->rollbackTransaction();
 				return -1;
 			}
 		}

 		return $this->endTransaction();
 	}

 	/**
 	 * Creates a new FTS dictionary or FTS dictionary template.
 	 * @param string $dictname The name of the FTS dictionary to create
 	 * @param boolean $isTemplate Flag whether we create usual dictionary or dictionary template
 	 * @param string $template The existing FTS dictionary to be used as template for the new one
 	 * @param string $lexize The name of the function, which does transformation of input word
 	 * @param string $init The name of the function, which initializes dictionary
 	 * @param string $option Usually, it stores various options required for the dictionary
 	 * @param string $comment If omitted, defaults to nothing
	 * 
 	 * @return 0 success
 	 */
 	function createFtsDictionary($dictname, $isTemplate = false, $template = '', $lexize = '',
		$init = '', $option = '', $comment = '') {

		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
 		$this->fieldClean($dictname);
 		$this->fieldClean($template);
 		$this->fieldClean($lexize);
 		$this->fieldClean($init);
 		$this->fieldClean($option);

 		$sql = "CREATE TEXT SEARCH";
 		if ($isTemplate) {
 			$sql .= " TEMPLATE \"{$f_schema}\".\"{$dictname}\" (";
 			if ($lexize != '') $sql .= " LEXIZE = {$lexize}";
 			if ($init != '') $sql .= ", INIT = {$init}";
            $sql .= ")";
 			$whatToComment = 'TEXT SEARCH TEMPLATE';
 		} else {
 			$sql .= " DICTIONARY \"{$f_schema}\".\"{$dictname}\" (";
 			if ($template != '') {		
				$this->fieldClean($template['schema']);
				$this->fieldClean($template['name']);
				$template = "\"{$template['schema']}\".\"{$template['name']}\"";
			
				$sql .= " TEMPLATE = {$template}";
			}
 			if ($option != '') $sql .= ", {$option}";
            $sql .= ")";
 			$whatToComment = 'TEXT SEARCH DICTIONARY';
 		}

		/* if comment, begin a transaction to
		 * run both commands */
 		if ($comment != '') {
 			$status = $this->beginTransaction();
 			if ($status != 0) return -1;
 		}

 		// Create the FTS dictionary
 		$status =  $this->execute($sql);
 		if ($status != 0) {
 			$this->rollbackTransaction();
 			return -1;
 		}

 		// Set the comment
 		if ($comment != '') {
 			$status = $this->setComment($whatToComment, $dictname, '', $comment);
 			if ($status != 0) {
 				$this->rollbackTransaction();
 				return -1;
 			}
 		}

 		return $this->endTransaction();
 	}

 	/**
 	 * Alters FTS dictionary or dictionary template
	 * @param $dictname The dico's name 
	 * @param $comment The comment
	 * @param $name The new dico's name
	 *
	 * @return 0 on success
 	 */
 	function updateFtsDictionary($dictname, $comment, $name) {

 		$status = $this->beginTransaction();
 		if ($status != 0) {
 			$this->rollbackTransaction();
 			return -1;
 		}
		
		$this->fieldClean($dictname);
 		$status = $this->setComment('TEXT SEARCH DICTIONARY', $dictname, '', $comment);
 		if ($status != 0) {
 			$this->rollbackTransaction();
 			return -1;
 		}

 		// Only if the name has changed
 		if ($name != $dictname) {
			$f_schema = $this->_schema;
			$this->fieldClean($f_schema);
 			$this->fieldClean($name);

 			$sql = "ALTER TEXT SEARCH DICTIONARY \"{$f_schema}\".\"{$dictname}\" RENAME TO \"{$name}\"";
 			$status = $this->execute($sql);
 			if ($status != 0) {
 				$this->rollbackTransaction();
 				return -1;
 			}
 		}

 		return $this->endTransaction();
 	}

 	/**
 	 * Return all information relating to a FTS dictionary
 	 * @param $ftsdict The name of the FTS dictionary
	 * 
 	 * @return RecordSet of FTS dictionary information
 	 */
 	function getFtsDictionaryByName($ftsdict) {
	
 		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($ftsdict);
		
 		$sql = "SELECT
			   n.nspname as schema,
			   d.dictname as name,
			   ( SELECT COALESCE(nt.nspname, '(null)')::pg_catalog.text || '.' || t.tmplname FROM
				 pg_catalog.pg_ts_template t
									  LEFT JOIN pg_catalog.pg_namespace nt ON nt.oid = t.tmplnamespace
									  WHERE d.dicttemplate = t.oid ) AS  template,
			   d.dictinitoption as init,
			   pg_catalog.obj_description(d.oid, 'pg_ts_dict') as comment
			FROM pg_catalog.pg_ts_dict d
				LEFT JOIN pg_catalog.pg_namespace n ON n.oid = d.dictnamespace
			WHERE d.dictname = '{$ftsdict}'
			   AND pg_catalog.pg_ts_dict_is_visible(d.oid)
			   AND n.nspname='{$c_schema}'
			ORDER BY name";

 		return $this->selectSet($sql);
 	}

 	/**
 	 * Creates/updates/deletes FTS mapping.
 	 * @param string $cfgname The name of the FTS configuration to alter
 	 * @param array $mapping Array of tokens' names
 	 * @param string $action What to do with the mapping: add, alter or drop
 	 * @param string $dictname Dictionary that will process tokens given or null in case of drop action
	 * 
 	 * @return 0 success
 	 */
 	function changeFtsMapping($ftscfg, $mapping, $action, $dictname = null) {

 		if (count($mapping) > 0) {
			$f_schema = $this->_schema;
			$this->fieldClean($f_schema);
			$this->fieldClean($ftscfg);
			$this->fieldClean($dictname);
			$this->arrayClean($mapping);
		
 			switch ($action) {
 				case 'alter':
 					$whatToDo = "ALTER";
 					break;
 				case 'drop':
 					$whatToDo = "DROP";
 					break;
 				default:
 					$whatToDo = "ADD";
 					break;
 			}

 			$sql = "ALTER TEXT SEARCH CONFIGURATION \"{$f_schema}\".\"{$ftscfg}\" {$whatToDo} MAPPING FOR ";
 			$sql .= implode(",", $mapping);
 			if ($action != 'drop' && !empty($dictname)) {
 				$sql .= " WITH {$dictname}";
 			}

 			return $this->execute($sql);
 		}
		else {
 			return -1;
 		}
 	}

 	/**
 	 * Return all information related to a given FTS configuration's mapping
 	 * @param $ftscfg The name of the FTS configuration
 	 * @param $mapping The name of the mapping
	 * 
 	 * @return FTS configuration information
 	 */
 	function getFtsMappingByName($ftscfg, $mapping) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
 		$this->clean($ftscfg);
 		$this->clean($mapping);

 		$oidSet = $this->selectSet("SELECT c.oid, cfgparser
			FROM pg_catalog.pg_ts_config AS c
				LEFT JOIN pg_catalog.pg_namespace AS n ON n.oid = c.cfgnamespace
			WHERE c.cfgname = '{$ftscfg}'
				AND n.nspname='{$c_schema}'");
				
 		$oid = $oidSet->fields['oid'];
 		$cfgparser = $oidSet->fields['cfgparser'];

 		$tokenIdSet = $this->selectSet("SELECT tokid
			FROM pg_catalog.ts_token_type({$cfgparser})
			WHERE alias = '{$mapping}'");

 		$tokid = $tokenIdSet->fields['tokid'];

 		$sql = "SELECT
			    (SELECT t.alias FROM pg_catalog.ts_token_type(c.cfgparser) AS t WHERE t.tokid = m.maptokentype) AS name,
    	            d.dictname as dictionaries
			FROM pg_catalog.pg_ts_config AS c, pg_catalog.pg_ts_config_map AS m, pg_catalog.pg_ts_dict d
			WHERE c.oid = {$oid} AND m.mapcfg = c.oid AND m.maptokentype = {$tokid} AND m.mapdict = d.oid
			LIMIT 1;";

 		return $this->selectSet($sql);
 	}

 	/**
 	 * Return list of FTS mappings possible for given parser
	 * (specified by given configuration since configuration can only have 1 parser)
	 * @param $ftscfg The config's name that use the parser
	 *
	 * @return 0 on success
 	 */
 	function getFtsMappings($ftscfg) {
		
 		$cfg = $this->getFtsConfigurationByName($ftscfg);
		
 		$sql = "SELECT alias AS name, description
			FROM pg_catalog.ts_token_type({$cfg->fields['parser_id']})
			ORDER BY name";

 		return $this->selectSet($sql);
 	}

 	// Language functions

 	/**
	 * Gets all languages
	 * @param $all True to get all languages, regardless of show_system
	 * @return A recordset
	 */
	function getLanguages($all = false) {
		global $conf;

		if ($conf['show_system'] || $all)
			$where = '';
		else
			$where = 'WHERE lanispl';

		$sql = "
			SELECT
				lanname, lanpltrusted,
				lanplcallfoid::pg_catalog.regproc AS lanplcallf
			FROM
				pg_catalog.pg_language
			{$where}
			ORDER BY lanname
		";

		return $this->selectSet($sql);
	}

	// Aggregate functions

	/**
	 * Creates a new aggregate in the database
	 * @param $name The name of the aggregate
	 * @param $basetype The input data type of the aggregate
	 * @param $sfunc The name of the state transition function for the aggregate
	 * @param $stype The data type for the aggregate's state value
	 * @param $ffunc The name of the final function for the aggregate
	 * @param $initcond The initial setting for the state value
	 * @param $sortop The sort operator for the aggregate
	 * @param $comment Aggregate comment
	 * @return 0 success
	 * @return -1 error
	 */
	function createAggregate($name, $basetype, $sfunc, $stype, $ffunc, $initcond, $sortop, $comment) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($name);
		$this->fieldClean($basetype);
		$this->fieldClean($sfunc);
		$this->fieldClean($stype);
		$this->fieldClean($ffunc);
		$this->fieldClean($initcond);
		$this->fieldClean($sortop);

		$this->beginTransaction();

		$sql = "CREATE AGGREGATE \"{$f_schema}\".\"{$name}\" (BASETYPE = \"{$basetype}\", SFUNC = \"{$sfunc}\", STYPE = \"{$stype}\"";
		if(trim($ffunc) != '') $sql .= ", FINALFUNC = \"{$ffunc}\"";
		if(trim($initcond) != '') $sql .= ", INITCOND = \"{$initcond}\"";
		if(trim($sortop) != '') $sql .= ", SORTOP = \"{$sortop}\"";
		$sql .= ")";

		$status = $this->execute($sql);
		if ($status) {
			$this->rollbackTransaction();
			return -1;
		}

		if (trim($comment) != '') {
			$status = $this->setComment('AGGREGATE', $name, '', $comment, $basetype);
			if ($status) {
				$this->rollbackTransaction();
				return -1;
			}
		}

		return $this->endTransaction();
	}

	/**
	 * Renames an aggregate function
	 * @param $aggrname The actual name of the aggregate
	 * @param $aggrtype The actual input data type of the aggregate
	 * @param $newaggrname The new name of the aggregate
	 * @return 0 success
	 */
	function renameAggregate($aggrschema, $aggrname, $aggrtype, $newaggrname) {
		/* this function is called from alterAggregate where params are cleaned */
		$sql = "ALTER AGGREGATE \"{$aggrschema}\"" . '.' . "\"{$aggrname}\" (\"{$aggrtype}\") RENAME TO \"{$newaggrname}\"";
		return $this->execute($sql);
	}

	/**
	 * Removes an aggregate function from the database
	 * @param $aggrname The name of the aggregate
	 * @param $aggrtype The input data type of the aggregate
	 * @param $cascade True to cascade drop, false to restrict
	 * @return 0 success
	 */
	function dropAggregate($aggrname, $aggrtype, $cascade) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($aggrname);
		$this->fieldClean($aggrtype);

		$sql = "DROP AGGREGATE \"{$f_schema}\".\"{$aggrname}\" (\"{$aggrtype}\")";
		if ($cascade) $sql .= " CASCADE";

		return $this->execute($sql);
	}

	/**
	 * Gets all information for an aggregate
	 * @param $name The name of the aggregate
	 * @param $basetype The input data type of the aggregate
	 * @return A recordset
	 */
	function getAggregate($name, $basetype) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->fieldclean($name);
		$this->fieldclean($basetype);

		$sql = "
			SELECT p.proname, CASE p.proargtypes[0]
				WHEN 'pg_catalog.\"any\"'::pg_catalog.regtype THEN NULL
				ELSE pg_catalog.format_type(p.proargtypes[0], NULL) END AS proargtypes,
				a.aggtransfn, format_type(a.aggtranstype, NULL) AS aggstype, a.aggfinalfn,
				a.agginitval, a.aggsortop, u.usename, pg_catalog.obj_description(p.oid, 'pg_proc') AS aggrcomment
			FROM pg_catalog.pg_proc p, pg_catalog.pg_namespace n, pg_catalog.pg_user u, pg_catalog.pg_aggregate a
			WHERE n.oid = p.pronamespace AND p.proowner=u.usesysid AND p.oid=a.aggfnoid
				AND p.prokind = 'a' AND n.nspname='{$c_schema}'
				AND p.proname='" . $name . "'
				AND CASE p.proargtypes[0]
					WHEN 'pg_catalog.\"any\"'::pg_catalog.regtype THEN ''
					ELSE pg_catalog.format_type(p.proargtypes[0], NULL)
				END ='" . $basetype . "'";

		return $this->selectSet($sql);
	}

	/**
	 * Gets all aggregates
	 * @return A recordset
	 */
	function getAggregates() {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$sql = "SELECT p.proname, CASE p.proargtypes[0] WHEN 'pg_catalog.\"any\"'::pg_catalog.regtype THEN NULL ELSE
			   pg_catalog.format_type(p.proargtypes[0], NULL) END AS proargtypes, a.aggtransfn, u.usename,
			   pg_catalog.obj_description(p.oid, 'pg_proc') AS aggrcomment
			   FROM pg_catalog.pg_proc p, pg_catalog.pg_namespace n, pg_catalog.pg_user u, pg_catalog.pg_aggregate a
			   WHERE n.oid = p.pronamespace AND p.proowner=u.usesysid AND p.oid=a.aggfnoid
			   AND p.prokind = 'a' AND n.nspname='{$c_schema}' ORDER BY 1, 2";

		return $this->selectSet($sql);
	}

	/**
	 * Changes the owner of an aggregate function
	 * @param $aggrname The name of the aggregate
	 * @param $aggrtype The input data type of the aggregate
	 * @param $newaggrowner The new owner of the aggregate
	 * @return 0 success
	 */
	function changeAggregateOwner($aggrname, $aggrtype, $newaggrowner) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($aggrname);
		$this->fieldClean($newaggrowner);
		$sql = "ALTER AGGREGATE \"{$f_schema}\".\"{$aggrname}\" (\"{$aggrtype}\") OWNER TO \"{$newaggrowner}\"";
		return $this->execute($sql);
	}

	/**
	 * Changes the schema of an aggregate function
	 * @param $aggrname The name of the aggregate
	 * @param $aggrtype The input data type of the aggregate
	 * @param $newaggrschema The new schema for the aggregate
	 * @return 0 success
	 */
	function changeAggregateSchema($aggrname, $aggrtype, $newaggrschema) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($aggrname);
		$this->fieldClean($newaggrschema);
		$sql = "ALTER AGGREGATE \"{$f_schema}\".\"{$aggrname}\" (\"{$aggrtype}\") SET SCHEMA  \"{$newaggrschema}\"";
		return $this->execute($sql);
	}

	/**
	 * Alters an aggregate
	 * @param $aggrname The actual name of the aggregate
	 * @param $aggrtype The actual input data type of the aggregate
	 * @param $aggrowner The actual owner of the aggregate
	 * @param $aggrschema The actual schema the aggregate belongs to
	 * @param $aggrcomment The actual comment for the aggregate
	 * @param $newaggrname The new name of the aggregate
	 * @param $newaggrowner The new owner of the aggregate
	 * @param $newaggrschema The new schema where the aggregate will belong to
	 * @param $newaggrcomment The new comment for the aggregate
	 * @return 0 success
	 * @return -1 change owner error
	 * @return -2 change comment error
	 * @return -3 change schema error
	 * @return -4 change name error
	 */
	function alterAggregate($aggrname, $aggrtype, $aggrowner, $aggrschema, $aggrcomment, $newaggrname, $newaggrowner, $newaggrschema, $newaggrcomment) {
		// Clean fields
		$this->fieldClean($aggrname);
		$this->fieldClean($aggrtype);
		$this->fieldClean($aggrowner);
		$this->fieldClean($aggrschema);
		$this->fieldClean($newaggrname);
		$this->fieldClean($newaggrowner);
		$this->fieldClean($newaggrschema);

		$this->beginTransaction();

		// Change the owner, if it has changed
		if($aggrowner != $newaggrowner) {
			$status = $this->changeAggregateOwner($aggrname, $aggrtype, $newaggrowner);
			if($status != 0) {
				$this->rollbackTransaction();
				return -1;
			}
		}

		// Set the comment, if it has changed
		if($aggrcomment != $newaggrcomment) {
			$status = $this->setComment('AGGREGATE', $aggrname, '', $newaggrcomment, $aggrtype);
			if ($status) {
				$this->rollbackTransaction();
				return -2;
			}
		}

		// Change the schema, if it has changed
		if($aggrschema != $newaggrschema) {
			$status = $this->changeAggregateSchema($aggrname, $aggrtype, $newaggrschema);
			if($status != 0) {
				$this->rollbackTransaction();
				return -3;
			}
		}

		// Rename the aggregate, if it has changed
		if($aggrname != $newaggrname) {
			$status = $this->renameAggregate($newaggrschema, $aggrname, $aggrtype, $newaggrname);
			if($status != 0) {
				$this->rollbackTransaction();
				return -4;
			}
		}

		return $this->endTransaction();
	}

	// Role, User/Group functions

	/**
	 * Returns all roles in the database cluster
	 * @param $rolename (optional) The role name to exclude from the select
	 * @return All roles
	 */
	function getRoles($rolename = '') {
		$sql = '
			SELECT rolname, rolsuper, rolcreatedb, rolcreaterole, rolinherit,
				rolcanlogin, rolconnlimit, rolvaliduntil, rolconfig
			FROM pg_catalog.pg_roles';
		if($rolename) $sql .= " WHERE rolname!='{$rolename}'";
		$sql .= ' ORDER BY rolname';

		return $this->selectSet($sql);
	}

	/**
	 * Returns information about a single role
	 * @param $rolename The name of the role to retrieve
	 * @return The role's data
	 */
	function getRole($rolename) {
		$this->clean($rolename);

		$sql = "
			SELECT rolname, rolsuper, rolcreatedb, rolcreaterole, rolinherit,
				rolcanlogin, rolconnlimit, rolvaliduntil, rolconfig
			FROM pg_catalog.pg_roles WHERE rolname='{$rolename}'";

		return $this->selectSet($sql);
	}

	/**
	 * Grants membership in a role
	 * @param $role The name of the target role
	 * @param $rolename The name of the role that will belong to the target role
	 * @param $admin (optional) Flag to grant the admin option
	 * @return 0 success
	 */
	function grantRole($role, $rolename, $admin=0) {
		$this->fieldClean($role);
		$this->fieldClean($rolename);

		$sql = "GRANT \"{$role}\" TO \"{$rolename}\"";
		if($admin == 1) $sql .= ' WITH ADMIN OPTION';

		return $this->execute($sql);
	}

	/**
	 * Revokes membership in a role
	 * @param $role The name of the target role
	 * @param $rolename The name of the role that will not belong to the target role
	 * @param $admin (optional) Flag to revoke only the admin option
	 * @param $type (optional) Type of revoke: RESTRICT | CASCADE
	 * @return 0 success
	 */
	function revokeRole($role, $rolename, $admin = 0, $type = 'RESTRICT') {
		$this->fieldClean($role);
		$this->fieldClean($rolename);

		$sql = "REVOKE ";
		if($admin == 1) $sql .= 'ADMIN OPTION FOR ';
		$sql .= "\"{$role}\" FROM \"{$rolename}\" {$type}";

		return $this->execute($sql);
	}

	/**
	 * Returns all users in the database cluster
	 * @return All users
	 */
	function getUsers() {
		$sql = "SELECT usename, usesuper, usecreatedb, valuntil AS useexpires, useconfig
			FROM pg_user
			ORDER BY usename";

		return $this->selectSet($sql);
	}

	/**
	 * Returns information about a single user
	 * @param $username The username of the user to retrieve
	 * @return The user's data
	 */
	function getUser($username) {
		$this->clean($username);

		$sql = "SELECT usename, usesuper, usecreatedb, valuntil AS useexpires, useconfig
			FROM pg_user 
			WHERE usename='{$username}'";

		return $this->selectSet($sql);
	}

	/**
	 * Creates a new role
	 * @param $rolename The name of the role to create
	 * @param $password A password for the role
	 * @param $superuser Boolean whether or not the role is a superuser
	 * @param $createdb Boolean whether or not the role can create databases
	 * @param $createrole Boolean whether or not the role can create other roles
	 * @param $inherits Boolean whether or not the role inherits the privileges from parent roles
	 * @param $login Boolean whether or not the role will be allowed to login
	 * @param $connlimit Number of concurrent connections the role can make
	 * @param $expiry String Format 'YYYY-MM-DD HH:MM:SS'.  '' means never expire
	 * @param $memberof (array) Roles to which the new role will be immediately added as a new member
	 * @param $members (array) Roles which are automatically added as members of the new role
	 * @param $adminmembers (array) Roles which are automatically added as admin members of the new role
	 * @return 0 success
	 */
	function createRole($rolename, $password, $superuser, $createdb, $createrole, $inherits, $login, $connlimit, $expiry, $memberof, $members, $adminmembers) {
		$enc = $this->_encryptPassword($rolename, $password);
		$this->fieldClean($rolename);
		$this->clean($enc);
		$this->clean($connlimit);
		$this->clean($expiry);
		$this->fieldArrayClean($memberof);
		$this->fieldArrayClean($members);
		$this->fieldArrayClean($adminmembers);

		$sql = "CREATE ROLE \"{$rolename}\"";
		if ($password != '') $sql .= " WITH ENCRYPTED PASSWORD '{$enc}'";
		$sql .= ($superuser) ? ' SUPERUSER' : ' NOSUPERUSER';
		$sql .= ($createdb) ? ' CREATEDB' : ' NOCREATEDB';
		$sql .= ($createrole) ? ' CREATEROLE' : ' NOCREATEROLE';
		$sql .= ($inherits) ? ' INHERIT' : ' NOINHERIT';
		$sql .= ($login) ? ' LOGIN' : ' NOLOGIN';
		if ($connlimit != '') $sql .= " CONNECTION LIMIT {$connlimit}"; else  $sql .= ' CONNECTION LIMIT -1';
		if ($expiry != '') $sql .= " VALID UNTIL '{$expiry}'"; else $sql .= " VALID UNTIL 'infinity'";
		if (is_array($memberof) && sizeof($memberof) > 0) $sql .= ' IN ROLE "' . join('", "', $memberof) . '"';
		if (is_array($members) && sizeof($members) > 0) $sql .= ' ROLE "' . join('", "', $members) . '"';
		if (is_array($adminmembers) && sizeof($adminmembers) > 0) $sql .= ' ADMIN "' . join('", "', $adminmembers) . '"';

		return $this->execute($sql);
	}

	/**
	 * Adjusts a role's info
	 * @param $rolename The name of the role to adjust
	 * @param $password A password for the role
	 * @param $superuser Boolean whether or not the role is a superuser
	 * @param $createdb Boolean whether or not the role can create databases
	 * @param $createrole Boolean whether or not the role can create other roles
	 * @param $inherits Boolean whether or not the role inherits the privileges from parent roles
	 * @param $login Boolean whether or not the role will be allowed to login
	 * @param $connlimit Number of concurrent connections the role can make
	 * @param $expiry string Format 'YYYY-MM-DD HH:MM:SS'.  '' means never expire
	 * @param $memberof (array) Roles to which the role will be immediately added as a new member
	 * @param $members (array) Roles which are automatically added as members of the role
	 * @param $adminmembers (array) Roles which are automatically added as admin members of the role
	 * @param $memberofold (array) Original roles whose the role belongs to
	 * @param $membersold (array) Original roles that are members of the role
	 * @param $adminmembersold (array) Original roles that are admin members of the role
	 * @return 0 success
	 */
	function setRole($rolename, $password, $superuser, $createdb, $createrole, $inherits, $login, $connlimit, $expiry, $memberof, $members, $adminmembers, $memberofold, $membersold, $adminmembersold) {
		$enc = $this->_encryptPassword($rolename, $password);
		$this->fieldClean($rolename);
		$this->clean($enc);
		$this->clean($connlimit);
		$this->clean($expiry);
		$this->fieldArrayClean($memberof);
		$this->fieldArrayClean($members);
		$this->fieldArrayClean($adminmembers);

		$sql = "ALTER ROLE \"{$rolename}\"";
		if ($password != '') $sql .= " WITH ENCRYPTED PASSWORD '{$enc}'";
		$sql .= ($superuser) ? ' SUPERUSER' : ' NOSUPERUSER';
		$sql .= ($createdb) ? ' CREATEDB' : ' NOCREATEDB';
		$sql .= ($createrole) ? ' CREATEROLE' : ' NOCREATEROLE';
		$sql .= ($inherits) ? ' INHERIT' : ' NOINHERIT';
		$sql .= ($login) ? ' LOGIN' : ' NOLOGIN';
		if ($connlimit != '') $sql .= " CONNECTION LIMIT {$connlimit}"; else $sql .= ' CONNECTION LIMIT -1';
		if ($expiry != '') $sql .= " VALID UNTIL '{$expiry}'"; else $sql .= " VALID UNTIL 'infinity'";

		$status = $this->execute($sql);

		if ($status != 0) return -1;

		//memberof
		$old = explode(',', $memberofold);
		foreach ($memberof as $m) {
			if (!in_array($m, $old)) {
				$status = $this->grantRole($m, $rolename);
				if ($status != 0) return -1;
			}
		}
		if($memberofold)
		{
			foreach ($old as $o) {
				if (!in_array($o, $memberof)) {
					$status = $this->revokeRole($o, $rolename, 0, 'CASCADE');
					if ($status != 0) return -1;
				}
			}
		}

		//members
		$old = explode(',', $membersold);
		foreach ($members as $m) {
			if (!in_array($m, $old)) {
				$status = $this->grantRole($rolename, $m);
				if ($status != 0) return -1;
			}
		}
		if($membersold)
		{
			foreach ($old as $o) {
				if (!in_array($o, $members)) {
					$status = $this->revokeRole($rolename, $o, 0, 'CASCADE');
					if ($status != 0) return -1;
				}
			}
		}

		//adminmembers
		$old = explode(',', $adminmembersold);
		foreach ($adminmembers as $m) {
			if (!in_array($m, $old)) {
				$status = $this->grantRole($rolename, $m, 1);
				if ($status != 0) return -1;
			}
		}
		if($adminmembersold)
		{
			foreach ($old as $o) {
				if (!in_array($o, $adminmembers)) {
					$status = $this->revokeRole($rolename, $o, 1, 'CASCADE');
					if ($status != 0) return -1;
				}
			}
		}

		return $status;
	}

	/**
	 * Renames a role
	 * @param $rolename The name of the role to rename
	 * @param $newrolename The new name of the role
	 * @return 0 success
	 */
	function renameRole($rolename, $newrolename){
		$this->fieldClean($rolename);
		$this->fieldClean($newrolename);

		$sql = "ALTER ROLE \"{$rolename}\" RENAME TO \"{$newrolename}\"";

		return $this->execute($sql);
	}

	/**
	 * Adjusts a role's info and renames it
	 * @param $rolename The name of the role to adjust
	 * @param $password A password for the role
	 * @param $superuser Boolean whether or not the role is a superuser
	 * @param $createdb Boolean whether or not the role can create databases
	 * @param $createrole Boolean whether or not the role can create other roles
	 * @param $inherits Boolean whether or not the role inherits the privileges from parent roles
	 * @param $login Boolean whether or not the role will be allowed to login
	 * @param $connlimit Number of concurrent connections the role can make
	 * @param $expiry string Format 'YYYY-MM-DD HH:MM:SS'.  '' means never expire
	 * @param $memberof (array) Roles to which the role will be immediately added as a new member
	 * @param $members (array) Roles which are automatically added as members of the role
	 * @param $adminmembers (array) Roles which are automatically added as admin members of the role
	 * @param $memberofold (array) Original roles whose the role belongs to
	 * @param $membersold (array) Original roles that are members of the role
	 * @param $adminmembersold (array) Original roles that are admin members of the role
	 * @param $newrolename The new name of the role
	 * @return 0 success
	 * @return -1 transaction error
	 * @return -2 set role attributes error
	 * @return -3 rename error
	 */
	function setRenameRole($rolename, $password, $superuser, $createdb, $createrole,
	$inherits, $login, $connlimit, $expiry, $memberof, $members, $adminmembers,
	$memberofold, $membersold, $adminmembersold, $newrolename) {

		$status = $this->beginTransaction();
		if ($status != 0) return -1;

		if ($rolename != $newrolename){
			$status = $this->renameRole($rolename, $newrolename);
			if ($status != 0) {
				$this->rollbackTransaction();
				return -3;
			}
			$rolename = $newrolename;
		}

		$status = $this->setRole($rolename, $password, $superuser, $createdb, $createrole, $inherits, $login, $connlimit, $expiry, $memberof, $members, $adminmembers, $memberofold, $membersold, $adminmembersold);
		if ($status != 0) {
			$this->rollbackTransaction();
			return -2;
		}

		return $this->endTransaction();
	}

	/**
	 * Removes a role
	 * @param $rolename The name of the role to drop
	 * @return 0 success
	 */
	function dropRole($rolename) {
		$this->fieldClean($rolename);

		$sql = "DROP ROLE \"{$rolename}\"";

		return $this->execute($sql);
	}

	/**
	 * Creates a new user
	 * @param $username The username of the user to create
	 * @param $password A password for the user
	 * @param $createdb boolean Whether or not the user can create databases
	 * @param $createuser boolean Whether or not the user can create other users
	 * @param $expiry string Format 'YYYY-MM-DD HH:MM:SS'.  '' means never expire
	 * @param $group (array) The groups to create the user in
	 * @return 0 success
	 */
	function createUser($username, $password, $createdb, $createuser, $expiry, $groups) {
		$enc = $this->_encryptPassword($username, $password);
		$this->fieldClean($username);
		$this->clean($enc);
		$this->clean($expiry);
		$this->fieldArrayClean($groups);

		$sql = "CREATE USER \"{$username}\"";
		if ($password != '') $sql .= " WITH ENCRYPTED PASSWORD '{$enc}'";
		$sql .= ($createdb) ? ' CREATEDB' : ' NOCREATEDB';
		$sql .= ($createuser) ? ' CREATEUSER' : ' NOCREATEUSER';
		if (is_array($groups) && sizeof($groups) > 0) $sql .= " IN GROUP \"" . join('", "', $groups) . "\"";
		if ($expiry != '') $sql .= " VALID UNTIL '{$expiry}'";
		else $sql .= " VALID UNTIL 'infinity'";

		return $this->execute($sql);
	}

	/**
	 * Renames a user
	 * @param $username The username of the user to rename
	 * @param $newname The new name of the user
	 * @return 0 success
	 */
	function renameUser($username, $newname){
		$this->fieldClean($username);
		$this->fieldClean($newname);

		$sql = "ALTER USER \"{$username}\" RENAME TO \"{$newname}\"";

		return $this->execute($sql);
	}

	/**
	 * Adjusts a user's info
	 * @param $username The username of the user to modify
	 * @param $password A new password for the user
	 * @param $createdb boolean Whether or not the user can create databases
	 * @param $createuser boolean Whether or not the user can create other users
	 * @param $expiry string Format 'YYYY-MM-DD HH:MM:SS'.  '' means never expire.
	 * @return 0 success
	 */
	function setUser($username, $password, $createdb, $createuser, $expiry) {
		$enc = $this->_encryptPassword($username, $password);
		$this->fieldClean($username);
		$this->clean($enc);
		$this->clean($expiry);

		$sql = "ALTER USER \"{$username}\"";
		if ($password != '') $sql .= " WITH ENCRYPTED PASSWORD '{$enc}'";
		$sql .= ($createdb) ? ' CREATEDB' : ' NOCREATEDB';
		$sql .= ($createuser) ? ' CREATEUSER' : ' NOCREATEUSER';
		if ($expiry != '') $sql .= " VALID UNTIL '{$expiry}'";
		else $sql .= " VALID UNTIL 'infinity'";

		return $this->execute($sql);
	}

	/**
	 * Adjusts a user's info and renames the user
	 * @param $username The username of the user to modify
	 * @param $password A new password for the user
	 * @param $createdb boolean Whether or not the user can create databases
	 * @param $createuser boolean Whether or not the user can create other users
	 * @param $expiry string Format 'YYYY-MM-DD HH:MM:SS'.  '' means never expire.
	 * @param $newname The new name of the user
	 * @return 0 success
	 * @return -1 transaction error
	 * @return -2 set user attributes error
	 * @return -3 rename error
	 */
	function setRenameUser($username, $password, $createdb, $createuser, $expiry, $newname) {
		$status = $this->beginTransaction();
		if ($status != 0) return -1;

		if ($username != $newname){
			$status = $this->renameUser($username, $newname);
			if ($status != 0) {
				$this->rollbackTransaction();
				return -3;
			}
			$username = $newname;
		}

		$status = $this->setUser($username, $password, $createdb, $createuser, $expiry);
		if ($status != 0) {
			$this->rollbackTransaction();
			return -2;
		}

		return $this->endTransaction();
	}

	/**
	 * Removes a user
	 * @param $username The username of the user to drop
	 * @return 0 success
	 */
	function dropUser($username) {
		$this->fieldClean($username);

		$sql = "DROP USER \"{$username}\"";

		return $this->execute($sql);
	}

	/**
	 * Determines whether or not a user is a super user
	 * @param $username The username of the user
	 * @return True if is a super user, false otherwise
	 */
	function isSuperUser($username = '') {
		$this->clean($username);

		if (empty($usename)) {
			$val = pg_parameter_status($this->conn->_connectionID, 'is_superuser');
			if ($val !== false) return $val == 'on';
		}

		$sql = "SELECT usesuper FROM pg_user WHERE usename='{$username}'";

		$usesuper = $this->selectField($sql, 'usesuper');
		if ($usesuper == -1) return false;
		else return $usesuper == 't';
	}

	/**
	 * Changes a role's password
	 * @param $rolename The role name
	 * @param $password The new password
	 * @return 0 success
	 */
	function changePassword($rolename, $password) {
		$enc = $this->_encryptPassword($rolename, $password);
		$this->fieldClean($rolename);
		$this->clean($enc);

		$sql = "ALTER ROLE \"{$rolename}\" WITH ENCRYPTED PASSWORD '{$enc}'";

		return $this->execute($sql);
	}

	/**
	 * Adds a group member
	 * @param $groname The name of the group
	 * @param $user The name of the user to add to the group
	 * @return 0 success
	 */
	function addGroupMember($groname, $user) {
		$this->fieldClean($groname);
		$this->fieldClean($user);

		$sql = "ALTER GROUP \"{$groname}\" ADD USER \"{$user}\"";

		return $this->execute($sql);
	}

	/**
	 * Returns all role names which the role belongs to
	 * @param $rolename The role name
	 * @return All role names
	 */
	function getMemberOf($rolename) {
		$this->clean($rolename);

		$sql = "
			SELECT rolname FROM pg_catalog.pg_roles R, pg_auth_members M
			WHERE R.oid=M.roleid
				AND member IN (
					SELECT oid FROM pg_catalog.pg_roles
					WHERE rolname='{$rolename}')
			ORDER BY rolname";

		return $this->selectSet($sql);
	}

	/**
	 * Returns all role names that are members of a role
	 * @param $rolename The role name
	 * @param $admin (optional) Find only admin members
	 * @return All role names
	 */
	function getMembers($rolename, $admin = 'f') {
		$this->clean($rolename);

		$sql = "
			SELECT rolname FROM pg_catalog.pg_roles R, pg_auth_members M
			WHERE R.oid=M.member AND admin_option='{$admin}'
				AND roleid IN (SELECT oid FROM pg_catalog.pg_roles
					WHERE rolname='{$rolename}')
			ORDER BY rolname";

		return $this->selectSet($sql);
	}

	/**
	 * Removes a group member
	 * @param $groname The name of the group
	 * @param $user The name of the user to remove from the group
	 * @return 0 success
	 */
	function dropGroupMember($groname, $user) {
		$this->fieldClean($groname);
		$this->fieldClean($user);

		$sql = "ALTER GROUP \"{$groname}\" DROP USER \"{$user}\"";

		return $this->execute($sql);
	}

	/**
	 * Return users in a specific group
	 * @param $groname The name of the group
	 * @return All users in the group
	 */
	function getGroup($groname) {
		$this->clean($groname);

		$sql = "
			SELECT s.usename FROM pg_catalog.pg_user s, pg_catalog.pg_group g
			WHERE g.groname='{$groname}' AND s.usesysid = ANY (g.grolist)
			ORDER BY s.usename";

		return $this->selectSet($sql);
	}

	/**
	 * Returns all groups in the database cluser
	 * @return All groups
	 */
	function getGroups() {
		$sql = "SELECT groname FROM pg_group ORDER BY groname";

		return $this->selectSet($sql);
	}

	/**
	 * Creates a new group
	 * @param $groname The name of the group
	 * @param $users An array of users to add to the group
	 * @return 0 success
	 */
	function createGroup($groname, $users) {
		$this->fieldClean($groname);

		$sql = "CREATE GROUP \"{$groname}\"";

		if (is_array($users) && sizeof($users) > 0) {
			$this->fieldArrayClean($users);
			$sql .= ' WITH USER "' . join('", "', $users) . '"';
		}

		return $this->execute($sql);
	}

	/**
	 * Removes a group
	 * @param $groname The name of the group to drop
	 * @return 0 success
	 */
	function dropGroup($groname) {
		$this->fieldClean($groname);

		$sql = "DROP GROUP \"{$groname}\"";

		return $this->execute($sql);
	}

	/**
	 * Internal function used for parsing ACLs
	 * @param $acl The ACL to parse (of type aclitem[])
	 * @return Privileges array
	 */
	function _parseACL($acl) {
		// Take off the first and last characters (the braces)
		$acl = substr($acl, 1, strlen($acl) - 2);

		// Pick out individual ACE's by carefully parsing.  This is necessary in order
		// to cope with usernames and stuff that contain commas
		$aces = array();
		$i = $j = 0;
		$in_quotes = false;
		while ($i < strlen($acl)) {
			// If current char is a double quote and it's not escaped, then
			// enter quoted bit
			$char = substr($acl, $i, 1);
			if ($char == '"' && ($i == 0 || substr($acl, $i - 1, 1) != '\\'))
				$in_quotes = !$in_quotes;
			elseif ($char == ',' && !$in_quotes) {
				// Add text so far to the array
				$aces[] = substr($acl, $j, $i - $j);
				$j = $i + 1;
			}
			$i++;
		}
		// Add final text to the array
		$aces[] = substr($acl, $j);

		// Create the array to be returned
		$temp = array();

		// For each ACE, generate an entry in $temp
		foreach ($aces as $v) {

			// If the ACE begins with a double quote, strip them off both ends
			// and unescape backslashes and double quotes
			$unquote = false;
			if (strpos($v, '"') === 0) {
				$v = substr($v, 1, strlen($v) - 2);
				$v = str_replace('\\"', '"', $v);
				$v = str_replace('\\\\', '\\', $v);
			}

			// Figure out type of ACE (public, user or group)
			if (strpos($v, '=') === 0)
				$atype = 'public';
			else if ($this->hasRoles()) {
				$atype = 'role';
			}
			else if (strpos($v, 'group ') === 0) {
				$atype = 'group';
				// Tear off 'group' prefix
				$v = substr($v, 6);
			}
			else
				$atype = 'user';

			// Break on unquoted equals sign...
			$i = 0;
			$in_quotes = false;
			$entity = null;
			$chars = null;
			while ($i < strlen($v)) {
				// If current char is a double quote and it's not escaped, then
				// enter quoted bit
				$char = substr($v, $i, 1);
				$next_char = substr($v, $i + 1, 1);
				if ($char == '"' && ($i == 0 || $next_char != '"')) {
					$in_quotes = !$in_quotes;
				}
				// Skip over escaped double quotes
				elseif ($char == '"' && $next_char == '"') {
					$i++;
				}
				elseif ($char == '=' && !$in_quotes) {
					// Split on current equals sign
					$entity = substr($v, 0, $i);
					$chars = substr($v, $i + 1);
					break;
				}
				$i++;
			}

			// Check for quoting on entity name, and unescape if necessary
			if (strpos($entity, '"') === 0) {
				$entity = substr($entity, 1, strlen($entity) - 2);
				$entity = str_replace('""', '"', $entity);
			}

			// New row to be added to $temp
			// (type, grantee, privileges, grantor, grant option?
			$row = array($atype, $entity, array(), '', array());

			// Loop over chars and add privs to $row
			for ($i = 0; $i < strlen($chars); $i++) {
				// Append to row's privs list the string representing
				// the privilege
				$char = substr($chars, $i, 1);
				if ($char == '*')
					$row[4][] = $this->privmap[substr($chars, $i - 1, 1)];
				elseif ($char == '/') {
					$grantor = substr($chars, $i + 1);
					// Check for quoting
					if (strpos($grantor, '"') === 0) {
						$grantor = substr($grantor, 1, strlen($grantor) - 2);
						$grantor = str_replace('""', '"', $grantor);
					}
					$row[3] = $grantor;
					break;
				}
				else {
					if (!isset($this->privmap[$char]))
						return -3;
					else
						$row[2][] = $this->privmap[$char];
				}
			}

			// Append row to temp
			$temp[] = $row;
		}

		return $temp;
	}

	/**
	 * Grabs an array of users and their privileges for an object,
	 * given its type.
	 * @param $object The name of the object whose privileges are to be retrieved
	 * @param $type The type of the object (eg. database, schema, relation, function or language)
	 * @param $table Optional, column's table if type = column
	 * @return Privileges array
	 * @return -1 invalid type
	 * @return -2 object not found
	 * @return -3 unknown privilege type
	 */
	function getPrivileges($object, $type, $table = null) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($object);

		switch ($type) {
			case 'column':
				$this->clean($table);
				$sql = "
					SELECT E'{' || pg_catalog.array_to_string(attacl, E',') || E'}' as acl
					FROM pg_catalog.pg_attribute a
						LEFT JOIN pg_catalog.pg_class c ON (a.attrelid = c.oid)
						LEFT JOIN pg_catalog.pg_namespace n ON (c.relnamespace=n.oid)
					WHERE n.nspname='{$c_schema}'
						AND c.relname='{$table}'
						AND a.attname='{$object}'";
				break;
			case 'table':
			case 'view':
			case 'sequence':
				$sql = "
					SELECT relacl AS acl FROM pg_catalog.pg_class
					WHERE relname='{$object}'
						AND relnamespace=(SELECT oid FROM pg_catalog.pg_namespace
							WHERE nspname='{$c_schema}')";
				break;
			case 'database':
				$sql = "SELECT datacl AS acl FROM pg_catalog.pg_database WHERE datname='{$object}'";
				break;
			case 'function':
				// Since we fetch functions by oid, they are already constrained to
				// the current schema.
				$sql = "SELECT proacl AS acl FROM pg_catalog.pg_proc WHERE oid='{$object}'";
				break;
			case 'language':
				$sql = "SELECT lanacl AS acl FROM pg_catalog.pg_language WHERE lanname='{$object}'";
				break;
			case 'schema':
				$sql = "SELECT nspacl AS acl FROM pg_catalog.pg_namespace WHERE nspname='{$object}'";
				break;
			case 'tablespace':
				$sql = "SELECT spcacl AS acl FROM pg_catalog.pg_tablespace WHERE spcname='{$object}'";
				break;
			default:
				return -1;
		}

		// Fetch the ACL for object
		$acl = $this->selectField($sql, 'acl');
		if ($acl == -1) return -2;
		elseif ($acl == '' || $acl == null) return array();
		else return $this->_parseACL($acl);
	}

	/**
	 * Grants a privilege to a user, group or public
	 * @param $mode 'GRANT' or 'REVOKE';
	 * @param $type The type of object
	 * @param $object The name of the object
	 * @param $public True to grant to public, false otherwise
	 * @param $usernames The array of usernames to grant privs to.
	 * @param $groupnames The array of group names to grant privs to.
	 * @param $privileges The array of privileges to grant (eg. ('SELECT', 'ALL PRIVILEGES', etc.) )
	 * @param $grantoption True if has grant option, false otherwise
	 * @param $cascade True for cascade revoke, false otherwise
	 * @param $table the column's table if type=column
	 * @return 0 success
	 * @return -1 invalid type
	 * @return -2 invalid entity
	 * @return -3 invalid privileges
	 * @return -4 not granting to anything
	 * @return -4 invalid mode
	 */
	function setPrivileges($mode, $type, $object, $public, $usernames, $groupnames,
		$privileges, $grantoption, $cascade, $table
	) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldArrayClean($usernames);
		$this->fieldArrayClean($groupnames);

		// Input checking
		if (!is_array($privileges) || sizeof($privileges) == 0) return -3;
		if (!is_array($usernames) || !is_array($groupnames) ||
			(!$public && sizeof($usernames) == 0 && sizeof($groupnames) == 0)) return -4;
		if ($mode != 'GRANT' && $mode != 'REVOKE') return -5;

		$sql = $mode;

		// Grant option
		if ($this->hasGrantOption() && $mode == 'REVOKE' && $grantoption) {
			$sql .= ' GRANT OPTION FOR';
		}

		if (in_array('ALL PRIVILEGES', $privileges)) {
			$sql .= ' ALL PRIVILEGES';
		}
		else {
			if ($type == 'column') {
				$this->fieldClean($object);
				$sql .= ' ' . join(" (\"{$object}\"), ", $privileges);
			}
			else {
				$sql .= ' ' . join(', ', $privileges);
			}
		}

		switch ($type) {
			case 'column':
				$sql .= " (\"{$object}\")";
				$object = $table;
			case 'table':
			case 'view':
			case 'sequence':
				$this->fieldClean($object);
				$sql .= " ON \"{$f_schema}\".\"{$object}\"";
				break;
			case 'database':
				$this->fieldClean($object);
				$sql .= " ON DATABASE \"{$object}\"";
				break;
			case 'function':
				// Function comes in with $object as function OID
				$fn = $this->getFunction($object);
				$this->fieldClean($fn->fields['proname']);
				$sql .= " ON FUNCTION \"{$f_schema}\".\"{$fn->fields['proname']}\"({$fn->fields['proarguments']})";
				break;
			case 'language':
				$this->fieldClean($object);
				$sql .= " ON LANGUAGE \"{$object}\"";
				break;
			case 'schema':
				$this->fieldClean($object);
				$sql .= " ON SCHEMA \"{$object}\"";
				break;
			case 'tablespace':
				$this->fieldClean($object);
				$sql .= " ON TABLESPACE \"{$object}\"";
				break;
			default:
				return -1;
		}

		// Dump PUBLIC
		$first = true;
		$sql .= ($mode == 'GRANT') ? ' TO ' : ' FROM ';
		if ($public) {
			$sql .= 'PUBLIC';
			$first = false;
		}
		// Dump users
		foreach ($usernames as $v) {
			if ($first) {
				$sql .= "\"{$v}\"";
				$first = false;
			}
			else {
				$sql .= ", \"{$v}\"";
			}
		}
		// Dump groups
		foreach ($groupnames as $v) {
			if ($first) {
				$sql .= "GROUP \"{$v}\"";
				$first = false;
			}
			else {
				$sql .= ", GROUP \"{$v}\"";
			}
		}

		// Grant option
		if ($this->hasGrantOption() && $mode == 'GRANT' && $grantoption) {
			$sql .= ' WITH GRANT OPTION';
		}

		// Cascade revoke
		if ($this->hasGrantOption() && $mode == 'REVOKE' && $cascade) {
			$sql .= ' CASCADE';
		}

		return $this->execute($sql);
	}

	/**
	 * Helper function that computes encrypted PostgreSQL passwords
	 * @param $username The username
	 * @param $password The password
	 */
	function _encryptPassword($username, $password) {
		return 'md5' . md5($password . $username);
		}

	// Tablespace functions

	/**
	 * Retrieves information for all tablespaces
	 * @param $all Include all tablespaces (necessary when moving objects back to the default space)
	 * @return A recordset
	 */
	function getTablespaces($all = false) {
		global $conf;

		$sql = "SELECT spcname, pg_catalog.pg_get_userbyid(spcowner) AS spcowner, pg_catalog.pg_tablespace_location(oid) as spclocation,
                    (SELECT description FROM pg_catalog.pg_shdescription pd WHERE pg_tablespace.oid=pd.objoid AND pd.classoid='pg_tablespace'::regclass) AS spccomment
					FROM pg_catalog.pg_tablespace";

		if (!$conf['show_system'] && !$all) {
			$sql .= ' WHERE spcname NOT LIKE $$pg\_%$$';
		}

		$sql .= " ORDER BY spcname";

		return $this->selectSet($sql);
	}

	/**
	 * Retrieves a tablespace's information
	 * @return A recordset
	 */
	function getTablespace($spcname) {
		$this->clean($spcname);

		$sql = "SELECT spcname, pg_catalog.pg_get_userbyid(spcowner) AS spcowner, pg_catalog.pg_tablespace_location(oid) as spclocation,
                    (SELECT description FROM pg_catalog.pg_shdescription pd WHERE pg_tablespace.oid=pd.objoid AND pd.classoid='pg_tablespace'::regclass) AS spccomment
					FROM pg_catalog.pg_tablespace WHERE spcname='{$spcname}'";

		return $this->selectSet($sql);
	}

	/**
	 * Creates a tablespace
	 * @param $spcname The name of the tablespace to create
	 * @param $spcowner The owner of the tablespace. '' for current
	 * @param $spcloc The directory in which to create the tablespace
	 * @return 0 success
	 */
	function createTablespace($spcname, $spcowner, $spcloc, $comment='') {
		$this->fieldClean($spcname);
		$this->clean($spcloc);

		$sql = "CREATE TABLESPACE \"{$spcname}\"";

		if ($spcowner != '') {
			$this->fieldClean($spcowner);
			$sql .= " OWNER \"{$spcowner}\"";
	}

		$sql .= " LOCATION '{$spcloc}'";

		$status = $this->execute($sql);
		if ($status != 0) return -1;

		if ($comment != '' && $this->hasSharedComments()) {
			$status = $this->setComment('TABLESPACE',$spcname,'',$comment);
			if ($status != 0) return -2;
		}

		return 0;
	}

	/**
	 * Alters a tablespace
	 * @param $spcname The name of the tablespace
	 * @param $name The new name for the tablespace
	 * @param $owner The new owner for the tablespace
	 * @return 0 success
	 * @return -1 transaction error
	 * @return -2 owner error
	 * @return -3 rename error
	 * @return -4 comment error
	 */
	function alterTablespace($spcname, $name, $owner, $comment='') {
		$this->fieldClean($spcname);
		$this->fieldClean($name);
		$this->fieldClean($owner);

		// Begin transaction
		$status = $this->beginTransaction();
		if ($status != 0) return -1;

		// Owner
		$sql = "ALTER TABLESPACE \"{$spcname}\" OWNER TO \"{$owner}\"";
		$status = $this->execute($sql);
		if ($status != 0) {
			$this->rollbackTransaction();
			return -2;
		}

		// Rename (only if name has changed)
		if ($name != $spcname) {
			$sql = "ALTER TABLESPACE \"{$spcname}\" RENAME TO \"{$name}\"";
			$status = $this->execute($sql);
			if ($status != 0) {
				$this->rollbackTransaction();
				return -3;
			}

			$spcname = $name;
		}

		// Set comment if it has changed
		if (trim($comment) != '' && $this->hasSharedComments()) {
			$status = $this->setComment('TABLESPACE',$spcname,'',$comment);
			if ($status != 0) return -4;
		}

		return $this->endTransaction();
	}

	/**
	 * Drops a tablespace
	 * @param $spcname The name of the domain to drop
	 * @return 0 success
	 */
	function dropTablespace($spcname) {
		$this->fieldClean($spcname);

		$sql = "DROP TABLESPACE \"{$spcname}\"";

		return $this->execute($sql);
		}

	// Administration functions

	/**
	 * Analyze a database
	 * @param $table (optional) The table to analyze
	 */
	function analyzeDB($table = '') {
		if ($table != '') {
			$f_schema = $this->_schema;
			$this->fieldClean($f_schema);
			$this->fieldClean($table);

			$sql = "ANALYZE \"{$f_schema}\".\"{$table}\"";
		}
		else
			$sql = "ANALYZE";

		return $this->execute($sql);
	}

	/**
	 * Vacuums a database
	 * @param $table The table to vacuum
 	 * @param $analyze If true, also does analyze
	 * @param $full If true, selects "full" vacuum
	 * @param $freeze If true, selects aggressive "freezing" of tuples
	 */
	function vacuumDB($table = '', $analyze = false, $full = false, $freeze = false) {

		$sql = "VACUUM";
		if ($full) $sql .= " FULL";
		if ($freeze) $sql .= " FREEZE";
		if ($analyze) $sql .= " ANALYZE";
		if ($table != '') {
			$f_schema = $this->_schema;
			$this->fieldClean($f_schema);
			$this->fieldClean($table);
			$sql .= " \"{$f_schema}\".\"{$table}\"";
		}

		return $this->execute($sql);
	}

	/**
	 * Returns all autovacuum global configuration
	 * @return associative array array( param => value, ...)
	 */
	function getAutovacuum() {

		$_defaults = $this->selectSet("SELECT name, setting
			FROM pg_catalog.pg_settings
			WHERE 
				name = 'autovacuum' 
				OR name = 'autovacuum_vacuum_threshold'
				OR name = 'autovacuum_vacuum_scale_factor'
				OR name = 'autovacuum_analyze_threshold'
				OR name = 'autovacuum_analyze_scale_factor'
				OR name = 'autovacuum_vacuum_cost_delay'
				OR name = 'autovacuum_vacuum_cost_limit'
				OR name = 'vacuum_freeze_min_age'
				OR name = 'autovacuum_freeze_max_age'
			"
		);

		$ret = array();
		while (!$_defaults->EOF) {
			$ret[$_defaults->fields['name']] = $_defaults->fields['setting'];
			$_defaults->moveNext();
		}

		return $ret;
	}

	/**
	 * Returns all available autovacuum per table information.
	 * @return A recordset
	 */
	function saveAutovacuum($table, $vacenabled, $vacthreshold, $vacscalefactor, $anathresold,
		$anascalefactor, $vaccostdelay, $vaccostlimit)
	{	
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($table);

		$sql = "ALTER TABLE \"{$f_schema}\".\"{$table}\" SET (";

		if (!empty($vacenabled)) {
			$this->clean($vacenabled);
			$params[] = "autovacuum_enabled='{$vacenabled}'";
		}
		if (!empty($vacthreshold)) {
			$this->clean($vacthreshold);
			$params[] = "autovacuum_vacuum_threshold='{$vacthreshold}'";
		}
		if (!empty($vacscalefactor)) {
			$this->clean($vacscalefactor);
			$params[] = "autovacuum_vacuum_scale_factor='{$vacscalefactor}'";
		}
		if (!empty($anathresold)) {
			$this->clean($anathresold);
			$params[] = "autovacuum_analyze_threshold='{$anathresold}'";
		}
		if (!empty($anascalefactor)) {
			$this->clean($anascalefactor);
			$params[] = "autovacuum_analyze_scale_factor='{$anascalefactor}'";
		}
		if (!empty($vaccostdelay)) {
			$this->clean($vaccostdelay);
			$params[] = "autovacuum_vacuum_cost_delay='{$vaccostdelay}'";
		}
		if (!empty($vaccostlimit)) {
			$this->clean($vaccostlimit);
			$params[] = "autovacuum_vacuum_cost_limit='{$vaccostlimit}'";
		}

		$sql = $sql . implode(',', $params) . ');';

		return $this->execute($sql);
	}
	
	function dropAutovacuum($table) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($table);
		
		return $this->execute("
			ALTER TABLE \"{$f_schema}\".\"{$table}\" RESET (autovacuum_enabled, autovacuum_vacuum_threshold,
				autovacuum_vacuum_scale_factor, autovacuum_analyze_threshold, autovacuum_analyze_scale_factor,
				autovacuum_vacuum_cost_delay, autovacuum_vacuum_cost_limit
			);"
		);
	}

	/**
	 * Returns all available process information.
	 * @param $database (optional) Find only connections to specified database
	 * @return A recordset
	 */
	function getProcesses($database = null) {
		if ($database === null)
			$sql = "SELECT datname, usename, pid, 
                    case when wait_event is null then 'false' else wait_event_type || '::' || wait_event end as waiting, 
                    query_start, application_name, client_addr, 
                  case when state='idle in transaction' then '<IDLE> in transaction' when state = 'idle' then '<IDLE>' else query end as query 
				FROM pg_catalog.pg_stat_activity
				ORDER BY datname, usename, pid";
		else {
			$this->clean($database);
			$sql = "SELECT datname, usename, pid, 
                    case when wait_event is null then 'false' else wait_event_type || '::' || wait_event end as waiting, 
                    query_start, application_name, client_addr, 
                  case when state='idle in transaction' then '<IDLE> in transaction' when state = 'idle' then '<IDLE>' else query end as query 
				FROM pg_catalog.pg_stat_activity
				WHERE datname='{$database}'
				ORDER BY usename, pid";
		}

		return $this->selectSet($sql);
	}

	/**
	 * Returns table locks information in the current database
	 * @return A recordset
	 */

	function getLocks() {
		global $conf;

		if (!$conf['show_system'])
			$where = 'AND pn.nspname NOT LIKE $$pg\_%$$';
		else
			$where = "AND nspname !~ '^pg_t(emp_[0-9]+|oast)$'";

		$sql = "
			SELECT
				pn.nspname, pc.relname AS tablename, pl.pid, pl.mode, pl.granted, pl.virtualtransaction,
				(select transactionid from pg_catalog.pg_locks l2 where l2.locktype='transactionid'
					and l2.mode='ExclusiveLock' and l2.virtualtransaction=pl.virtualtransaction) as transaction
			FROM
				pg_catalog.pg_locks pl,
				pg_catalog.pg_class pc,
				pg_catalog.pg_namespace pn
			WHERE
				pl.relation = pc.oid AND pc.relnamespace=pn.oid
			{$where}
			ORDER BY pid,nspname,tablename";

		return $this->selectSet($sql);
	}

	/**
	 * Sends a cancel or kill command to a process
	 * @param $pid The ID of the backend process
	 * @param $signal 'CANCEL'
	 * @return 0 success
	 * @return -1 invalid signal type
	 */
	function sendSignal($pid, $signal) {
		// Clean
		$pid = (int)$pid;

		if ($signal == 'CANCEL') 
			$sql = "SELECT pg_catalog.pg_cancel_backend({$pid}) AS val";
		elseif ($signal == 'KILL')  
			$sql = "SELECT pg_catalog.pg_terminate_backend({$pid}) AS val";
		else	
			return -1;
		

		// Execute the query
		$val = $this->selectField($sql, 'val');

		if ($val === 'f') return -1;
		elseif ($val === 't') return 0;
		else return -1;
	}

	// Misc functions

	/**
	 * Sets the comment for an object in the database
	 * @pre All parameters must already be cleaned
	 * @param $obj_type One of 'TABLE' | 'COLUMN' | 'VIEW' | 'SCHEMA' | 'SEQUENCE' | 'TYPE' | 'FUNCTION' | 'AGGREGATE'
	 * @param $obj_name The name of the object for which to attach a comment.
	 * @param $table Name of table that $obj_name belongs to.  Ignored unless $obj_type is 'TABLE' or 'COLUMN'.
	 * @param $comment The comment to add.
	 * @return 0 success
	 */
	function setComment($obj_type, $obj_name, $table, $comment, $basetype = NULL) {
		$sql = "COMMENT ON {$obj_type} " ;
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->clean($comment);  // Passing in an already cleaned comment will lead to double escaped data
                                         // So, while counter-intuitive, it is important to not clean comments before
                                         // calling setComment. We will clean it here instead. 
/*
		$this->fieldClean($table);
		$this->fieldClean($obj_name);
*/

		switch ($obj_type) {
			case 'TABLE':
				$sql .= "\"{$f_schema}\".\"{$table}\" IS ";
				break;
			case 'COLUMN':
				$sql .= "\"{$f_schema}\".\"{$table}\".\"{$obj_name}\" IS ";
				break;
			case 'SEQUENCE':
			case 'VIEW':
			case 'TEXT SEARCH CONFIGURATION':
			case 'TEXT SEARCH DICTIONARY':
			case 'TEXT SEARCH TEMPLATE':
			case 'TEXT SEARCH PARSER':
			case 'TYPE':
				$sql .= "\"{$f_schema}\".";
			case 'DATABASE':
			case 'ROLE':
			case 'SCHEMA':
			case 'TABLESPACE':
				$sql .= "\"{$obj_name}\" IS ";
				break;
			case 'FUNCTION':
				$sql .= "\"{$f_schema}\".{$obj_name} IS ";
				break;
			case 'AGGREGATE':
				$sql .= "\"{$f_schema}\".\"{$obj_name}\" (\"{$basetype}\") IS ";
				break;
			default:
				// Unknown object type
			return -1;
		}

		if ($comment != '')
			$sql .= "'{$comment}';";
		else
			$sql .= 'NULL;';

		return $this->execute($sql);

	}

	/**
	 * A private helper method for executeScript that advances the
	 * character by 1.  In psql this is careful to take into account
	 * multibyte languages, but we don't at the moment, so this function
	 * is someone redundant, since it will always advance by 1
	 * @param &$i The current character position in the line
	 * @param &$prevlen Length of previous character (ie. 1)
	 * @param &$thislen Length of current character (ie. 1)
	 */
	private
	function advance_1(&$i, &$prevlen, &$thislen) {
		$prevlen = $thislen;
		$i += $thislen;
		$thislen = 1;
	}

	/**
	 * Private helper method to detect a valid $foo$ quote delimiter at
	 * the start of the parameter dquote
	 * @return True if valid, false otherwise
	 */
	private
	function valid_dolquote($dquote) {
		// XXX: support multibyte
		return (preg_match('/^[$][$]/', $dquote) || preg_match('/^[$][_[:alpha:]][_[:alnum:]]*[$]/', $dquote));
	}

	/**
	 * Executes an SQL script as a series of SQL statements.  Returns
	 * the result of the final step.  This is a very complicated lexer
	 * based on the REL7_4_STABLE src/bin/psql/mainloop.c lexer in
	 * the PostgreSQL source code.
	 * XXX: It does not handle multibyte languages properly.
	 * @param $name Entry in $_FILES to use
	 * @param $callback (optional) Callback function to call with each query,
	                               its result and line number.
	 * @return True for general success, false on any failure.
	 */
	function executeScript($name, $callback = null) {
		global $data;

		// This whole function isn't very encapsulated, but hey...
		$conn = $data->conn->_connectionID;
		if (!is_uploaded_file($_FILES[$name]['tmp_name'])) return false;

		$fd = fopen($_FILES[$name]['tmp_name'], 'r');
		if (!$fd) return false;

		// Build up each SQL statement, they can be multiline
		$query_buf = null;
		$query_start = 0;
		$in_quote = 0;
		$in_xcomment = 0;
		$bslash_count = 0;
		$dol_quote = null;
		$paren_level = 0;
		$len = 0;
		$i = 0;
		$prevlen = 0;
		$thislen = 0;
		$lineno = 0;

		// Loop over each line in the file
		while (!feof($fd)) {
			$line = fgets($fd);
			$lineno++;

			// Nothing left on line? Then ignore...
			if (trim($line) == '') continue;

		    $len = strlen($line);
		    $query_start = 0;

    		/*
    		 * Parse line, looking for command separators.
    		 *
    		 * The current character is at line[i], the prior character at line[i
    		 * - prevlen], the next character at line[i + thislen].
    		 */
    		$prevlen = 0;
    		$thislen = ($len > 0) ? 1 : 0;

    		for ($i = 0; $i < $len; $this->advance_1($i, $prevlen, $thislen)) {

    			/* was the previous character a backslash? */
    			if ($i > 0 && substr($line, $i - $prevlen, 1) == '\\')
    				$bslash_count++;
    			else
    				$bslash_count = 0;

    			/*
    			 * It is important to place the in_* test routines before the
    			 * in_* detection routines. i.e. we have to test if we are in
    			 * a quote before testing for comments.
    			 */

    			/* in quote? */
    			if ($in_quote !== 0)
    			{
    				/*
    				 * end of quote if matching non-backslashed character.
    				 * backslashes don't count for double quotes, though.
    				 */
    				if (substr($line, $i, 1) == $in_quote &&
    					($bslash_count % 2 == 0 || $in_quote == '"'))
    					$in_quote = 0;
    			}

				/* in or end of $foo$ type quote? */
				else if ($dol_quote) {
					if (strncmp(substr($line, $i), $dol_quote, strlen($dol_quote)) == 0) {
						$this->advance_1($i, $prevlen, $thislen);
						while(substr($line, $i, 1) != '$')
							$this->advance_1($i, $prevlen, $thislen);
						$dol_quote = null;
					}
				}

    			/* start of extended comment? */
    			else if (substr($line, $i, 2) == '/*')
    			{
    				$in_xcomment++;
    				if ($in_xcomment == 1)
    					$this->advance_1($i, $prevlen, $thislen);
    			}

    			/* in or end of extended comment? */
    			else if ($in_xcomment)
    			{
    				if (substr($line, $i, 2) == '*/' && !--$in_xcomment)
    					$this->advance_1($i, $prevlen, $thislen);
    			}

    			/* start of quote? */
    			else if (substr($line, $i, 1) == '\'' || substr($line, $i, 1) == '"') {
    				$in_quote = substr($line, $i, 1);
    		    }

				/*
				 * start of $foo$ type quote?
				 */
				else if (!$dol_quote && $this->valid_dolquote(substr($line, $i))) {
					$dol_end = strpos(substr($line, $i + 1), '$');
					$dol_quote = substr($line, $i, $dol_end + 1);
					$this->advance_1($i, $prevlen, $thislen);
					while (substr($line, $i, 1) != '$') {
						$this->advance_1($i, $prevlen, $thislen);
					}

				}

    			/* single-line comment? truncate line */
    			else if (substr($line, $i, 2) == '--')
    			{
    			    $line = substr($line, 0, $i); /* remove comment */
    				break;
    			}

    			/* count nested parentheses */
				else if (substr($line, $i, 1) == '(') {
    				$paren_level++;
				}

    			else if (substr($line, $i, 1) == ')' && $paren_level > 0) {
    				$paren_level--;
    			}

    			/* semicolon? then send query */
    			else if (substr($line, $i, 1) == ';' && !$bslash_count && !$paren_level)
    			{
    			    $subline = substr(substr($line, 0, $i), $query_start);
    				/* is there anything else on the line? */
    				if (strspn($subline, " \t\n\r") != strlen($subline))
    				{
    					/*
    					 * insert a cosmetic newline, if this is not the first
    					 * line in the buffer
						 */
    					if (strlen($query_buf) > 0)
    					    $query_buf .= "\n";
    						$query_buf .= $subline;
					}
    					$query_buf .= ';';

					/* is there anything in the query_buf? */
					if (trim($query_buf))
					{
						// Execute the query. PHP cannot execute
						// empty queries, unlike libpq
						$res = @pg_query($conn, $query_buf);

						// Call the callback function for display
						if ($callback !== null) $callback($query_buf, $res, $lineno);
            			// Check for COPY request
            			if (pg_result_status($res) == 4) { // 4 == PGSQL_COPY_FROM
            				while (!feof($fd)) {
            					$copy = fgets($fd, 32768);
            					$lineno++;
            					pg_put_line($conn, $copy);
            					if ($copy == "\\.\n" || $copy == "\\.\r\n") {
            						pg_end_copy($conn);
            						break;
            					}
            				}
            			}
            		}

					$query_buf = null;
					$query_start = $i + $thislen;
    			}

				/*
				 * keyword or identifier?
				 * We grab the whole string so that we don't
				 * mistakenly see $foo$ inside an identifier as the start
				 * of a dollar quote.
				 */
				// XXX: multibyte here
				else if (preg_match('/^[_[:alpha:]]$/', substr($line, $i, 1))) {
					$sub = substr($line, $i, $thislen);
					while (preg_match('/^[\$_A-Za-z0-9]$/', $sub)) {
						/* keep going while we still have identifier chars */
						$this->advance_1($i, $prevlen, $thislen);
						$sub = substr($line, $i, $thislen);
					}
					// Since we're now over the next character to be examined, it is necessary
					// to move back one space.
					$i-=$prevlen;
				}
    	    } // end for

    		/* Put the rest of the line in the query buffer. */
    		$subline = substr($line, $query_start);
    		if ($in_quote || $dol_quote || strspn($subline, " \t\n\r") != strlen($subline))
    		{
    			if (strlen($query_buf) > 0)
    			    $query_buf .= "\n";
    			$query_buf .= $subline;
    		}

    		$line = null;

    	} // end while

    	/*
    	 * Process query at the end of file without a semicolon, so long as
    	 * it's non-empty.
		 */
    	if (strlen($query_buf) > 0 && strspn($query_buf, " \t\n\r") != strlen($query_buf))
    	{
			// Execute the query
			$res = @pg_query($conn, $query_buf);

			// Call the callback function for display
			if ($callback !== null) $callback($query_buf, $res, $lineno);
			// Check for COPY request
			if (pg_result_status($res) == 4) { // 4 == PGSQL_COPY_FROM
				while (!feof($fd)) {
					$copy = fgets($fd, 32768);
					$lineno++;
					pg_put_line($conn, $copy);
					if ($copy == "\\.\n" || $copy == "\\.\r\n") {
						pg_end_copy($conn);
						break;
					}
				}
			}
		}

		fclose($fd);

		return true;
	}

	/**
	 * Generates the SQL for the 'select' function
	 * @param $table The table from which to select
	 * @param $show An array of columns to show.  Empty array means all columns.
	 * @param $values An array mapping columns to values
	 * @param $ops An array of the operators to use
	 * @param $orderby (optional) An array of column numbers or names (one based)
	 *        mapped to sort direction (asc or desc or '' or null) to order by
	 * @return The SQL query
	 */
	function getSelectSQL($table, $show, $values, $ops, $orderby = array()) {
		$this->fieldArrayClean($show);

		// If an empty array is passed in, then show all columns
		if (sizeof($show) == 0) {
			if ($this->hasObjectID($table))
				$sql = "SELECT \"{$this->id}\", * FROM ";
			else
				$sql = "SELECT * FROM ";
		}
		else {
			// Add oid column automatically to results for editing purposes
			if (!in_array($this->id, $show) && $this->hasObjectID($table))
				$sql = "SELECT \"{$this->id}\", \"";
			else
				$sql = "SELECT \"";

			$sql .= join('","', $show) . "\" FROM ";
		}

		$this->fieldClean($table);

		if (isset($_REQUEST['schema'])) {
			$f_schema = $_REQUEST['schema'];
			$this->fieldClean($f_schema);
			$sql .= "\"{$f_schema}\".";
		}
		$sql .= "\"{$table}\"";

		// If we have values specified, add them to the WHERE clause
		$first = true;
		if (is_array($values) && sizeof($values) > 0) {
			foreach ($values as $k => $v) {
				if ($v != '' || $this->selectOps[$ops[$k]] == 'p') {
					$this->fieldClean($k);
					if ($first) {
						$sql .= " WHERE ";
						$first = false;
					} else {
						$sql .= " AND ";
					}
					// Different query format depending on operator type
					switch ($this->selectOps[$ops[$k]]) {
						case 'i':
							// Only clean the field for the inline case
							// this is because (x), subqueries need to
							// to allow 'a','b' as input.
							$this->clean($v);
							$sql .= "\"{$k}\" {$ops[$k]} '{$v}'";
							break;
						case 'p':
							$sql .= "\"{$k}\" {$ops[$k]}";
							break;
						case 'x':
							$sql .= "\"{$k}\" {$ops[$k]} ({$v})";
							break;
						case 't':
							$sql .= "\"{$k}\" {$ops[$k]}('{$v}')";
							break;
						default:
							// Shouldn't happen
					}
				}
			}
		}

		// ORDER BY
		if (is_array($orderby) && sizeof($orderby) > 0) {
			$sql .= " ORDER BY ";
			$first = true;
			foreach ($orderby as $k => $v) {
				if ($first) $first = false;
				else $sql .= ', ';
				if (preg_match('/^[0-9]+$/', $k)) {
					$sql .= $k;
				}
				else {
					$this->fieldClean($k);
					$sql .= '"' . $k . '"';
				}
				if (strtoupper($v) == 'DESC') $sql .= " DESC";
			}
		}

		return $sql;
	}

	/**
	 * Returns a recordset of all columns in a query.  Supports paging.
	 * @param $type Either 'QUERY' if it is an SQL query, or 'TABLE' if it is a table identifier,
	 *              or 'SELECT" if it's a select query
	 * @param $table The base table of the query.  NULL for no table.
	 * @param $query The query that is being executed.  NULL for no query.
	 * @param $sortkey The column number to sort by, or '' or null for no sorting
	 * @param $sortdir The direction in which to sort the specified column ('asc' or 'desc')
	 * @param $page The page of the relation to retrieve
	 * @param $page_size The number of rows per page
	 * @param &$max_pages (return-by-ref) The max number of pages in the relation
	 * @return A recordset on success
	 * @return -1 transaction error
	 * @return -2 counting error
	 * @return -3 page or page_size invalid
	 * @return -4 unknown type
	 * @return -5 failed setting transaction read only
	 */
	function browseQuery($type, $table, $query, $sortkey, $sortdir, $page, $page_size, &$max_pages) {
		// Check that we're not going to divide by zero
		if (!is_numeric($page_size) || $page_size != (int)$page_size || $page_size <= 0) return -3;

		// If $type is TABLE, then generate the query
		switch ($type) {
			case 'TABLE':
				if (preg_match('/^[0-9]+$/', $sortkey) && $sortkey > 0) $orderby = array($sortkey => $sortdir);
				else $orderby = array();
				$query = $this->getSelectSQL($table, array(), array(), array(), $orderby);
				break;
			case 'QUERY':
			case 'SELECT':
				// Trim query
				$query = trim($query);
				// Trim off trailing semi-colon if there is one
				if (substr($query, strlen($query) - 1, 1) == ';')
					$query = substr($query, 0, strlen($query) - 1);
				break;
			default:
				return -4;
		}

		// Generate count query
		$count = "SELECT COUNT(*) AS total FROM ($query) AS sub";

		// Open a transaction
		$status = $this->beginTransaction();
		if ($status != 0) return -1;

		// If backend supports read only queries, then specify read only mode
		// to avoid side effects from repeating queries that do writes.
		if ($this->hasReadOnlyQueries()) {
			$status = $this->execute("SET TRANSACTION READ ONLY");
			if ($status != 0) {
				$this->rollbackTransaction();
				return -5;
					}
				}


		// Count the number of rows
		$total = $this->browseQueryCount($query, $count);
		if ($total < 0) {
			$this->rollbackTransaction();
			return -2;
    			}

		// Calculate max pages
		$max_pages = ceil($total / $page_size);

		// Check that page is less than or equal to max pages
		if (!is_numeric($page) || $page != (int)$page || $page > $max_pages || $page < 1) {
			$this->rollbackTransaction();
			return -3;
					}

		// Set fetch mode to NUM so that duplicate field names are properly returned
		// for non-table queries.  Since the SELECT feature only allows selecting one
		// table, duplicate fields shouldn't appear.
		if ($type == 'QUERY') $this->conn->setFetchMode(ADODB_FETCH_NUM);

		// Figure out ORDER BY.  Sort key is always the column number (based from one)
		// of the column to order by.  Only need to do this for non-TABLE queries
		if ($type != 'TABLE' && preg_match('/^[0-9]+$/', $sortkey) && $sortkey > 0) {
			$orderby = " ORDER BY {$sortkey}";
			// Add sort order
			if ($sortdir == 'desc')
				$orderby .= ' DESC';
			else
				$orderby .= ' ASC';
				}
		else $orderby = '';

		// Actually retrieve the rows, with offset and limit
		$rs = $this->selectSet("SELECT * FROM ({$query}) AS sub {$orderby} LIMIT {$page_size} OFFSET " . ($page - 1) * $page_size);
		$status = $this->endTransaction();
		if ($status != 0) {
			$this->rollbackTransaction();
			return -1;
    			}

		return $rs;
    			}

	/**
	 * Finds the number of rows that would be returned by a
	 * query.
	 * @param $query The SQL query
	 * @param $count The count query
	 * @return The count of rows
	 * @return -1 error
	 */
	function browseQueryCount($query, $count) {
		return $this->selectField($count, 'total');
    }

	/**
	 * Returns a recordset of all columns in a table
	 * @param $table The name of a table
	 * @param $key The associative array holding the key to retrieve
	 * @return A recordset
    					 */
	function browseRow($table, $key) {
		$f_schema = $this->_schema;
		$this->fieldClean($f_schema);
		$this->fieldClean($table);

		$sql = "SELECT * FROM \"{$f_schema}\".\"{$table}\"";
		if (is_array($key) && sizeof($key) > 0) {
			$sql .= " WHERE true";
			foreach ($key as $k => $v) {
				$this->fieldClean($k);
				$this->clean($v);
				$sql .= " AND \"{$k}\"='{$v}'";
           	}
   		}

		return $this->selectSet($sql);
   	}

	// Type conversion routines

	/**
	 * Change the value of a parameter to 't' or 'f' depending on whether it evaluates to true or false
	 * @param $parameter the parameter
				 */
	function dbBool(&$parameter) {
		if ($parameter) $parameter = 't';
		else $parameter = 'f';

		return $parameter;
    }

	/**
	 * Change a parameter from 't' or 'f' to a boolean, (others evaluate to false)
	 * @param $parameter the parameter
	 */
	function phpBool($parameter) {
		$parameter = ($parameter == 't');
		return $parameter;
	}

	// interfaces Statistics collector functions

	/**
	 * Fetches statistics for a database
	 * @param $database The database to fetch stats for
	 * @return A recordset
    	 */
	function getStatsDatabase($database) {
		$this->clean($database);

		$sql = "SELECT * FROM pg_stat_database WHERE datname='{$database}'";

		return $this->selectSet($sql);
	}

	/**
	 * Fetches tuple statistics for a table
	 * @param $table The table to fetch stats for
	 * @return A recordset
	 */
	function getStatsTableTuples($table) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($table);

		$sql = "SELECT * FROM pg_stat_all_tables 
			WHERE schemaname='{$c_schema}' AND relname='{$table}'";

		return $this->selectSet($sql);
	}

	/**
	 * Fetches I/0 statistics for a table
	 * @param $table The table to fetch stats for
	 * @return A recordset
	 */
	function getStatsTableIO($table) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($table);

		$sql = "SELECT * FROM pg_statio_all_tables 
			WHERE schemaname='{$c_schema}' AND relname='{$table}'";

		return $this->selectSet($sql);
	}

	/**
	 * Fetches tuple statistics for all indexes on a table
	 * @param $table The table to fetch index stats for
	 * @return A recordset
	 */
	function getStatsIndexTuples($table) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($table);

		$sql = "SELECT * FROM pg_stat_all_indexes 
			WHERE schemaname='{$c_schema}' AND relname='{$table}' ORDER BY indexrelname";

		return $this->selectSet($sql);
    }

	/**
	 * Fetches I/0 statistics for all indexes on a table
	 * @param $table The table to fetch index stats for
	 * @return A recordset
	 */
	function getStatsIndexIO($table) {
		$c_schema = $this->_schema;
		$this->clean($c_schema);
		$this->clean($table);

		$sql = "SELECT * FROM pg_statio_all_indexes 
			WHERE schemaname='{$c_schema}' AND relname='{$table}' 
			ORDER BY indexrelname";

		return $this->selectSet($sql);
	}

	// Capabilities

	function hasAggregateSortOp() { return true; }
	function hasAlterAggregate() { return true; }
	function hasAlterColumnType() { return true; }
	function hasAlterDatabaseOwner() { return true; }
	function hasAlterDatabaseRename() { return true; }
	function hasAlterSchema() { return true; }
	function hasAlterSchemaOwner() { return true; }
	function hasAlterSequenceSchema() { return true; }
	function hasAlterSequenceStart() { return true; }
	function hasAlterTableSchema() { return true; }
	function hasAutovacuum() { return true; }
	function hasCreateTableLike() { return true; }
	function hasCreateTableLikeWithConstraints() { return true; }
	function hasCreateTableLikeWithIndexes() { return true; }
	function hasCreateFieldWithConstraints() { return true; }
	function hasDisableTriggers() { return true; }
	function hasAlterDomains() { return true; }
	function hasDomainConstraints() { return true; }
	function hasEnumTypes() { return true; }
	function hasFTS() { return true; }
	function hasFunctionAlterOwner() { return true; }
	function hasFunctionAlterSchema() { return true; }
	function hasFunctionCosting() { return true; }
	function hasFunctionGUC() { return true; }
	function hasGrantOption() { return true; }
	function hasNamedParams() { return true; }
	function hasPrepare() { return true; }
	function hasPreparedXacts() { return true; }
	function hasReadOnlyQueries() { return true; }
	function hasRecluster() { return true; }
	function hasRoles() { return true; }
	function hasServerAdminFuncs() { return true; }
	function hasSharedComments() { return true; }
	function hasQueryCancel() { return true; }
	function hasTablespaces() { return true; }
	function hasUserRename() { return true; }
    function hasUserSignals() { return true; }
	function hasVirtualTransactionId() { return true; }
	function hasAlterDatabase() { return $this->hasAlterDatabaseRename(); }
	function hasDatabaseCollation() { return true; }
	function hasMagicTypes() { return true; }
	function hasQueryKill() { return true; }
	function hasConcurrentIndexBuild() { return true; }
	function hasForceReindex() { return false; }
	function hasByteaHexDefault() { return true; } 
	function hasServerOids() { return false; }
	
}
?>
