<?php
/**
 * Set of functions used to build XML dumps of tables
 */

declare(strict_types=1);

namespace PhpMyAdmin\Plugins\Export;

use PhpMyAdmin\DatabaseInterface;
use PhpMyAdmin\Plugins\ExportPlugin;
use PhpMyAdmin\Properties\Options\Groups\OptionsPropertyMainGroup;
use PhpMyAdmin\Properties\Options\Groups\OptionsPropertyRootGroup;
use PhpMyAdmin\Properties\Options\Items\BoolPropertyItem;
use PhpMyAdmin\Properties\Options\Items\HiddenPropertyItem;
use PhpMyAdmin\Properties\Plugins\ExportPluginProperties;
use PhpMyAdmin\Util;
use const PHP_VERSION;
use function count;
use function htmlspecialchars;
use function is_array;
use function mb_substr;
use function rtrim;
use function str_replace;
use function stripslashes;
use function strlen;

// phpcs:disable PSR1.Files.SideEffects
/* Can't do server export */
if (! isset($GLOBALS['db']) || strlen($GLOBALS['db']) === 0) {
    $GLOBALS['skip_import'] = true;

    return;
}
// phpcs:enable

/**
 * Handles the export for the XML class
 */
class ExportXml extends ExportPlugin
{
    /**
     * Table name
     *
     * @var string
     */
    private $table;
    /**
     * Table names
     *
     * @var array
     */
    private $tables;

    public function __construct()
    {
        parent::__construct();
        $this->setProperties();
    }

    /**
     * Initialize the local variables that are used for export XML
     *
     * @return void
     */
    protected function initSpecificVariables()
    {
        global $table, $tables;
        $this->setTable($table);
        if (! is_array($tables)) {
            return;
        }

        $this->setTables($tables);
    }

    /**
     * Sets the export XML properties
     *
     * @return void
     */
    protected function setProperties()
    {
        // create the export plugin property item
        $exportPluginProperties = new ExportPluginProperties();
        $exportPluginProperties->setText('XML');
        $exportPluginProperties->setExtension('xml');
        $exportPluginProperties->setMimeType('text/xml');
        $exportPluginProperties->setOptionsText(__('Options'));

        // create the root group that will be the options field for
        // $exportPluginProperties
        // this will be shown as "Format specific options"
        $exportSpecificOptions = new OptionsPropertyRootGroup(
            'Format Specific Options'
        );

        // general options main group
        $generalOptions = new OptionsPropertyMainGroup('general_opts');
        // create primary items and add them to the group
        $leaf = new HiddenPropertyItem('structure_or_data');
        $generalOptions->addProperty($leaf);
        // add the main group to the root group
        $exportSpecificOptions->addProperty($generalOptions);

        // export structure main group
        $structure = new OptionsPropertyMainGroup(
            'structure',
            __('Object creation options (all are recommended)')
        );

        // create primary items and add them to the group
        $leaf = new BoolPropertyItem(
            'export_events',
            __('Events')
        );
        $structure->addProperty($leaf);
        $leaf = new BoolPropertyItem(
            'export_functions',
            __('Functions')
        );
        $structure->addProperty($leaf);
        $leaf = new BoolPropertyItem(
            'export_procedures',
            __('Procedures')
        );
        $structure->addProperty($leaf);
        $leaf = new BoolPropertyItem(
            'export_tables',
            __('Tables')
        );
        $structure->addProperty($leaf);
        $leaf = new BoolPropertyItem(
            'export_triggers',
            __('Triggers')
        );
        $structure->addProperty($leaf);
        $leaf = new BoolPropertyItem(
            'export_views',
            __('Views')
        );
        $structure->addProperty($leaf);
        $exportSpecificOptions->addProperty($structure);

        // data main group
        $data = new OptionsPropertyMainGroup(
            'data',
            __('Data dump options')
        );
        // create primary items and add them to the group
        $leaf = new BoolPropertyItem(
            'export_contents',
            __('Export contents')
        );
        $data->addProperty($leaf);
        $exportSpecificOptions->addProperty($data);

        // set the options for the export plugin property item
        $exportPluginProperties->setOptions($exportSpecificOptions);
        $this->properties = $exportPluginProperties;
    }

