<?php
/**
 * PhpMyAdmin\Plugins\Export\Helpers\Pdf class
 */

declare(strict_types=1);

namespace PhpMyAdmin\Plugins\Export\Helpers;

use PhpMyAdmin\DatabaseInterface;
use PhpMyAdmin\Pdf as PdfLib;
use PhpMyAdmin\Relation;
use PhpMyAdmin\Transformations;
use PhpMyAdmin\Util;
use TCPDF_STATIC;
use function array_key_exists;
use function count;
use function ksort;
use function stripos;

/**
 * Adapted from a LGPL script by Philip Clarke
 */
class Pdf extends PdfLib
{
    /** @var array */
    public $tablewidths;

    /** @var array */
    public $headerset;

    /** @var int|float */
    private $dataY;

    /** @var int|float */
    private $cellFontSize;

    /** @var mixed */
    private $titleFontSize;

    /** @var mixed */
    private $titleText;

    /** @var mixed */
    private $dbAlias;

    /** @var mixed */
    private $tableAlias;

    /** @var mixed */
    private $purpose;

    /** @var array */
    private $colTitles;

    /** @var mixed */
    private $results;

    /** @var array */
    private $colAlign;

    /** @var mixed */
    private $titleWidth;

    /** @var mixed */
    private $colFits;

    /** @var array */
    private $displayColumn;

    /** @var int */
    private $numFields;

    /** @var array */
    private $fields;

    /** @var int|float */
    private $sColWidth;

    /** @var mixed */
    private $currentDb;

    /** @var mixed */
    private $currentTable;

    /** @var array */
    private $aliases;

    /** @var Relation */
    private $relation;

    /** @var Transformations */
    private $transformations;

    /**
     * Constructs PDF and configures standard parameters.
     *
     * @param string    $orientation page orientation
     * @param string    $unit        unit
     * @param string    $format      the format used for pages
     * @param bool      $unicode     true means that the input text is unicode
     * @param string    $encoding    charset encoding; default is UTF-8.
     * @param bool      $diskcache   DEPRECATED TCPDF FEATURE
     * @param false|int $pdfa        If not false, set the document to PDF/A mode and the good version (1 or 3)
     *
     * @access public
     */
    public function __construct(
        $orientation = 'P',
        $unit = 'mm',
        $format = 'A4',
        $unicode = true,
        $encoding = 'UTF-8',
        $diskcache = false,
        $pdfa = false
    ) {
        global $dbi;

        parent::__construct(
            $orientation,
            $unit,
            $format,
            $unicode,
            $encoding,
            $diskcache,
            $pdfa
        );
        $this->relation = new Relation($dbi);
        $this->transformations = new Transformations();
    }

    /**
     * Add page if needed.
     *
     * @param float|int $h       cell height. Default value: 0
     * @param mixed     $y       starting y position, leave empty for current
     *                           position
     * @param bool      $addpage if true add a page, otherwise only return
     *                           the true/false state
     *
     * @return bool true in case of page break, false otherwise.
     */
    public function checkPageBreak($h = 0, $y = '', $addpage = true)
    {
        if (TCPDF_STATIC::empty_string($y)) {
            $y = $this->y;
        }
        $current_page = $this->page;
        if (($y + $h > $this->PageBreakTrigger)
            && (! $this->InFooter)
            && $this->AcceptPageBreak()
        ) {
            if ($addpage) {
                //Automatic page break
                $x = $this->x;
                $this->AddPage($this->CurOrientation);
                $this->y = $this->dataY;
                $oldpage = $this->page - 1;

                $this_page_orm = $this->pagedim[$this->page]['orm'];
                $old_page_orm = $this->pagedim[$oldpage]['orm'];
                $this_page_olm = $this->pagedim[$this->page]['olm'];
                $old_page_olm = $this->pagedim[$oldpage]['olm'];
                if ($this->rtl) {
                    if ($this_page_orm != $old_page_orm) {
                        $this->x = $x - ($this_page_orm - $old_page_orm);
                    } else {
                        $this->x = $x;
                    }
                } else {
                    if ($this_page_olm != $old_page_olm) {
                        $this->x = $x + $this_page_olm - $old_page_olm;
                    } else {
                        $this->x = $x;
                    }
                }
            }

            return true;
        }

        // account for columns mode
        return $current_page != $this->page;
    }

