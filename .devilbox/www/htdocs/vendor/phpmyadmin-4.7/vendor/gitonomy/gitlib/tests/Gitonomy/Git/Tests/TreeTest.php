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

class TreeTest extends AbstractTest
{
    const PATH_RESOLVING_COMMIT = 'cc06ac171d884282202dff88c1ded499a1f89420';
    /**
     * @dataProvider provideFooBar
     */
    public function testCase($repository)
    {
        $tree = $repository->getCommit(self::LONGFILE_COMMIT)->getTree();

        $entries = $tree->getEntries();

        $this->assertTrue(isset($entries['long.php']), 'long.php is present');
        $this->assertTrue($entries['long.php'][1] instanceof Blob, 'long.php is a Blob');

        $this->assertTrue(isset($entries['README.md']), 'README.md is present');
        $this->assertTrue($entries['README.md'][1] instanceof Blob, 'README.md is a Blob');
    }

    /**
     * @dataProvider provideFooBar
     */
    public function testResolvePath($repository)
    {
        $tree = $repository->getCommit(self::PATH_RESOLVING_COMMIT)->getTree();
        $path = 'test/a/b/c';

        $resolved = $tree->resolvePath($path);
        $entries = $resolved->getEntries();

        $this->assertTrue(isset($entries['d']), 'Successfully resolved source folder');
    }
}