    /**
     * Generates output for SQL defintions of routines
     *
     * @param string $db      Database name
     * @param string $type    Item type to be used in XML output
     * @param string $dbitype Item type used in DBI queries
     *
     * @return string XML with definitions
     */
    private function exportRoutinesDefinition($db, $type, $dbitype)
    {
        global $dbi;

        // Export routines
        $routines = $dbi->getProceduresOrFunctions(
            $db,
            $dbitype
        );

        return $this->exportDefinitions($db, $type, $dbitype, $routines);
    }

    /**
     * Generates output for SQL defintions
     *
     * @param string $db      Database name
     * @param string $type    Item type to be used in XML output
     * @param string $dbitype Item type used in DBI queries
     * @param array  $names   Names of items to export
     *
     * @return string XML with definitions
     */
    private function exportDefinitions($db, $type, $dbitype, array $names)
    {
        global $crlf, $dbi;

        $head = '';

        if ($names) {
            foreach ($names as $name) {
                $head .= '            <pma:' . $type . ' name="'
                    . htmlspecialchars($name) . '">' . $crlf;

                // Do some formatting
                $sql = $dbi->getDefinition($db, $dbitype, $name);
                $sql = htmlspecialchars(rtrim($sql));
                $sql = str_replace("\n", "\n                ", $sql);

                $head .= '                ' . $sql . $crlf;
                $head .= '            </pma:' . $type . '>' . $crlf;
            }
        }

        return $head;
    }

