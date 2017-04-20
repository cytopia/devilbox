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

use Gitonomy\Git\Diff\File;
use Gitonomy\Git\Diff\FileChange;

class DiffParser extends ParserBase
{
    public $files;

    protected function doParse()
    {
        $this->files = array();

        while (!$this->isFinished()) {
            // 1. title
            $vars = $this->consumeRegexp('/diff --git (a\/.*) (b\/.*)\n/');
            $oldName = $vars[1];
            $newName = $vars[2];
            $oldIndex = null;
            $newIndex = null;
            $oldMode = null;
            $newMode = null;

            // 2. mode
            if ($this->expects('new file mode ')) {
                $newMode = $this->consumeTo("\n");
                $this->consumeNewLine();
                $oldMode = null;
            }
            if ($this->expects('old mode ')) {
                $oldMode = $this->consumeTo("\n");
                $this->consumeNewLine();
                $this->consume('new mode ');
                $newMode = $this->consumeTo("\n");
                $this->consumeNewLine();
            }
            if ($this->expects('deleted file mode ')) {
                $oldMode = $this->consumeTo("\n");
                $newMode = null;
                $this->consumeNewLine();
            }

            if ($this->expects('similarity index ')) {
                $this->consumeRegexp('/\d{1,3}%\n/');
                $this->consume('rename from ');
                $this->consumeTo("\n");
                $this->consumeNewLine();
                $this->consume('rename to ');
                $this->consumeTo("\n");
                $this->consumeNewLine();
            }

            // 4. File informations
            $isBinary = false;
            if ($this->expects('index ')) {
                $oldIndex = $this->consumeHash();
                $this->consume('..');
                $newIndex = $this->consumeHash();
                if ($this->expects(' ')) {
                    $vars = $this->consumeRegexp('/\d{6}/');
                    $newMode = $oldMode = $vars[0];
                }
                $this->consumeNewLine();

                if ($this->expects('--- ')) {
                    $oldName = $this->consumeTo("\n");
                    $this->consumeNewLine();
                    $this->consume('+++ ');
                    $newName = $this->consumeTo("\n");
                    $this->consumeNewLine();
                } elseif ($this->expects('Binary files ')) {
                    $vars = $this->consumeRegexp('/(.*) and (.*) differ\n/');
                    $isBinary = true;
                    $oldName = $vars[1];
                    $newName = $vars[2];
                }
            }

            $oldName = $oldName === '/dev/null' ? null : substr($oldName, 2);
            $newName = $newName === '/dev/null' ? null : substr($newName, 2);
            $oldIndex = preg_match('/^0+$/', $oldIndex) ? null : $oldIndex;
            $newIndex = preg_match('/^0+$/', $newIndex) ? null : $newIndex;
            $file = new File($oldName, $newName, $oldMode, $newMode, $oldIndex, $newIndex, $isBinary);

            // 5. Diff
            while ($this->expects('@@ ')) {
                $vars = $this->consumeRegexp('/-(\d+)(?:,(\d+))? \+(\d+)(?:,(\d+))?/');
                $rangeOldStart = $vars[1];
                $rangeOldCount = $vars[2];
                $rangeNewStart = $vars[3];
                $rangeNewCount = isset($vars[4]) ? $vars[4] : $vars[2]; // @todo Ici, t'as pris un gros raccourci mon loulou
                $this->consume(' @@');
                $this->consumeTo("\n");
                $this->consumeNewLine();

                // 6. Lines
                $lines = array();
                while (true) {
                    if ($this->expects(' ')) {
                        $lines[] = array(FileChange::LINE_CONTEXT, $this->consumeTo("\n"));
                    } elseif ($this->expects('+')) {
                        $lines[] = array(FileChange::LINE_ADD, $this->consumeTo("\n"));
                    } elseif ($this->expects('-')) {
                        $lines[] = array(FileChange::LINE_REMOVE, $this->consumeTo("\n"));
                    } elseif ($this->expects("\ No newline at end of file")) {
                        // Ignore this case...
                    } else {
                        break;
                    }

                    $this->consumeNewLine();
                }

                $change = new FileChange($rangeOldStart, $rangeOldCount, $rangeNewStart, $rangeNewCount, $lines);

                $file->addChange($change);
            }

            $this->files[] = $file;
        }
    }
}
