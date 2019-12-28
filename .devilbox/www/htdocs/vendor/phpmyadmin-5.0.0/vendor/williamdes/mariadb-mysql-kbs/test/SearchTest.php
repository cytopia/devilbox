<?php
declare(strict_types = 1);
namespace Williamdes\MariaDBMySQLKBS\Test;

use \PHPUnit\Framework\TestCase;
use \Williamdes\MariaDBMySQLKBS\SlimData;
use \Williamdes\MariaDBMySQLKBS\Search;
use \Williamdes\MariaDBMySQLKBS\KBException;

class SearchTest extends TestCase
{

    /**
     * Load slim data
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        $sd = new SlimData();
        $sd->addVariable("variable-1", "boolean", true);
        $sd->addVariable("variable-2", null, null);
        $sd->addVariable("variable-3", null, true);
        $variable4 = $sd->addVariable("variable-4", null, false);
        $variable4->addDocumentation("https://mariadb.com/testurl/for/variable/4", "myanchor");
        $variable4->addDocumentation("https://dev.mysql.com/testurl_for-variable/4", "my_anchor");
        Search::loadTestData($sd);
    }

    /**
     * test get by name
     *
     * @return void
     */
    public function testGetByName(): void
    {
        $found = Search::getByName("variable-4");
        $this->assertEquals("https://mariadb.com/testurl/for/variable/4#myanchor", $found);
    }

    /**
     * test get by name for MySQL
     *
     * @return void
     */
    public function testGetByNameMYSQL(): void
    {
        $found = Search::getByName("variable-4", Search::MYSQL);
        $this->assertEquals("https://dev.mysql.com/testurl_for-variable/4#my_anchor", $found);
    }

    /**
     * test get by name for MARIADB
     *
     * @return void
     */
    public function testGetByNameMARIADB(): void
    {
        $found = Search::getByName("variable-4", Search::MARIADB);
        $this->assertEquals("https://mariadb.com/testurl/for/variable/4#myanchor", $found);
    }

    /**
     * test get by name
     *
     *
     * @return void
     */
    public function testException(): void
    {
        $this->expectException(KBException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessageRegExp('/(.+) does not exist for this type of documentation !/');
        Search::getByName("variable-3", Search::MARIADB);
    }

    /**
     * test get by name not found variable
     *
     *
     * @return void
     */
    public function testExceptionNoFoundGetVariableType(): void
    {
        $this->expectException(KBException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessageRegExp('/(.+) does not exist !/');
        Search::getVariableType("acbdefghi0202");
    }

    /**
     * test get by name not found variable
     *
     *
     * @return void
     */
    public function testExceptionNoFound(): void
    {
        $this->expectException(KBException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessageRegExp('/(.+) does not exist !/');
        Search::getByName("acbdefghi0202", Search::MARIADB);
    }

    /**
     * test get by name not found variable
     *
     *
     * @return void
     */
    public function testExceptionNoFoundGetVariable(): void
    {
        $this->expectException(KBException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessageRegExp('/(.+) does not exist !/');
        Search::getVariable("acbdefghi0202");
    }

    /**
     * test load data fail
     *
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testExceptionLoadData(): void
    {
        $this->expectException(KBException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessageRegExp('/(.+) does not exist !/');
        Search::$DATA_DIR = ".";
        Search::$loaded   = false;
        Search::loadData();
    }

    /**
     * test get variables with dynamic status
     *
     * @return void
     */
    public function testGetVariablesWithDynamic(): void
    {
        $dynamic = Search::getVariablesWithDynamic(true);
        $this->assertEquals($dynamic, Search::getDynamicVariables());
        $static = Search::getVariablesWithDynamic(false);
        $this->assertEquals($static, Search::getStaticVariables());
        $this->assertEquals(2, count($dynamic));
        $this->assertEquals(1, count($static));
        $common = \array_intersect($dynamic, $static);
        $this->assertEquals(0, count($common));// Impossible to be dynamic and not
    }

    /**
     * test Exception get variable type has no type
     *
     *
     * @return void
     */
    public function testExceptionGetVariableType(): void
    {
        $this->expectException(KBException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessageRegExp('/(.+) does have a known type !/');
        Search::getVariableType("variable-2");
    }

    /**
     * test get variable type
     *
     * @return void
     */
    public function testGetVariableType(): void
    {
        $type = Search::getVariableType("variable-1");
        $this->assertEquals("boolean", $type);
    }

}