    /**
     * This method is used to render the page header.
     *
     * @return void
     */
    // @codingStandardsIgnoreLine
    public function Header()
    {
        global $maxY;
        // We don't want automatic page breaks while generating header
        // as this can lead to infinite recursion as auto generated page
        // will want header as well causing another page break
        // FIXME: Better approach might be to try to compact the content
        $this->SetAutoPageBreak(false);
        // Check if header for this page already exists
        if (! isset($this->headerset[$this->page])) {
            $this->SetY($this->tMargin - ($this->FontSizePt / $this->k) * 5);
            $this->cellFontSize = $this->FontSizePt;
            $this->SetFont(
                PdfLib::PMA_PDF_FONT,
                '',
                ($this->titleFontSize
                    ?: $this->FontSizePt)
            );
            $this->Cell(0, $this->FontSizePt, $this->titleText, 0, 1, 'C');
            $this->SetFont(PdfLib::PMA_PDF_FONT, '', $this->cellFontSize);
            $this->SetY($this->tMargin - ($this->FontSizePt / $this->k) * 2.5);
            $this->Cell(
                0,
                $this->FontSizePt,
                __('Database:') . ' ' . $this->dbAlias . ',  '
                . __('Table:') . ' ' . $this->tableAlias . ',  '
                . __('Purpose:') . ' ' . $this->purpose,
                0,
                1,
                'L'
            );
            $l = $this->lMargin;
            foreach ($this->colTitles as $col => $txt) {
                $this->SetXY($l, $this->tMargin);
                $this->MultiCell(
                    $this->tablewidths[$col],
                    $this->FontSizePt,
                    $txt
                );
                $l += $this->tablewidths[$col];
                $maxY = $maxY < $this->GetY() ? $this->GetY() : $maxY;
            }
            $this->SetXY($this->lMargin, $this->tMargin);
            $this->SetFillColor(200, 200, 200);
            $l = $this->lMargin;
            foreach ($this->colTitles as $col => $txt) {
                $this->SetXY($l, $this->tMargin);
                $this->Cell(
                    $this->tablewidths[$col],
                    $maxY - $this->tMargin,
                    '',
                    1,
                    0,
                    'L',
                    1
                );
                $this->SetXY($l, $this->tMargin);
                $this->MultiCell(
                    $this->tablewidths[$col],
                    $this->FontSizePt,
                    $txt,
                    0,
                    'C'
                );
                $l += $this->tablewidths[$col];
            }
            $this->SetFillColor(255, 255, 255);
            // set headerset
            $this->headerset[$this->page] = 1;
        }

        $this->dataY = $maxY;
        $this->SetAutoPageBreak(true);
    }

    /**
     * Generate table
     *
     * @param int $lineheight Height of line
     *
     * @return void
     */
    public function morepagestable($lineheight = 8)
    {
        global $dbi;

        // some things to set and 'remember'
        $l = $this->lMargin;
        $startheight = $h = $this->dataY;
        $startpage = $currpage = $this->page;

        // calculate the whole width
        $fullwidth = 0;
        foreach ($this->tablewidths as $width) {
            $fullwidth += $width;
        }

        // Now let's start to write the table
        $row = 0;
        $tmpheight = [];
        $maxpage = $this->page;

        while ($data = $dbi->fetchRow($this->results)) {
            $this->page = $currpage;
            // write the horizontal borders
            $this->Line($l, $h, $fullwidth + $l, $h);
            // write the content and remember the height of the highest col
            foreach ($data as $col => $txt) {
                $this->page = $currpage;
                $this->SetXY($l, $h);
                if ($this->tablewidths[$col] > 0) {
                    $this->MultiCell(
                        $this->tablewidths[$col],
                        $lineheight,
                        $txt,
                        0,
                        $this->colAlign[$col]
                    );
                    $l += $this->tablewidths[$col];
                }

                if (! isset($tmpheight[$row . '-' . $this->page])) {
                    $tmpheight[$row . '-' . $this->page] = 0;
                }
                if ($tmpheight[$row . '-' . $this->page] < $this->GetY()) {
                    $tmpheight[$row . '-' . $this->page] = $this->GetY();
                }
                if ($this->page > $maxpage) {
                    $maxpage = $this->page;
                }
                unset($data[$col]);
            }

            // get the height we were in the last used page
            $h = $tmpheight[$row . '-' . $maxpage];
            // set the "pointer" to the left margin
            $l = $this->lMargin;
            // set the $currpage to the last page
            $currpage = $maxpage;
            unset($data[$row]);
            $row++;
        }
        // draw the borders
        // we start adding a horizontal line on the last page
        $this->page = $maxpage;
        $this->Line($l, $h, $fullwidth + $l, $h);
        // now we start at the top of the document and walk down
        for ($i = $startpage; $i <= $maxpage; $i++) {
            $this->page = $i;
            $l = $this->lMargin;
            $t = $i == $startpage ? $startheight : $this->tMargin;
            $lh = $i == $maxpage ? $h : $this->h - $this->bMargin;
            $this->Line($l, $t, $l, $lh);
            foreach ($this->tablewidths as $width) {
                $l += $width;
                $this->Line($l, $t, $l, $lh);
            }
        }
        // set it to the last page, if not it'll cause some problems
        $this->page = $maxpage;
    }

