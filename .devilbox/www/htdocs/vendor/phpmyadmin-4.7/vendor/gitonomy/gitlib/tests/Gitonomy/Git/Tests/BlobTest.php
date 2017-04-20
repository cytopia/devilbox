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

class BlobTest extends AbstractTest
{
    const README_FRAGMENT = 'Foo Bar project';

    public function getReadmeBlob($repository)
    {
        return $repository->getCommit(self::LONGFILE_COMMIT)->getTree()->resolvePath('README.md');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetContent($repository)
    {
        $blob = $this->getReadmeBlob($repository);

        $this->assertContains(self::README_FRAGMENT, $blob->getContent());
    }

    /**
     * @dataProvider provideFoobar
     * @expectedException RuntimeException
     */
    public function testNotExisting($repository)
    {
        $blob = $repository->getBlob('foobar');
        $blob->getContent();
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetMimetype($repository)
    {
        $blob = $this->getReadmeBlob($repository);
        $this->assertRegexp('#text/plain#', $blob->getMimetype());
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testIsText($repository)
    {
        $blob = $this->getReadmeBlob($repository);
        $this->assertTrue($blob->isText());
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testIsBinary($repository)
    {
        $blob = $this->getReadmeBlob($repository);
        $this->assertFalse($blob->isBinary());
    }
}
