<?php

namespace Codacy\Coverage\Parser;

use Codacy\Coverage\Report\CoverageReport;
use Codacy\Coverage\Report\FileReport;

/**
 * Parses Clover XML file and produces a CoverageReport object.
 * Inherits constructor from abstract class Parser and implements
 * the IParser interface.
 * @author Jakob Pupke <jakob.pupke@gmail.com>
 */
class CloverParser extends XMLParser implements IParser
{

    /**
     * Extracts basic information about coverage report and delegates
     * more detailed extraction work to _makeFileReports() method.
     * @return CoverageReport $report The CoverageReport object
     */
    public function makeReport()
    {
        $project = $this->element->project;
        $projectMetrics = $project->metrics;
        $coveredStatements = intval($projectMetrics['coveredstatements']);
        $statementsTotal = intval($projectMetrics['statements']);
        $reportTotal = round($this->safeDivision($coveredStatements, $statementsTotal) * 100);
        $fileReports = $this->makeFileReports($project);
        $report = new CoverageReport($reportTotal, $fileReports);
        return $report;
    }

    /**
     * Takes the root \SimpleXMLElement object of the parsed file
     * and decides on how to iterate it to extract information of all
     * <file...>..</file> nodes.
     * @param \SimpleXMLElement $node the root XML node.
     * @return array holding FileReport objects
     */
    private function makeFileReports(\SimpleXMLElement $node)
    {
        $fileReports = array();
        /*
        * Most clover reports will have project/package/file/line xPath.
        * But there could be files that are not part of any package, i.e files that 
        * that do not declare namespace.
        */
        if ($node->file->count() > 0) {
            // so there is a file without package
            $fileReports = $this->makeFileReportsFromFiles($node->file, $fileReports);
        }
        if ($node->package->count() > 0) {
            $fileReports = $this->makeFileReportsFromPackages($node->package, $fileReports);
        }
        return $fileReports;
    }

    /**
     * Iterates all over all <file...>..</file> nodes.
     * @param \SimpleXMLElement $node The XML node holding the file nodes.
     * @param array $fileReports array of FileReport objects
     * @return array holding FileReport objects
     */
    private function makeFileReportsFromFiles(\SimpleXMLElement $node, $fileReports)
    {
        foreach ($node as $file) {
            // iterate files in the package
            $countStatement = intval($file->metrics['statements']);
            $countCoveredStatements = intval($file->metrics['coveredstatements']);
            if ($countStatement == 0) {
                $fileTotal = 0;
            } else {
                $fileTotal = round($this->safeDivision($countCoveredStatements, $countStatement) * 100);
            }
            $fileName = $this->getRelativePath($file['name']);
            $lineCoverage = $this->getLineCoverage($file);
            $fileReport = new FileReport($fileTotal, $fileName, $lineCoverage);
            array_push($fileReports, $fileReport);
        }
        return $fileReports;
    }

    /**
     * Iterates over all <package..>...</package> nodes and calls _makeFileReportsFromFiles on them
     * @param \SimpleXMLElement $node The XML node holding all <package..>...</package> nodes
     * @param array $fileReports array of FileReport objects
     * @return array holding FileReport objects
     */
    private function makeFileReportsFromPackages(\SimpleXMLElement $node, $fileReports)
    {
        // iterate all packages
        foreach ($node as $package) {
            $fileReports = $this->makeFileReportsFromFiles($package->file, $fileReports);
        }
        return $fileReports;
    }

    /**
     * Iterates all <line></line> nodes and produces an array holding line coverage information.
     * Only adds lines of type "stmt" and with count greater than 0.
     * @param \SimpleXMLElement $node The XML node holding the <line></line> nodes
     * @return array: (lineNumber -> hits)
     */
    private function getLineCoverage(\SimpleXMLElement $node)
    {
        $lineCoverage = (object)array();
        foreach ($node as $line) {
            $count = intval($line['count']);
            // iterate all lines in that file
            if ($line['type'] == 'stmt' && $count > 0) {
                $lineNr = (string)$line['num'];
                $hit = $count;
                $lineCoverage->$lineNr = $hit;
            }
        }
        return $lineCoverage;
    }

    /**
     * Cuts the file name so we have relative path to projectRoot.
     * In a clover file file names are saved from / on.
     * We are only interested in relative filename
     * @param \SimpleXMLElement $fileName The filename attribute
     * @return string The relative path of that file
     */
    private function getRelativePath(\SimpleXMLElement $fileName)
    {
        $prefix = $this->rootDir . DIRECTORY_SEPARATOR;
        $str = (string)$fileName;

        if (substr($str, 0, strlen($prefix)) == $prefix) {
            $str = substr($str, strlen($prefix));
        }

        return $str;
    }

    private function safeDivision($a, $b)
    {
        if ($b === 0) {
            return 0;
        }
        return $a / $b;
    }
}
