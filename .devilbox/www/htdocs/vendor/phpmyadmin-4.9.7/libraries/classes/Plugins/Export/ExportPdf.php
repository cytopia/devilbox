<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Produce a PDF report (export) from a query
 *
 * @package    PhpMyAdmin-Export
 * @subpackage PDF
 */
namespace PhpMyAdmin\Plugins\Export;

use PhpMyAdmin\Export;
use PhpMyAdmin\Plugins\ExportPlugin;
use PhpMyAdmin\Plugins\Export\Helpers\Pdf;
use PhpMyAdmin\Properties\Plugins\ExportPluginProperties;
use PhpMyAdmin\Properties\Options\Groups\OptionsPropertyMainGroup;
use PhpMyAdmin\Properties\Options\Groups\OptionsPropertyRootGroup;
use PhpMyAdmin\Properties\Options\Items\RadioPropertyItem;
use PhpMyAdmin\Properties\Options\Items\TextPropertyItem;

/**
 * Skip the plugin if TCPDF is not available.
 */
if (! class_exists('TCPDF')) {
    $GLOBALS['skip_import'] = true;
    return;
}

/**
 * Handles the export for the PDF class
 *
 * @package    PhpMyAdmin-Export
 * @subpackage PDF
 */
class ExportPdf extends ExportPlugin
{
    /**
     * PhpMyAdmin\Plugins\Export\Helpers\Pdf instance
     *
     * @var Pdf
     */
    private $_pdf;
    /**
     * PDF Report Title
     *
     * @var string
     */
    private $_pdfReportTitle;

    /**
     * Constructor
     */
    public function __construct()
    {
        // initialize the specific export PDF variables
        $this->initSpecificVariables();

        $this->setProperties();
    }

    /**
     * Initialize the local variables that are used for export PDF
     *
     * @return void
     */
    protected function initSpecificVariables()
    {
        if (!empty($_POST['pdf_report_title'])) {
            $this->_setPdfReportTitle($_POST['pdf_report_title']);
        }
        $this->_setPdf(new Pdf('L', 'pt', 'A3'));
    }

    /**
     * Sets the export PDF properties
     *
     * @return void
     */
    protected function setProperties()
    {
        $exportPluginProperties = new ExportPluginProperties();
        $exportPluginProperties->setText('PDF');
        $exportPluginProperties->setExtension('pdf');
        $exportPluginProperties->setMimeType('application/pdf');
        $exportPluginProperties->setForceFile(true);
        $exportPluginProperties->setOptionsText(__('Options'));

        // create the root group that will be the options field for
        // $exportPluginProperties
        // this will be shown as "Format specific options"
        $exportSpecificOptions = new OptionsPropertyRootGroup(
            "Format Specific Options"
        );

        // general options main group
        $generalOptions = new OptionsPropertyMainGroup("general_opts");
        // create primary items and add them to the group
        $leaf = new TextPropertyItem(
            "report_title",
            __('Report title:')
        );
        $generalOptions->addProperty($leaf);
        // add the group to the root group
        $exportSpecificOptions->addProperty($generalOptions);

        // what to dump (structure/data/both) main group
        $dumpWhat = new OptionsPropertyMainGroup(
            "dump_what", __('Dump table')
        );
        $leaf = new RadioPropertyItem("structure_or_data");
        $leaf->setValues(
            array(
                'structure'          => __('structure'),
                'data'               => __('data'),
                'structure_and_data' => __('structure and data'),
            )
        );
        $dumpWhat->addProperty($leaf);
        // add the group to the root group
        $exportSpecificOptions->addProperty($dumpWhat);

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
        $pdf_report_title = $this->_getPdfReportTitle();
        $pdf = $this->_getPdf();
        $pdf->Open();

        $attr = array('titleFontSize' => 18, 'titleText' => $pdf_report_title);
        $pdf->setAttributes($attr);
        $pdf->setTopMargin(30);

        return true;
    }

