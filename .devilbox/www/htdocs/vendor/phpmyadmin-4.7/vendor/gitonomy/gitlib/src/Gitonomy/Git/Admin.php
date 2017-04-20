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
namespace Gitonomy\Git;

use Gitonomy\Git\Exception\RuntimeException;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Administration class for Git repositories.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class Admin
{
    /**
     * Initializes a repository and returns the instance.
     *
     * @param string $path    path to the repository
     * @param bool   $bare    indicate to create a bare repository
     * @param array  $options options for Repository creation
     *
     * @return Repository
     *
     * @throws RuntimeException Directory exists or not writable (only if debug=true)
     */
    public static function init($path, $bare = true, array $options = array())
    {
        $process = static::getProcess('init', array_merge(array('-q'), $bare ? array('--bare') : array(), array($path)), $options);

        $process->run();

        if (!$process->isSuccessFul()) {
            throw new RuntimeException(sprintf("Error on repository initialization, command wasn't successful (%s). Error output:\n%s", $process->getCommandLine(), $process->getErrorOutput()));
        }

        return new Repository($path, $options);
    }

    /**
     * Checks the validity of a git repository url without cloning it.
     *
     * This will use the `ls-remote` command of git against the given url.
     * Usually, this command returns 0 when successful, and 128 when the
     * repository is not found.
     *
     * @param string $url     url of repository to check
     * @param array  $options options for Repository creation
     *
     * @return bool true if url is valid
     */
    public static function isValidRepository($url, array $options = array())
    {
        $process = static::getProcess('ls-remote', array($url), $options);

        $process->run();

        return $process->isSuccessFul();
    }

    /**
     * Clone a repository to a local path.
     *
     * @param string $path    indicates where to clone repository
     * @param string $url     url of repository to clone
     * @param bool   $bare    indicates if repository should be bare or have a working copy
     * @param array  $options options for Repository creation
     *
     * @return Repository
     */
    public static function cloneTo($path, $url, $bare = true, array $options = array())
    {
        $args = $bare ? array('--bare') : array();

        return static::cloneRepository($path, $url, $args, $options);
    }

    /**
     * Clone a repository branch to a local path.
     *
     * @param string $path    indicates where to clone repository
     * @param string $url     url of repository to clone
     * @param string $branch  branch to clone
     * @param bool   $bare    indicates if repository should be bare or have a working copy
     * @param array  $options options for Repository creation
     *
     * @return Repository
     */
    public static function cloneBranchTo($path, $url, $branch, $bare = true, $options = array())
    {
        $args = array('--branch', $branch);
        if ($bare) {
            $args[] = '--bare';
        }

        return static::cloneRepository($path, $url, $args, $options);
    }

    /**
     * Mirrors a repository (fetch all revisions, not only branches).
     *
     * @param string $path    indicates where to clone repository
     * @param string $url     url of repository to clone
     * @param array  $options options for Repository creation
     *
     * @return Repository
     */
    public static function mirrorTo($path, $url, array $options = array())
    {
        return static::cloneRepository($path, $url, array('--mirror'), $options);
    }

    /**
     * Internal method to launch effective ``git clone`` command.
     *
     * @param string $path    indicates where to clone repository
     * @param string $url     url of repository to clone
     * @param array  $args    arguments to be added to the command-line
     * @param array  $options options for Repository creation
     *
     * @return Repository
     */
    public static function cloneRepository($path, $url, array $args = array(), array $options = array())
    {
        $process = static::getProcess('clone', array_merge(array('-q'), $args, array($url, $path)), $options);

        $process->run();

        if (!$process->isSuccessFul()) {
            throw new RuntimeException(sprintf('Error while initializing repository: %s', $process->getErrorOutput()));
        }

        return new Repository($path, $options);
    }

    /**
     * This internal method is used to create a process object.
     */
    private static function getProcess($command, array $args = array(), array $options = array())
    {
        $is_windows = defined('PHP_WINDOWS_VERSION_BUILD');
        $options = array_merge(array(
            'environment_variables' => $is_windows ? array('PATH' => getenv('PATH')) : array(),
            'command' => 'git',
            'process_timeout' => 3600,
        ), $options);

        $builder = ProcessBuilder::create(array_merge(array($options['command'], $command), $args));
        $builder->inheritEnvironmentVariables(false);

        $process = $builder->getProcess();
        $process->setEnv($options['environment_variables']);
        $process->setTimeout($options['process_timeout']);
        $process->setIdleTimeout($options['process_timeout']);

        return $process;
    }
}
