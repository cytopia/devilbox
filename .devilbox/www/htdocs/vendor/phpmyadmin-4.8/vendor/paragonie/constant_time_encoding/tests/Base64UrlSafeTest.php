<?php
use \ParagonIE\ConstantTime\Base64UrlSafe;

class Base64UrlSafeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Base64UrlSafe::encode()
     * @covers Base64UrlSafe::decode()
     */
    public function testRandom()
    {
        for ($i = 1; $i < 32; ++$i) {
            for ($j = 0; $j < 50; ++$j) {
                $random = \random_bytes($i);

                $enc = Base64UrlSafe::encode($random);
                $this->assertSame(
                    $random,
                    Base64UrlSafe::decode($enc)
                );
                $this->assertSame(
                    \strtr(\base64_encode($random), '+/', '-_'),
                    $enc
                );

            }
        }
    }
}