    /**
     * Outputs export header. It is the first method to be called, so all
     * the required variables are initialized here.
     *
     * @return bool Whether it succeeded
     */
    public function exportHeader()
    {
        $this->initSpecificVariables();
        global $crlf, $cfg, $db, $dbi;
        $table = $this->getTable();
        $tables = $this->getTables();

        $export_struct = isset($GLOBALS['xml_export_functions'])
            || isset($GLOBALS['xml_export_procedures'])
            || isset($GLOBALS['xml_export_tables'])
            || isset($GLOBALS['xml_export_triggers'])
            || isset($GLOBALS['xml_export_views']);
        $export_data = isset($GLOBALS['xml_export_contents']);

        if ($GLOBALS['output_charset_conversion']) {
            $charset = $GLOBALS['charset'];
        } else {
            $charset = 'utf-8';
        }

        $head = '<?xml version="1.0" encoding="' . $charset . '"?>' . $crlf
            . '<!--' . $crlf
            . '- phpMyAdmin XML Dump' . $crlf
            . '- version ' . PMA_VERSION . $crlf
            . '- https://www.phpmyadmin.net' . $crlf
            . '-' . $crlf
            . '- ' . __('Host:') . ' ' . htmlspecialchars($cfg['Server']['host']);
        if (! empty($cfg['Server']['port'])) {
            $head .= ':' . $cfg['Server']['port'];
        }
        $head .= $crlf
            . '- ' . __('Generation Time:') . ' '
            . Util::localisedDate() . $crlf
            . '- ' . __('Server version:') . ' ' . $dbi->getVersionString() . $crlf
            . '- ' . __('PHP Version:') . ' ' . PHP_VERSION . $crlf
            . '-->' . $crlf . $crlf;

        $head .= '<pma_xml_export version="1.0"'
            . ($export_struct
                ? ' xmlns:pma="https://www.phpmyadmin.net/some_doc_url/"'
                : '')
            . '>' . $crlf;

        if ($export_struct) {
            $result = $dbi->fetchResult(
                'SELECT `DEFAULT_CHARACTER_SET_NAME`, `DEFAULT_COLLATION_NAME`'
                . ' FROM `information_schema`.`SCHEMATA` WHERE `SCHEMA_NAME`'
                . ' = \'' . $dbi->escapeString($db) . '\' LIMIT 1'
            );
            $db_collation = $result[0]['DEFAULT_COLLATION_NAME'];
            $db_charset = $result[0]['DEFAULT_CHARACTER_SET_NAME'];

            $head .= '    <!--' . $crlf;
            $head .= '    - Structure schemas' . $crlf;
            $head .= '    -->' . $crlf;
            $head .= '    <pma:structure_schemas>' . $crlf;
            $head .= '        <pma:database name="' . htmlspecialchars($db)
                . '" collation="' . htmlspecialchars($db_collation) . '" charset="' . htmlspecialchars($db_charset)
                . '">' . $crlf;

            if ($tables === null) {
                $tables = [];
            }

            if (count($tables) === 0) {
                $tables[] = $table;
            }

            foreach ($tables as $table) {
                // Export tables and views
                $result = $dbi->fetchResult(
                    'SHOW CREATE TABLE ' . Util::backquote($db) . '.'
                    . Util::backquote($table),
                    0
                );
                $tbl = (string) $result[$table][1];

                $is_view = $dbi->getTable($db, $table)
                    ->isView();

                if ($is_view) {
                    $type = 'view';
                } else {
                    $type = 'table';
                }

                if ($is_view && ! isset($GLOBALS['xml_export_views'])) {
                    continue;
                }

                if (! $is_view && ! isset($GLOBALS['xml_export_tables'])) {
                    continue;
                }

                $head .= '            <pma:' . $type . ' name="' . htmlspecialchars($table) . '">'
                    . $crlf;

                $tbl = '                ' . htmlspecialchars($tbl);
                $tbl = str_replace("\n", "\n                ", $tbl);

                $head .= $tbl . ';' . $crlf;
                $head .= '            </pma:' . $type . '>' . $crlf;

                if (! isset($GLOBALS['xml_export_triggers'])
                    || ! $GLOBALS['xml_export_triggers']
                ) {
                    continue;
                }

                // Export triggers
                $triggers = $dbi->getTriggers($db, $table);
                if (! $triggers) {
                    continue;
                }

                foreach ($triggers as $trigger) {
                    $code = $trigger['create'];
                    $head .= '            <pma:trigger name="'
                        . htmlspecialchars($trigger['name']) . '">' . $crlf;

                    // Do some formatting
                    $code = mb_substr(rtrim($code), 0, -3);
                    $code = '                ' . htmlspecialchars($code);
                    $code = str_replace("\n", "\n                ", $code);

                    $head .= $code . $crlf;
                    $head .= '            </pma:trigger>' . $crlf;
                }

                unset($trigger, $triggers);
            }

            if (isset($GLOBALS['xml_export_functions'])
                && $GLOBALS['xml_export_functions']
            ) {
                $head .= $this->exportRoutinesDefinition($db, 'function', 'FUNCTION');
            }

            if (isset($GLOBALS['xml_export_procedures'])
                && $GLOBALS['xml_export_procedures']
            ) {
                $head .= $this->exportRoutinesDefinition($db, 'procedure', 'PROCEDURE');
            }

            if (isset($GLOBALS['xml_export_events'])
                && $GLOBALS['xml_export_events']
            ) {
                // Export events
                $events = $dbi->fetchResult(
                    'SELECT EVENT_NAME FROM information_schema.EVENTS '
                    . "WHERE EVENT_SCHEMA='" . $dbi->escapeString($db)
                    . "'"
                );
                $head .= $this->exportDefinitions(
                    $db,
                    'event',
                    'EVENT',
                    $events
                );
            }

            unset($result);

            $head .= '        </pma:database>' . $crlf;
            $head .= '    </pma:structure_schemas>' . $crlf;

            if ($export_data) {
                $head .= $crlf;
            }
        }

        return $this->export->outputHandler($head);
    }

