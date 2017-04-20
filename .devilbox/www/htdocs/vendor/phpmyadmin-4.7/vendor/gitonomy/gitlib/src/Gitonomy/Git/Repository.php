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

use Gitonomy\Git\Diff\Diff;
use Gitonomy\Git\Exception\InvalidArgumentException;
use Gitonomy\Git\Exception\ProcessException;
use Gitonomy\Git\Exception\RuntimeException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Git repository object.
 *
 * Main entry point for browsing a Git repository.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class Repository
{
    const DEFAULT_DESCRIPTION = "Unnamed repository; edit this file 'description' to name the repository.\n";

    /**
     * Directory containing git files.
     *
     * @var string
     */
    protected $gitDir;

    /**
     * Working directory.
     *
     * @var string
     */
    protected $workingDir;

    /**
     * Cache containing all objects of the repository.
     *
     * Associative array, indexed by object hash
     *
     * @var array
     */
    protected $objects;

    /**
     * Reference bag associated to this repository.
     *
     * @var ReferenceBag
     */
    protected $referenceBag;

    /**
     * Logger (can be null).
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Path to git command.
     */
    protected $command;

    /**
     * Debug flag, indicating if errors should be thrown.
     *
     * @var bool
     */
    protected $debug;

    /**
     * Environment variables that should be set for every running process.
     *
     * @var array
     */
    protected $environmentVariables;

    /**
     * Timeout that should be set for every running process.
     *
     * @var int
     */
    protected $processTimeout;

    /**
     * Constructs a new repository.
     *
     * Available options are:
     *
     * * working_dir : specify where working copy is located (option --work-tree)
     *
     * * debug : (default: true) enable/disable minimize errors and reduce log level
     *
     * * logger : a logger to use for logging actions (Psr\Log\LoggerInterface)
     *
     * * environment_variables : define environment variables for every ran process
     *
     * @param string $dir     path to git repository
     * @param array  $options array of options values
     *
     * @throws InvalidArgumentException The folder does not exists
     */
    public function __construct($dir, $options = array())
    {
        $is_windows = defined('PHP_WINDOWS_VERSION_BUILD');
        $options = array_merge(array(
            'working_dir' => null,
            'debug' => true,
            'logger' => null,
            'environment_variables' => $is_windows ? array('PATH' => getenv('path')) : array(),
            'command' => 'git',
            'process_timeout' => 3600,
        ), $options);

        if (null !== $options['logger'] && !$options['logger'] instanceof LoggerInterface) {
            throw new InvalidArgumentException(sprintf('Argument "logger" passed to Repository should be a Psr\Log\LoggerInterface. A %s was provided', is_object($options['logger']) ? get_class($options['logger']) : gettype($options['logger'])));
        }

        $this->logger = $options['logger'];
        $this->initDir($dir, $options['working_dir']);

        $this->objects = array();
        $this->debug = (bool) $options['debug'];
        $this->environmentVariables = $options['environment_variables'];
        $this->processTimeout = $options['process_timeout'];
        $this->command = $options['command'];

        if (true === $this->debug && null !== $this->logger) {
            $this->logger->debug(sprintf('Repository created (git dir: "%s", working dir: "%s")', $this->gitDir, $this->workingDir ?: 'none'));
        }
    }

    /**
     * Initializes directory attributes on repository:.
     *
     * @param string $gitDir     directory of a working copy with files checked out
     * @param string $workingDir directory containing git files (objects, config...)
     */
    private function initDir($gitDir, $workingDir = null)
    {
        $gitDir = realpath($gitDir);

        if (null === $workingDir && is_dir($gitDir.'/.git')) {
            $workingDir = $gitDir;
            $gitDir = $gitDir.'/.git';
        } elseif (!is_dir($gitDir)) {
            throw new InvalidArgumentException(sprintf('Directory "%s" does not exist or is not a directory', $gitDir));
        }

        $this->gitDir = $gitDir;
        $this->workingDir = $workingDir;
    }

    /**
     * Tests if repository is a bare repository.
     *
     * @return bool
     */
    public function isBare()
    {
        return null === $this->workingDir;
    }

    /**
     * Returns the HEAD resolved as a commit.
     *
     * @return Revision|null returns a Commit, Branch, or ``null`` if repository is empty
     */
    public function getHeadCommit()
    {
        $head = $this->getHead();

        if ($head instanceof Reference) {
            return $head->getCommit();
        }

        return $head;
    }

    /**
     * @throws RuntimeException Unable to find file HEAD (debug-mode only)
     *
     * @return Reference|Commit|null current HEAD object or null if error occurs
     */
    public function getHead()
    {
        $file = $this->gitDir.'/HEAD';

        if (!file_exists($file)) {
            $message = sprintf('Unable to find HEAD file ("%s")', $file);

            if (null !== $this->logger) {
                $this->logger->error($message);
            }

            if (true === $this->debug) {
                throw new RuntimeException($message);
            }
        }

        $content = trim(file_get_contents($file));

        if (null !== $this->logger) {
            $this->logger->debug('HEAD file read: '.$content);
        }

        if (preg_match('/^ref: (.+)$/', $content, $vars)) {
            return $this->getReferences()->get($vars[1]);
        } elseif (preg_match('/^[0-9a-f]{40}$/', $content)) {
            return $this->getCommit($content);
        }

        $message = sprintf('Unexpected HEAD file content (file: %s). Content of file: %s', $file, $content);

        if (null !== $this->logger) {
            $this->logger->error($message);
        }

        if (true === $this->debug) {
            throw new RuntimeException($message);
        }
    }

    /**
     * @return bool
     */
    public function isHeadDetached()
    {
        return !$this->isHeadAttached();
    }

    /**
     * @return bool
     */
    public function isHeadAttached()
    {
        return $this->getHead() instanceof Reference;
    }

    /**
     * Returns the path to the git repository.
     *
     * @return string A directory path
     */
    public function getPath()
    {
        return $this->workingDir === null ? $this->gitDir : $this->workingDir;
    }

    /**
     * Returns the directory containing git files (git-dir).
     *
     * @return string
     */
    public function getGitDir()
    {
        return $this->gitDir;
    }

    /**
     * Returns the work-tree directory. This may be null if repository is
     * bare.
     *
     * @return string path to repository or null if repository is bare
     */
    public function getWorkingDir()
    {
        return $this->workingDir;
    }

    /**
     * Instanciates a revision.
     *
     * @param string $name Name of the revision
     *
     * @return Revision
     */
    public function getRevision($name)
    {
        return new Revision($this, $name);
    }

    /**
     * @return WorkingCopy
     */
    public function getWorkingCopy()
    {
        return new WorkingCopy($this);
    }

    /**
     * Returns the reference list associated to the repository.
     *
     * @return ReferenceBag
     */
    public function getReferences()
    {
        if (null === $this->referenceBag) {
            $this->referenceBag = new ReferenceBag($this);
        }

        return $this->referenceBag;
    }

    /**
     * Instanciates a commit object or fetches one from the cache.
     *
     * @param string $hash A commit hash, with a length of 40
     *
     * @return Commit
     */
    public function getCommit($hash)
    {
        if (!isset($this->objects[$hash])) {
            $this->objects[$hash] = new Commit($this, $hash);
        }

        return $this->objects[$hash];
    }

    /**
     * Instanciates a tree object or fetches one from the cache.
     *
     * @param string $hash A tree hash, with a length of 40
     *
     * @return Tree
     */
    public function getTree($hash)
    {
        if (!isset($this->objects[$hash])) {
            $this->objects[$hash] = new Tree($this, $hash);
        }

        return $this->objects[$hash];
    }

    /**
     * Instanciates a blob object or fetches one from the cache.
     *
     * @param string $hash A blob hash, with a length of 40
     *
     * @return Blob
     */
    public function getBlob($hash)
    {
        if (!isset($this->objects[$hash])) {
            $this->objects[$hash] = new Blob($this, $hash);
        }

        return $this->objects[$hash];
    }

    public function getBlame($revision, $file, $lineRange = null)
    {
        if (is_string($revision)) {
            $revision = $this->getRevision($revision);
        }

        return new Blame($this, $revision, $file, $lineRange);
    }

    /**
     * Returns log for a given set of revisions and paths.
     *
     * All those values can be null, meaning everything.
     *
     * @param array $revisions An array of revisions to show logs from. Can be
     *                         any text value type
     * @param array $paths     Restrict log to modifications occurring on given
     *                         paths.
     * @param int   $offset    Start from a given offset in results.
     * @param int   $limit     Limit number of total results.
     *
     * @return Log
     */
    public function getLog($revisions = null, $paths = null, $offset = null, $limit = null)
    {
        return new Log($this, $revisions, $paths, $offset, $limit);
    }

    /**
     * @return Diff
     */
    public function getDiff($revisions)
    {
        if (null !== $revisions && !$revisions instanceof RevisionList) {
            $revisions = new RevisionList($this, $revisions);
        }

        $args = array_merge(array('-r', '-p', '-m', '-M', '--no-commit-id', '--full-index'), $revisions->getAsTextArray());

        $diff = Diff::parse($this->run('diff', $args));
        $diff->setRepository($this);

        return $diff;
    }

    /**
     * Returns the size of repository, in kilobytes.
     *
     * @return int A sum, in kilobytes
     *
     * @throws RuntimeException An error occurred while computing size
     */
    public function getSize()
    {
        $process = ProcessBuilder::create(array('du', '-skc', $this->gitDir))->getProcess();

        $process->run();

        if (!preg_match('/(\d+)\s+total$/', trim($process->getOutput()), $vars)) {
            $message = sprintf("Unable to parse process output\ncommand: %s\noutput: %s", $process->getCommandLine(), $process->getOutput());

            if (null !== $this->logger) {
                $this->logger->error($message);
            }

            if (true === $this->debug) {
                throw new RuntimeException('unable to parse repository size output');
            }

            return;
        }

        return $vars[1];
    }

    /**
     * Executes a shell command on the repository, using PHP pipes.
     *
     * @param string $command The command to execute
     */
    public function shell($command, array $env = array())
    {
        $argument = sprintf('%s \'%s\'', $command, $this->gitDir);

        $prefix = '';
        foreach ($env as $name => $value) {
            $prefix .= sprintf('export %s=%s;', escapeshellarg($name), escapeshellarg($value));
        }

        proc_open($prefix.'git shell -c '.escapeshellarg($argument), array(STDIN, STDOUT, STDERR), $pipes);
    }

    /**
     * Returns the hooks object.
     *
     * @return Hooks
     */
    public function getHooks()
    {
        return new Hooks($this);
    }

    /**
     * Returns description of repository from description file in git directory.
     *
     * @return string The description
     */
    public function getDescription()
    {
        $file = $this->gitDir.'/description';
        $exists = is_file($file);

        if (null !== $this->logger && true === $this->debug) {
            if (false === $exists) {
                $this->logger->debug(sprintf('no description file in repository ("%s")', $file));
            } else {
                $this->logger->debug(sprintf('reading description file in repository ("%s")', $file));
            }
        }

        if (false === $exists) {
            return static::DEFAULT_DESCRIPTION;
        }

        return file_get_contents($this->gitDir.'/description');
    }

    /**
     * Tests if repository has a custom set description.
     *
     * @return bool
     */
    public function hasDescription()
    {
        return static::DEFAULT_DESCRIPTION !== $this->getDescription();
    }

    /**
     * Changes the repository description (file description in git-directory).
     *
     * @return Repository the current repository
     */
    public function setDescription($description)
    {
        $file = $this->gitDir.'/description';

        if (null !== $this->logger && true === $this->debug) {
            $this->logger->debug(sprintf('change description file content to "%s" (file: %s)', $description, $file));
        }
        file_put_contents($file, $description);

        return $this;
    }

    /**
     * This command is a facility command. You can run any command
     * directly on git repository.
     *
     * @param string $command Git command to run (checkout, branch, tag)
     * @param array  $args    Arguments of git command
     *
     * @return string Output of a successful process or null if execution failed and debug-mode is disabled.
     *
     * @throws RuntimeException Error while executing git command (debug-mode only)
     */
    public function run($command, $args = array())
    {
        $process = $this->getProcess($command, $args);

        if ($this->logger) {
            $this->logger->info(sprintf('run command: %s "%s" ', $command, implode(' ', $args)));
            $before = microtime(true);
        }

        $process->run();

        $output = $process->getOutput();

        if ($this->logger && $this->debug) {
            $duration = microtime(true) - $before;
            $this->logger->debug(sprintf('last command (%s) duration: %sms', $command, sprintf('%.2f', $duration * 1000)));
            $this->logger->debug(sprintf('last command (%s) return code: %s', $command, $process->getExitCode()));
            $this->logger->debug(sprintf('last command (%s) output: %s', $command, $output));
        }

        if (!$process->isSuccessful()) {
            $error = sprintf("error while running %s\n output: \"%s\"", $command, $process->getErrorOutput());

            if ($this->logger) {
                $this->logger->error($error);
            }

            if ($this->debug) {
                throw new ProcessException($process);
            }

            return;
        }

        return $output;
    }

    /**
     * Set repository logger.
     *
     * @param LoggerInterface $logger A logger
     *
     * @return Repository The current repository
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * Returns repository logger.
     *
     * @return LoggerInterface the logger or null
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Clones the current repository to a new directory and return instance of new repository.
     *
     * @param string $path path to the new repository in which current repository will be cloned
     * @param bool   $bare flag indicating if repository is bare or has a working-copy
     *
     * @return Repository the newly created repository
     */
    public function cloneTo($path, $bare = true, array $options = array())
    {
        return Admin::cloneTo($path, $this->gitDir, $bare, $options);
    }

    /**
     * This internal method is used to create a process object.
     *
     * Made private to be sure that process creation is handled through the run method.
     * run method ensures logging and debug.
     *
     * @see self::run
     */
    private function getProcess($command, $args = array())
    {
        $base = array($this->command, '--git-dir', $this->gitDir);

        if ($this->workingDir) {
            $base = array_merge($base, array('--work-tree', $this->workingDir));
        }

        $base[] = $command;

        $builder = new ProcessBuilder(array_merge($base, $args));
        $builder->inheritEnvironmentVariables(false);
        $process = $builder->getProcess();
        $process->setEnv($this->environmentVariables);
        $process->setTimeout($this->processTimeout);
        $process->setIdleTimeout($this->processTimeout);

        return $process;
    }
}
