<?php
declare(strict_types = 1);
namespace Williamdes\MariaDBMySQLKBS\Test;

use \PHPUnit\Framework\TestCase;
use \Swaggest\JsonSchema\Schema;
use \stdClass;

class DataTest extends TestCase
{

    /**
     * Validate json data
     *
     * @param stdClass $contents The file contents
     * @param string   $id       The schema path
     * @example validate($slimData, __DIR__ . '/../schemas/merged-slim.json');
     * @return bool
     */
    public static function validate(stdClass $contents, string $id): bool
    {
        $schema = Schema::import($id);
        $schema->in($contents);
        return true;// No exception occured
    }

    /**
     * test that the vendor class can be found
     * if not found the test is marked as skipped
     * That will allow packagers to run tests without the vendor (Debian :wink:)
     *
     * @return void
     */
    public function testVendorFound(): void
    {
        if (class_exists(Schema::class)) {
            // tests that depend on the vendor can be run
            $this->assertTrue(true);
        } else {
            $this->markTestSkipped('vnf');
        }
    }

    /**
     * test files
     * @depends testVendorFound
     *
     * @return void
     */
    public function testFileSample(): void
    {
        $slimDataTestData = json_decode((string) file_get_contents(__DIR__ . "/data/ultraSlimDataTestWithVariables.json"));
        $this->assertTrue(self::validate($slimDataTestData, __DIR__ . '/../schemas/merged-ultraslim.json'));
    }

    /**
     * test slim data
     * @depends testVendorFound
     *
     * @return void
     */
    public function testFileSlim(): void
    {
        $slimData = json_decode((string) file_get_contents(__DIR__ . "/../dist/merged-slim.json"));
        $this->assertTrue(self::validate($slimData, __DIR__ . '/../schemas/merged-slim.json'));
    }

    /**
     * test ultra slim data
     * @depends testVendorFound
     *
     * @return void
     */
    public function testFileUltraSlim(): void
    {
        $slimData = json_decode((string) file_get_contents(__DIR__ . "/../dist/merged-ultraslim.json"));
        $this->assertTrue(self::validate($slimData, __DIR__ . '/../schemas/merged-ultraslim.json'));
    }

    /**
     * test ultra slim data
     * @depends testVendorFound
     *
     * @return void
     */
    public function testFileRaw(): void
    {
        $slimData = json_decode((string) file_get_contents(__DIR__ . "/../dist/merged-raw.json"));
        $this->assertTrue(self::validate($slimData, __DIR__ . '/../schemas/merged-raw.json'));
    }

}