    /**
     * Outputs export footer
     *
     * @return bool Whether it succeeded
     */
    public function exportFooter()
    {
        $foot = '</pma_xml_export>';

        return $this->export->outputHandler($foot);
    }

    /**
     * Outputs database header
     *
     * @param string $db       Database name
     * @param string $db_alias Aliases of db
     *
     * @return bool Whether it succeeded
     */
    public function exportDBHeader($db, $db_alias = '')
    {
        global $crlf;

        if (empty($db_alias)) {
            $db_alias = $db;
        }
        if (isset($GLOBALS['xml_export_contents'])
            && $GLOBALS['xml_export_contents']
        ) {
            $head = '    <!--' . $crlf
                . '    - ' . __('Database:') . ' \''
                . htmlspecialchars($db_alias) . '\'' . $crlf
                . '    -->' . $crlf . '    <database name="'
                . htmlspecialchars($db_alias) . '">' . $crlf;

            return $this->export->outputHandler($head);
        }

        return true;
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

        if (isset($GLOBALS['xml_export_contents'])
            && $GLOBALS['xml_export_contents']
        ) {
            return $this->export->outputHandler('    </database>' . $crlf);
        }

        return true;
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
        return true;
    }

    /**
     * Outputs the content of a table in XML format
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
        array $aliases = []
    ) {
        global $dbi;

        // Do not export data for merge tables
        if ($dbi->getTable($db, $table)->isMerge()) {
            return true;
        }

        $db_alias = $db;
        $table_alias = $table;
        $this->initAlias($aliases, $db_alias, $table_alias);
        if (isset($GLOBALS['xml_export_contents'])
            && $GLOBALS['xml_export_contents']
        ) {
            $result = $dbi->query(
                $sql_query,
                DatabaseInterface::CONNECT_USER,
                DatabaseInterface::QUERY_UNBUFFERED
            );

            $columns_cnt = $dbi->numFields($result);
            $columns = [];
            for ($i = 0; $i < $columns_cnt; $i++) {
                $columns[$i] = stripslashes($dbi->fieldName($result, $i));
            }
            unset($i);

            $buffer = '        <!-- ' . __('Table') . ' '
                . htmlspecialchars($table_alias) . ' -->' . $crlf;
            if (! $this->export->outputHandler($buffer)) {
                return false;
            }

            while ($record = $dbi->fetchRow($result)) {
                $buffer = '        <table name="'
                    . htmlspecialchars($table_alias) . '">' . $crlf;
                for ($i = 0; $i < $columns_cnt; $i++) {
                    $col_as = $columns[$i];
                    if (! empty($aliases[$db]['tables'][$table]['columns'][$col_as])
                    ) {
                        $col_as
                            = $aliases[$db]['tables'][$table]['columns'][$col_as];
                    }
                    // If a cell is NULL, still export it to preserve
                    // the XML structure
                    if (! isset($record[$i]) || $record[$i] === null) {
                        $record[$i] = 'NULL';
                    }
                    $buffer .= '            <column name="'
                        . htmlspecialchars($col_as) . '">'
                        . htmlspecialchars((string) $record[$i])
                        . '</column>' . $crlf;
                }
                $buffer .= '        </table>' . $crlf;

                if (! $this->export->outputHandler($buffer)) {
                    return false;
                }
            }
            $dbi->freeResult($result);
        }

        return true;
    }

    /* ~~~~~~~~~~~~~~~~~~~~ Getters and Setters ~~~~~~~~~~~~~~~~~~~~ */

    /**
     * Gets the table name
     *
     * @return string
     */
    private function getTable()
    {
        return $this->table;
    }

    /**
     * Sets the table name
     *
     * @param string $table table name
     *
     * @return void
     */
    private function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * Gets the table names
     *
     * @return array
     */
    private function getTables()
    {
        return $this->tables;
    }

    /**
     * Sets the table names
     *
     * @param array $tables table names
     *
     * @return void
     */
    private function setTables(array $tables)
    {
        $this->tables = $tables;
    }
}
