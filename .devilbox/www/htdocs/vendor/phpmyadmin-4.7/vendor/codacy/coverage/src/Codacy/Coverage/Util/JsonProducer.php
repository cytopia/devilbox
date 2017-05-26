<?php

namespace Codacy\Coverage\Util;

use Codacy\Coverage\Parser\IParser;

/**
 * Class JsonProducer
 * Is composed of a parser that implements the IParser interface.
 * @author Jakob Pupke <jakob.pupke@gmail.com>
 */
class JsonProducer
{
    /**
     * @var Parser that implements IParser interface
     */
    private $_parser;

    /**
     * Sets the JsonParser's member field
     * @param $parser IParser Any parser class that implements the IParser interface
     */
    public function setParser(IParser $parser)
    {
        $this->_parser = $parser;
    }

    /**
     * Delegates the job to the parser's makeReport() method
     * @return CoverageReport The CoverageReport object
     */
    public function makeReport()
    {
        return $this->_parser->makeReport();
    }

    /**
     * Takes a CoverageReport object, the result of makeReport(), and outputs JSON.
     * Example JSON format:
     * {
     *  "total": 67,
     *  "fileReports": [
     *       {
     *          "filename": "src/Codacy/Coverage/Api/Api.php",
     *          "total": 3,
     *          "coverage": {
     *              "12": 3,
     *              "13": 5,
     *              .........
     *              .........
     *          }
     *      },
     *      .........
     *      .......
     *  ]
     * }
     *
     * @return string the JSON string
     */
    public function makeJson()
    {
        $report = $this->makeReport();
        $array = array();
        $array['total'] = $report->getTotal();

        $fileReportsArray = array();
        $fileReports = $report->getFileReports();

        foreach ($fileReports as $fr) {
            $fileArray = array();
            $fileArray['filename'] = $fr->getFileName();
            $fileArray['total'] = $fr->getTotal();
            $fileArray['coverage'] = $fr->getLineCoverage();

            array_push($fileReportsArray, $fileArray);
        }

        $array['fileReports'] = $fileReportsArray;

        if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
            return json_encode($array, JSON_UNESCAPED_SLASHES);
        } else {
            return str_replace('\/', '/', json_encode($array));
        }

    }
}
