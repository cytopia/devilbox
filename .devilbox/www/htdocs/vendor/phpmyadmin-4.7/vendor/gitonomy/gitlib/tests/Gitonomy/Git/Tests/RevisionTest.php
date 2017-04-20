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
use Gitonomy\Git\Log;
use Gitonomy\Git\Revision;

class RevisionTest extends AbstractTest
{
    /**
     * @dataProvider provideFoobar
     */
    public function testGetCommit($repository)
    {
        $revision = $repository->getRevision(self::LONGFILE_COMMIT.'^');

        $this->assertTrue($revision instanceof Revision, 'Revision object type');

        $commit = $revision->getCommit();

        $this->assertTrue($commit instanceof Commit, 'getCommit returns a Commit');

        $this->assertEquals(self::BEFORE_LONGFILE_COMMIT, $commit->getHash(), 'Resolution is correct');
    }

    /**
     * @dataProvider provideFoobar
     * @expectedException Gitonomy\Git\Exception\ReferenceNotFoundException
     * @expectedExceptionMessage Can not find revision "non-existent-commit"
     */
    public function testGetFailingReference($repository)
    {
        $revision = $repository->getRevision('non-existent-commit')->getCommit();
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetLog($repository)
    {
        $revision = $repository->getRevision(self::LONGFILE_COMMIT);

        $log = $revision->getLog(null, 2, 3);

        $this->assertTrue($log instanceof Log, 'Log type object');
        $this->assertEquals(2, $log->getOffset(), 'Log offset is passed');
        $this->assertEquals(3, $log->getLimit(), 'Log limit is passed');
        $this->assertEquals(array($revision), $log->getRevisions()->getAll(), 'Revision is passed');
    }
}
