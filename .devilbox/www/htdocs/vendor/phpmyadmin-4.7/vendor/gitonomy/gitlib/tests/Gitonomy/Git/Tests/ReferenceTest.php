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

use Gitonomy\Git\Reference\Branch;
use Gitonomy\Git\Reference\Tag;

class ReferenceTest extends AbstractTest
{
    private $references;

    /**
     * @dataProvider provideEmpty
     */
    public function testEmptyRepository($repository)
    {
        $this->assertCount(0, $repository->getReferences());
        $this->assertEquals(array(), $repository->getReferences()->getAll());
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetBranch($repository)
    {
        $branch = $repository->getReferences()->getBranch('master');

        $this->assertTrue($branch instanceof Branch, 'Branch object is correct type');
        $this->assertEquals($branch->getCommitHash(), $branch->getCommit()->getHash(), 'Hash is correctly resolved');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testHasBranch($repository)
    {
        $this->assertTrue($repository->getReferences()->hasBranch('master'), 'Branch master exists');
        $this->assertFalse($repository->getReferences()->hasBranch('foobar'), 'Branch foobar does not exists');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testHasTag($repository)
    {
        $this->assertTrue($repository->getReferences()->hasTag('0.1'), 'Tag 0.1 exists');
        $this->assertFalse($repository->getReferences()->hasTag('foobar'), 'Tag foobar does not exists');
    }

    /**
     * @dataProvider provideFoobar
     * @expectedException Gitonomy\Git\Exception\ReferenceNotFoundException
     */
    public function testGetBranch_NotExisting_Error($repository)
    {
        $branch = $repository->getReferences()->getBranch('notexisting');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetTag($repository)
    {
        $tag = $repository->getReferences()->getTag('0.1');

        $this->assertTrue($tag instanceof Tag, 'Tag object is correct type');

        $this->assertEquals(self::LONGFILE_COMMIT, $tag->getCommitHash(), 'Commit hash is correct');
        $this->assertEquals(self::LONGFILE_COMMIT, $tag->getCommit()->getHash(), 'Commit hash is correct');
    }

    /**
     * @dataProvider provideFoobar
     * @expectedException Gitonomy\Git\Exception\ReferenceNotFoundException
     */
    public function testGetTag_NotExisting_Error($repository)
    {
        $branch = $repository->getReferences()->getTag('notexisting');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testResolve($repository)
    {
        $commit = $repository->getReferences()->getTag('0.1')->getCommit();
        $resolved = $repository->getReferences()->resolve($commit->getHash());

        $this->assertEquals(1, count($resolved), '1 revision resolved');
        $this->assertTrue(reset($resolved) instanceof Tag, 'Resolved object is a tag');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testResolveTags($repository)
    {
        $commit = $repository->getReferences()->getTag('0.1')->getCommit();
        $resolved = $repository->getReferences()->resolveTags($commit->getHash());

        $this->assertEquals(1, count($resolved), '1 revision resolved');
        $this->assertTrue(reset($resolved) instanceof Tag, 'Resolved object is a tag');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testResolveBranches($repository)
    {
        $master = $repository->getReferences()->getBranch('master');

        $resolved = $repository->getReferences()->resolveBranches($master->getCommitHash());

        if ($repository->isBare()) {
            $this->assertEquals(1, count($resolved), '1 revision resolved');
        } else {
            $this->assertEquals(2, count($resolved), '2 revision resolved');
        }

        $this->assertTrue(reset($resolved) instanceof Branch, 'Resolved object is a branch');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testCountable($repository)
    {
        $this->assertGreaterThanOrEqual(2, count($repository->getReferences()), 'At least two references in repository');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testIterable($repository)
    {
        $i = 0;
        foreach ($repository->getReferences() as $ref) {
            ++$i;
        }
        $this->assertGreaterThanOrEqual(2, $i, 'At least two references in repository');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testCreateAndDeleteTag($repository)
    {
        $references = $repository->getReferences();
        $tag = $references->createTag('0.0', self::INITIAL_COMMIT);

        $this->assertTrue($references->hasTag('0.0'), 'Tag 0.0 created');
        $this->assertEquals(self::INITIAL_COMMIT, $tag->getCommit()->getHash());
        $this->assertSame($tag, $references->getTag('0.0'));

        $tag->delete();
        $this->assertFalse($references->hasTag('0.0'), 'Tag 0.0 removed');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testCreateAndDeleteBranch($repository)
    {
        $references = $repository->getReferences();
        $branch = $references->createBranch('foobar', self::INITIAL_COMMIT);

        $this->assertTrue($references->hasBranch('foobar'), 'Branch foobar created');
        $this->assertEquals(self::INITIAL_COMMIT, $branch->getCommit()->getHash());
        $this->assertSame($branch, $references->getBranch('foobar'));

        $branch->delete();
        $this->assertFalse($references->hasBranch('foobar'), 'Branch foobar removed');
    }
}
