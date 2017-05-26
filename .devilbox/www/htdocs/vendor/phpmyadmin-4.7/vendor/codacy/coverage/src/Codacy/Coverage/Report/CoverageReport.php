<?php

namespace Codacy\Coverage\Report;

/**
 * Class CoverageReport
 * Holds the coverage report total result and a list (array)
 * of FileReports
 * @author Jakob Pupke <jakob.pupke@gmail.com>
 */
class CoverageReport
{
    /**
     * @var integer
     */
    private $_total;

    /**
     * @var array (of type FileReport)
     */
    private $_fileReports;

    /**
     * @param $total string
     * @param $fileReports array (of type FileReport)
     */
    public function __construct($total, $fileReports)
    {
        $this->_total = $total;
        $this->_fileReports = $fileReports;
    }

    /**
     * @return integer
     */
    public function getTotal() 
    {
        return $this->_total;
    }

    /**
     * @return array (of type FileReport)
     */
    public function getFileReports() 
    {
        return $this->_fileReports;
    }
}