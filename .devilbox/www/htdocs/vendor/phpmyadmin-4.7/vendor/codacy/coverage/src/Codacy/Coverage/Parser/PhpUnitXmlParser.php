<?php

namespace Codacy\Coverage\Parser;

use Codacy\Coverage\Report\CoverageReport;
use Codacy\Coverage\Report\FileReport;

/**
 * Parses XML file, result of phpunit --coverage-xml, and produces
 * a CoverageReport object. The challenging problem here is that
 * the report is scattered over different files. Basic information
 * can be parsed from the index.xml file. But the relevant information
 * for each file is stored in individual files.
 * @author Jakob Pupke <jakob.pupke@gmail.com>
 */
class PhpUnitXmlParser extends XMLParser implements IParser
{

    protected $dirOfFileXmls;
    /**
     * Extracts basic information about coverage report
     * from the root xml file (index.xml).
     * For line coverage information about the files it has
     * to parse each individual file. This is handled by
     * _getLineCoverage() private method.
     * @return CoverageReport $report The CoverageReport object
     */

    /**
     * @param $dir string The path to where the single file xmls reside
     */
    public function setDirOfFileXmls($dir)
    {
        $this->dirOfFileXmls = $dir;
    }

    /**
     * @return string The path to where the single file xmls reside
     */
    public function getDirOfFileXmls()
    {
        return $this->dirOfFileXmls;
    }

    public function makeReport()
    {
        //we can get the report total from the first directory summary.
        $reportTotal = $this->getTotalFromPercent($this->element->project->directory->totals->lines["percent"]);

        $fileReports = array();
        foreach ($this->element->project->directory->file as $file) {
            $fileName = $this->getRelativePath($file["href"]);
            $fileTotal = $this->getTotalFromPercent($file->totals->lines["percent"]);

            $xmlFileHref = (string)$file["href"];
            $base = $this->getDirOfFileXmls();
            // get the corresponding xml file to get lineCoverage information.
            if (file_exists($base . DIRECTORY_SEPARATOR . $xmlFileHref)) {
                $fileXml = simplexml_load_file($base . DIRECTORY_SEPARATOR . $xmlFileHref);
            } else {
                throw new \InvalidArgumentException(
                    "Error: Cannot read XML file. Using: " . $base . DIRECTORY_SEPARATOR . $xmlFileHref . "\n\r"
                );
            }

            $lineCoverage = $this->getLineCoverage($fileXml);
            $fileReport = new FileReport($fileTotal, $fileName, $lineCoverage);
            array_push($fileReports, $fileReport);
        }
        $report = new CoverageReport($reportTotal, $fileReports);
        return $report;
    }

    /**
     * Iterates all <line></line> nodes and produces an array holding line coverage information.
     * @param \SimpleXMLElement $node The XML node holding the <line></line> nodes
     * @return array: (lineNumber -> hits)
     */
    private function getLineCoverage(\SimpleXMLElement $node)
    {
        $lineCoverage = (object)array();
        if ($node->file->coverage) {
            foreach ($node->file->coverage->line as $line) {
                $count = $line->covered->count();
                if ($count > 0) {
                    $nr = (string)$line["nr"];
                    $lineCoverage->$nr = $count;
                }
            }
        }
        // else there is no line coverage, return empty array then.
        return $lineCoverage;
    }

    /**
     * Gets Integer from percent. Example: 95.00% -> 95
     * @param \SimpleXMLElement $percent The percent attribute of the node
     * @return integer The according integer value
     */
    private function getTotalFromPercent(\SimpleXMLElement $percent)
    {
        $percent = (string)$percent;
        $percent = substr($percent, 0, -1);
        return round($percent);
    }

    /**
     * The PhpUnit XML Coverage format does not save the full path of the filename
     * We can get the filename by combining the path of the first directory with
     * the href attribute of each file.
     * @param \SimpleXMLElement $fileName The href attribute of the <file></file> node.
     * @return string The relative path of the file, that is, relative to project root.
     */
    private function getRelativePath(\SimpleXMLElement $fileName)
    {
        $dirOfSrcFiles = $this->element->project->directory["name"];
        $projectRoot = $this->rootDir;
        // Need to cut off everything lower than projectRoot
        $dirFromProjectRoot = substr($dirOfSrcFiles, strlen($projectRoot) + 1);
        // remove .xml and convert to string
        $relativeFilePath = substr((string)$fileName, 0, -4);
        return join(DIRECTORY_SEPARATOR, array($dirFromProjectRoot, $relativeFilePath));
    }
}
