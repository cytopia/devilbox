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

/**
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class Revision
{
    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $revision;

    public function __construct(Repository $repository, $revision)
    {
        $this->repository = $repository;
        $this->revision = $revision;
    }

    /**
     * @return Log
     */
    public function getLog($paths = null, $offset = null, $limit = null)
    {
        return $this->repository->getLog($this, $paths, $offset, $limit);
    }

    /**
     * Returns the last modification date of the reference.
     *
     * @return Commit
     */
    public function getCommit()
    {
        return $this->getLog()->getSingleCommit();
    }

    /**
     * @return string
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * @return Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }
}
