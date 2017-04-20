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

use Gitonomy\Git\Exception\ReferenceNotFoundException;
use Gitonomy\Git\Exception\RuntimeException;
use Gitonomy\Git\Reference\Branch;
use Gitonomy\Git\Reference\Stash;
use Gitonomy\Git\Reference\Tag;

/**
 * Reference set associated to a repository.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 * @author Julien DIDIER <genzo.wm@gmail.com>
 */
class ReferenceBag implements \Countable, \IteratorAggregate
{
    /**
     * Repository object.
     *
     * @var Gitonomy\Git\Repository
     */
    protected $repository;

    /**
     * Associative array of fullname references.
     *
     * @var array
     */
    protected $references;

    /**
     * List with all tags.
     *
     * @var array
     */
    protected $tags;

    /**
     * List with all branches.
     *
     * @var array
     */
    protected $branches;

    /**
     * A boolean indicating if the bag is already initialized.
     *
     * @var bool
     */
    protected $initialized = false;

    /**
     * Constructor.
     *
     * @param Gitonomy\Git\Repository $repository The repository
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
        $this->references = array();
        $this->tags = array();
        $this->branches = array();
    }

    /**
     * Returns a reference, by name.
     *
     * @param string $fullname Fullname of the reference (refs/heads/master, for example).
     *
     * @return Gitonomy\Git\Reference A reference object.
     */
    public function get($fullname)
    {
        $this->initialize();

        if (!isset($this->references[$fullname])) {
            throw new ReferenceNotFoundException($fullname);
        }

        return $this->references[$fullname];
    }

    public function has($fullname)
    {
        $this->initialize();

        return isset($this->references[$fullname]);
    }

    public function update(Reference $reference)
    {
        $fullname = $reference->getFullname();

        $this->initialize();
        $this->repository->run('update-ref', array($fullname, $reference->getCommitHash()));

        $this->references[$fullname] = $reference;

        return $reference;
    }

    public function createBranch($name, $commitHash)
    {
        $branch = new Branch($this->repository, 'refs/heads/'.$name, $commitHash);

        return $this->update($branch);
    }

    public function createTag($name, $commitHash)
    {
        $tag = new Tag($this->repository, 'refs/tags/'.$name, $commitHash);

        return $this->update($tag);
    }

    public function delete($fullname)
    {
        $this->repository->run('update-ref', array('-d', $fullname));

        unset($this->references[$fullname]);
    }

    public function hasBranches()
    {
        $this->initialize();

        return count($this->branches) > 0;
    }

    public function hasBranch($name)
    {
        return $this->has('refs/heads/'.$name);
    }

    public function hasRemoteBranch($name)
    {
        return $this->has('refs/remotes/'.$name);
    }

    public function hasTag($name)
    {
        return $this->has('refs/tags/'.$name);
    }

    public function getFirstBranch()
    {
        $this->initialize();
        reset($this->branches);

        return current($this->references);
    }

    /**
     * @return array An array of Tag objects
     */
    public function resolveTags($hash)
    {
        $this->initialize();

        if ($hash instanceof Commit) {
            $hash = $hash->getHash();
        }

        $tags = array();
        foreach ($this->references as $reference) {
            if ($reference instanceof Reference\Tag && $reference->getCommitHash() === $hash) {
                $tags[] = $reference;
            }
        }

        return $tags;
    }

    /**
     * @return array An array of Branch objects
     */
    public function resolveBranches($hash)
    {
        $this->initialize();

        if ($hash instanceof Commit) {
            $hash = $hash->getHash();
        }

        $branches = array();
        foreach ($this->references as $reference) {
            if ($reference instanceof Reference\Branch && $reference->getCommitHash() === $hash) {
                $branches[] = $reference;
            }
        }

        return $branches;
    }

    /**
     * @return array An array of references
     */
    public function resolve($hash)
    {
        $this->initialize();

        if ($hash instanceof Commit) {
            $hash = $hash->getHash();
        }

        $result = array();
        foreach ($this->references as $k => $reference) {
            if ($reference->getCommitHash() === $hash) {
                $result[] = $reference;
            }
        }

        return $result;
    }

    /**
     * Returns all tags.
     *
     * @return array
     */
    public function getTags()
    {
        $this->initialize();

        return $this->tags;
    }

    /**
     * Returns all branches.
     *
     * @return array
     */
    public function getBranches()
    {
        $this->initialize();

        $result = array();
        foreach ($this->references as $reference) {
            if ($reference instanceof Reference\Branch) {
                $result[] = $reference;
            }
        }

        return $result;
    }

    /**
     * Returns all locales branches.
     *
     * @return array
     */
    public function getLocalBranches()
    {
        $result = array();
        foreach ($this->getBranches() as $branch) {
            if ($branch->isLocal()) {
                $result[] = $branch;
            }
        }

        return $result;
    }

    /**
     * Returns all remote branches.
     *
     * @return array
     */
    public function getRemoteBranches()
    {
        $result = array();
        foreach ($this->getBranches() as $branch) {
            if ($branch->isRemote()) {
                $result[] = $branch;
            }
        }

        return $result;
    }

    /**
     * @return array An associative array with fullname as key (refs/heads/master, refs/tags/0.1)
     */
    public function getAll()
    {
        $this->initialize();

        return $this->references;
    }

    /**
     * @return Tag
     */
    public function getTag($name)
    {
        $this->initialize();

        return $this->get('refs/tags/'.$name);
    }

    /**
     * @return Branch
     */
    public function getBranch($name)
    {
        $this->initialize();

        return $this->get('refs/heads/'.$name);
    }

    /**
     * @return Branch
     */
    public function getRemoteBranch($name)
    {
        $this->initialize();

        return $this->get('refs/remotes/'.$name);
    }

    protected function initialize()
    {
        if (true === $this->initialized) {
            return;
        }
        $this->initialized = true;

        try {
            $parser = new Parser\ReferenceParser();
            $output = $this->repository->run('show-ref');
        } catch (RuntimeException $e) {
            $output = $e->getOutput();
            $error = $e->getErrorOutput();
            if ($error) {
                throw new RuntimeException('Error while getting list of references: '.$error);
            }
        }
        $parser->parse($output);

        foreach ($parser->references as $row) {
            list($commitHash, $fullname) = $row;

            if (preg_match('#^refs/(heads|remotes)/(.*)$#', $fullname)) {
                if (preg_match('#.*HEAD$#', $fullname)) {
                    continue;
                }
                $reference = new Branch($this->repository, $fullname, $commitHash);
                $this->references[$fullname] = $reference;
                $this->branches[] = $reference;
            } elseif (preg_match('#^refs/tags/(.*)$#', $fullname)) {
                $reference = new Tag($this->repository, $fullname, $commitHash);
                $this->references[$fullname] = $reference;
                $this->tags[] = $reference;
            } elseif ($fullname === 'refs/stash') {
                $reference = new Stash($this->repository, $fullname, $commitHash);
                $this->references[$fullname] = $reference;
            } elseif (preg_match('#^refs/pull/(.*)$#', $fullname)) {
                // Do nothing here
            } else {
                throw new RuntimeException(sprintf('Unable to parse "%s"', $fullname));
            }
        }
    }

    /**
     * @return int
     *
     * @see Countable
     */
    public function count()
    {
        $this->initialize();

        return count($this->references);
    }

    /**
     * @see IteratorAggregate
     */
    public function getIterator()
    {
        $this->initialize();

        return new \ArrayIterator($this->references);
    }
}
