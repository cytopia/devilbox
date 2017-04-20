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

use Gitonomy\Git\Exception\ProcessException;
use Gitonomy\Git\Exception\ReferenceNotFoundException;
use Gitonomy\Git\Util\StringHelper;

/**
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class Log implements \Countable, \IteratorAggregate
{
    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @var array
     */
    protected $revisions;

    /**
     * @var array
     */
    protected $paths;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @var int
     */
    protected $limit;

    /**
     * Instanciates a git log object.
     *
     * @param Repository   $repository the repository where log occurs
     * @param RevisionList $revisions  a list of revisions or null if you want all history
     * @param array        $paths      paths to filter on
     * @param int|null     $offset     start list from a given position
     * @param int|null     $limit      limit number of fetched elements
     */
    public function __construct(Repository $repository, $revisions = null, $paths = null, $offset = null, $limit = null)
    {
        if (null !== $revisions && !$revisions instanceof RevisionList) {
            $revisions = new RevisionList($repository, $revisions);
        }

        if (null === $paths) {
            $paths = array();
        } elseif (is_string($paths)) {
            $paths = array($paths);
        } elseif (!is_array($paths)) {
            throw new \InvalidArgumentException(sprintf('Expected a string or an array, got a "%s".', is_object($paths) ? get_class($paths) : gettype($paths)));
        }

        $this->repository = $repository;
        $this->revisions = $revisions;
        $this->paths = $paths;
        $this->offset = $offset;
        $this->limit = $limit;
    }

    /**
     * @return Diff
     */
    public function getDiff()
    {
        return $this->repository->getDiff($this->revisions);
    }

    /**
     * @return RevisionList
     */
    public function getRevisions()
    {
        return $this->revisions;
    }

    /**
     * @return array
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function getSingleCommit()
    {
        $limit = $this->limit;
        $this->limit = 1;
        $commits = $this->getCommits();
        $this->setLimit($limit);

        if (count($commits) === 0) {
            throw new ReferenceNotFoundException('The log is empty');
        }

        return array_pop($commits);
    }

    /**
     * @return array
     */
    public function getCommits()
    {
        $args = array('--encoding='.StringHelper::getEncoding(), '--format=raw');

        if (null !== $this->offset) {
            $args[] = '--skip='.((int) $this->offset);
        }

        if (null !== $this->limit) {
            $args[] = '-n';
            $args[] = (int) $this->limit;
        }

        if (null !== $this->revisions) {
            $args = array_merge($args, $this->revisions->getAsTextArray());
        } else {
            $args[] = '--all';
        }

        $args[] = '--';

        $args = array_merge($args, $this->paths);

        try {
            $output = $this->repository->run('log', $args);
        } catch (ProcessException $e) {
            throw new ReferenceNotFoundException(sprintf('Can not find revision "%s"', implode(' ', $this->revisions->getAsTextArray())), null, $e);
        }

        $parser = new Parser\LogParser();
        $parser->parse($output);

        $result = array();
        foreach ($parser->log as $commitData) {
            $hash = $commitData['id'];
            unset($commitData['id']);

            $commit = $this->repository->getCommit($hash);
            $commit->setData($commitData);

            $result[] = $commit;
        }

        return $result;
    }

    /**
     * @see Countable
     */
    public function count()
    {
        return $this->countCommits();
    }

    /**
     * @see IteratorAggregate
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getCommits());
    }

    /**
     * Count commits, without offset or limit.
     *
     * @return int
     */
    public function countCommits()
    {
        if (count($this->revisions)) {
            $output = $this->repository->run('rev-list', array_merge(array('--count'), $this->revisions->getAsTextArray(), array('--'), $this->paths));
        } else {
            $output = $this->repository->run('rev-list', array_merge(array('--count', '--all', '--'), $this->paths));
        }

        return (int) $output;
    }
}
