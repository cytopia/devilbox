<?php
declare(strict_types = 1);
namespace Williamdes\MariaDBMySQLKBS\Test;

use \PHPUnit\Framework\TestCase;
use \Swaggest\JsonSchema\Schema;
use \Swaggest\JsonSchema\Context;
use \stdClass;

class DataTest extends TestCase
{

    /**
     * Validate json data
     *
     * @param stdClass $contents The file contents
     * @param string   $id       The schema id
     * @example validate($slimData, "urn:williamdes:mariadb-mysql-kbs:slimdata");
     * @return bool
     */
    public static function validate(stdClass $contents, string $id): bool
    {
        $options = new Context();
        $options->setRemoteRefProvider(new RefProvider());
        $schema = Schema::import($id, $options);
        $schema->in($contents);
        return true;// No exception occured
    }

    /**
     * test files
     *
     * @return void
     */
    public function testFileSample(): void
    {
        $slimDataTestData = json_decode((string) file_get_contents(__DIR__."/data/ultraSlimDataTestWithVariables.json"));
        $this->assertTrue(self::validate($slimDataTestData, "urn:williamdes:mariadb-mysql-kbs:ultraslimdata"));
    }

    /**
     * test slim data
     *
     * @return void
     */
    public function testFileSlim(): void
    {
        $slimData = json_decode((string) file_get_contents(__DIR__."/../dist/merged-slim.json"));
        $this->assertTrue(self::validate($slimData, "urn:williamdes:mariadb-mysql-kbs:slimdata"));
    }

    /**
     * test ultra slim data
     *
     * @return void
     */
    public function testFileUltraSlim(): void
    {
        $slimData = json_decode((string) file_get_contents(__DIR__."/../dist/merged-ultraslim.json"));
        $this->assertTrue(self::validate($slimData, "urn:williamdes:mariadb-mysql-kbs:ultraslimdata"));
    }

    /**
     * test ultra slim data
     *
     * @return void
     */
    public function testFileRaw(): void
    {
        $slimData = json_decode((string) file_get_contents(__DIR__."/../dist/merged-raw.json"));
        $this->assertTrue(self::validate($slimData, "urn:williamdes:mariadb-mysql-kbs:rawdata"));
    }

}
