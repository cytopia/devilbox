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

use Gitonomy\Git\Admin;
use Gitonomy\Git\Reference\Branch;

class WorkingCopyTest extends AbstractTest
{
    /**
     * @expectedException LogicException
     */
    public function testNoWorkingCopyInBare()
    {
        $path = self::createTempDir();
        $repo = Admin::init($path, true, self::getOptions());

        $repo->getWorkingCopy();
    }

    public function testCheckout()
    {
        $repository = self::createFoobarRepository(false);
        $wc = $repository->getWorkingCopy();
        $wc->checkout('origin/new-feature', 'new-feature');

        $head = $repository->getHead();
        $this->assertTrue($head instanceof Branch, 'HEAD is a branch');
        $this->assertEquals('new-feature', $head->getName(), 'HEAD is branch new-feature');
    }

    public function testDiffStaged()
    {
        $repository = self::createFoobarRepository(false);
        $wc = $repository->getWorkingCopy();

        $diffStaged = $wc->getDiffStaged();
        $this->assertCount(0, $diffStaged->getFiles());

        $file = $repository->getWorkingDir().'/foobar-test';
        file_put_contents($file, 'test');
        $repository->run('add', array($file));

        $diffStaged = $wc->getDiffStaged();
        $this->assertCount(1, $diffStaged->getFiles());
    }

    public function testDiffPending()
    {
        $repository = self::createFoobarRepository(false);
        $wc = $repository->getWorkingCopy();

        $diffPending = $wc->getDiffPending();
        $this->assertCount(0, $diffPending->getFiles());

        $file = $repository->getWorkingDir().'/test.sh';
        file_put_contents($file, 'test');

        $diffPending = $wc->getDiffPending();
        $this->assertCount(1, $diffPending->getFiles());
    }

    /**
     * @expectedException RuntimeException
     */
    public function testCheckoutUnexisting()
    {
        self::createFoobarRepository(false)->getWorkingCopy()->checkout('foobar');
    }

    public function testAttachedHead()
    {
        $repository = self::createFoobarRepository(false);
        $wc = $repository->getWorkingCopy();
        $wc->checkout('master');

        $head = $repository->getHead();
        $this->assertTrue($repository->isHeadAttached(), 'HEAD is attached');
        $this->assertFalse($repository->isHeadDetached(), 'HEAD is not detached');
    }

    public function testDetachedHead()
    {
        $repository = self::createFoobarRepository(false);
        $wc = $repository->getWorkingCopy();
        $wc->checkout('0.1');

        $head = $repository->getHead();
        $this->assertFalse($repository->isHeadAttached(), 'HEAD is not attached');
        $this->assertTrue($repository->isHeadDetached(), 'HEAD is detached');
    }

    public function testGetUntracked()
    {
        $repository = self::createFoobarRepository(false);
        $wc = $repository->getWorkingCopy();

        $file = $repository->getWorkingDir().'/untracked.txt';
        file_put_contents($file, 'foo');

        $this->assertContains('untracked.txt', $wc->getUntrackedFiles());
    }
}