    /**
     * Sets a set of attributes.
     *
     * @param array $attr array containing the attributes
     *
     * @return void
     */
    public function setAttributes(array $attr = [])
    {
        foreach ($attr as $key => $val) {
            $this->$key = $val;
        }
    }

    /**
     * Defines the top margin.
     * The method can be called before creating the first page.
     *
     * @param float $topMargin the margin
     *
     * @return void
     */
    public function setTopMargin($topMargin)
    {
        $this->tMargin = $topMargin;
    }

    /**
     * Prints triggers
     *
     * @param string $db    database name
     * @param string $table table name
     *
     * @return void
     */
    public function getTriggers($db, $table)
    {
        global $dbi;

        $triggers = $dbi->getTriggers($db, $table);
        if ($triggers === []) {
            return; //prevents printing blank trigger list for any table
        }

        unset(
            $this->tablewidths,
            $this->colTitles,
            $this->titleWidth,
            $this->colFits,
            $this->displayColumn,
            $this->colAlign
        );

        /**
         * Making table heading
         * Keeping column width constant
         */
        $this->colTitles[0] = __('Name');
        $this->tablewidths[0] = 90;
        $this->colTitles[1] = __('Time');
        $this->tablewidths[1] = 80;
        $this->colTitles[2] = __('Event');
        $this->tablewidths[2] = 40;
        $this->colTitles[3] = __('Definition');
        $this->tablewidths[3] = 240;

        for ($columns_cnt = 0; $columns_cnt < 4; $columns_cnt++) {
            $this->colAlign[$columns_cnt] = 'L';
            $this->displayColumn[$columns_cnt] = true;
        }

        // Starting to fill table with required info

        $this->SetY($this->tMargin);
        $this->AddPage();
        $this->SetFont(PdfLib::PMA_PDF_FONT, '', 9);

        $l = $this->lMargin;
        $startheight = $h = $this->dataY;
        $startpage = $currpage = $this->page;

        // calculate the whole width
        $fullwidth = 0;
        foreach ($this->tablewidths as $width) {
            $fullwidth += $width;
        }

        $row = 0;
        $tmpheight = [];
        $maxpage = $this->page;
        $data = [];

        foreach ($triggers as $trigger) {
            $data[] = $trigger['name'];
            $data[] = $trigger['action_timing'];
            $data[] = $trigger['event_manipulation'];
            $data[] = $trigger['definition'];
            $this->page = $currpage;
            // write the horizontal borders
            $this->Line($l, $h, $fullwidth + $l, $h);
            // write the content and remember the height of the highest col
            foreach ($data as $col => $txt) {
                $this->page = $currpage;
                $this->SetXY($l, $h);
                if ($this->tablewidths[$col] > 0) {
                    $this->MultiCell(
                        $this->tablewidths[$col],
                        $this->FontSizePt,
                        $txt,
                        0,
                        $this->colAlign[$col]
                    );
                    $l += $this->tablewidths[$col];
                }

                if (! isset($tmpheight[$row . '-' . $this->page])) {
                    $tmpheight[$row . '-' . $this->page] = 0;
                }
                if ($tmpheight[$row . '-' . $this->page] < $this->GetY()) {
                    $tmpheight[$row . '-' . $this->page] = $this->GetY();
                }
                if ($this->page <= $maxpage) {
                    continue;
                }

                $maxpage = $this->page;
            }
            // get the height we were in the last used page
            $h = $tmpheight[$row . '-' . $maxpage];
            // set the "pointer" to the left margin
            $l = $this->lMargin;
            // set the $currpage to the last page
            $currpage = $maxpage;
            unset($data);
            $row++;
        }
        // draw the borders
        // we start adding a horizontal line on the last page
        $this->page = $maxpage;
        $this->Line($l, $h, $fullwidth + $l, $h);
        // now we start at the top of the document and walk down
        for ($i = $startpage; $i <= $maxpage; $i++) {
            $this->page = $i;
            $l = $this->lMargin;
            $t = $i == $startpage ? $startheight : $this->tMargin;
            $lh = $i == $maxpage ? $h : $this->h - $this->bMargin;
            $this->Line($l, $t, $l, $lh);
            foreach ($this->tablewidths as $width) {
                $l += $width;
                $this->Line($l, $t, $l, $lh);
            }
        }
        // set it to the last page, if not it'll cause some problems
        $this->page = $maxpage;
    }

