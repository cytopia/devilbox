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
namespace Gitonomy\Git\Blame;

use Gitonomy\Git\Commit;

/**
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class Line
{
    /**
     * @var Commit
     */
    protected $commit;
    protected $sourceLine;
    protected $targetLine;
    protected $blockLine;
    protected $content;

    /**
     * Instanciates a new Line object.
     */
    public function __construct(Commit $commit, $sourceLine, $targetLine, $blockLine, $content)
    {
        $this->commit = $commit;
        $this->sourceLine = $sourceLine;
        $this->targetLine = $targetLine;
        $this->blockLine = $blockLine;
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getLine()
    {
        return $this->sourceLine;
    }

    public function getCommit()
    {
        return $this->commit;
    }
}
