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

class ReferenceParser extends ParserBase
{
    public $references;

    protected function doParse()
    {
        $this->references = array();

        while (!$this->isFinished()) {
            $hash = $this->consumeHash();
            $this->consume(' ');
            $name = $this->consumeTo("\n");
            $this->consumeNewLine();
            $this->references[] = array($hash, $name);
        }
    }
}