    /**
     * Outputs export footer
     *
     * @return bool Whether it succeeded
     */
    public function exportFooter()
    {
        $pdf = $this->_getPdf();

        // instead of $pdf->Output():
        return Export::outputHandler($pdf->getPDFData());
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
     * Outputs the content of a table in NHibernate format
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
        $db_alias = $db;
        $table_alias = $table;
        $this->initAlias($aliases, $db_alias, $table_alias);
        $pdf = $this->_getPdf();
        $attr = array(
            'currentDb'    => $db,
            'currentTable' => $table,
            'dbAlias'      => $db_alias,
            'tableAlias'   => $table_alias,
            'aliases'      => $aliases,
        );
        $pdf->setAttributes($attr);
        $pdf->purpose = __('Dumping data');
        $pdf->mysqlReport($sql_query);

        return true;
    } // end of the 'PMA_exportData()' function

    /**
     * Outputs table structure
     *
     * @param string $db          database name
     * @param string $table       table name
     * @param string $crlf        the end of line sequence
     * @param string $error_url   the url to go back in case of error
     * @param string $export_mode 'create_table', 'triggers', 'create_view',
     *                            'stand_in'
     * @param string $export_type 'server', 'database', 'table'
     * @param bool   $do_relation whether to include relation comments
     * @param bool   $do_comments whether to include the pmadb-style column
     *                            comments as comments in the structure;
     *                            this is deprecated but the parameter is
     *                            left here because export.php calls
     *                            PMA_exportStructure() also for other
     *                            export types which use this parameter
     * @param bool   $do_mime     whether to include mime comments
     * @param bool   $dates       whether to include creation/update/check dates
     * @param array  $aliases     aliases for db/table/columns
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
        $do_relation = false,
        $do_comments = false,
        $do_mime = false,
        $dates = false,
        array $aliases = array()
    ) {
        $db_alias = $db;
        $table_alias = $table;
        $this->initAlias($aliases, $db_alias, $table_alias);
        $pdf = $this->_getPdf();
        // getting purpose to show at top
        switch ($export_mode) {
        case 'create_table':
            $purpose = __('Table structure');
            break;
        case 'triggers':
            $purpose = __('Triggers');
            break;
        case 'create_view':
            $purpose = __('View structure');
            break;
        case 'stand_in':
            $purpose = __('Stand in');
        } // end switch

        $attr = array(
            'currentDb'    => $db,
            'currentTable' => $table,
            'dbAlias'      => $db_alias,
            'tableAlias'   => $table_alias,
            'aliases'      => $aliases,
            'purpose'      => $purpose,
        );
        $pdf->setAttributes($attr);
        /**
         * comment display set true as presently in pdf
         * format, no option is present to take user input.
         */
        $do_comments = true;
        switch ($export_mode) {
        case 'create_table':
            $pdf->getTableDef(
                $db,
                $table,
                $do_relation,
                $do_comments,
                $do_mime,
                false,
                $aliases
            );
            break;
        case 'triggers':
            $pdf->getTriggers($db, $table);
            break;
        case 'create_view':
            $pdf->getTableDef(
                $db,
                $table,
                $do_relation,
                $do_comments,
                $do_mime,
                false,
                $aliases
            );
            break;
        case 'stand_in':
            /* export a stand-in definition to resolve view dependencies
             * Yet to develop this function
             * $pdf->getTableDefStandIn($db, $table, $crlf);
             */
        } // end switch

        return true;
    }


    /* ~~~~~~~~~~~~~~~~~~~~ Getters and Setters ~~~~~~~~~~~~~~~~~~~~ */

    /**
     * Gets the PhpMyAdmin\Plugins\Export\Helpers\Pdf instance
     *
     * @return Pdf
     */
    private function _getPdf()
    {
        return $this->_pdf;
    }

    /**
     * Instantiates the PhpMyAdmin\Plugins\Export\Helpers\Pdf class
     *
     * @param Pdf $pdf The instance
     *
     * @return void
     */
    private function _setPdf($pdf)
    {
        $this->_pdf = $pdf;
    }

    /**
     * Gets the PDF report title
     *
     * @return string
     */
    private function _getPdfReportTitle()
    {
        return $this->_pdfReportTitle;
    }

    /**
     * Sets the PDF report title
     *
     * @param string $pdfReportTitle PDF report title
     *
     * @return void
     */
    private function _setPdfReportTitle($pdfReportTitle)
    {
        $this->_pdfReportTitle = $pdfReportTitle;
    }
}
