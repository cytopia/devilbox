<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Set of functions used to build SQL dumps of tables
 *
 * @package    PhpMyAdmin-Export
 * @subpackage SQL
 */
namespace PhpMyAdmin\Plugins\Export;

use PhpMyAdmin\Charsets;
use PhpMyAdmin\DatabaseInterface;
use PhpMyAdmin\Export;
use PhpMyAdmin\Plugins\ExportPlugin;
use PhpMyAdmin\Properties\Plugins\ExportPluginProperties;
use PhpMyAdmin\Properties\Options\Groups\OptionsPropertyMainGroup;
use PhpMyAdmin\Properties\Options\Groups\OptionsPropertyRootGroup;
use PhpMyAdmin\Properties\Options\Groups\OptionsPropertySubgroup;
use PhpMyAdmin\Properties\Options\Items\BoolPropertyItem;
use PhpMyAdmin\Properties\Options\Items\MessageOnlyPropertyItem;
use PhpMyAdmin\Properties\Options\Items\NumberPropertyItem;
use PhpMyAdmin\Properties\Options\Items\RadioPropertyItem;
use PhpMyAdmin\Properties\Options\Items\SelectPropertyItem;
use PhpMyAdmin\Properties\Options\Items\TextPropertyItem;
use PhpMyAdmin\Relation;
use PhpMyAdmin\SqlParser\Components\CreateDefinition;
use PhpMyAdmin\SqlParser\Context;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Statements\SelectStatement;
use PhpMyAdmin\SqlParser\Token;
use PhpMyAdmin\Transformations;
use PhpMyAdmin\Util;

/**
 * Handles the export for the SQL class
 *
 * @package    PhpMyAdmin-Export
 * @subpackage SQL
 */
class ExportSql extends ExportPlugin
{
    /**
     * Whether charset header was sent.
     *
     * @var boolean
     */
    private $_sent_charset = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setProperties();

