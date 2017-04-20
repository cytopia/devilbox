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
use Gitonomy\Git\Repository;

abstract class AbstractTest extends \PHPUnit_Framework_TestCase
{
    const REPOSITORY_URL = 'http://github.com/gitonomy/foobar.git';

    const LONGFILE_COMMIT = '4f17752acc9b7c54ba679291bf24cb7d354f0f4f';
    const BEFORE_LONGFILE_COMMIT = 'e0ec50e2af75fa35485513f60b2e658e245227e9';
    const LONGMESSAGE_COMMIT = '3febd664b6886344a9b32d70657687ea4b1b4fab';
    const INITIAL_COMMIT = '74acd054c8ec873ae6be044041d3a85a4f890ba5';
    const MERGE_COMMIT = '2f5b9d0a4e6e7173d7816e417805709c708674f8';
    const ENCODING_COMMIT = '779420b9b936f18a0b6579e1499a85b14270802e';

    /**
     * Local clone of remote URL. Avoids network call on each test.
     */
    private static $localRepository;

    /**
     * Creates an empty git repository and returns instance.
     *
     * @return Repository
     */
    public static function createEmptyRepository($bare = true)
    {
        $dir = self::createTempDir();
        $repository = Admin::init($dir, $bare, self::getOptions());
        self::registerDeletion($repository);

        return $repository;
    }

    /**
     * Can be used as data provider to get bare/not-bare repositories.
     */
    public static function provideFoobar()
    {
        return array(
            array(self::createFoobarRepository()),
            array(self::createFoobarRepository(false)),
        );
    }

    /**
     * Can be used as data provider to get bare/not-bare repositories.
     */
    public static function provideEmpty()
    {
        return array(
            array(self::createEmptyRepository()),
            array(self::createEmptyRepository(false)),
        );
    }

    /**
     * Creates a fixture test repository.
     *
     * @return Repository
     */
    public static function createFoobarRepository($bare = true)
    {
        if (null === self::$localRepository) {
            self::$localRepository = Admin::cloneTo(self::createTempDir(), self::REPOSITORY_URL, $bare, self::getOptions());
        }

        $repository = self::$localRepository->cloneTo(self::createTempDir(), $bare, self::getOptions());
        self::registerDeletion($repository);

        return $repository;
    }

    public static function registerDeletion(Repository $repository)
    {
        register_shutdown_function(function () use ($repository) {
            if ($repository->getWorkingDir()) {
                $dir = $repository->getWorkingDir();
            } else {
                $dir = $repository->getGitDir();
            }
            AbstractTest::deleteDir($dir);
        });
    }

    /**
     * Created an empty directory and return path to it.
     *
     * @return string a fullpath
     */
    public static function createTempDir()
    {
        $tmpDir = tempnam(sys_get_temp_dir(), 'gitlib_');
        unlink($tmpDir);
        mkdir($tmpDir);

        return $tmpDir;
    }

    /**
     * Deletes a directory recursively.
     *
     * @param string $dir directory to delete
     */
    public static function deleteDir($dir)
    {
        $iterator = new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::FOLLOW_SYMLINKS);
        $iterator = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($iterator as $file) {
            if (!is_link($file)) {
                chmod($file, 0777);
            }
            if (is_dir($file)) {
                rmdir($file);
            } else {
                unlink($file);
            }
        }

        chmod($dir, 0777);
        rmdir($dir);
    }

    protected static function getOptions()
    {
        $command = isset($_SERVER['GIT_COMMAND']) ? $_SERVER['GIT_COMMAND'] : 'git';
        $envs = isset($_SERVER['GIT_ENVS']) ? (array) $_SERVER['GIT_ENVS'] : array();

        return array(
            'command' => $command,
            'environment_variables' => $envs,
            'process_timeout' => 60,
        );
    }
}
