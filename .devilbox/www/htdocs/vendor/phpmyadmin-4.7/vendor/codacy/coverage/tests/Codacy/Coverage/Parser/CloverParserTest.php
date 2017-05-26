<?php

use Codacy\Coverage\Parser\CloverParser;

class CloverParserTest extends PHPUnit_Framework_TestCase
{

    public function testThrowsExceptionOnWrongPath()
    {
        $this->setExpectedException('InvalidArgumentException');
        new CloverParser("/home/foo/bar/baz/m.xml");
    }

    /**
     * Testing against the clover coverage report 'tests/res/clover/clover.xml'
     */
    public function testCanParseCloverXmlWithoutProject()
    {
        $this->_canParseClover('tests/res/clover/clover.xml', "/home/jacke/Desktop/codacy-php");
    }

    /**
     * Testing against the clover coverage report 'tests/res/clover/clover.xml'
     * The test had been made in /home/jacke/Desktop/codacy-php so we need to pass this
     * as 2nd (optional) parameter. Otherwise the filename will not be correct and test
     * would fail on other machines or in other directories.
     */
    public function testCanParseCloverXmlWithProject()
    {
        $this->_canParseClover('tests/res/clover/clover_without_packages.xml', "/home/jacke/Desktop/codacy-php");
    }

    private function _canParseClover($path, $rootDir)
    {
        $parser = new CloverParser($path, $rootDir);
        $report = $parser->makeReport();
        $this->assertEquals(38, $report->getTotal());
        $this->assertEquals(5, sizeof($report->getFileReports()));

        $parserFileReports = $report->getFileReports();

        $parserFileReport = $parserFileReports[0];
        $coverageReportFileReport = $parserFileReports[1];

        $this->assertEquals(33, $parserFileReport->getTotal());
        $this->assertEquals(33, $coverageReportFileReport->getTotal());

        $parserFileName = $parserFileReport->getFileName();

        $reportFileName = $coverageReportFileReport->getFileName();

        $fileReports = $report->getFileReports();
        $fileReport = $fileReports[1];

        $expLineCoverage = (object)array(11 => 1, 12 => 1, 13 => 1, 16 => 1);
        $this->assertEquals($fileReport->getLineCoverage(), $expLineCoverage);

        $this->assertEquals("src/Codacy/Coverage/Parser/Parser.php", $parserFileName);
        $this->assertEquals("src/Codacy/Coverage/Report/CoverageReport.php", $reportFileName);
    }
}
