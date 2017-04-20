<?php

/**
 * This file is part of Gitonomy.
 *
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 * (c) Julien DIDIER <genzo.wm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Gitonomy\Git\Tests;

use Gitonomy\Git\Blob;
use Gitonomy\Git\Repository;

class RepositoryTest extends AbstractTest
{
    /**
     * @dataProvider provideFoobar
     */
    public function testGetBlob_WithExisting_Works($repository)
    {
        $blob = $repository->getCommit(self::LONGFILE_COMMIT)->getTree()->resolvePath('README.md');

        $this->assertTrue($blob instanceof Blob, 'getBlob() returns a Blob object');
        $this->assertContains('Foo Bar project', $blob->getContent(), 'file is correct');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetSize($repository)
    {
        $size = $repository->getSize();
        $this->assertGreaterThan(70, $size, 'Repository is greater than 70KB');
    }

    public function testIsBare()
    {
        $bare = self::createFoobarRepository(true);
        $this->assertTrue($bare->isBare(), 'Lib repository is bare');

        $notBare = self::createFoobarRepository(false);
        $this->assertFalse($notBare->isBare(), 'Working copy is not bare');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetDescription($repository)
    {
        $this->assertSame("Unnamed repository; edit this file 'description' to name the repository.\n", $repository->getDescription());
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testLoggerOk($repository)
    {
        if (!interface_exists('Psr\Log\LoggerInterface')) {
            $this->markTestSkipped();
        }

        $logger = $this->getMock('Psr\Log\LoggerInterface');
        $logger
            ->expects($this->once())
            ->method('info')
        ;
        $logger
            ->expects($this->exactly(3)) // duration, return code and output
            ->method('debug')
        ;

        $repository->setLogger($logger);

        $repository->run('remote');
    }

    /**
     * @dataProvider provideFoobar
     * @expectedException RuntimeException
     */
    public function testLoggerNOk($repository)
    {
        if (!interface_exists('Psr\Log\LoggerInterface')) {
            $this->markTestSkipped();
        }

        $logger = $this->getMock('Psr\Log\LoggerInterface');
        $logger
            ->expects($this->once())
            ->method('info')
        ;
        $logger
            ->expects($this->exactly(3)) // duration, return code and output
            ->method('debug')
        ;
        $logger
            ->expects($this->once())
            ->method('error')
        ;

        $repository->setLogger($logger);

        $repository->run('not-work');
    }
}
