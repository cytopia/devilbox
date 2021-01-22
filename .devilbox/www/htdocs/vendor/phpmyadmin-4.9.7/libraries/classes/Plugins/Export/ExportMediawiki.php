<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Set of functions used to build MediaWiki dumps of tables
 *
 * @package    PhpMyAdmin-Export
 * @subpackage MediaWiki
 */
namespace PhpMyAdmin\Plugins\Export;

use PhpMyAdmin\DatabaseInterface;
use PhpMyAdmin\Export;
use PhpMyAdmin\Plugins\ExportPlugin;
use PhpMyAdmin\Properties\Plugins\ExportPluginProperties;
use PhpMyAdmin\Properties\Options\Groups\OptionsPropertyMainGroup;
use PhpMyAdmin\Properties\Options\Groups\OptionsPropertyRootGroup;
use PhpMyAdmin\Properties\Options\Groups\OptionsPropertySubgroup;
use PhpMyAdmin\Properties\Options\Items\BoolPropertyItem;
use PhpMyAdmin\Properties\Options\Items\RadioPropertyItem;
use PhpMyAdmin\Util;

/**
 * Handles the export for the MediaWiki class
 *
 * @package    PhpMyAdmin-Export
 * @subpackage MediaWiki
 */
class ExportMediawiki extends ExportPlugin
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setProperties();
    }

    /**
     * Sets the export MediaWiki properties
     *
     * @return void
     */
    protected function setProperties()
    {
        $exportPluginProperties = new ExportPluginProperties();
        $exportPluginProperties->setText('MediaWiki Table');
        $exportPluginProperties->setExtension('mediawiki');
        $exportPluginProperties->setMimeType('text/plain');
        $exportPluginProperties->setOptionsText(__('Options'));

        // create the root group that will be the options field for
        // $exportPluginProperties
        // this will be shown as "Format specific options"
        $exportSpecificOptions = new OptionsPropertyRootGroup(
            "Format Specific Options"
        );

        // general options main group
        $generalOptions = new OptionsPropertyMainGroup(
            "general_opts", __('Dump table')
        );

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

        // export table name
        $leaf = new BoolPropertyItem(
            "caption",
            __('Export table names')
        );
        $generalOptions->addProperty($leaf);

        // export table headers
        $leaf = new BoolPropertyItem(
            "headers",
            __('Export table headers')
        );
        $generalOptions->addProperty($leaf);
        //add the main group to the root group
        $exportSpecificOptions->addProperty($generalOptions);

        // set the options for the export plugin property item
        $exportPluginProperties->setOptions($exportSpecificOptions);
        $this->properties = $exportPluginProperties;
    }

    /**
     * Outputs export header
     *
     * @return bool Whether it succeeded
     */
    public function exportHeader()
    {
        return true;
    }

    /**
     * Outputs export footer
     *
     * @return bool Whether it succeeded
     */
    public function exportFooter()
    {
        return true;
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
     * Outputs table's structure
     *
     * @param string $db          database name
     * @param string $table       table name
     * @param string $crlf        the end of line sequence
     * @param string $error_url   the url to go back in case of error
     * @param string $export_mode 'create_table','triggers','create_view',
     *                            'stand_in'
     * @param string $export_type 'server', 'database', 'table'
     * @param bool   $do_relation whether to include relation comments
     * @param bool   $do_comments whether to include the pmadb-style column
     *                            comments as comments in the structure; this is
     *                            deprecated but the parameter is left here
     *                            because export.php calls exportStructure()
     *                            also for other export types which use this
     *                            parameter
     * @param bool   $do_mime     whether to include mime comments
     * @param bool   $dates       whether to include creation/update/check dates
     * @param array  $aliases     Aliases of db/table/columns
     *
     * @return bool               Whether it succeeded
     */
    public function exportStructure(
        $db,
        $table,
        $crlf,
        $error_url,
        $export_mode,
        $export_type,
        $do_relation = false,
        $do_comments = false,
        $do_mime = false,
        $dates = false,
        array $aliases = array()
    ) {
        $db_alias = $db;
        $table_alias = $table;
        $this->initAlias($aliases, $db_alias, $table_alias);

        $output = '';
        switch ($export_mode) {
        case 'create_table':
            $columns = $GLOBALS['dbi']->getColumns($db, $table);
            $columns = array_values($columns);
            $row_cnt = count($columns);

            // Print structure comment
            $output = $this->_exportComment(
                "Table structure for "
                . Util::backquote($table_alias)
            );

            // Begin the table construction
            $output .= "{| class=\"wikitable\" style=\"text-align:center;\""
                . $this->_exportCRLF();

            // Add the table name
            if (isset($GLOBALS['mediawiki_caption'])) {
                $output .= "|+'''" . $table_alias . "'''" . $this->_exportCRLF();
            }

            // Add the table headers
            if (isset($GLOBALS['mediawiki_headers'])) {
                $output .= "|- style=\"background:#ffdead;\"" . $this->_exportCRLF();
                $output .= "! style=\"background:#ffffff\" | "
                    . $this->_exportCRLF();
                for ($i = 0; $i < $row_cnt; ++$i) {
                    $col_as = $columns[$i]['Field'];
                    if (!empty($aliases[$db]['tables'][$table]['columns'][$col_as])
                    ) {
                        $col_as
                            = $aliases[$db]['tables'][$table]['columns'][$col_as];
                    }
                    $output .= " | " . $col_as . $this->_exportCRLF();
                }
            }

            // Add the table structure
            $output .= "|-" . $this->_exportCRLF();
            $output .= "! Type" . $this->_exportCRLF();
            for ($i = 0; $i < $row_cnt; ++$i) {
                $output .= " | " . $columns[$i]['Type'] . $this->_exportCRLF();
            }

            $output .= "|-" . $this->_exportCRLF();
            $output .= "! Null" . $this->_exportCRLF();
            for ($i = 0; $i < $row_cnt; ++$i) {
                $output .= " | " . $columns[$i]['Null'] . $this->_exportCRLF();
            }

            $output .= "|-" . $this->_exportCRLF();
            $output .= "! Default" . $this->_exportCRLF();
            for ($i = 0; $i < $row_cnt; ++$i) {
                $output .= " | " . $columns[$i]['Default'] . $this->_exportCRLF();
            }

            $output .= "|-" . $this->_exportCRLF();
            $output .= "! Extra" . $this->_exportCRLF();
            for ($i = 0; $i < $row_cnt; ++$i) {
                $output .= " | " . $columns[$i]['Extra'] . $this->_exportCRLF();
            }

            $output .= "|}" . str_repeat($this->_exportCRLF(), 2);
            break;
        } // end switch

        return Export::outputHandler($output);
    }

    /**
     * Outputs the content of a table in MediaWiki format
     *
     * @param string $db        database name
     * @param string $table     table name
     * @param string $crlf      the end of line sequence
     * @param string $error_url the url to go back in case of error
     * @param string $sql_query SQL query for obtaining data
     * @param array  $aliases   Aliases of db/table/columns
     *
     * @return bool             Whether it succeeded
     */
    public function exportData(
        $db,
        $table,
        $crlf,
        $error_url,
        $sql_query,
        array $aliases = array()
    ) {
        $db_alias = $db;
        $table_alias = $table;
        $this->initAlias($aliases, $db_alias, $table_alias);

        // Print data comment
        $output = $this->_exportComment(
            "Table data for " . Util::backquote($table_alias)
        );

        // Begin the table construction
        // Use the "wikitable" class for style
        // Use the "sortable"  class for allowing tables to be sorted by column
        $output .= "{| class=\"wikitable sortable\" style=\"text-align:center;\""
            . $this->_exportCRLF();

        // Add the table name
        if (isset($GLOBALS['mediawiki_caption'])) {
            $output .= "|+'''" . $table_alias . "'''" . $this->_exportCRLF();
        }

        // Add the table headers
        if (isset($GLOBALS['mediawiki_headers'])) {
            // Get column names
            $column_names = $GLOBALS['dbi']->getColumnNames($db, $table);

            // Add column names as table headers
            if (!is_null($column_names)) {
                // Use '|-' for separating rows
                $output .= "|-" . $this->_exportCRLF();

                // Use '!' for separating table headers
                foreach ($column_names as $column) {
                    if (!empty($aliases[$db]['tables'][$table]['columns'][$column])
                    ) {
                        $column
                            = $aliases[$db]['tables'][$table]['columns'][$column];
                    }
                    $output .= " ! " . $column . "" . $this->_exportCRLF();
                }
            }
        }

        // Get the table data from the database
        $result = $GLOBALS['dbi']->query(
            $sql_query,
            DatabaseInterface::CONNECT_USER,
            DatabaseInterface::QUERY_UNBUFFERED
        );
        $fields_cnt = $GLOBALS['dbi']->numFields($result);

        while ($row = $GLOBALS['dbi']->fetchRow($result)) {
            $output .= "|-" . $this->_exportCRLF();

            // Use '|' for separating table columns
            for ($i = 0; $i < $fields_cnt; ++$i) {
                $output .= " | " . $row[$i] . "" . $this->_exportCRLF();
            }
        }

        // End table construction
        $output .= "|}" . str_repeat($this->_exportCRLF(), 2);

        return Export::outputHandler($output);
    }

    /**
     * Outputs comments containing info about the exported tables
     *
     * @param string $text Text of comment
     *
     * @return string The formatted comment
     */
    private function _exportComment($text = '')
    {
        // see https://www.mediawiki.org/wiki/Help:Formatting
        $comment = $this->_exportCRLF();
        $comment .= '<!--' . $this->_exportCRLF();
        $comment .= htmlspecialchars($text) . $this->_exportCRLF();
        $comment .= '-->' . str_repeat($this->_exportCRLF(), 2);

        return $comment;
    }

    /**
     * Outputs CRLF
     *
     * @return string CRLF
     */
    private function _exportCRLF()
    {
        // The CRLF expected by the mediawiki format is "\n"
        return "\n";
    }
}
