<?php

namespace Codacy\Coverage\Report;

/**
 * Class FileReport
 * Holds the file report total result, the filename and a list (associative array)
 * mapping line numbers to hits [lineNr => hits]
 * @author Jakob Pupke <jakob.pupke@gmail.com>
 */
class FileReport
{
    /**
     * @var integer
     */
    private $_total;

    /**
     * @var string
     */
    private $_fileName;

    /**
     * @var array (line -> hits) of type [string -> int]
     */
    private $_lineCoverage;

    /**
     * @param $total string
     * @param $fileName string
     * @param $lineCoverage array
     */
    public function __construct($total, $fileName, $lineCoverage)
    {
        $this->_total  = $total;
        $this->_fileName = $fileName;
        $this->_lineCoverage = $lineCoverage;
    }

    /**
     * @return integer
     */
    public function getTotal()
    {
        return $this->_total;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->_fileName;
    }

    /**
     * @return array
     */
    public function getLineCoverage()
    {
        return $this->_lineCoverage;
    }
}