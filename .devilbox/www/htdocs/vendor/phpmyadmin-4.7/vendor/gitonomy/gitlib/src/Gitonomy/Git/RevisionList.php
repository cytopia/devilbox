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
class RevisionList implements \IteratorAggregate, \Countable
{
    protected $revisions;

    /**
     * Constructs a revision list from a variety of types.
     *
     * @param mixed $revisions can be a string, an array of strings or an array of Revision, Branch, Tag, Commit
     */
    public function __construct(Repository $repository, $revisions)
    {
        if (is_string($revisions)) {
            $revisions = array($repository->getRevision($revisions));
        } elseif ($revisions instanceof Revision) {
            $revisions = array($revisions);
        } elseif (!is_array($revisions)) {
            throw new \InvalidArgumentException(sprintf('Expected a string, a Revision or an array, got a "%s".', is_object($revisions) ? get_class($revisions) : gettype($revisions)));
        }

        if (count($revisions) == 0) {
            throw new \InvalidArgumentException(sprintf('Empty revision list not allowed'));
        }

        foreach ($revisions as $i => $revision) {
            if (is_string($revision)) {
                $revisions[$i] = new Revision($repository, $revision);
            } elseif (!$revision instanceof Revision) {
                throw new \InvalidArgumentException(sprintf('Expected a "Revision", got a "%s".', is_object($revision) ? get_class($revision) : gettype($revision)));
            }
        }

        $this->revisions = $revisions;
    }

    public function getAll()
    {
        return $this->revisions;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->revisions);
    }

    public function count()
    {
        return count($this->revisions);
    }

    public function getAsTextArray()
    {
        return array_map(function ($revision) {
            return $revision->getRevision();
        }, $this->revisions);
    }
}
