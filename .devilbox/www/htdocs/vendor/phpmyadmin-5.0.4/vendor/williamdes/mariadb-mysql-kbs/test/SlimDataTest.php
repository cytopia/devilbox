<?php
declare(strict_types = 1);
namespace Williamdes\MariaDBMySQLKBS\Test;

use \PHPUnit\Framework\TestCase;
use \Williamdes\MariaDBMySQLKBS\SlimData;

class SlimDataTest extends TestCase
{

    /**
     * Create an instance of SlimData
     *
     * @return SlimData
     */
    public function testCreateInstance(): SlimData
    {
        $slimData = new SlimData();
        $this->assertInstanceOf(SlimData::class, $slimData);
        return $slimData;
    }

    /**
     * Test json_encode empty object
     *
     * @param SlimData $slimData SlimData instance
     * @depends testCreateInstance
     * @return void
     */
    public function testToJsonEmpty(SlimData $slimData): void
    {
        $this->assertEquals('{"version":1}', json_encode($slimData));
    }

    /**
     * Test json_encode with variables
     *
     * @param SlimData $slimData SlimData instance
     * @depends testCreateInstance
     * @return void
     */
    public function testToJsonWithVariables(SlimData $slimData): void
    {
        $slimData->addVariable("Test_var", "boolean", true);
        $slimData->addVariable("another-variable", "string", false);
        $kbe = $slimData->addVariable("doc-variable_ok", "integer", true);
        $kbe->addDocumentation("https://example.org/williamdes/mariadb-mysql-kbs", "a_doc-variable_ok");
        $kbe->addDocumentation("https://example.org/williamdes/mariadb-mysql-kbs", "a_href_ok");
        $kbe->addDocumentation("https://example.org/williamdes/mariadb-mysql-kbs/_doc-variable_ok");
        $kbe->addDocumentation("https://dev.mysql.com/");
        $kbe->addDocumentation("https://mariadb.com/");
        $kbe->addDocumentation("https://mariadb.com/", "anchorname");
        $this->assertEquals(
            file_get_contents(__DIR__."/data/ultraSlimDataTestWithVariables.json"),
            json_encode($slimData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
        );
    }

}