        // Avoids undefined variables, use NULL so isset() returns false
        if (!isset($GLOBALS['sql_backquotes'])) {
            $GLOBALS['sql_backquotes'] = null;
        }
    }

    /**
     * Sets the export SQL properties
     *
     * @return void
     */
    protected function setProperties()
    {
        global $plugin_param;

        $hide_sql = false;
        $hide_structure = false;
        if ($plugin_param['export_type'] == 'table'
            && !$plugin_param['single_table']
        ) {
            $hide_structure = true;
            $hide_sql = true;
        }

        if (!$hide_sql) {
            $exportPluginProperties = new ExportPluginProperties();
            $exportPluginProperties->setText('SQL');
            $exportPluginProperties->setExtension('sql');
            $exportPluginProperties->setMimeType('text/x-sql');
            $exportPluginProperties->setOptionsText(__('Options'));

            // create the root group that will be the options field for
            // $exportPluginProperties
            // this will be shown as "Format specific options"
            $exportSpecificOptions = new OptionsPropertyRootGroup(
                "Format Specific Options"
            );

            // general options main group
            $generalOptions = new OptionsPropertyMainGroup("general_opts");

            // comments
            $subgroup = new OptionsPropertySubgroup("include_comments");
            $leaf = new BoolPropertyItem(
                'include_comments',
                __(
                    'Display comments <i>(includes info such as export'
                    . ' timestamp, PHP version, and server version)</i>'
                )
            );
            $subgroup->setSubgroupHeader($leaf);

            $leaf = new TextPropertyItem(
                'header_comment',
                __('Additional custom header comment (\n splits lines):')
            );
            $subgroup->addProperty($leaf);
            $leaf = new BoolPropertyItem(
                'dates',
                __(
                    'Include a timestamp of when databases were created, last'
                    . ' updated, and last checked'
                )
            );
            $subgroup->addProperty($leaf);
            if (!empty($GLOBALS['cfgRelation']['relation'])) {
                $leaf = new BoolPropertyItem(
                    'relation',
                    __('Display foreign key relationships')
                );
                $subgroup->addProperty($leaf);
            }
            if (!empty($GLOBALS['cfgRelation']['mimework'])) {
                $leaf = new BoolPropertyItem(
                    'mime',
                    __('Display MIME types')
                );
                $subgroup->addProperty($leaf);
            }
            $generalOptions->addProperty($subgroup);

            // enclose in a transaction
            $leaf = new BoolPropertyItem(
                "use_transaction",
                __('Enclose export in a transaction')
            );
            $leaf->setDoc(
                array(
                    'programs',
                    'mysqldump',
                    'option_mysqldump_single-transaction',
                )
            );
            $generalOptions->addProperty($leaf);

            // disable foreign key checks
            $leaf = new BoolPropertyItem(
                "disable_fk",
                __('Disable foreign key checks')
            );
            $leaf->setDoc(
                array(
                    'manual_MySQL_Database_Administration',
                    'server-system-variables',
                    'sysvar_foreign_key_checks',
                )
            );
            $generalOptions->addProperty($leaf);

            // export views as tables
            $leaf = new BoolPropertyItem(
                "views_as_tables",
                __('Export views as tables')
            );
            $generalOptions->addProperty($leaf);

            // export metadata
            $leaf = new BoolPropertyItem(
                "metadata",
                __('Export metadata')
            );
            $generalOptions->addProperty($leaf);

            // compatibility maximization
            $compats = $GLOBALS['dbi']->getCompatibilities();
            if (count($compats) > 0) {
                $values = array();
                foreach ($compats as $val) {
                    $values[$val] = $val;
                }

                $leaf = new SelectPropertyItem(
                    "compatibility",
                    __(
                        'Database system or older MySQL server to maximize output'
                        . ' compatibility with:'
                    )
                );
                $leaf->setValues($values);
                $leaf->setDoc(
                    array(
                        'manual_MySQL_Database_Administration',
                        'Server_SQL_mode',
                    )
                );
                $generalOptions->addProperty($leaf);

                unset($values);
            }

            // what to dump (structure/data/both)
            $subgroup = new OptionsPropertySubgroup(
                "dump_table", __("Dump table")
            );
            $leaf = new RadioPropertyItem('structure_or_data');
            $leaf->setValues(
                array(
                    'structure'          => __('structure'),
                    'data'               => __('data'),
                    'structure_and_data' => __('structure and data'),
                )
            );
            $subgroup->setSubgroupHeader($leaf);
            $generalOptions->addProperty($subgroup);

            // add the main group to the root group
            $exportSpecificOptions->addProperty($generalOptions);

            // structure options main group
            if (!$hide_structure) {
                $structureOptions = new OptionsPropertyMainGroup(
                    "structure", __('Object creation options')
                );
                $structureOptions->setForce('data');

                // begin SQL Statements
                $subgroup = new OptionsPropertySubgroup();
                $leaf = new MessageOnlyPropertyItem(
                    'add_statements',
                    __('Add statements:')
                );
                $subgroup->setSubgroupHeader($leaf);

                // server export options
                if ($plugin_param['export_type'] == 'server') {
                    $leaf = new BoolPropertyItem(
                        "drop_database",
                        sprintf(__('Add %s statement'), '<code>DROP DATABASE IF EXISTS</code>')
                    );
                    $subgroup->addProperty($leaf);
                }

                if ($plugin_param['export_type'] == 'database') {
                    $create_clause = '<code>CREATE DATABASE / USE</code>';
                    $leaf = new BoolPropertyItem(
                        'create_database',
                        sprintf(__('Add %s statement'), $create_clause)
                    );
                    $subgroup->addProperty($leaf);
                }

                if ($plugin_param['export_type'] == 'table') {
                    $drop_clause = $GLOBALS['dbi']->getTable(
                        $GLOBALS['db'],
                        $GLOBALS['table']
                    )->isView()
                        ? '<code>DROP VIEW</code>'
                        : '<code>DROP TABLE</code>';
                } else {
                    $drop_clause = '<code>DROP TABLE / VIEW / PROCEDURE'
                        . ' / FUNCTION / EVENT</code>';
                }

                $drop_clause .= '<code> / TRIGGER</code>';

                $leaf = new BoolPropertyItem(
                    'drop_table',
                    sprintf(__('Add %s statement'), $drop_clause)
                );
                $subgroup->addProperty($leaf);

                $subgroup_create_table = new OptionsPropertySubgroup();

                // Add table structure option
                $leaf = new BoolPropertyItem(
                    'create_table',
                    sprintf(__('Add %s statement'), '<code>CREATE TABLE</code>')
                );
                $subgroup_create_table->setSubgroupHeader($leaf);

                $leaf = new BoolPropertyItem(
                    'if_not_exists',
                    '<code>IF NOT EXISTS</code> ' . __(
                        '(less efficient as indexes will be generated during table '
                        . 'creation)'
                    )
                );
                $subgroup_create_table->addProperty($leaf);

                $leaf = new BoolPropertyItem(
                    'auto_increment',
                    sprintf(__('%s value'), '<code>AUTO_INCREMENT</code>')
                );
                $subgroup_create_table->addProperty($leaf);

                $subgroup->addProperty($subgroup_create_table);

                // Add view option
                $leaf = new BoolPropertyItem(
                    'create_view',
                    sprintf(__('Add %s statement'), '<code>CREATE VIEW</code>')
                );
                $subgroup->addProperty($leaf);

                $leaf = new BoolPropertyItem(
                    'procedure_function',
                    sprintf(
                        __('Add %s statement'),
                        '<code>CREATE PROCEDURE / FUNCTION / EVENT</code>'
                    )
                );
                $subgroup->addProperty($leaf);

                // Add triggers option
                $leaf = new BoolPropertyItem(
                    'create_trigger',
                    sprintf(__('Add %s statement'), '<code>CREATE TRIGGER</code>')
                );
                $subgroup->addProperty($leaf);

                $structureOptions->addProperty($subgroup);

                $leaf = new BoolPropertyItem(
                    "backquotes",
                    __(
                        'Enclose table and column names with backquotes '
                        . '<i>(Protects column and table names formed with'
                        . ' special characters or keywords)</i>'
                    )
                );

                $structureOptions->addProperty($leaf);

                // add the main group to the root group
                $exportSpecificOptions->addProperty($structureOptions);
            }

            // begin Data options
            $dataOptions = new OptionsPropertyMainGroup(
                "data", __('Data creation options')
            );
            $dataOptions->setForce('structure');
            $leaf = new BoolPropertyItem(
                "truncate",
                __('Truncate table before insert')
            );
            $dataOptions->addProperty($leaf);

            // begin SQL Statements
            $subgroup = new OptionsPropertySubgroup();
            $leaf = new MessageOnlyPropertyItem(
                __('Instead of <code>INSERT</code> statements, use:')
            );
            $subgroup->setSubgroupHeader($leaf);

            $leaf = new BoolPropertyItem(
                "delayed",
                __('<code>INSERT DELAYED</code> statements')
            );
            $leaf->setDoc(
                array(
                    'manual_MySQL_Database_Administration',
                    'insert_delayed'
                )
            );
            $subgroup->addProperty($leaf);

            $leaf = new BoolPropertyItem(
                "ignore",
                __('<code>INSERT IGNORE</code> statements')
            );
            $leaf->setDoc(
                array(
                    'manual_MySQL_Database_Administration',
                    'insert',
                )
            );
            $subgroup->addProperty($leaf);
            $dataOptions->addProperty($subgroup);

            // Function to use when dumping dat
            $leaf = new SelectPropertyItem(
                "type",
                __('Function to use when dumping data:')
            );
            $leaf->setValues(
                array(
                    'INSERT'  => 'INSERT',
                    'UPDATE'  => 'UPDATE',
                    'REPLACE' => 'REPLACE',
                )
            );
            $dataOptions->addProperty($leaf);

            /* Syntax to use when inserting data */
            $subgroup = new OptionsPropertySubgroup();
            $leaf = new MessageOnlyPropertyItem(
                null,
                __('Syntax to use when inserting data:')
            );
            $subgroup->setSubgroupHeader($leaf);
            $leaf = new RadioPropertyItem(
                "insert_syntax",
                __('<code>INSERT IGNORE</code> statements')
            );
            $leaf->setValues(
                array(
                    'complete' => __(
                        'include column names in every <code>INSERT</code> statement'
                        . ' <br /> &nbsp; &nbsp; &nbsp; Example: <code>INSERT INTO'
                        . ' tbl_name (col_A,col_B,col_C) VALUES (1,2,3)</code>'
                    ),
                    'extended' => __(
                        'insert multiple rows in every <code>INSERT</code> statement'
                        . '<br /> &nbsp; &nbsp; &nbsp; Example: <code>INSERT INTO'
                        . ' tbl_name VALUES (1,2,3), (4,5,6), (7,8,9)</code>'
                    ),
                    'both'     => __(
                        'both of the above<br /> &nbsp; &nbsp; &nbsp; Example:'
                        . ' <code>INSERT INTO tbl_name (col_A,col_B,col_C) VALUES'
                        . ' (1,2,3), (4,5,6), (7,8,9)</code>'
                    ),
                    'none'     => __(
                        'neither of the above<br /> &nbsp; &nbsp; &nbsp; Example:'
                        . ' <code>INSERT INTO tbl_name VALUES (1,2,3)</code>'
                    ),
                )
            );
            $subgroup->addProperty($leaf);
            $dataOptions->addProperty($subgroup);

            // Max length of query
            $leaf = new NumberPropertyItem(
                "max_query_size",
                __('Maximal length of created query')
            );
            $dataOptions->addProperty($leaf);

            // Dump binary columns in hexadecimal
            $leaf = new BoolPropertyItem(
                "hex_for_binary",
                __(
                    'Dump binary columns in hexadecimal notation'
                    . ' <i>(for example, "abc" becomes 0x616263)</i>'
                )
            );
            $dataOptions->addProperty($leaf);

            // Dump time in UTC
            $leaf = new BoolPropertyItem(
                "utc_time",
                __(
                    'Dump TIMESTAMP columns in UTC <i>(enables TIMESTAMP columns'
                    . ' to be dumped and reloaded between servers in different'
                    . ' time zones)</i>'
                )
            );
            $dataOptions->addProperty($leaf);

            // add the main group to the root group
            $exportSpecificOptions->addProperty($dataOptions);

            // set the options for the export plugin property item
            $exportPluginProperties->setOptions($exportSpecificOptions);
            $this->properties = $exportPluginProperties;
        }
    }

    /**
     * Generates SQL for routines export
     *
     * @param string $db        Database
     * @param array  $aliases   Aliases of db/table/columns
     * @param string $type      Type of exported routine
     * @param string $name      Verbose name of exported routine
     * @param array  $routines  List of routines to export
     * @param string $delimiter Delimiter to use in SQL
     *
     * @return string SQL query
     */
    protected function _exportRoutineSQL(
        $db, array $aliases, $type, $name, array $routines, $delimiter
    ) {
        global $crlf;

        $text = $this->_exportComment()
            . $this->_exportComment($name)
            . $this->_exportComment();

        $used_alias = false;
        $proc_query = '';

        foreach ($routines as $routine) {
            if (!empty($GLOBALS['sql_drop_table'])) {
                $proc_query .= 'DROP ' . $type . ' IF EXISTS '
                    . Util::backquote($routine)
                    . $delimiter . $crlf;
            }
            $create_query = $this->replaceWithAliases(
                $GLOBALS['dbi']->getDefinition($db, $type, $routine),
                $aliases,
                $db,
                '',
                $flag
            );
            // One warning per database
            if ($flag) {
                $used_alias = true;
            }
            $proc_query .= $create_query . $delimiter . $crlf . $crlf;
        }
        if ($used_alias) {
            $text .= $this->_exportComment(
                __('It appears your database uses routines;')
            )
            . $this->_exportComment(
                __('alias export may not work reliably in all cases.')
            )
            . $this->_exportComment();
        }
        $text .= $proc_query;

        return $text;
    }

    /**
     * Exports routines (procedures and functions)
     *
     * @param string $db      Database
     * @param array  $aliases Aliases of db/table/columns
     *
     * @return bool Whether it succeeded
     */
    public function exportRoutines($db, array $aliases = array())
    {
        global $crlf;

        $db_alias = $db;
        $this->initAlias($aliases, $db_alias);

        $text = '';
        $delimiter = '$$';

        $procedure_names = $GLOBALS['dbi']
            ->getProceduresOrFunctions($db, 'PROCEDURE');
        $function_names = $GLOBALS['dbi']->getProceduresOrFunctions($db, 'FUNCTION');

        if ($procedure_names || $function_names) {
            $text .= $crlf
                . 'DELIMITER ' . $delimiter . $crlf;

            if ($procedure_names) {
                $text .= $this->_exportRoutineSQL(
                    $db,
                    $aliases,
                    'PROCEDURE',
                    __('Procedures'),
                    $procedure_names,
                    $delimiter
                );
            }

            if ($function_names) {
                $text .= $this->_exportRoutineSQL(
                    $db,
                    $aliases,
                    'FUNCTION',
                    __('Functions'),
                    $function_names,
                    $delimiter
                );
            }

            $text .= 'DELIMITER ;' . $crlf;
        }

        if (!empty($text)) {
            return Export::outputHandler($text);
        }

        return false;
    }

    /**
     * Possibly outputs comment
     *
     * @param string $text Text of comment
     *
     * @return string The formatted comment
     */
    private function _exportComment($text = '')
    {
        if (isset($GLOBALS['sql_include_comments'])
            && $GLOBALS['sql_include_comments']
        ) {
            // see https://dev.mysql.com/doc/refman/5.0/en/ansi-diff-comments.html
            if (empty($text)) {
                return '--' . $GLOBALS['crlf'];
            }

            $lines = preg_split("/\\r\\n|\\r|\\n/", $text);
            $result = array();
            foreach ($lines as $line) {
                $result[] = '-- ' . $line . $GLOBALS['crlf'];
            }
            return implode('', $result);
        }

        return '';
    }

    /**
     * Possibly outputs CRLF
     *
     * @return string $crlf or nothing
     */
    private function _possibleCRLF()
    {
        if (isset($GLOBALS['sql_include_comments'])
            && $GLOBALS['sql_include_comments']
        ) {
            return $GLOBALS['crlf'];
        }

        return '';
    }

    /**
     * Outputs export footer
     *
     * @return bool Whether it succeeded
     */
    public function exportFooter()
    {
        global $crlf;

        $foot = '';

        if (isset($GLOBALS['sql_disable_fk'])) {
            $foot .= 'SET FOREIGN_KEY_CHECKS=1;' . $crlf;
        }

        if (isset($GLOBALS['sql_use_transaction'])) {
            $foot .= 'COMMIT;' . $crlf;
        }

        // restore connection settings
        if ($this->_sent_charset) {
            $foot .= $crlf
                . '/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;'
                . $crlf
                . '/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;'
                . $crlf
                . '/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;'
                . $crlf;
            $this->_sent_charset = false;
        }

        /* Restore timezone */
        if (isset($GLOBALS['sql_utc_time']) && $GLOBALS['sql_utc_time']) {
            $GLOBALS['dbi']->query('SET time_zone = "' . $GLOBALS['old_tz'] . '"');
        }

        return Export::outputHandler($foot);
    }

    /**
     * Outputs export header. It is the first method to be called, so all
     * the required variables are initialized here.
     *
     * @return bool Whether it succeeded
     */
    public function exportHeader()
    {
        global $crlf, $cfg;

        if (isset($GLOBALS['sql_compatibility'])) {
            $tmp_compat = $GLOBALS['sql_compatibility'];
            if ($tmp_compat == 'NONE') {
                $tmp_compat = '';
            }
            $GLOBALS['dbi']->tryQuery('SET SQL_MODE="' . $tmp_compat . '"');
            unset($tmp_compat);
        }
        $head = $this->_exportComment('phpMyAdmin SQL Dump')
            . $this->_exportComment('version ' . PMA_VERSION)
            . $this->_exportComment('https://www.phpmyadmin.net/')
            . $this->_exportComment();
        $host_string = __('Host:') . ' ' . $cfg['Server']['host'];
        if (!empty($cfg['Server']['port'])) {
            $host_string .= ':' . $cfg['Server']['port'];
        }
        $head .= $this->_exportComment($host_string);
        $head .= $this->_exportComment(
            __('Generation Time:') . ' '
            . Util::localisedDate()
        )
        . $this->_exportComment(
            __('Server version:') . ' ' . $GLOBALS['dbi']->getVersionString()
        )
        . $this->_exportComment(__('PHP Version:') . ' ' . phpversion())
        . $this->_possibleCRLF();

        if (isset($GLOBALS['sql_header_comment'])
            && !empty($GLOBALS['sql_header_comment'])
        ) {
            // '\n' is not a newline (like "\n" would be), it's the characters
            // backslash and n, as explained on the export interface
            $lines = explode('\n', $GLOBALS['sql_header_comment']);
            $head .= $this->_exportComment();
            foreach ($lines as $one_line) {
                $head .= $this->_exportComment($one_line);
            }
            $head .= $this->_exportComment();
        }

        if (isset($GLOBALS['sql_disable_fk'])) {
            $head .= 'SET FOREIGN_KEY_CHECKS=0;' . $crlf;
        }

        // We want exported AUTO_INCREMENT columns to have still same value,
        // do this only for recent MySQL exports
        if ((! isset($GLOBALS['sql_compatibility'])
            || $GLOBALS['sql_compatibility'] == 'NONE')
        ) {
            $head .= 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";' . $crlf;
        }

        if (isset($GLOBALS['sql_use_transaction'])) {
            $head .= 'SET AUTOCOMMIT = 0;' . $crlf
                . 'START TRANSACTION;' . $crlf;
        }

        /* Change timezone if we should export timestamps in UTC */
        if (isset($GLOBALS['sql_utc_time']) && $GLOBALS['sql_utc_time']) {
            $head .= 'SET time_zone = "+00:00";' . $crlf;
            $GLOBALS['old_tz'] = $GLOBALS['dbi']
                ->fetchValue('SELECT @@session.time_zone');
            $GLOBALS['dbi']->query('SET time_zone = "+00:00"');
        }

        $head .= $this->_possibleCRLF();

        if (! empty($GLOBALS['asfile'])) {
            // we are saving as file, therefore we provide charset information
            // so that a utility like the mysql client can interpret
            // the file correctly
            if (isset($GLOBALS['charset'])
                && isset(Charsets::$mysql_charset_map[$GLOBALS['charset']])
            ) {
                // we got a charset from the export dialog
                $set_names = Charsets::$mysql_charset_map[$GLOBALS['charset']];
            } else {
                // by default we use the connection charset
                $set_names = Charsets::$mysql_charset_map['utf-8'];
            }
            if ($set_names == 'utf8' && $GLOBALS['dbi']->getVersion() > 50503) {
                $set_names = 'utf8mb4';
            }
            $head .= $crlf
                . '/*!40101 SET @OLD_CHARACTER_SET_CLIENT='
                . '@@CHARACTER_SET_CLIENT */;' . $crlf
                . '/*!40101 SET @OLD_CHARACTER_SET_RESULTS='
                . '@@CHARACTER_SET_RESULTS */;' . $crlf
                . '/*!40101 SET @OLD_COLLATION_CONNECTION='
                . '@@COLLATION_CONNECTION */;' . $crlf
                . '/*!40101 SET NAMES ' . $set_names . ' */;' . $crlf . $crlf;
            $this->_sent_charset = true;
        }

        return Export::outputHandler($head);
    }

    /**
     * Outputs CREATE DATABASE statement
     *
     * @param string $db          Database name
     * @param string $export_type 'server', 'database', 'table'
     * @param string $db_alias    Aliases of db
     *
     * @return bool Whether it succeeded
     */
    public function exportDBCreate($db, $export_type, $db_alias = '')
    {
        global $crlf;

        if (empty($db_alias)) {
            $db_alias = $db;
        }
        if (isset($GLOBALS['sql_compatibility'])) {
            $compat = $GLOBALS['sql_compatibility'];
        } else {
            $compat = 'NONE';
        }
        if (isset($GLOBALS['sql_drop_database'])) {
            if (!Export::outputHandler(
                'DROP DATABASE IF EXISTS '
                . Util::backquoteCompat(
                    $db_alias,
                    $compat,
                    isset($GLOBALS['sql_backquotes'])
                )
                . ';' . $crlf
            )
            ) {
                return false;
            }
        }
        if ($export_type == 'database' && !isset($GLOBALS['sql_create_database'])) {
            return true;
        }

        $create_query = 'CREATE DATABASE IF NOT EXISTS '
            . Util::backquoteCompat(
                $db_alias,
                $compat,
                isset($GLOBALS['sql_backquotes'])
            );
        $collation = $GLOBALS['dbi']->getDbCollation($db);
        if (mb_strpos($collation, '_')) {
            $create_query .= ' DEFAULT CHARACTER SET '
                . mb_substr(
                    $collation,
                    0,
                    mb_strpos($collation, '_')
                )
                . ' COLLATE ' . $collation;
        } else {
            $create_query .= ' DEFAULT CHARACTER SET ' . $collation;
        }
        $create_query .= ';' . $crlf;
        if (!Export::outputHandler($create_query)) {
            return false;
        }

        return $this->_exportUseStatement($db_alias, $compat);
    }

    /**
     * Outputs USE statement
     *
     * @param string $db     db to use
     * @param string $compat sql compatibility
     *
     * @return bool Whether it succeeded
     */
    private function _exportUseStatement($db, $compat)
    {
        global $crlf;

        if (isset($GLOBALS['sql_compatibility'])
            && $GLOBALS['sql_compatibility'] == 'NONE'
        ) {
            $result = Export::outputHandler(
                'USE '
                . Util::backquoteCompat(
                    $db,
                    $compat,
                    isset($GLOBALS['sql_backquotes'])
                )
                . ';' . $crlf
            );
        } else {
            $result = Export::outputHandler('USE ' . $db . ';' . $crlf);
        }

        return $result;
    }

    /**
     * Outputs database header
     *
     * @param string $db       Database name
     * @param string $db_alias Alias of db
     *
     * @return bool Whether it succeeded
     */
    public function exportDBHeader($db, $db_alias = '')
    {
        if (empty($db_alias)) {
            $db_alias = $db;
        }
        if (isset($GLOBALS['sql_compatibility'])) {
            $compat = $GLOBALS['sql_compatibility'];
        } else {
            $compat = 'NONE';
        }
        $head = $this->_exportComment()
            . $this->_exportComment(
                __('Database:') . ' '
                . Util::backquoteCompat(
                    $db_alias,
                    $compat,
                    isset($GLOBALS['sql_backquotes'])
                )
            )
            . $this->_exportComment();

        return Export::outputHandler($head);
    }

    /**
     * Outputs database footer
     *
     * @param string $db Database name
     *
     * @return bool Whether it succeeded
     */
    public function exportDBFooter($db)
    {
        global $crlf;

        $result = true;

        //add indexes to the sql dump file
        if (isset($GLOBALS['sql_indexes'])) {
            $result = Export::outputHandler($GLOBALS['sql_indexes']);
            unset($GLOBALS['sql_indexes']);
        }
        //add auto increments to the sql dump file
        if (isset($GLOBALS['sql_auto_increments'])) {
            $result = Export::outputHandler($GLOBALS['sql_auto_increments']);
            unset($GLOBALS['sql_auto_increments']);
        }
        //add constraints to the sql dump file
        if (isset($GLOBALS['sql_constraints'])) {
            $result = Export::outputHandler($GLOBALS['sql_constraints']);
            unset($GLOBALS['sql_constraints']);
        }

        return $result;
    }

    /**
     * Exports events
     *
     * @param string $db Database
     *
     * @return bool Whether it succeeded
     */
    public function exportEvents($db)
    {
        global $crlf;

        $text = '';
        $delimiter = '$$';

        $event_names = $GLOBALS['dbi']->fetchResult(
            "SELECT EVENT_NAME FROM information_schema.EVENTS WHERE"
            . " EVENT_SCHEMA= '" . $GLOBALS['dbi']->escapeString($db)
            . "';"
        );

        if ($event_names) {
            $text .= $crlf
                . "DELIMITER " . $delimiter . $crlf;

            $text .= $this->_exportComment()
                . $this->_exportComment(__('Events'))
                . $this->_exportComment();

            foreach ($event_names as $event_name) {
                if (!empty($GLOBALS['sql_drop_table'])) {
                    $text .= "DROP EVENT "
                        . Util::backquote($event_name)
                        . $delimiter . $crlf;
                }
                $text .= $GLOBALS['dbi']->getDefinition($db, 'EVENT', $event_name)
                    . $delimiter . $crlf . $crlf;
            }

            $text .= "DELIMITER ;" . $crlf;
        }

        if (!empty($text)) {
            return Export::outputHandler($text);
        }

        return false;
    }

    /**
     * Exports metadata from Configuration Storage
     *
     * @param string       $db            database being exported
     * @param string|array $tables        table(s) being exported
     * @param array        $metadataTypes types of metadata to export
     *
     * @return bool Whether it succeeded
     */
    public function exportMetadata(
        $db,
        $tables,
        array $metadataTypes
    ) {
        $cfgRelation = $this->relation->getRelationsParam();
        if (!isset($cfgRelation['db'])) {
            return true;
        }

        $comment = $this->_possibleCRLF()
            . $this->_possibleCRLF()
            . $this->_exportComment()
            . $this->_exportComment(__('Metadata'))
            . $this->_exportComment();
        if (!Export::outputHandler($comment)) {
            return false;
        }

        if (!$this->_exportUseStatement(
            $cfgRelation['db'],
            $GLOBALS['sql_compatibility']
        )
        ) {
            return false;
        }

        $r = true;
        if (is_array($tables)) {
            // export metadata for each table
            foreach ($tables as $table) {
                $r &= $this->_exportMetadata($db, $table, $metadataTypes);
            }
            // export metadata for the database
            $r &= $this->_exportMetadata($db, null, $metadataTypes);
        } else {
            // export metadata for single table
            $r &= $this->_exportMetadata($db, $tables, $metadataTypes);
        }

        return $r;
    }

    /**
     * Exports metadata from Configuration Storage
     *
     * @param string $db            database being exported
     * @param string $table         table being exported
     * @param array  $metadataTypes types of metadata to export
     *
     * @return bool Whether it succeeded
     */
    private function _exportMetadata(
        $db,
        $table,
        array $metadataTypes
    ) {
        $cfgRelation = $this->relation->getRelationsParam();

        if (isset($table)) {
            $types = array(
                'column_info'   => 'db_name',
                'table_uiprefs' => 'db_name',
                'tracking'      => 'db_name',
            );
        } else {
            $types = array(
                'bookmark'        => 'dbase',
                'relation'        => 'master_db',
                'pdf_pages'       => 'db_name',
                'savedsearches'   => 'db_name',
                'central_columns' => 'db_name',
            );
        }

        $aliases = array();

        $comment = $this->_possibleCRLF()
            . $this->_exportComment();

        if (isset($table)) {
            $comment .= $this->_exportComment(
                sprintf(
                    __('Metadata for table %s'),
                    $table
                )
            );
        } else {
            $comment .= $this->_exportComment(
                sprintf(
                    __('Metadata for database %s'),
                    $db
                )
            );
        }

        $comment .= $this->_exportComment();

        if (!Export::outputHandler($comment)) {
            return false;
        }

        foreach ($types as $type => $dbNameColumn) {
            if (in_array($type, $metadataTypes) && isset($cfgRelation[$type])) {

                // special case, designer pages and their coordinates
                if ($type == 'pdf_pages') {

                    $sql_query = "SELECT `page_nr`, `page_descr` FROM "
                        . Util::backquote($cfgRelation['db'])
                        . "." . Util::backquote($cfgRelation[$type])
                        . " WHERE " . Util::backquote($dbNameColumn)
                        . " = '" . $GLOBALS['dbi']->escapeString($db) . "'";

                    $result = $GLOBALS['dbi']->fetchResult(
                        $sql_query,
                        'page_nr',
                        'page_descr'
                    );

                    foreach ($result as $page => $name) {
                        // insert row for pdf_page
                        $sql_query_row = "SELECT `db_name`, `page_descr` FROM "
                            . Util::backquote($cfgRelation['db'])
                            . "." . Util::backquote(
                                $cfgRelation[$type]
                            )
                            . " WHERE " . Util::backquote(
                                $dbNameColumn
                            )
                            . " = '" . $GLOBALS['dbi']->escapeString($db) . "'"
                            . " AND `page_nr` = '" . intval($page) . "'";

                        if (!$this->exportData(
                            $cfgRelation['db'],
                            $cfgRelation[$type],
                            $GLOBALS['crlf'],
                            '',
                            $sql_query_row,
                            $aliases
                        )
                        ) {
                            return false;
                        }

                        $lastPage = $GLOBALS['crlf']
                            . "SET @LAST_PAGE = LAST_INSERT_ID();"
                            . $GLOBALS['crlf'];
                        if (!Export::outputHandler($lastPage)) {
                            return false;
                        }

                        $sql_query_coords = "SELECT `db_name`, `table_name`, "
                            . "'@LAST_PAGE' AS `pdf_page_number`, `x`, `y` FROM "
                            . Util::backquote($cfgRelation['db'])
                            . "." . Util::backquote(
                                $cfgRelation['table_coords']
                            )
                            . " WHERE `pdf_page_number` = '" . $page . "'";

                        $GLOBALS['exporting_metadata'] = true;
                        if (!$this->exportData(
                            $cfgRelation['db'],
                            $cfgRelation['table_coords'],
                            $GLOBALS['crlf'],
                            '',
                            $sql_query_coords,
                            $aliases
                        )
                        ) {
                            $GLOBALS['exporting_metadata'] = false;

                            return false;
                        }
                        $GLOBALS['exporting_metadata'] = false;
                    }
                    continue;
                }

                // remove auto_incrementing id field for some tables
                if ($type == 'bookmark') {
                    $sql_query = "SELECT `dbase`, `user`, `label`, `query` FROM ";
                } elseif ($type == 'column_info') {
                    $sql_query = "SELECT `db_name`, `table_name`, `column_name`,"
                        . " `comment`, `mimetype`, `transformation`,"
                        . " `transformation_options`, `input_transformation`,"
                        . " `input_transformation_options` FROM";
                } elseif ($type == 'savedsearches') {
                    $sql_query = "SELECT `username`, `db_name`, `search_name`,"
                        . " `search_data` FROM";
                } else {
                    $sql_query = "SELECT * FROM ";
                }
                $sql_query .= Util::backquote($cfgRelation['db'])
                    . '.' . Util::backquote($cfgRelation[$type])
                    . " WHERE " . Util::backquote($dbNameColumn)
                    . " = '" . $GLOBALS['dbi']->escapeString($db) . "'";
                if (isset($table)) {
                    $sql_query .= " AND `table_name` = '"
                        . $GLOBALS['dbi']->escapeString($table) . "'";
                }

                if (!$this->exportData(
                    $cfgRelation['db'],
                    $cfgRelation[$type],
                    $GLOBALS['crlf'],
                    '',
                    $sql_query,
                    $aliases
                )
                ) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Returns a stand-in CREATE definition to resolve view dependencies
     *
     * @param string $db      the database name
     * @param string $view    the view name
     * @param string $crlf    the end of line sequence
     * @param array  $aliases Aliases of db/table/columns
     *
     * @return string resulting definition
     */
    public function getTableDefStandIn($db, $view, $crlf, $aliases = array())
    {
        $db_alias = $db;
        $view_alias = $view;
        $this->initAlias($aliases, $db_alias, $view_alias);
        $create_query = '';
        if (!empty($GLOBALS['sql_drop_table'])) {
            $create_query .= 'DROP VIEW IF EXISTS '
                . Util::backquote($view_alias)
                . ';' . $crlf;
        }

        $create_query .= 'CREATE TABLE ';

        if (isset($GLOBALS['sql_if_not_exists'])
            && $GLOBALS['sql_if_not_exists']
        ) {
            $create_query .= 'IF NOT EXISTS ';
        }
        $create_query .= Util::backquote($view_alias) . ' (' . $crlf;
        $tmp = array();
        $columns = $GLOBALS['dbi']->getColumnsFull($db, $view);
        foreach ($columns as $column_name => $definition) {
            $col_alias = $column_name;
            if (!empty($aliases[$db]['tables'][$view]['columns'][$col_alias])) {
                $col_alias = $aliases[$db]['tables'][$view]['columns'][$col_alias];
            }
            $tmp[] = Util::backquote($col_alias) . ' ' .
                $definition['Type'] . $crlf;
        }
        $create_query .= implode(',', $tmp) . ');' . $crlf;

        return ($create_query);
    }

    /**
     * Returns CREATE definition that matches $view's structure
     *
     * @param string $db            the database name
     * @param string $view          the view name
     * @param string $crlf          the end of line sequence
     * @param bool   $add_semicolon whether to add semicolon and end-of-line at
     *                              the end
     * @param array  $aliases       Aliases of db/table/columns
     *
     * @return string resulting schema
     */
    private function _getTableDefForView(
        $db,
        $view,
        $crlf,
        $add_semicolon = true,
        array $aliases = array()
    ) {
        $db_alias = $db;
        $view_alias = $view;
        $this->initAlias($aliases, $db_alias, $view_alias);
        $create_query = "CREATE TABLE";
        if (isset($GLOBALS['sql_if_not_exists'])) {
            $create_query .= " IF NOT EXISTS ";
        }
        $create_query .= Util::backquote($view_alias) . "(" . $crlf;

        $columns = $GLOBALS['dbi']->getColumns($db, $view, null, true);

        $firstCol = true;
        foreach ($columns as $column) {
            $col_alias = $column['Field'];
            if (!empty($aliases[$db]['tables'][$view]['columns'][$col_alias])) {
                $col_alias = $aliases[$db]['tables'][$view]['columns'][$col_alias];
            }
            $extracted_columnspec = Util::extractColumnSpec(
                $column['Type']
            );

            if (!$firstCol) {
                $create_query .= "," . $crlf;
            }
            $create_query .= "    " . Util::backquote($col_alias);
            $create_query .= " " . $column['Type'];
            if ($extracted_columnspec['can_contain_collation']
                && !empty($column['Collation'])
            ) {
                $create_query .= " COLLATE " . $column['Collation'];
            }
            if ($column['Null'] == 'NO') {
                $create_query .= " NOT NULL";
            }
            if (isset($column['Default'])) {
                $create_query .= " DEFAULT '"
                    . $GLOBALS['dbi']->escapeString($column['Default']) . "'";
            } else {
                if ($column['Null'] == 'YES') {
                    $create_query .= " DEFAULT NULL";
                }
            }
            if (!empty($column['Comment'])) {
                $create_query .= " COMMENT '"
                    . $GLOBALS['dbi']->escapeString($column['Comment']) . "'";
            }
            $firstCol = false;
        }
        $create_query .= $crlf . ")" . ($add_semicolon ? ';' : '') . $crlf;

        if (isset($GLOBALS['sql_compatibility'])) {
            $compat = $GLOBALS['sql_compatibility'];
        } else {
            $compat = 'NONE';
        }
        if ($compat == 'MSSQL') {
            $create_query = $this->_makeCreateTableMSSQLCompatible(
                $create_query
            );
        }

        return $create_query;
    }

    /**
     * Returns $table's CREATE definition
     *
     * @param string $db                        the database name
     * @param string $table                     the table name
     * @param string $crlf                      the end of line sequence
     * @param string $error_url                 the url to go back in case
     *                                          of error
     * @param bool   $show_dates                whether to include creation/
     *                                          update/check dates
     * @param bool   $add_semicolon             whether to add semicolon and
     *                                          end-of-line at the end
     * @param bool   $view                      whether we're handling a view
     * @param bool   $update_indexes_increments whether we need to update
     *                                          two global variables
     * @param array  $aliases                   Aliases of db/table/columns
     *
     * @return string resulting schema
     */
    public function getTableDef(
        $db,
        $table,
        $crlf,
        $error_url,
        $show_dates = false,
        $add_semicolon = true,
        $view = false,
        $update_indexes_increments = true,
        array $aliases = array()
    ) {
        global $sql_drop_table, $sql_backquotes, $sql_constraints,
               $sql_constraints_query, $sql_indexes, $sql_indexes_query,
               $sql_auto_increments, $sql_drop_foreign_keys;

        $db_alias = $db;
        $table_alias = $table;
        $this->initAlias($aliases, $db_alias, $table_alias);

        $schema_create = '';
        $auto_increment = '';
        $new_crlf = $crlf;

        if (isset($GLOBALS['sql_compatibility'])) {
            $compat = $GLOBALS['sql_compatibility'];
        } else {
            $compat = 'NONE';
        }

        // need to use PhpMyAdmin\DatabaseInterface::QUERY_STORE
        // with $GLOBALS['dbi']->numRows() in mysqli
        $result = $GLOBALS['dbi']->tryQuery(
            'SHOW TABLE STATUS FROM ' . Util::backquote($db)
            . ' WHERE Name = \'' . $GLOBALS['dbi']->escapeString($table) . '\'',
            DatabaseInterface::CONNECT_USER,
            DatabaseInterface::QUERY_STORE
        );
        if ($result != false) {
            if ($GLOBALS['dbi']->numRows($result) > 0) {
                $tmpres = $GLOBALS['dbi']->fetchAssoc($result);

                // Here we optionally add the AUTO_INCREMENT next value,
                // but starting with MySQL 5.0.24, the clause is already included
                // in SHOW CREATE TABLE so we'll remove it below
                if (isset($GLOBALS['sql_auto_increment'])
                    && !empty($tmpres['Auto_increment'])
                ) {
                    $auto_increment .= ' AUTO_INCREMENT='
                        . $tmpres['Auto_increment'] . ' ';
                }

                if ($show_dates
                    && isset($tmpres['Create_time'])
                    && !empty($tmpres['Create_time'])
                ) {
                    $schema_create .= $this->_exportComment(
                        __('Creation:') . ' '
                        . Util::localisedDate(
                            strtotime($tmpres['Create_time'])
                        )
                    );
                    $new_crlf = $this->_exportComment() . $crlf;
                }

                if ($show_dates
                    && isset($tmpres['Update_time'])
                    && !empty($tmpres['Update_time'])
                ) {
                    $schema_create .= $this->_exportComment(
                        __('Last update:') . ' '
                        . Util::localisedDate(
                            strtotime($tmpres['Update_time'])
                        )
                    );
                    $new_crlf = $this->_exportComment() . $crlf;
                }

                if ($show_dates
                    && isset($tmpres['Check_time'])
                    && !empty($tmpres['Check_time'])
                ) {
                    $schema_create .= $this->_exportComment(
                        __('Last check:') . ' '
                        . Util::localisedDate(
                            strtotime($tmpres['Check_time'])
                        )
                    );
                    $new_crlf = $this->_exportComment() . $crlf;
                }
            }
            $GLOBALS['dbi']->freeResult($result);
        }

        $schema_create .= $new_crlf;

        // no need to generate a DROP VIEW here, it was done earlier
        if (!empty($sql_drop_table)
            && !$GLOBALS['dbi']->getTable($db, $table)->isView()
        ) {
            $schema_create .= 'DROP TABLE IF EXISTS '
                . Util::backquote($table_alias, $sql_backquotes) . ';'
                . $crlf;
        }

        // Complete table dump,
        // Whether to quote table and column names or not
        if ($sql_backquotes) {
            $GLOBALS['dbi']->query('SET SQL_QUOTE_SHOW_CREATE = 1');
        } else {
            $GLOBALS['dbi']->query('SET SQL_QUOTE_SHOW_CREATE = 0');
        }

        // I don't see the reason why this unbuffered query could cause problems,
        // because SHOW CREATE TABLE returns only one row, and we free the
        // results below. Nonetheless, we got 2 user reports about this
        // (see bug 1562533) so I removed the unbuffered mode.
        // $result = $GLOBALS['dbi']->query('SHOW CREATE TABLE ' . backquote($db)
        // . '.' . backquote($table), null, DatabaseInterface::QUERY_UNBUFFERED);
        //
        // Note: SHOW CREATE TABLE, at least in MySQL 5.1.23, does not
        // produce a displayable result for the default value of a BIT
        // column, nor does the mysqldump command. See MySQL bug 35796
        $GLOBALS['dbi']->tryQuery('USE ' . Util::backquote($db));
        $result = $GLOBALS['dbi']->tryQuery(
            'SHOW CREATE TABLE ' . Util::backquote($db) . '.'
            . Util::backquote($table)
        );
        // an error can happen, for example the table is crashed
        $tmp_error = $GLOBALS['dbi']->getError();
        if ($tmp_error) {
            $message = sprintf(__('Error reading structure for table %s:'), "$db.$table");
            $message .= ' ' . $tmp_error;
            if (! defined('TESTSUITE')) {
                trigger_error($message, E_USER_ERROR);
            }
            return $this->_exportComment($message);
        }

        // Old mode is stored so it can be restored once exporting is done.
        $old_mode = Context::$MODE;

        $warning = '';
        if ($result != false && ($row = $GLOBALS['dbi']->fetchRow($result))) {
            $create_query = $row[1];
            unset($row);

            // Convert end of line chars to one that we want (note that MySQL
            // doesn't return query it will accept in all cases)
            if (mb_strpos($create_query, "(\r\n ")) {
                $create_query = str_replace("\r\n", $crlf, $create_query);
            } elseif (mb_strpos($create_query, "(\n ")) {
                $create_query = str_replace("\n", $crlf, $create_query);
            } elseif (mb_strpos($create_query, "(\r ")) {
                $create_query = str_replace("\r", $crlf, $create_query);
            }

            /*
             * Drop database name from VIEW creation.
             *
             * This is a bit tricky, but we need to issue SHOW CREATE TABLE with
             * database name, but we don't want name to show up in CREATE VIEW
             * statement.
             */
            if ($view) {
                $create_query = preg_replace(
                    '/' . preg_quote(Util::backquote($db), '/') . '\./',
                    '',
                    $create_query
                );
            }

            // Substitute aliases in `CREATE` query.
            $create_query = $this->replaceWithAliases(
                $create_query,
                $aliases,
                $db,
                $table,
                $flag
            );

            // One warning per view.
            if ($flag && $view) {
                $warning = $this->_exportComment()
                    . $this->_exportComment(
                        __('It appears your database uses views;')
                    )
                    . $this->_exportComment(
                        __('alias export may not work reliably in all cases.')
                    )
                    . $this->_exportComment();
            }

            // Adding IF NOT EXISTS, if required.
            if (isset($GLOBALS['sql_if_not_exists'])) {
                $create_query = preg_replace(
                    '/^CREATE TABLE/',
                    'CREATE TABLE IF NOT EXISTS',
                    $create_query
                );
            }

            // Making the query MSSQL compatible.
            if ($compat == 'MSSQL') {
                $create_query = $this->_makeCreateTableMSSQLCompatible(
                    $create_query
                );
            }

            // Views have no constraints, indexes, etc. They do not require any
            // analysis.
            if (!$view) {

                if (empty($sql_backquotes)) {
                    // Option "Enclose table and column names with backquotes"
                    // was checked.
                    Context::$MODE |= Context::SQL_MODE_NO_ENCLOSING_QUOTES;
                }

                // Using appropriate quotes.
                if (($compat === 'MSSQL') || ($sql_backquotes === '"')) {
                    Context::$MODE |= Context::SQL_MODE_ANSI_QUOTES;
                }
            }

            /**
             * Parser used for analysis.
             *
             * @var Parser
             */
            $parser = new Parser($create_query);

            /**
             * `CREATE TABLE` statement.
             *
             * @var SelectStatement
             */
            $statement = $parser->statements[0];

            if (!empty($statement->entityOptions)) {
                $engine = $statement->entityOptions->has('ENGINE');
            } else {
                $engine = '';
            }

            /* Avoid operation on ARCHIVE tables as those can not be altered */
            if (!empty($statement->fields) && (empty($engine) || strtoupper($engine) != 'ARCHIVE')) {

                /**
                 * Fragments containining definition of each constraint.
                 *
                 * @var array
                 */
                $constraints = array();

                /**
                 * Fragments containining definition of each index.
                 *
                 * @var array
                 */
                $indexes = array();

                /**
                 * Fragments containining definition of each FULLTEXT index.
                 *
                 * @var array
                 */
                $indexes_fulltext = array();

                /**
                 * Fragments containining definition of each foreign key that will
                 * be dropped.
                 *
                 * @var array
                 */
                $dropped = array();

                /**
                 * Fragment containining definition of the `AUTO_INCREMENT`.
                 *
                 * @var array
                 */
                $auto_increment = array();

                // Scanning each field of the `CREATE` statement to fill the arrays
                // above.
                // If the field is used in any of the arrays above, it is removed
                // from the original definition.
                // Also, AUTO_INCREMENT attribute is removed.
                /** @var CreateDefinition $field */
                foreach ($statement->fields as $key => $field) {

                    if ($field->isConstraint) {
                        // Creating the parts that add constraints.
                        $constraints[] = $field::build($field);
                        unset($statement->fields[$key]);
                    } elseif (!empty($field->key)) {
                        // Creating the parts that add indexes (must not be
                        // constraints).
                        if ($field->key->type === 'FULLTEXT KEY') {
                            $indexes_fulltext[] = $field->build($field);
                            unset($statement->fields[$key]);
                        } else {
                            if (empty($GLOBALS['sql_if_not_exists'])) {
                                $indexes[] = str_replace(
                                    'COMMENT=\'', 'COMMENT \'', $field::build($field)
                                );
                                unset($statement->fields[$key]);
                            }
                        }
                    }

                    // Creating the parts that drop foreign keys.
                    if (!empty($field->key)) {
                        if ($field->key->type === 'FOREIGN KEY') {
                            $dropped[] = 'FOREIGN KEY ' . Context::escape(
                                $field->name
                            );
                            unset($statement->fields[$key]);
                        }
                    }

                    // Dropping AUTO_INCREMENT.
                    if (!empty($field->options)) {
                        if ($field->options->has('AUTO_INCREMENT')
                            && empty($GLOBALS['sql_if_not_exists'])
                        ) {

                            $auto_increment[] = $field::build($field);
                            $field->options->remove('AUTO_INCREMENT');
                        }
                    }
                }

                /**
                 * The header of the `ALTER` statement (`ALTER TABLE tbl`).
                 *
                 * @var string
                 */
                $alter_header = 'ALTER TABLE ' .
                    Util::backquoteCompat(
                        $table_alias,
                        $compat,
                        $sql_backquotes
                    );

                /**
                 * The footer of the `ALTER` statement (usually ';')
                 *
                 * @var string
                 */
                $alter_footer = ';' . $crlf;

                // Generating constraints-related query.
                if (!empty($constraints)) {
                    $sql_constraints_query = $alter_header . $crlf . '  ADD '
                        . implode(',' . $crlf . '  ADD ', $constraints)
                        . $alter_footer;

                    $sql_constraints = $this->generateComment(
                        $crlf,
                        $sql_constraints,
                        __('Constraints for dumped tables'),
                        __('Constraints for table'),
                        $table_alias,
                        $compat
                    ) . $sql_constraints_query;
                }

                // Generating indexes-related query.
                $sql_indexes_query = '';

                if (!empty($indexes)) {
                    $sql_indexes_query .= $alter_header . $crlf . '  ADD '
                        . implode(',' . $crlf . '  ADD ', $indexes)
                        . $alter_footer;
                }

                if (!empty($indexes_fulltext)) {
                    // InnoDB supports one FULLTEXT index creation at a time.
                    // So FULLTEXT indexes are created one-by-one after other
                    // indexes where created.
                    $sql_indexes_query .= $alter_header .
                        ' ADD ' . implode(
                            $alter_footer . $alter_header . ' ADD ',
                            $indexes_fulltext
                        ) . $alter_footer;
                }

                if ((!empty($indexes)) || (!empty($indexes_fulltext))) {
                    $sql_indexes = $this->generateComment(
                        $crlf,
                        $sql_indexes,
                        __('Indexes for dumped tables'),
                        __('Indexes for table'),
                        $table_alias,
                        $compat
                    ) . $sql_indexes_query;
                }

                // Generating drop foreign keys-related query.
                if (!empty($dropped)) {
                    $sql_drop_foreign_keys = $alter_header . $crlf . '  DROP '
                        . implode(',' . $crlf . '  DROP ', $dropped)
                        . $alter_footer;
                }

                // Generating auto-increment-related query.
                if ((! empty($auto_increment)) && ($update_indexes_increments)) {
                    $sql_auto_increments_query = $alter_header . $crlf . '  MODIFY '
                        . implode(',' . $crlf . '  MODIFY ', $auto_increment);
                    if (isset($GLOBALS['sql_auto_increment'])
                        && ($statement->entityOptions->has('AUTO_INCREMENT') !== false)
                    ) {
                        if (!isset($GLOBALS['table_data'])
                            || (isset($GLOBALS['table_data'])
                            && in_array($table, $GLOBALS['table_data']))
                        ) {
                            $sql_auto_increments_query .= ', AUTO_INCREMENT='
                                . $statement->entityOptions->has('AUTO_INCREMENT');
                        }
                    }
                    $sql_auto_increments_query .= ';' . $crlf;

                    $sql_auto_increments = $this->generateComment(
                        $crlf,
                        $sql_auto_increments,
                        __('AUTO_INCREMENT for dumped tables'),
                        __('AUTO_INCREMENT for table'),
                        $table_alias,
                        $compat
                    ) . $sql_auto_increments_query;
                }

                // Removing the `AUTO_INCREMENT` attribute from the `CREATE TABLE`
                // too.
                if (!empty($statement->entityOptions)
                    && (empty($GLOBALS['sql_if_not_exists'])
                    || empty($GLOBALS['sql_auto_increment']))
                ) {
                    $statement->entityOptions->remove('AUTO_INCREMENT');
                }

                // Rebuilding the query.
                $create_query = $statement->build();
            }

            $schema_create .= $create_query;
        }

        $GLOBALS['dbi']->freeResult($result);

        // Restoring old mode.
        Context::$MODE = $old_mode;

        return $warning . $schema_create . ($add_semicolon ? ';' . $crlf : '');
    } // end of the 'getTableDef()' function

    /**
     * Returns $table's comments, relations etc.
     *
     * @param string $db          database name
     * @param string $table       table name
     * @param string $crlf        end of line sequence
     * @param bool   $do_relation whether to include relation comments
     * @param bool   $do_mime     whether to include mime comments
     * @param array  $aliases     Aliases of db/table/columns
     *
     * @return string resulting comments
     */
    private function _getTableComments(
        $db,
        $table,
        $crlf,
        $do_relation = false,
        $do_mime = false,
        array $aliases = array()
    ) {
        global $cfgRelation, $sql_backquotes;

        $db_alias = $db;
        $table_alias = $table;
        $this->initAlias($aliases, $db_alias, $table_alias);

        $schema_create = '';

        // Check if we can use Relations
        list($res_rel, $have_rel) = $this->relation->getRelationsAndStatus(
            $do_relation && !empty($cfgRelation['relation']),
            $db,
            $table
        );

        if ($do_mime && $cfgRelation['mimework']) {
            if (!($mime_map = Transformations::getMIME($db, $table, true))) {
                unset($mime_map);
            }
        }

        if (isset($mime_map) && count($mime_map) > 0) {
            $schema_create .= $this->_possibleCRLF()
                . $this->_exportComment()
                . $this->_exportComment(
                    __('MIME TYPES FOR TABLE') . ' '
                    . Util::backquote($table, $sql_backquotes) . ':'
                );
            foreach ($mime_map as $mime_field => $mime) {
                $schema_create .= $this->_exportComment(
                    '  '
                    . Util::backquote($mime_field, $sql_backquotes)
                )
                . $this->_exportComment(
                    '      '
                    . Util::backquote(
                        $mime['mimetype'],
                        $sql_backquotes
                    )
                );
            }
            $schema_create .= $this->_exportComment();
        }

        if ($have_rel) {
            $schema_create .= $this->_possibleCRLF()
                . $this->_exportComment()
                . $this->_exportComment(
                    __('RELATIONSHIPS FOR TABLE') . ' '
                    . Util::backquote($table_alias, $sql_backquotes)
                    . ':'
                );

            foreach ($res_rel as $rel_field => $rel) {
                if ($rel_field != 'foreign_keys_data') {
                    $rel_field_alias = !empty(
                        $aliases[$db]['tables'][$table]['columns'][$rel_field]
                    ) ? $aliases[$db]['tables'][$table]['columns'][$rel_field]
                        : $rel_field;
                    $schema_create .= $this->_exportComment(
                        '  '
                        . Util::backquote(
                            $rel_field_alias,
                            $sql_backquotes
                        )
                    )
                    . $this->_exportComment(
                        '      '
                        . Util::backquote(
                            $rel['foreign_table'],
                            $sql_backquotes
                        )
                        . ' -> '
                        . Util::backquote(
                            $rel['foreign_field'],
                            $sql_backquotes
                        )
                    );
                } else {
                    foreach ($rel as $one_key) {
                        foreach ($one_key['index_list'] as $index => $field) {
                            $rel_field_alias = !empty(
                                $aliases[$db]['tables'][$table]['columns'][$field]
                            ) ? $aliases[$db]['tables'][$table]['columns'][$field]
                                : $field;
                            $schema_create .= $this->_exportComment(
                                '  '
                                . Util::backquote(
                                    $rel_field_alias,
                                    $sql_backquotes
                                )
                            )
                            . $this->_exportComment(
                                '      '
                                . Util::backquote(
                                    $one_key['ref_table_name'],
                                    $sql_backquotes
                                )
                                . ' -> '
                                . Util::backquote(
                                    $one_key['ref_index_list'][$index],
                                    $sql_backquotes
                                )
                            );
                        }
                    }
                }
            }
            $schema_create .= $this->_exportComment();
        }

        return $schema_create;
    } // end of the '_getTableComments()' function

    /**
     * Outputs table's structure
     *
     * @param string $db          database name
     * @param string $table       table name
     * @param string $crlf        the end of line sequence
     * @param string $error_url   the url to go back in case of error
     * @param string $export_mode 'create_table','triggers','create_view',
     *                            'stand_in'
     * @param string $export_type 'server', 'database', 'table'
     * @param bool   $relation    whether to include relation comments
     * @param bool   $comments    whether to include the pmadb-style column
     *                            comments as comments in the structure; this is
     *                            deprecated but the parameter is left here
     *                            because export.php calls exportStructure()
     *                            also for other export types which use this
     *                            parameter
     * @param bool   $mime        whether to include mime comments
     * @param bool   $dates       whether to include creation/update/check dates
     * @param array  $aliases     Aliases of db/table/columns
     *
     * @return bool Whether it succeeded
     */
    public function exportStructure(
        $db,
        $table,
        $crlf,
        $error_url,
        $export_mode,
        $export_type,
        $relation = false,
        $comments = false,
        $mime = false,
        $dates = false,
        array $aliases = array()
    ) {
        $db_alias = $db;
        $table_alias = $table;
        $this->initAlias($aliases, $db_alias, $table_alias);
        if (isset($GLOBALS['sql_compatibility'])) {
            $compat = $GLOBALS['sql_compatibility'];
        } else {
            $compat = 'NONE';
        }

        $formatted_table_name = Util::backquoteCompat(
            $table_alias,
            $compat,
            isset($GLOBALS['sql_backquotes'])
        );
        $dump = $this->_possibleCRLF()
            . $this->_exportComment(str_repeat('-', 56))
            . $this->_possibleCRLF()
            . $this->_exportComment();

        switch ($export_mode) {
        case 'create_table':
            $dump .= $this->_exportComment(
                __('Table structure for table') . ' ' . $formatted_table_name
            );
            $dump .= $this->_exportComment();
            $dump .= $this->getTableDef(
                $db,
                $table,
                $crlf,
                $error_url,
                $dates,
                true,
                false,
                true,
                $aliases
            );
            $dump .= $this->_getTableComments(
                $db,
                $table,
                $crlf,
                $relation,
                $mime,
                $aliases
            );
            break;
        case 'triggers':
            $dump = '';
            $delimiter = '$$';
            $triggers = $GLOBALS['dbi']->getTriggers($db, $table, $delimiter);
            if ($triggers) {
                $dump .= $this->_possibleCRLF()
                    . $this->_exportComment()
                    . $this->_exportComment(
                        __('Triggers') . ' ' . $formatted_table_name
                    )
                    . $this->_exportComment();
                $used_alias = false;
                $trigger_query = '';
                foreach ($triggers as $trigger) {
                    if (!empty($GLOBALS['sql_drop_table'])) {
                        $trigger_query .= $trigger['drop'] . ';' . $crlf;
                    }

                    $trigger_query .= 'DELIMITER ' . $delimiter . $crlf;
                    $trigger_query .= $this->replaceWithAliases(
                        $trigger['create'],
                        $aliases,
                        $db,
                        $table,
                        $flag
                    );
                    if ($flag) {
                        $used_alias = true;
                    }
                    $trigger_query .= 'DELIMITER ;' . $crlf;
                }
                // One warning per table.
                if ($used_alias) {
                    $dump .= $this->_exportComment(
                        __('It appears your table uses triggers;')
                    )
                    . $this->_exportComment(
                        __('alias export may not work reliably in all cases.')
                    )
                    . $this->_exportComment();
                }
                $dump .= $trigger_query;
            }
            break;
        case 'create_view':
            if (empty($GLOBALS['sql_views_as_tables'])) {
                $dump .= $this->_exportComment(
                    __('Structure for view')
                    . ' '
                    . $formatted_table_name
                )
                . $this->_exportComment();
                // delete the stand-in table previously created (if any)
                if ($export_type != 'table') {
                    $dump .= 'DROP TABLE IF EXISTS '
                        . Util::backquote($table_alias) . ';' . $crlf;
                }
                $dump .= $this->getTableDef(
                    $db,
                    $table,
                    $crlf,
                    $error_url,
                    $dates,
                    true,
                    true,
                    true,
                    $aliases
                );
            } else {
                $dump .= $this->_exportComment(
                    sprintf(
                        __('Structure for view %s exported as a table'),
                        $formatted_table_name
                    )
                )
                . $this->_exportComment();
                // delete the stand-in table previously created (if any)
                if ($export_type != 'table') {
                    $dump .= 'DROP TABLE IF EXISTS '
                        . Util::backquote($table_alias) . ';' . $crlf;
                }
                $dump .= $this->_getTableDefForView(
                    $db,
                    $table,
                    $crlf,
                    true,
                    $aliases
                );
            }
            break;
        case 'stand_in':
            $dump .= $this->_exportComment(
                __('Stand-in structure for view') . ' ' . $formatted_table_name
            )
                . $this->_exportComment(
                __('(See below for the actual view)')
            )
                . $this->_exportComment();
            // export a stand-in definition to resolve view dependencies
            $dump .= $this->getTableDefStandIn($db, $table, $crlf, $aliases);
        } // end switch

        // this one is built by getTableDef() to use in table copy/move
        // but not in the case of export
        unset($GLOBALS['sql_constraints_query']);

        return Export::outputHandler($dump);
    }

    /**
     * Outputs the content of a table in SQL format
     *
     * @param string $db        database name
     * @param string $table     table name
     * @param string $crlf      the end of line sequence
     * @param string $error_url the url to go back in case of error
     * @param string $sql_query SQL query for obtaining data
     * @param array  $aliases   Aliases of db/table/columns
     *
     * @return bool Whether it succeeded
     */
    public function exportData(
        $db,
        $table,
        $crlf,
        $error_url,
        $sql_query,
        array $aliases = array()
    ) {
        global $current_row, $sql_backquotes;

        // Do not export data for merge tables
        if ($GLOBALS['dbi']->getTable($db, $table)->isMerge()) {
            return true;
        }

        $db_alias = $db;
        $table_alias = $table;
        $this->initAlias($aliases, $db_alias, $table_alias);

        if (isset($GLOBALS['sql_compatibility'])) {
            $compat = $GLOBALS['sql_compatibility'];
        } else {
            $compat = 'NONE';
        }

        $formatted_table_name = Util::backquoteCompat(
            $table_alias,
            $compat,
            $sql_backquotes
        );

        // Do not export data for a VIEW, unless asked to export the view as a table
        // (For a VIEW, this is called only when exporting a single VIEW)
        if ($GLOBALS['dbi']->getTable($db, $table)->isView()
            && empty($GLOBALS['sql_views_as_tables'])
        ) {
            $head = $this->_possibleCRLF()
                . $this->_exportComment()
                . $this->_exportComment('VIEW ' . ' ' . $formatted_table_name)
                . $this->_exportComment(__('Data:') . ' ' . __('None'))
                . $this->_exportComment()
                . $this->_possibleCRLF();

            return Export::outputHandler($head);
        }

        $result = $GLOBALS['dbi']->tryQuery(
            $sql_query,
            DatabaseInterface::CONNECT_USER,
            DatabaseInterface::QUERY_UNBUFFERED
        );
        // a possible error: the table has crashed
        $tmp_error = $GLOBALS['dbi']->getError();
        if ($tmp_error) {
            $message = sprintf(__('Error reading data for table %s:'), "$db.$table");
            $message .= ' ' . $tmp_error;
            if (! defined('TESTSUITE')) {
                trigger_error($message, E_USER_ERROR);
            }
            return Export::outputHandler(
                $this->_exportComment($message)
            );
        }

        if ($result == false) {
            $GLOBALS['dbi']->freeResult($result);

            return true;
        }

        $fields_cnt = $GLOBALS['dbi']->numFields($result);

        // Get field information
        $fields_meta = $GLOBALS['dbi']->getFieldsMeta($result);
        $field_flags = array();
        for ($j = 0; $j < $fields_cnt; $j++) {
            $field_flags[$j] = $GLOBALS['dbi']->fieldFlags($result, $j);
        }

        $field_set = array();
        for ($j = 0; $j < $fields_cnt; $j++) {
            $col_as = $fields_meta[$j]->name;
            if (!empty($aliases[$db]['tables'][$table]['columns'][$col_as])) {
                $col_as = $aliases[$db]['tables'][$table]['columns'][$col_as];
            }
            $field_set[$j] = Util::backquoteCompat(
                $col_as,
                $compat,
                $sql_backquotes
            );
        }

        if (isset($GLOBALS['sql_type'])
            && $GLOBALS['sql_type'] == 'UPDATE'
        ) {
            // update
            $schema_insert = 'UPDATE ';
            if (isset($GLOBALS['sql_ignore'])) {
                $schema_insert .= 'IGNORE ';
            }
            // avoid EOL blank
            $schema_insert .= Util::backquoteCompat(
                $table_alias,
                $compat,
                $sql_backquotes
            ) . ' SET';
        } else {
            // insert or replace
            if (isset($GLOBALS['sql_type'])
                && $GLOBALS['sql_type'] == 'REPLACE'
            ) {
                $sql_command = 'REPLACE';
            } else {
                $sql_command = 'INSERT';
            }

            // delayed inserts?
            if (isset($GLOBALS['sql_delayed'])) {
                $insert_delayed = ' DELAYED';
            } else {
                $insert_delayed = '';
            }

            // insert ignore?
            if (isset($GLOBALS['sql_type'])
                && $GLOBALS['sql_type'] == 'INSERT'
                && isset($GLOBALS['sql_ignore'])
            ) {
                $insert_delayed .= ' IGNORE';
            }
            //truncate table before insert
            if (isset($GLOBALS['sql_truncate'])
                && $GLOBALS['sql_truncate']
                && $sql_command == 'INSERT'
            ) {
                $truncate = 'TRUNCATE TABLE '
                    . Util::backquoteCompat(
                        $table_alias,
                        $compat,
                        $sql_backquotes
                    ) . ";";
                $truncatehead = $this->_possibleCRLF()
                    . $this->_exportComment()
                    . $this->_exportComment(
                        __('Truncate table before insert') . ' '
                        . $formatted_table_name
                    )
                    . $this->_exportComment()
                    . $crlf;
                Export::outputHandler($truncatehead);
                Export::outputHandler($truncate);
            }

            // scheme for inserting fields
            if ($GLOBALS['sql_insert_syntax'] == 'complete'
                || $GLOBALS['sql_insert_syntax'] == 'both'
            ) {
                $fields = implode(', ', $field_set);
                $schema_insert = $sql_command . $insert_delayed . ' INTO '
                    . Util::backquoteCompat(
                        $table_alias,
                        $compat,
                        $sql_backquotes
                    )
                    // avoid EOL blank
                    . ' (' . $fields . ') VALUES';
            } else {
                $schema_insert = $sql_command . $insert_delayed . ' INTO '
                    . Util::backquoteCompat(
                        $table_alias,
                        $compat,
                        $sql_backquotes
                    )
                    . ' VALUES';
            }
        }

        //\x08\\x09, not required
        $current_row = 0;
        $query_size = 0;
        if (($GLOBALS['sql_insert_syntax'] == 'extended'
            || $GLOBALS['sql_insert_syntax'] == 'both')
            && (!isset($GLOBALS['sql_type'])
            || $GLOBALS['sql_type'] != 'UPDATE')
        ) {
            $separator = ',';
            $schema_insert .= $crlf;
        } else {
            $separator = ';';
        }

        while ($row = $GLOBALS['dbi']->fetchRow($result)) {
            if ($current_row == 0) {
                $head = $this->_possibleCRLF()
                    . $this->_exportComment()
                    . $this->_exportComment(
                        __('Dumping data for table') . ' '
                        . $formatted_table_name
                    )
                    . $this->_exportComment()
                    . $crlf;
                if (!Export::outputHandler($head)) {
                    return false;
                }
            }
            // We need to SET IDENTITY_INSERT ON for MSSQL
            if (isset($GLOBALS['sql_compatibility'])
                && $GLOBALS['sql_compatibility'] == 'MSSQL'
                && $current_row == 0
            ) {
                if (!Export::outputHandler(
                    'SET IDENTITY_INSERT '
                    . Util::backquoteCompat(
                        $table_alias,
                        $compat,
                        $sql_backquotes
                    )
                    . ' ON ;' . $crlf
                )
                ) {
                    return false;
                }
            }
            $current_row++;
            $values = array();
            for ($j = 0; $j < $fields_cnt; $j++) {
                // NULL
                if (!isset($row[$j]) || is_null($row[$j])) {
                    $values[] = 'NULL';
                } elseif ($fields_meta[$j]->numeric
                    && $fields_meta[$j]->type != 'timestamp'
                    && !$fields_meta[$j]->blob
                ) {
                    // a number
                    // timestamp is numeric on some MySQL 4.1, BLOBs are
                    // sometimes numeric
                    $values[] = $row[$j];
                } elseif (stristr($field_flags[$j], 'BINARY') !== false
                    && isset($GLOBALS['sql_hex_for_binary'])
                ) {
                    // a true BLOB
                    // - mysqldump only generates hex data when the --hex-blob
                    //   option is used, for fields having the binary attribute
                    //   no hex is generated
                    // - a TEXT field returns type blob but a real blob
                    //   returns also the 'binary' flag

                    // empty blobs need to be different, but '0' is also empty
                    // :-(
                    if (empty($row[$j]) && $row[$j] != '0') {
                        $values[] = '\'\'';
                    } else {
                        $values[] = '0x' . bin2hex($row[$j]);
                    }
                } elseif ($fields_meta[$j]->type == 'bit') {
                    // detection of 'bit' works only on mysqli extension
                    $values[] = "b'" . $GLOBALS['dbi']->escapeString(
                        Util::printableBitValue(
                            $row[$j],
                            $fields_meta[$j]->length
                        )
                    )
                    . "'";
                } elseif ($fields_meta[$j]->type === 'geometry') {
                    // export GIS types as hex
                    $values[] = '0x' . bin2hex($row[$j]);
                } elseif (!empty($GLOBALS['exporting_metadata'])
                    && $row[$j] == '@LAST_PAGE'
                ) {
                    $values[] = '@LAST_PAGE';
                } else {
                    // something else -> treat as a string
                    $values[] = '\''
                        . $GLOBALS['dbi']->escapeString($row[$j])
                        . '\'';
                } // end if
            } // end for

            // should we make update?
            if (isset($GLOBALS['sql_type'])
                && $GLOBALS['sql_type'] == 'UPDATE'
            ) {

                $insert_line = $schema_insert;
                for ($i = 0; $i < $fields_cnt; $i++) {
                    if (0 == $i) {
                        $insert_line .= ' ';
                    }
                    if ($i > 0) {
                        // avoid EOL blank
                        $insert_line .= ',';
                    }
                    $insert_line .= $field_set[$i] . ' = ' . $values[$i];
                }

                list($tmp_unique_condition, $tmp_clause_is_unique)
                    = Util::getUniqueCondition(
                        $result, // handle
                        $fields_cnt, // fields_cnt
                        $fields_meta, // fields_meta
                        $row, // row
                        false, // force_unique
                        false, // restrict_to_table
                        null // analyzed_sql_results
                    );
                $insert_line .= ' WHERE ' . $tmp_unique_condition;
                unset($tmp_unique_condition, $tmp_clause_is_unique);
            } else {

                // Extended inserts case
                if ($GLOBALS['sql_insert_syntax'] == 'extended'
                    || $GLOBALS['sql_insert_syntax'] == 'both'
                ) {
                    if ($current_row == 1) {
                        $insert_line = $schema_insert . '('
                            . implode(', ', $values) . ')';
                    } else {
                        $insert_line = '(' . implode(', ', $values) . ')';
                        $insertLineSize = mb_strlen($insert_line);
                        $sql_max_size = $GLOBALS['sql_max_query_size'];
                        if (isset($sql_max_size)
                            && $sql_max_size > 0
                            && $query_size + $insertLineSize > $sql_max_size
                        ) {
                            if (!Export::outputHandler(';' . $crlf)) {
                                return false;
                            }
                            $query_size = 0;
                            $current_row = 1;
                            $insert_line = $schema_insert . $insert_line;
                        }
                    }
                    $query_size += mb_strlen($insert_line);
                    // Other inserts case
                } else {
                    $insert_line = $schema_insert
                        . '(' . implode(', ', $values) . ')';
                }
            }
            unset($values);

            if (!Export::outputHandler(
                ($current_row == 1 ? '' : $separator . $crlf)
                . $insert_line
            )
            ) {
                return false;
            }
        } // end while

        if ($current_row > 0) {
            if (!Export::outputHandler(';' . $crlf)) {
                return false;
            }
        }

        // We need to SET IDENTITY_INSERT OFF for MSSQL
        if (isset($GLOBALS['sql_compatibility'])
            && $GLOBALS['sql_compatibility'] == 'MSSQL'
            && $current_row > 0
        ) {
            $outputSucceeded = Export::outputHandler(
                $crlf . 'SET IDENTITY_INSERT '
                . Util::backquoteCompat(
                    $table_alias,
                    $compat,
                    $sql_backquotes
                )
                . ' OFF;' . $crlf
            );
            if (!$outputSucceeded) {
                return false;
            }
        }

        $GLOBALS['dbi']->freeResult($result);

        return true;
    } // end of the 'exportData()' function

    /**
     * Make a create table statement compatible with MSSQL
     *
     * @param string $create_query MySQL create table statement
     *
     * @return string MSSQL compatible create table statement
     */
    private function _makeCreateTableMSSQLCompatible($create_query)
    {
        // In MSSQL
        // 1. No 'IF NOT EXISTS' in CREATE TABLE
        // 2. DATE field doesn't exists, we will use DATETIME instead
        // 3. UNSIGNED attribute doesn't exist
        // 4. No length on INT, TINYINT, SMALLINT, BIGINT and no precision on
        //    FLOAT fields
        // 5. No KEY and INDEX inside CREATE TABLE
        // 6. DOUBLE field doesn't exists, we will use FLOAT instead

        $create_query = preg_replace(
            "/^CREATE TABLE IF NOT EXISTS/",
            'CREATE TABLE',
            $create_query
        );
        // first we need  to replace all lines ended with '" DATE ...,\n'
        // last preg_replace preserve us from situation with date text
        // inside DEFAULT field value
        $create_query = preg_replace(
            "/\" date DEFAULT NULL(,)?\n/",
            '" datetime DEFAULT NULL$1' . "\n",
            $create_query
        );
        $create_query = preg_replace(
            "/\" date NOT NULL(,)?\n/",
            '" datetime NOT NULL$1' . "\n",
            $create_query
        );
        $create_query = preg_replace(
            '/" date NOT NULL DEFAULT \'([^\'])/',
            '" datetime NOT NULL DEFAULT \'$1',
            $create_query
        );

        // next we need to replace all lines ended with ') UNSIGNED ...,'
        // last preg_replace preserve us from situation with unsigned text
        // inside DEFAULT field value
        $create_query = preg_replace(
            "/\) unsigned NOT NULL(,)?\n/",
            ') NOT NULL$1' . "\n",
            $create_query
        );
        $create_query = preg_replace(
            "/\) unsigned DEFAULT NULL(,)?\n/",
            ') DEFAULT NULL$1' . "\n",
            $create_query
        );
        $create_query = preg_replace(
            '/\) unsigned NOT NULL DEFAULT \'([^\'])/',
            ') NOT NULL DEFAULT \'$1',
            $create_query
        );

        // we need to replace all lines ended with
        // '" INT|TINYINT([0-9]{1,}) ...,' last preg_replace preserve us
        // from situation with int([0-9]{1,}) text inside DEFAULT field
        // value
        $create_query = preg_replace(
            '/" (int|tinyint|smallint|bigint)\([0-9]+\) DEFAULT NULL(,)?\n/',
            '" $1 DEFAULT NULL$2' . "\n",
            $create_query
        );
        $create_query = preg_replace(
            '/" (int|tinyint|smallint|bigint)\([0-9]+\) NOT NULL(,)?\n/',
            '" $1 NOT NULL$2' . "\n",
            $create_query
        );
        $create_query = preg_replace(
            '/" (int|tinyint|smallint|bigint)\([0-9]+\) NOT NULL DEFAULT \'([^\'])/',
            '" $1 NOT NULL DEFAULT \'$2',
            $create_query
        );

        // we need to replace all lines ended with
        // '" FLOAT|DOUBLE([0-9,]{1,}) ...,'
        // last preg_replace preserve us from situation with
        // float([0-9,]{1,}) text inside DEFAULT field value
        $create_query = preg_replace(
            '/" (float|double)(\([0-9]+,[0-9,]+\))? DEFAULT NULL(,)?\n/',
            '" float DEFAULT NULL$3' . "\n",
            $create_query
        );
        $create_query = preg_replace(
            '/" (float|double)(\([0-9,]+,[0-9,]+\))? NOT NULL(,)?\n/',
            '" float NOT NULL$3' . "\n",
            $create_query
        );
        $create_query = preg_replace(
            '/" (float|double)(\([0-9,]+,[0-9,]+\))? NOT NULL DEFAULT \'([^\'])/',
            '" float NOT NULL DEFAULT \'$3',
            $create_query
        );

        // @todo remove indexes from CREATE TABLE

        return $create_query;
    }

    /**
     * replaces db/table/column names with their aliases
     *
     * @param string $sql_query SQL query in which aliases are to be substituted
     * @param array  $aliases   Alias information for db/table/column
     * @param string $db        the database name
     * @param string $table     the tablename
     * @param string &$flag     the flag denoting whether any replacement was done
     *
     * @return string query replaced with aliases
     */
    public function replaceWithAliases(
        $sql_query,
        array $aliases,
        $db,
        $table = '',
        &$flag = null
    ) {
        $flag = false;

        /**
         * The parser of this query.
         *
         * @var Parser $parser
         */
        $parser = new Parser($sql_query);

        if (empty($parser->statements[0])) {
            return $sql_query;
        }

        /**
         * The statement that represents the query.
         *
         * @var \PhpMyAdmin\SqlParser\Statements\CreateStatement $statement
         */
        $statement = $parser->statements[0];

        /**
         * Old database name.
         *
         * @var string $old_database
         */
        $old_database = $db;

        // Replacing aliases in `CREATE TABLE` statement.
        if ($statement->options->has('TABLE')) {

            // Extracting the name of the old database and table from the
            // statement to make sure the parameters are corect.
            if (!empty($statement->name->database)) {
                $old_database = $statement->name->database;
            }

            /**
             * Old table name.
             *
             * @var string $old_table
             */
            $old_table = $statement->name->table;

            // Finding the aliased database name.
            // The database might be empty so we have to add a few checks.
            $new_database = null;
            if (!empty($statement->name->database)) {
                $new_database = $statement->name->database;
                if (!empty($aliases[$old_database]['alias'])) {
                    $new_database = $aliases[$old_database]['alias'];
                }
            }

            // Finding the aliases table name.
            $new_table = $old_table;
            if (!empty($aliases[$old_database]['tables'][$old_table]['alias'])) {
                $new_table = $aliases[$old_database]['tables'][$old_table]['alias'];
            }

            // Replacing new values.
            if (($statement->name->database !== $new_database)
                || ($statement->name->table !== $new_table)
            ) {
                $statement->name->database = $new_database;
                $statement->name->table = $new_table;
                $statement->name->expr = null; // Force rebuild.
                $flag = true;
            }

            foreach ($statement->fields as $field) {

                // Column name.
                if (!empty($field->type)) {
                    if (!empty($aliases[$old_database]['tables'][$old_table]['columns'][$field->name])) {
                        $field->name = $aliases[$old_database]['tables']
                        [$old_table]['columns'][$field->name];
                        $flag = true;
                    }
                }

                // Key's columns.
                if (!empty($field->key)) {
                    foreach ($field->key->columns as $key => $column) {
                        if (!empty($aliases[$old_database]['tables'][$old_table]['columns'][$column['name']])) {
                            $field->key->columns[$key]['name'] = $aliases[$old_database]
                                ['tables'][$old_table]['columns'][$column['name']];
                            $flag = true;
                        }
                    }
                }

                // References.
                if (!empty($field->references)) {
                    $ref_table = $field->references->table->table;
                    // Replacing table.
                    if (!empty($aliases[$old_database]['tables'][$ref_table]['alias'])) {
                        $field->references->table->table
                            = $aliases[$old_database]['tables'][$ref_table]['alias'];
                        $field->references->table->expr = null;
                        $flag = true;
                    }
                    // Replacing column names.
                    foreach ($field->references->columns as $key => $column) {
                        if (!empty($aliases[$old_database]['tables'][$ref_table]['columns'][$column])) {
                            $field->references->columns[$key]
                                = $aliases[$old_database]['tables'][$ref_table]['columns'][$column];
                            $flag = true;
                        }
                    }
                }
            }
        } elseif ($statement->options->has('TRIGGER')) {

            // Extracting the name of the old database and table from the
            // statement to make sure the parameters are corect.
            if (!empty($statement->table->database)) {
                $old_database = $statement->table->database;
            }

            /**
             * Old table name.
             *
             * @var string $old_table
             */
            $old_table = $statement->table->table;

            if (!empty($aliases[$old_database]['tables'][$old_table]['alias'])) {
                $statement->table->table
                    = $aliases[$old_database]['tables'][$old_table]['alias'];
                $statement->table->expr = null; // Force rebuild.
                $flag = true;
            }
        }

        if (($statement->options->has('TRIGGER'))
            || ($statement->options->has('PROCEDURE'))
            || ($statement->options->has('FUNCTION'))
            || ($statement->options->has('VIEW'))
        ) {

            // Repalcing the body.
            for ($i = 0, $count = count($statement->body); $i < $count; ++$i) {

                /**
                 * Token parsed at this moment.
                 *
                 * @var Token $token
                 */
                $token = $statement->body[$i];

                // Replacing only symbols (that are not variables) and unknown
                // identifiers.
                if ((($token->type === Token::TYPE_SYMBOL)
                    && (!($token->flags & Token::FLAG_SYMBOL_VARIABLE)))
                    || ((($token->type === Token::TYPE_KEYWORD)
                    && (!($token->flags & Token::FLAG_KEYWORD_RESERVED)))
                    || ($token->type === Token::TYPE_NONE))
                ) {
                    $alias = $this->getAlias($aliases, $token->value);
                    if (!empty($alias)) {
                        // Replacing the token.
                        $token->token = Context::escape($alias);
                        $flag = true;
                    }
                }
            }
        }

        return $statement->build();
    }

    /**
     * Generate comment
     *
     * @param string $crlf          Carriage return character
     * @param string $sql_statement SQL statement
     * @param string $comment1      Comment for dumped table
     * @param string $comment2      Comment for current table
     * @param string $table_alias   Table alias
     * @param string $compat        Compatibility mode
     *
     * @return string
     */
    protected function generateComment(
        $crlf,
        $sql_statement,
        $comment1,
        $comment2,
        $table_alias,
        $compat
    ) {
        if (!isset($sql_statement)) {
            if (isset($GLOBALS['no_constraints_comments'])) {
                $sql_statement = '';
            } else {
                $sql_statement = $crlf
                    . $this->_exportComment()
                    . $this->_exportComment($comment1)
                    . $this->_exportComment();
            }
        }

        // comments for current table
        if (!isset($GLOBALS['no_constraints_comments'])) {
            $sql_statement .= $crlf
                . $this->_exportComment()
                . $this->_exportComment(
                    $comment2 . ' ' . Util::backquoteCompat(
                        $table_alias,
                        $compat,
                        isset($GLOBALS['sql_backquotes'])
                    )
                )
                . $this->_exportComment();
        }

        return $sql_statement;
    }
}
