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

class HooksTest extends AbstractTest
{
    private static $symlinkOnWindows = null;

    public static function setUpBeforeClass()
    {
        if (defined('PHP_WINDOWS_VERSION_MAJOR')) {
            self::$symlinkOnWindows = true;
            $originDir = tempnam(sys_get_temp_dir(), 'sl');
            $targetDir = tempnam(sys_get_temp_dir(), 'sl');
            if (true !== @symlink($originDir, $targetDir)) {
                $report = error_get_last();
                if (is_array($report) && false !== strpos($report['message'], 'error code(1314)')) {
                    self::$symlinkOnWindows = false;
                }
            }
        }
    }

    public function hookPath($repository, $hook)
    {
        return $repository->getGitDir().'/hooks/'.$hook;
    }

    public function touchHook($repository, $hook, $content = '')
    {
        $path = $this->hookPath($repository, $hook);
        file_put_contents($path, $content);

        return $path;
    }

    public function assertHasHook($repository, $hook)
    {
        $file = $this->hookPath($repository, $hook);

        $this->assertTrue($repository->getHooks()->has($hook), "hook $hook in repository");
        $this->assertTrue(file_exists($file), "Hook $hook is present");
    }

    public function assertNoHook($repository, $hook)
    {
        $file = $this->hookPath($repository, $hook);

        $this->assertFalse($repository->getHooks()->has($hook), "No hook $hook in repository");
        $this->assertFalse(file_exists($file), "Hook $hook is not present");
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testHas($repository)
    {
        $this->assertNoHook($repository, 'foo');
        $this->touchHook($repository, 'foo');
        $this->assertHasHook($repository, 'foo');
    }

    /**
     * @dataProvider provideFoobar
     * @expectedException InvalidArgumentException
     */
    public function testGet_InvalidName_ThrowsException($repository)
    {
        $repository->getHooks()->get('foo');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testGet($repository)
    {
        $this->touchHook($repository, 'foo', 'foobar');

        $this->assertEquals('foobar', $repository->getHooks()->get('foo'));
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testSymlink($repository)
    {
        $this->markAsSkippedIfSymlinkIsMissing();

        $file = $this->touchHook($repository, 'bar', 'barbarbar');
        $repository->getHooks()->setSymlink('foo', $file);

        $this->assertTrue(is_link($this->hookPath($repository, 'foo')), 'foo hook is a symlink');
        $this->assertEquals($file, readlink($this->hookPath($repository, 'foo')), 'target of symlink is correct');
    }

    /**
     * @dataProvider provideFoobar
     * @expectedException LogicException
     */
    public function testSymlink_WithExisting_ThrowsLogicException($repository)
    {
        $this->markAsSkippedIfSymlinkIsMissing();

        $file = $this->hookPath($repository, 'target-symlink');
        $fooFile = $this->hookPath($repository, 'foo');

        file_put_contents($file, 'foobar');
        touch($fooFile);

        $repository->getHooks()->setSymlink('foo', $file);
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testSet($repository)
    {
        $file = $this->hookPath($repository, 'foo');
        $repository->getHooks()->set('foo', 'bar');

        $this->assertEquals('bar', file_get_contents($file), 'Hook content is correct');

        $perms = fileperms($file);
        $this->assertEquals(defined('PHP_WINDOWS_VERSION_BUILD') ? 0666 : 0777, $perms & 0777, 'Hook permissions are correct');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testSet_Existing_ThrowsLogicException($repository)
    {
        $repository->getHooks()->set('foo', 'bar');

        $this->setExpectedException('LogicException');
        $repository->getHooks()->set('foo', 'bar');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testRemove($repository)
    {
        $file = $this->hookPath($repository, 'foo');
        touch($file);

        $repository->getHooks()->remove('foo');
        $this->assertFalse(file_exists($file));
    }

    /**
     * @dataProvider provideFoobar
     * @expectedException LogicException
     */
    public function testRemove_NotExisting_ThrowsLogicException($repository)
    {
        $repository->getHooks()->remove('foo');
    }

    private function markAsSkippedIfSymlinkIsMissing()
    {
        if (!function_exists('symlink')) {
            $this->markTestSkipped('symlink is not supported');
        }

        if (defined('PHP_WINDOWS_VERSION_MAJOR') && false === self::$symlinkOnWindows) {
            $this->markTestSkipped('symlink requires "Create symbolic links" privilege on windows');
        }
    }
}
