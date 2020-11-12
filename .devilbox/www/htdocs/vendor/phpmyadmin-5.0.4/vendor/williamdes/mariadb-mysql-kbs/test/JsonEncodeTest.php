<?php
declare(strict_types = 1);
namespace Williamdes\MariaDBMySQLKBS\Test;

use \PHPUnit\Framework\TestCase;
use Williamdes\MariaDBMySQLKBS\KBDocumentation;
use \Williamdes\MariaDBMySQLKBS\KBEntry;

class JsonEncodeTest extends TestCase
{

    /**
     * test json encode a KBEntry class
     *
     * @return void
     */
    public function testEncodeKBEntry(): void
    {
        $entry = new KBEntry('a', null, null);
        $this->assertEquals('{"name":"a"}', json_encode($entry));
        $entry = new KBEntry('a', 'b', true);
        $this->assertEquals('{"name":"a","type":"b","dynamic":true}', json_encode($entry));
        $entry = new KBEntry('a', 'b', true);
        $entry->addDocumentation('d');
        $entry->addDocumentation('e', '#f');
        $this->assertEquals('{"name":"a","type":"b","dynamic":true,"docs":[{"url":"d"},{"url":"e","anchor":"#f"}]}', json_encode($entry));
    }

    /**
     * test json encode a KBDocumentation class
     *
     * @return void
     */
    public function testEncodeKBDocumentation(): void
    {
        $entry = new KBDocumentation('a', null);
        $this->assertEquals('{"url":"a"}', json_encode($entry));
        $entry = new KBDocumentation('a', 'b');
        $this->assertEquals('{"url":"a","anchor":"b"}', json_encode($entry));
    }
}
