<?php
/**
 * Contains PhpMyAdmin\Plugins\Schema\Pdf\TableStatsPdf class
 */

declare(strict_types=1);

namespace PhpMyAdmin\Plugins\Schema\Pdf;

use PhpMyAdmin\Pdf as PdfLib;
use PhpMyAdmin\Plugins\Schema\ExportRelationSchema;
use PhpMyAdmin\Plugins\Schema\TableStats;
use function count;
use function in_array;
use function max;
use function sprintf;

/**
 * Table preferences/statistics
 *
 * This class preserves the table co-ordinates,fields
 * and helps in drawing/generating the Tables in PDF document.
 *
 * @see     PMA_Schema_PDF
 *
 * @name    TableStatsPdf
 */
class TableStatsPdf extends TableStats
{
    /** @var int */
    public $height;

    /** @var string */
    private $ff = PdfLib::PMA_PDF_FONT;

    /**
     * @see PMA_Schema_PDF
     * @see TableStatsPdf::setWidthTable
     * @see PhpMyAdmin\Plugins\Schema\Pdf\TableStatsPdf::setHeightTable
     *
     * @param object $diagram        The PDF diagram
     * @param string $db             The database name
     * @param string $tableName      The table name
     * @param int    $fontSize       The font size
     * @param int    $pageNumber     The current page number (from the
     *                               $cfg['Servers'][$i]['table_coords'] table)
     * @param int    $sameWideWidth  The max. width among tables
     * @param bool   $showKeys       Whether to display keys or not
     * @param bool   $tableDimension Whether to display table position or not
     * @param bool   $offline        Whether the coordinates are sent
     *                               from the browser
     */
    public function __construct(
        $diagram,
        $db,
        $tableName,
        $fontSize,
        $pageNumber,
        &$sameWideWidth,
        $showKeys = false,
        $tableDimension = false,
        $offline = false
    ) {
        parent::__construct(
            $diagram,
            $db,
            $pageNumber,
            $tableName,
            $showKeys,
            $tableDimension,
            $offline
        );

        $this->heightCell = 6;
        $this->setHeight();
        /*
         * setWidth must me after setHeight, because title
        * can include table height which changes table width
        */
        $this->setWidth($fontSize);
        if ($sameWideWidth >= $this->width) {
            return;
        }

        $sameWideWidth = $this->width;
    }

    /**
     * Displays an error when the table cannot be found.
     *
     * @return void
     */
    protected function showMissingTableError()
    {
        ExportRelationSchema::dieSchema(
            $this->pageNumber,
            'PDF',
            sprintf(__('The %s table doesn\'t exist!'), $this->tableName)
        );
    }

    /**
     * Returns title of the current table,
     * title can have the dimensions of the table
     *
     * @return string
     */
    protected function getTitle()
    {
        $ret = '';
        if ($this->tableDimension) {
            $ret = sprintf('%.0fx%0.f', $this->width, $this->height);
        }

        return $ret . ' ' . $this->tableName;
    }

    /**
     * Sets the width of the table
     *
     * @see    PMA_Schema_PDF
     *
     * @param int $fontSize The font size
     *
     * @return void
     *
     * @access private
     */
    private function setWidth($fontSize)
    {
        foreach ($this->fields as $field) {
            $this->width = max($this->width, $this->diagram->GetStringWidth($field));
        }
        $this->width += $this->diagram->GetStringWidth('      ');
        $this->diagram->SetFont($this->ff, 'B', $fontSize);
        /*
         * it is unknown what value must be added, because
         * table title is affected by the table width value
         */
        while ($this->width < $this->diagram->GetStringWidth($this->getTitle())) {
            $this->width += 5;
        }
        $this->diagram->SetFont($this->ff, '', $fontSize);
    }

    /**
     * Sets the height of the table
     *
     * @return void
     *
     * @access private
     */
    private function setHeight()
    {
        $this->height = (count($this->fields) + 1) * $this->heightCell;
    }

    /**
     * Do draw the table
     *
     * @see    PMA_Schema_PDF
     *
     * @param int      $fontSize The font size
     * @param bool     $withDoc  Whether to include links to documentation
     * @param bool|int $setColor Whether to display color
     *
     * @return void
     *
     * @access public
     */
    public function tableDraw($fontSize, $withDoc, $setColor = 0)
    {
        $this->diagram->setXyScale($this->x, $this->y);
        $this->diagram->SetFont($this->ff, 'B', $fontSize);
        if ($setColor) {
            $this->diagram->SetTextColor(200);
            $this->diagram->SetFillColor(0, 0, 128);
        }
        if ($withDoc) {
            $this->diagram->SetLink(
                $this->diagram->customLinks['RT'][$this->tableName]['-'],
                -1
            );
        } else {
            $this->diagram->customLinks['doc'][$this->tableName]['-'] = '';
        }

        $this->diagram->cellScale(
            $this->width,
            $this->heightCell,
            $this->getTitle(),
            1,
            1,
            'C',
            $setColor,
            $this->diagram->customLinks['doc'][$this->tableName]['-']
        );
        $this->diagram->setXScale($this->x);
        $this->diagram->SetFont($this->ff, '', $fontSize);
        $this->diagram->SetTextColor(0);
        $this->diagram->SetFillColor(255);

        foreach ($this->fields as $field) {
            if ($setColor) {
                if (in_array($field, $this->primary)) {
                    $this->diagram->SetFillColor(215, 121, 123);
                }
                if ($field == $this->displayfield) {
                    $this->diagram->SetFillColor(142, 159, 224);
                }
            }
            if ($withDoc) {
                $this->diagram->SetLink(
                    $this->diagram->customLinks['RT'][$this->tableName][$field],
                    -1
                );
            } else {
                $this->diagram->customLinks['doc'][$this->tableName][$field] = '';
            }

            $this->diagram->cellScale(
                $this->width,
                $this->heightCell,
                ' ' . $field,
                1,
                1,
                'L',
                $setColor,
                $this->diagram->customLinks['doc'][$this->tableName][$field]
            );
            $this->diagram->setXScale($this->x);
            $this->diagram->SetFillColor(255);
        }
    }
}