    /**
     * Print $table's CREATE definition
     *
     * @param string $db          the database name
     * @param string $table       the table name
     * @param bool   $do_relation whether to include relation comments
     * @param bool   $do_comments whether to include the pmadb-style column
     *                            comments as comments in the structure;
     *                            this is deprecated but the parameter is
     *                            left here because /export calls
     *                            PMA_exportStructure() also for other
     *                            export types which use this parameter
     * @param bool   $do_mime     whether to include mime comments
     * @param bool   $view        whether we're handling a view
     * @param array  $aliases     aliases of db/table/columns
     *
     * @return void
     */
    public function getTableDef(
        $db,
        $table,
        $do_relation,
        $do_comments,
        $do_mime,
        $view = false,
        array $aliases = []
    ) {
        global $dbi;

        // set $cfgRelation here, because there is a chance that it's modified
        // since the class initialization
        global $cfgRelation;

        unset(
            $this->tablewidths,
            $this->colTitles,
            $this->titleWidth,
            $this->colFits,
            $this->displayColumn,
            $this->colAlign
        );

        /**
         * Gets fields properties
         */
        $dbi->selectDb($db);

        /**
         * All these three checks do_relation, do_comment and do_mime is
         * not required. As presently all are set true by default.
         * But when, methods to take user input will be developed,
         * it will be of use
         */
        // Check if we can use Relations
        if ($do_relation) {
            // Find which tables are related with the current one and write it in
            // an array
            $res_rel = $this->relation->getForeigners($db, $table);
            $have_rel = ! empty($res_rel);
        } else {
            $have_rel = false;
        }

        //column count and table heading

        $this->colTitles[0] = __('Column');
        $this->tablewidths[0] = 90;
        $this->colTitles[1] = __('Type');
        $this->tablewidths[1] = 80;
        $this->colTitles[2] = __('Null');
        $this->tablewidths[2] = 40;
        $this->colTitles[3] = __('Default');
        $this->tablewidths[3] = 120;

        for ($columns_cnt = 0; $columns_cnt < 4; $columns_cnt++) {
            $this->colAlign[$columns_cnt] = 'L';
            $this->displayColumn[$columns_cnt] = true;
        }

        if ($do_relation && $have_rel) {
            $this->colTitles[$columns_cnt] = __('Links to');
            $this->displayColumn[$columns_cnt] = true;
            $this->colAlign[$columns_cnt] = 'L';
            $this->tablewidths[$columns_cnt] = 120;
            $columns_cnt++;
        }
        if ($do_comments /*&& $cfgRelation['commwork']*/) {
            $this->colTitles[$columns_cnt] = __('Comments');
            $this->displayColumn[$columns_cnt] = true;
            $this->colAlign[$columns_cnt] = 'L';
            $this->tablewidths[$columns_cnt] = 120;
            $columns_cnt++;
        }
        if ($do_mime && $cfgRelation['mimework']) {
            $this->colTitles[$columns_cnt] = __('Media type');
            $this->displayColumn[$columns_cnt] = true;
            $this->colAlign[$columns_cnt] = 'L';
            $this->tablewidths[$columns_cnt] = 120;
            $columns_cnt++;
        }

        // Starting to fill table with required info

        $this->SetY($this->tMargin);
        $this->AddPage();
        $this->SetFont(PdfLib::PMA_PDF_FONT, '', 9);

        // Now let's start to write the table structure

        if ($do_comments) {
            $comments = $this->relation->getComments($db, $table);
        }
        if ($do_mime && $cfgRelation['mimework']) {
            $mime_map = $this->transformations->getMime($db, $table, true);
        }

        $columns = $dbi->getColumns($db, $table);

        // some things to set and 'remember'
        $l = $this->lMargin;
        $startheight = $h = $this->dataY;
        $startpage = $currpage = $this->page;
        // calculate the whole width
        $fullwidth = 0;
        foreach ($this->tablewidths as $width) {
            $fullwidth += $width;
        }

        $row = 0;
        $tmpheight = [];
        $maxpage = $this->page;
        $data = [];

        // fun begin
        foreach ($columns as $column) {
            $extracted_columnspec
                = Util::extractColumnSpec($column['Type']);

            $type = $extracted_columnspec['print_type'];
            if (empty($type)) {
                $type = ' ';
            }

            if (! isset($column['Default'])) {
                if ($column['Null'] !== 'NO') {
                    $column['Default'] = 'NULL';
                }
            }
            $data[] = $column['Field'];
            $data[] = $type;
            $data[] = $column['Null'] == '' || $column['Null'] === 'NO'
                ? 'No'
                : 'Yes';
            $data[] = $column['Default'] ?? '';

            $field_name = $column['Field'];

            if ($do_relation && $have_rel) {
                $data[] = isset($res_rel[$field_name])
                    ? $res_rel[$field_name]['foreign_table']
                    . ' (' . $res_rel[$field_name]['foreign_field']
                    . ')'
                    : '';
            }
            if ($do_comments) {
                $data[] = $comments[$field_name] ?? '';
            }
            if ($do_mime) {
                $data[] = isset($mime_map[$field_name])
                    ? $mime_map[$field_name]['mimetype']
                    : '';
            }

            $this->page = $currpage;
            // write the horizontal borders
            $this->Line($l, $h, $fullwidth + $l, $h);
            // write the content and remember the height of the highest col
            foreach ($data as $col => $txt) {
                $this->page = $currpage;
                $this->SetXY($l, $h);
                if ($this->tablewidths[$col] > 0) {
                    $this->MultiCell(
                        $this->tablewidths[$col],
                        $this->FontSizePt,
                        $txt,
                        0,
                        $this->colAlign[$col]
                    );
                    $l += $this->tablewidths[$col];
                }

                if (! isset($tmpheight[$row . '-' . $this->page])) {
                    $tmpheight[$row . '-' . $this->page] = 0;
                }
                if ($tmpheight[$row . '-' . $this->page] < $this->GetY()) {
                    $tmpheight[$row . '-' . $this->page] = $this->GetY();
                }
                if ($this->page <= $maxpage) {
                    continue;
                }

                $maxpage = $this->page;
            }

            // get the height we were in the last used page
            $h = $tmpheight[$row . '-' . $maxpage];
            // set the "pointer" to the left margin
            $l = $this->lMargin;
            // set the $currpage to the last page
            $currpage = $maxpage;
            unset($data);
            $row++;
        }
        // draw the borders
        // we start adding a horizontal line on the last page
        $this->page = $maxpage;
        $this->Line($l, $h, $fullwidth + $l, $h);
        // now we start at the top of the document and walk down
        for ($i = $startpage; $i <= $maxpage; $i++) {
            $this->page = $i;
            $l = $this->lMargin;
            $t = $i == $startpage ? $startheight : $this->tMargin;
            $lh = $i == $maxpage ? $h : $this->h - $this->bMargin;
            $this->Line($l, $t, $l, $lh);
            foreach ($this->tablewidths as $width) {
                $l += $width;
                $this->Line($l, $t, $l, $lh);
            }
        }
        // set it to the last page, if not it'll cause some problems
        $this->page = $maxpage;
    }

