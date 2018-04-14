<?php
use \ParagonIE\ConstantTime\Base32;

class Base32Test extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Base32::encode()
     * @covers Base32::decode()
     * @covers Base32::encodeUpper()
     * @covers Base32::decodeUpper()
     */
    public function testRandom()
    {
        for ($i = 1; $i < 32; ++$i) {
            for ($j = 0; $j < 50; ++$j) {
                $random = \random_bytes($i);

                $enc = Base32::encode($random);
                $this->assertSame(
                    $random,
                    Base32::decode($enc)
                );

                $enc = Base32::encodeUpper($random);
                $this->assertSame(
                    $random,
                    Base32::decodeUpper($enc)
                );
            }
        }
    }
}
