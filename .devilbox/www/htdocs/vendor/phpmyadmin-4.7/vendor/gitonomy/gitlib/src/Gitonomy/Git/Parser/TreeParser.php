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

class TreeParser extends ParserBase
{
    public $entries = array();

    protected function doParse()
    {
        while (!$this->isFinished()) {
            $vars = $this->consumeRegexp('/\d{6}/A');
            $mode = $vars[0];
            $this->consume(' ');

            $vars = $this->consumeRegexp('/(blob|tree|commit)/A');
            $type = $vars[0];
            $this->consume(' ');

            $hash = $this->consumeHash();
            $this->consume("\t");

            $name = $this->consumeTo("\n");
            $this->consumeNewLine();

            $this->entries[] = array($mode, $type, $hash, $name);
        }
    }
}