    /**
     * MySQL report
     *
     * @param string $query Query to execute
     *
     * @return void
     */
    public function mysqlReport($query)
    {
        global $dbi;

        unset(
            $this->tablewidths,
            $this->colTitles,
            $this->titleWidth,
            $this->colFits,
            $this->displayColumn,
            $this->colAlign
        );

        /**
         * Pass 1 for column widths
         */
        $this->results = $dbi->query(
            $query,
            DatabaseInterface::CONNECT_USER,
            DatabaseInterface::QUERY_UNBUFFERED
        );
        $this->numFields = $dbi->numFields($this->results);
        $this->fields = $dbi->getFieldsMeta($this->results);

        // sColWidth = starting col width (an average size width)
        $availableWidth = $this->w - $this->lMargin - $this->rMargin;
        $this->sColWidth = $availableWidth / $this->numFields;
        $totalTitleWidth = 0;

        // loop through results header and set initial
        // col widths/ titles/ alignment
        // if a col title is less than the starting col width,
        // reduce that column size
        $colFits = [];
        $titleWidth = [];
        for ($i = 0; $i < $this->numFields; $i++) {
            $col_as = $this->fields[$i]->name;
            $db = $this->currentDb;
            $table = $this->currentTable;
            if (! empty($this->aliases[$db]['tables'][$table]['columns'][$col_as])) {
                $col_as = $this->aliases[$db]['tables'][$table]['columns'][$col_as];
            }
            $stringWidth = $this->GetStringWidth($col_as) + 6;
            // save the real title's width
            $titleWidth[$i] = $stringWidth;
            $totalTitleWidth += $stringWidth;

            // set any column titles less than the start width to
            // the column title width
            if ($stringWidth < $this->sColWidth) {
                $colFits[$i] = $stringWidth;
            }
            $this->colTitles[$i] = $col_as;
            $this->displayColumn[$i] = true;

            switch ($this->fields[$i]->type) {
                case 'int':
                    $this->colAlign[$i] = 'R';
                    break;
                case 'blob':
                case 'tinyblob':
                case 'mediumblob':
                case 'longblob':
                    /**
                 * @todo do not deactivate completely the display
                 * but show the field's name and [BLOB]
                 */
                    if (stripos($this->fields[$i]->flags, 'BINARY') !== false) {
                        $this->displayColumn[$i] = false;
                        unset($this->colTitles[$i]);
                    }
                    $this->colAlign[$i] = 'L';
                    break;
                default:
                    $this->colAlign[$i] = 'L';
            }
        }

        // title width verification
        if ($totalTitleWidth > $availableWidth) {
            $adjustingMode = true;
        } else {
            $adjustingMode = false;
            // we have enough space for all the titles at their
            // original width so use the true title's width
            foreach ($titleWidth as $key => $val) {
                $colFits[$key] = $val;
            }
        }

        // loop through the data; any column whose contents
        // is greater than the column size is resized
        /**
         * @todo force here a LIMIT to avoid reading all rows
         */
        while ($row = $dbi->fetchRow($this->results)) {
            foreach ($colFits as $key => $val) {
                $stringWidth = $this->GetStringWidth($row[$key]) + 6;
                if ($adjustingMode && ($stringWidth > $this->sColWidth)) {
                    // any column whose data's width is bigger than
                    // the start width is now discarded
                    unset($colFits[$key]);
                } else {
                    // if data's width is bigger than the current column width,
                    // enlarge the column (but avoid enlarging it if the
                    // data's width is very big)
                    if ($stringWidth > $val
                        && $stringWidth < $this->sColWidth * 3
                    ) {
                        $colFits[$key] = $stringWidth;
                    }
                }
            }
        }

        $totAlreadyFitted = 0;
        foreach ($colFits as $key => $val) {
            // set fitted columns to smallest size
            $this->tablewidths[$key] = $val;
            // to work out how much (if any) space has been freed up
            $totAlreadyFitted += $val;
        }

        if ($adjustingMode) {
            $surplus = (count($colFits) * $this->sColWidth) - $totAlreadyFitted;
            $surplusToAdd = $surplus / ($this->numFields - count($colFits));
        } else {
            $surplusToAdd = 0;
        }

        for ($i = 0; $i < $this->numFields; $i++) {
            if (! array_key_exists($i, $colFits)) {
                $this->tablewidths[$i] = $this->sColWidth + $surplusToAdd;
            }
            if ($this->displayColumn[$i] != false) {
                continue;
            }

            $this->tablewidths[$i] = 0;
        }

        ksort($this->tablewidths);

        $dbi->freeResult($this->results);

        // Pass 2

        $this->results = $dbi->query(
            $query,
            DatabaseInterface::CONNECT_USER,
            DatabaseInterface::QUERY_UNBUFFERED
        );
        $this->SetY($this->tMargin);
        $this->AddPage();
        $this->SetFont(PdfLib::PMA_PDF_FONT, '', 9);
        $this->morepagestable($this->FontSizePt);
        $dbi->freeResult($this->results);
    }
}
