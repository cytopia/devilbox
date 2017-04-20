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
namespace Gitonomy\Git\Parser;

use Gitonomy\Git\Blame\Line;
use Gitonomy\Git\Repository;

class BlameParser extends ParserBase
{
    public $lines;

    protected $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    protected function doParse()
    {
        $this->lines = array();

        $memory = array();

        $line = 1;
        while (!$this->isFinished()) {
            $hash = $this->consumeHash();
            $this->consume(' ');
            $vars = $this->consumeRegexp('/(\d+) (\d+)( (\d+))?/A');
            $sourceLine = $vars[1];
            $targetLine = $vars[2];
            $blockLine = isset($vars[4]) ? $vars[4] : null;
            $this->consumeTo("\n");
            $this->consumeNewLine();

            if (!isset($memory[$hash])) {
                foreach (array('author', 'author-mail', 'author-time', 'author-tz',
                    'committer', 'committer-mail', 'committer-time', 'committer-tz',
                    'summary', ) as $key) {
                    $this->consume($key);
                    $this->consumeTo("\n");
                    $this->consumeNewLine();
                }

                if ($this->expects('previous ')) {
                    $this->consumeTo("\n");
                    $this->consumeNewLine();
                }

                if ($this->expects('boundary')) {
                    $this->consumeNewLine();
                }
                $this->consume('filename');
                $this->consumeTo("\n"); // filename
                $this->consumeNewLine();
                $memory[$hash] = $this->repository->getCommit($hash);
            }
            $content = $this->consumeTo("\n"); // content of line
            $this->consumeNewLine();

            $this->lines[$line] = new Line($memory[$hash], $sourceLine, $targetLine, $blockLine, $content);
            ++$line;
        }
    }
}
