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

use Gitonomy\Git\Commit;
use Gitonomy\Git\Diff\Diff;

class CommitTest extends AbstractTest
{
    /**
     * @dataProvider provideFoobar
     */
    public function testGetDiff($repository)
    {
        $commit = $repository->getCommit(self::LONGFILE_COMMIT);

        $diff = $commit->getDiff();

        $this->assertTrue($diff instanceof Diff, 'getDiff() returns a Diff object');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetHash($repository)
    {
        $commit = $repository->getCommit(self::LONGFILE_COMMIT);

        $this->assertEquals(self::LONGFILE_COMMIT, $commit->getHash());
    }

    /**
     * @dataProvider provideFoobar
     *
     * @expectedException Gitonomy\Git\Exception\ReferenceNotFoundException
     * @expectedExceptionMessage Reference not found: "that-hash-doest-not-exists"
     */
    public function testInvalideHashThrowException($repository)
    {
        $commit = new Commit($repository, 'that-hash-doest-not-exists');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetShortHash($repository)
    {
        $commit = $repository->getCommit(self::LONGFILE_COMMIT);

        $this->assertEquals('4f17752', $commit->getShortHash(), 'Short hash');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetParentHashes_WithNoParent($repository)
    {
        $commit = $repository->getCommit(self::INITIAL_COMMIT);

        $this->assertEquals(0, count($commit->getParentHashes()), 'No parent on initial commit');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetParentHashes_WithOneParent($repository)
    {
        $commit = $repository->getCommit(self::LONGFILE_COMMIT);
        $parents = $commit->getParentHashes();

        $this->assertEquals(1, count($parents), 'One parent found');
        $this->assertEquals(self::BEFORE_LONGFILE_COMMIT, $parents[0], 'Parent hash is correct');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetParents_WithOneParent($repository)
    {
        $commit = $repository->getCommit(self::LONGFILE_COMMIT);
        $parents = $commit->getParents();

        $this->assertEquals(1, count($parents), 'One parent found');
        $this->assertTrue($parents[0] instanceof Commit, 'First parent is a Commit object');
        $this->assertEquals(self::BEFORE_LONGFILE_COMMIT, $parents[0]->getHash(), "First parents's hash is correct");
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetTreeHash($repository)
    {
        $commit = $repository->getCommit(self::LONGFILE_COMMIT);

        $this->assertEquals('b06890c7b10904979d2f69613c2ccda30aafe262', $commit->getTreeHash(), 'Tree hash is correct');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetTree($repository)
    {
        $commit = $repository->getCommit(self::LONGFILE_COMMIT);

        $this->assertInstanceOf('Gitonomy\Git\Tree', $commit->getTree(), 'Tree is a tree');
        $this->assertEquals('b06890c7b10904979d2f69613c2ccda30aafe262', $commit->getTree()->getHash(), 'Tree hash is correct');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetAuthorName($repository)
    {
        $commit = $repository->getCommit(self::LONGFILE_COMMIT);

        $this->assertEquals('alice', $commit->getAuthorName(), 'Author name');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetAuthorEmail($repository)
    {
        $commit = $repository->getCommit(self::LONGFILE_COMMIT);

        $this->assertEquals('alice@example.org', $commit->getAuthorEmail(), 'Author email');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetAuthorDate($repository)
    {
        $commit = $repository->getCommit(self::LONGFILE_COMMIT);

        $this->assertEquals('2012-12-31 14:21:03', $commit->getAuthorDate()->format('Y-m-d H:i:s'), 'Author date');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetCommitterName($repository)
    {
        $commit = $repository->getCommit(self::LONGFILE_COMMIT);

        $this->assertEquals('alice', $commit->getCommitterName(), 'Committer name');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetCommitterEmail($repository)
    {
        $commit = $repository->getCommit(self::LONGFILE_COMMIT);

        $this->assertEquals('alice@example.org', $commit->getCommitterEmail(), 'Committer email');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetCommitterDate($repository)
    {
        $commit = $repository->getCommit(self::LONGFILE_COMMIT);

        $this->assertEquals('2012-12-31 14:21:03', $commit->getCommitterDate()->format('Y-m-d H:i:s'), 'Committer date');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetMessage($repository)
    {
        $commit = $repository->getCommit(self::LONGFILE_COMMIT);

        $this->assertEquals('add a long file'."\n", $commit->getMessage());
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetShortMessage($repository)
    {
        // tests with a multi-line message
        $commit = $repository->getCommit(self::LONGMESSAGE_COMMIT);

        $this->assertEquals('Fixed perm...', $commit->getShortMessage(10));
        $this->assertEquals('Fixed perm!!!', $commit->getShortMessage(10, false, '!!!'));
        $this->assertEquals('Fixed permissions!!!', $commit->getShortMessage(10, true, '!!!'));

        // tests with a single-line message
        $commit = $repository->getCommit(self::INITIAL_COMMIT);

        $this->assertEquals('Add README', $commit->getShortMessage(20));
        $this->assertEquals('A', $commit->getShortMessage(1, false, ''));
        $this->assertEquals('Add!!!', $commit->getShortMessage(1, true, '!!!'));
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetBodyMessage($repository)
    {
        $commit = $repository->getCommit(self::LONGMESSAGE_COMMIT);
        $message = <<<EOL
If you want to know everything,
I ran something like `chmox +x test.sh`

Hello and good bye.

EOL;

        $this->assertEquals($message, $commit->getBodyMessage());

        $commit = $repository->getCommit(self::INITIAL_COMMIT);

        $this->assertEquals('', $commit->getBodyMessage());
    }

    /**
     * @expectedException InvalidArgumentException
     * @dataProvider provideFoobar
     */
    public function testGetIncludingBranchesException($repository)
    {
        $commit = $repository->getCommit(self::INITIAL_COMMIT);

        $commit->getIncludingBranches(false, false);
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetIncludingBranches($repository)
    {
        $commit = $repository->getCommit(self::INITIAL_COMMIT);

        $branches = $commit->getIncludingBranches(true, false);
        $this->assertCount(count($repository->getReferences()->getLocalBranches()), $branches);

        $branches = $commit->getIncludingBranches(true, true);
        $this->assertCount(count($repository->getReferences()->getBranches()), $branches);

        $branches = $commit->getIncludingBranches(false, true);
        $this->assertCount(count($repository->getReferences()->getRemoteBranches()), $branches);
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetLastModification($repository)
    {
        $commit = $repository->getCommit(self::LONGFILE_COMMIT);

        $lastModification = $commit->getLastModification('image.jpg');

        $this->assertTrue($lastModification instanceof Commit, 'Last modification is a Commit object');
        $this->assertEquals(self::BEFORE_LONGFILE_COMMIT, $lastModification->getHash(), 'Last modification is current commit');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testMergeCommit($repository)
    {
        $commit = $repository->getCommit(self::MERGE_COMMIT);

        $this->assertEquals("Merge branch 'authors'", $commit->getSubjectMessage());
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testEncoding($repository)
    {
        $commit = $repository->getCommit(self::ENCODING_COMMIT);

        $this->assertEquals('contribute to AUTHORS file', $commit->getSubjectMessage());
    }
}
