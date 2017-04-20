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

use Gitonomy\Git\Diff\Diff;

class DiffTest extends AbstractTest
{
    const DELETE_COMMIT = '519d5693c72c925cd59205d9f11e9fa1d550028b';
    const CREATE_COMMIT = 'e6fa3c792facc06faa049a6938c84c411954deb5';
    const RENAME_COMMIT = '6640e0ef31518054847a1876328e26ee64083e0a';
    const CHANGEMODE_COMMIT = '93da965f58170f13017477b9a608657e87e23230';

    /**
     * @dataProvider provideFoobar
     */
    public function testSerialization($repository)
    {
        $data = $repository->getCommit(self::CREATE_COMMIT)->getDiff()->toArray();
        $diff = Diff::fromArray($data);

        $this->verifyCreateCommitDiff($diff);
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetFiles_Addition($repository)
    {
        $diff = $repository->getCommit(self::CREATE_COMMIT)->getDiff();
        $this->verifyCreateCommitDiff($diff);
    }

    protected function verifyCreateCommitDiff(Diff $diff)
    {
        $files = $diff->getFiles();

        $this->assertEquals(2, count($files), '1 file in diff');

        $this->assertTrue($files[0]->isCreation(), 'script_A.php created');

        $this->assertEquals(null,           $files[0]->getOldName(), 'First file name is a new file');
        $this->assertEquals('script_A.php', $files[0]->getNewName(), 'First file name is script_A.php');
        $this->assertEquals(null,           $files[0]->getOldMode(), 'First file mode is a new file');
        $this->assertEquals('100644',       $files[0]->getNewMode(), 'First file mode is correct');

        $this->assertEquals(1, $files[0]->getAdditions(), '1 line added');
        $this->assertEquals(0,  $files[0]->getDeletions(), '0 lines deleted');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetFiles_Modification($repository)
    {
        $files = $repository->getCommit(self::BEFORE_LONGFILE_COMMIT)->getDiff()->getFiles();

        $this->assertEquals(1, count($files), '1 files in diff');

        $this->assertTrue($files[0]->isModification(), 'image.jpg modified');

        $this->assertEquals('image.jpg', $files[0]->getOldName(), 'Second file name is image.jpg');
        $this->assertEquals('image.jpg', $files[0]->getNewName(), 'Second file name is image.jpg');
        $this->assertEquals('100644',    $files[0]->getOldMode(), 'Second file mode is a new file');
        $this->assertEquals('100644',    $files[0]->getNewMode(), 'Second file mode is correct');

        $this->assertTrue($files[0]->isBinary(), 'binary file');
        $this->assertEquals(0, $files[0]->getAdditions(), '0 lines added');
        $this->assertEquals(0, $files[0]->getDeletions(), '0 lines deleted');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetFiles_Deletion($repository)
    {
        $files = $repository->getCommit(self::DELETE_COMMIT)->getDiff()->getFiles();

        $this->assertEquals(1, count($files), '1 files modified');

        $this->assertTrue($files[0]->isDeletion(), 'File deletion');
        $this->assertEquals('script_B.php', $files[0]->getOldName(), 'verify old filename');
        $this->assertEquals(1, $files[0]->getDeletions(), '1 line deleted');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetFiles_Rename($repository)
    {
        $files = $repository->getCommit(self::RENAME_COMMIT)->getDiff()->getFiles();

        $this->assertEquals(1, count($files), '1 files modified');

        $this->assertTrue($files[0]->isModification());
        $this->assertTrue($files[0]->isRename());
        $this->assertFalse($files[0]->isDeletion());
        $this->assertFalse($files[0]->isCreation());
        $this->assertFalse($files[0]->isChangeMode());
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGetFiles_Changemode($repository)
    {
        $files = $repository->getCommit(self::CHANGEMODE_COMMIT)->getDiff()->getFiles();

        $this->assertEquals(1, count($files), '1 files modified');

        $this->assertTrue($files[0]->isModification());
        $this->assertTrue($files[0]->isChangeMode());
        $this->assertFalse($files[0]->isDeletion());
        $this->assertFalse($files[0]->isCreation());
        $this->assertFalse($files[0]->isRename());
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testDiffRangeParse($repository)
    {
        $files = $repository->getCommit(self::CREATE_COMMIT)->getDiff()->getFiles();

        $changes = $files[0]->getChanges();

        $this->assertEquals(0, $changes[0]->getRangeOldStart());
        $this->assertEquals(0, $changes[0]->getRangeOldCount());

        $this->assertEquals(1, $changes[0]->getRangeNewStart());
        $this->assertEquals(0, $changes[0]->getRangeNewCount());
    }
}
