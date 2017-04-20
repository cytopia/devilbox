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
use Gitonomy\Git\Repository;

class AdminTest extends AbstractTest
{
    private $tmpDir;

    public function setUp()
    {
        $this->tmpDir = self::createTempDir();
    }

    public function tearDown()
    {
        $this->deleteDir(self::createTempDir());
    }

    public function testBare()
    {
        $repository = Admin::init($this->tmpDir, true, self::getOptions());

        $objectDir = $this->tmpDir.'/objects';

        $this->assertTrue($repository->isBare(), 'Repository is bare');
        $this->assertTrue(is_dir($objectDir),     'objects/ folder is present');
        $this->assertTrue($repository instanceof Repository, 'Admin::init returns a repository');
        $this->assertEquals($this->tmpDir, $repository->getGitDir(), 'The folder passed as argument is git dir');
        $this->assertNull($repository->getWorkingDir(), 'No working dir in bare repository');
    }

    public function testNotBare()
    {
        $repository = Admin::init($this->tmpDir, false, self::getOptions());

        $objectDir = $this->tmpDir.'/.git/objects';

        $this->assertFalse($repository->isBare(), 'Repository is not bare');
        $this->assertTrue(is_dir($objectDir), 'objects/ folder is present');
        $this->assertTrue($repository instanceof Repository, 'Admin::init returns a repository');
        $this->assertEquals($this->tmpDir.'/.git', $repository->getGitDir(), 'git dir as subfolder of argument');
        $this->assertEquals($this->tmpDir, $repository->getWorkingDir(), 'working dir present in bare repository');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testClone($repository)
    {
        $newDir = self::createTempDir();
        $new = $repository->cloneTo($newDir, $repository->isBare(), self::getOptions());
        self::registerDeletion($new);

        $newRefs = array_keys($new->getReferences()->getAll());

        $this->assertTrue(in_array('refs/heads/master', $newRefs));
        $this->assertTrue(in_array('refs/tags/0.1', $newRefs));

        if ($repository->isBare()) {
            $this->assertEquals($newDir, $new->getGitDir());
            $this->assertTrue(in_array('refs/heads/new-feature', $newRefs));
        } else {
            $this->assertEquals($newDir.'/.git', $new->getGitDir());
            $this->assertEquals($newDir, $new->getWorkingDir());
        }
    }

    public function testCloneBranchBare()
    {
        //we can't use AbstractText::createFoobarRepository()
        //because it does not clone other branches than "master"
        //so we test it directly against the remote repository

        $newDir = self::createTempDir();
        $new = Admin::cloneBranchTo($newDir, self::REPOSITORY_URL, 'new-feature');
        self::registerDeletion($new);

        $head = $new->getHead();
        $this->assertTrue($head instanceof Branch, 'HEAD is a branch');
        $this->assertEquals('new-feature', $head->getName(), 'HEAD is branch new-feature');
    }

    public function testCloneBranchNotBare()
    {
        //we can't use AbstractText::createFoobarRepository()
        //because it does not clone other branches than "master"
        //so we test it directly against remote repository

        $newDir = self::createTempDir();
        $new = Admin::cloneBranchTo($newDir, self::REPOSITORY_URL, 'new-feature', false);
        self::registerDeletion($new);

        $head = $new->getHead();
        $this->assertTrue($head instanceof Branch, 'HEAD is a branch');
        $this->assertEquals('new-feature', $head->getName(), 'HEAD is branch new-feature');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testMirror($repository)
    {
        $newDir = self::createTempDir();
        $new = Admin::mirrorTo($newDir, $repository->getGitDir(), self::getOptions());
        self::registerDeletion($new);

        $newRefs = array_keys($new->getReferences()->getAll());

        $this->assertTrue(in_array('refs/heads/master', $newRefs));
        $this->assertTrue(in_array('refs/tags/0.1', $newRefs));
        $this->assertEquals($newDir, $new->getGitDir());

        if ($repository->isBare()) {
            $this->assertTrue(in_array('refs/heads/new-feature', $newRefs));
        } else {
            $this->assertTrue(in_array('refs/remotes/origin/new-feature', $newRefs));
        }
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testCheckValidRepository($repository)
    {
        $url = $repository->getGitDir();
        $this->assertTrue(Admin::isValidRepository($url));
    }

    public function testCheckInvalidRepository()
    {
        $url = $this->tmpDir.'/invalid.git';
        mkdir($url);

        $this->assertFalse(Admin::isValidRepository($url));
    }

    /**
     * @expectedException RuntimeException
     */
    public function testExistingFile()
    {
        $file = $this->tmpDir.'/test';
        touch($file);

        Admin::init($file, true, self::getOptions());
    }

    public function testCloneRepository()
    {
        $newDir = self::createTempDir();
        $args = array();

        $new = Admin::cloneRepository($newDir, self::REPOSITORY_URL, $args, self::getOptions());
        self::registerDeletion($new);

        $newRefs = array_keys($new->getReferences()->getAll());

        $this->assertTrue(in_array('refs/heads/master', $newRefs));
        $this->assertTrue(in_array('refs/tags/0.1', $newRefs));

        $this->assertEquals($newDir.'/.git', $new->getGitDir());
        $this->assertEquals($newDir, $new->getWorkingDir());
    }
}
