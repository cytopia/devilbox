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

use Gitonomy\Git\Blame\Line;
use Gitonomy\Git\Exception\InvalidArgumentException;
use Gitonomy\Git\Parser\BlameParser;

/**
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class Blame implements \Countable
{
    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @var Revision
     */
    protected $revision;

    /**
     * @var string
     */
    protected $file;

    /**
     * @var string|null
     */
    protected $lineRange;

    /**
     * @var array|null
     */
    protected $lines;

    /**
     * @param string $lineRange Argument to pass to git blame (-L).
     *                          Can be a line range (40,60 or 40,+21)
     *                          or a regexp ('/^function$/')
     */
    public function __construct(Repository $repository, Revision $revision, $file, $lineRange = null)
    {
        $this->repository = $repository;
        $this->revision = $revision;
        $this->lineRange = $lineRange;
        $this->file = $file;
    }

    /**
     * @return Line
     */
    public function getLine($number)
    {
        if ($number < 1) {
            throw new InvalidArgumentException('Line number should be at least 1');
        }

        $lines = $this->getLines();

        if (!isset($lines[$number])) {
            throw new InvalidArgumentException('Line does not exist');
        }

        return $lines[$number];
    }

    /**
     * Returns lines grouped by commit.
     *
     * @return array a list of two-elements array (commit, lines)
     */
    public function getGroupedLines()
    {
        $result = array();
        $commit = null;
        $current = array();

        foreach ($this->getLines() as $lineNumber => $line) {
            if ($commit !== $line->getCommit()) {
                if (count($current)) {
                    $result[] = array($commit, $current);
                }
                $commit = $line->getCommit();
                $current = array();
            }

            $current[$lineNumber] = $line;
        }

        if (count($current)) {
            $result[] = array($commit, $current);
        }

        return $result;
    }

    /**
     * Returns all lines of the blame.
     *
     * @return array
     */
    public function getLines()
    {
        if (null !== $this->lines) {
            return $this->lines;
        }

        $args = array('-p');

        if (null !== $this->lineRange) {
            $args[] = '-L';
            $args[] = $this->lineRange;
        }

        $args[] = $this->revision->getRevision();
        $args[] = '--';
        $args[] = $this->file;

        $parser = new BlameParser($this->repository);
        $parser->parse($this->repository->run('blame', $args));
        $this->lines = $parser->lines;

        return $this->lines;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->getLines());
    }
}
