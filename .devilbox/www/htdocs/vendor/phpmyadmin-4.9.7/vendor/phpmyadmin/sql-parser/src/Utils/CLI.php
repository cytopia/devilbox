<?php

/**
 * CLI interface.
 */

namespace PhpMyAdmin\SqlParser\Utils;

use PhpMyAdmin\SqlParser\Context;
use PhpMyAdmin\SqlParser\Lexer;
use PhpMyAdmin\SqlParser\Parser;

/**
 * CLI interface.
 *
 * @category   Exceptions
 *
 * @license    https://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 */
class CLI
{
    public function mergeLongOpts(&$params, &$longopts)
    {
        foreach ($longopts as $value) {
            $value = rtrim($value, ':');
            if (isset($params[$value])) {
                $params[$value[0]] = $params[$value];
            }
        }
    }

    public function usageHighlight()
    {
        echo "Usage: highlight-query --query SQL [--format html|cli|text] [--ansi]\n";
        echo "       cat file.sql | highlight-query\n";
    }

    public function getopt($opt, $long)
    {
        return getopt($opt, $long);
    }

    public function parseHighlight()
    {
        $longopts = array(
            'help',
            'query:',
            'format:',
            'ansi'
        );
        $params = $this->getopt(
            'hq:f:a',
            $longopts
        );
        if ($params === false) {
            return false;
        }
        $this->mergeLongOpts($params, $longopts);
        if (! isset($params['f'])) {
            $params['f'] = 'cli';
        }
        if (! in_array($params['f'], array('html', 'cli', 'text'))) {
            echo "ERROR: Invalid value for format!\n";

            return false;
        }

        return $params;
    }

    public function runHighlight()
    {
        $params = $this->parseHighlight();
        if ($params === false) {
            return 1;
        }
        if (isset($params['h'])) {
            $this->usageHighlight();

            return 0;
        }
        if (!isset($params['q'])) {
            if ($stdIn = $this->readStdin()) {
                $params['q'] = $stdIn;
            }
        }

        if (isset($params['a'])) {
            Context::setMode('ANSI_QUOTES');
        }
        if (isset($params['q'])) {
            echo Formatter::format(
                $params['q'],
                array('type' => $params['f'])
            );
            echo "\n";

            return 0;
        }
        echo "ERROR: Missing parameters!\n";
        $this->usageHighlight();

        return 1;
    }

    public function usageLint()
    {
        echo "Usage: lint-query --query SQL [--ansi]\n";
        echo "       cat file.sql | lint-query\n";
    }

    public function parseLint()
    {
        $longopts = array(
            'help',
            'query:',
            'context:',
            'ansi'
        );
        $params = $this->getopt(
            'hq:c:a',
            $longopts
        );
        $this->mergeLongOpts($params, $longopts);

        return $params;
    }

    public function runLint()
    {
        $params = $this->parseLint();
        if ($params === false) {
            return 1;
        }
        if (isset($params['h'])) {
            $this->usageLint();

            return 0;
        }
        if (isset($params['c'])) {
            Context::load($params['c']);
        }
        if (!isset($params['q'])) {
            if ($stdIn = $this->readStdin()) {
                $params['q'] = $stdIn;
            }
        }
        if (isset($params['a'])) {
            Context::setMode('ANSI_QUOTES');
        }

        if (isset($params['q'])) {
            $lexer = new Lexer($params['q'], false);
            $parser = new Parser($lexer->list);
            $errors = Error::get(array($lexer, $parser));
            if (count($errors) === 0) {
                return 0;
            }
            $output = Error::format($errors);
            echo implode("\n", $output);
            echo "\n";

            return 10;
        }
        echo "ERROR: Missing parameters!\n";
        $this->usageLint();

        return 1;
    }

    public function usageTokenize()
    {
        echo "Usage: tokenize-query --query SQL [--ansi]\n";
        echo "       cat file.sql | tokenize-query\n";
    }

    public function parseTokenize()
    {
        $longopts = array(
            'help',
            'query:',
            'ansi'
        );
        $params = $this->getopt(
            'hq:a',
            $longopts
        );
        $this->mergeLongOpts($params, $longopts);

        return $params;
    }

    public function runTokenize()
    {
        $params = $this->parseTokenize();
        if ($params === false) {
            return 1;
        }
        if (isset($params['h'])) {
            $this->usageTokenize();

            return 0;
        }
        if (!isset($params['q'])) {
            if ($stdIn = $this->readStdin()) {
                $params['q'] = $stdIn;
            }
        }

        if (isset($params['a'])) {
            Context::setMode('ANSI_QUOTES');
        }
        if (isset($params['q'])) {
            $lexer = new Lexer($params['q'], false);
            foreach ($lexer->list->tokens as $idx => $token) {
                echo '[TOKEN ', $idx, "]\n";
                echo 'Type = ', $token->type, "\n";
                echo 'Flags = ', $token->flags, "\n";
                echo 'Value = ';
                var_export($token->value);
                echo "\n";
                echo 'Token = ';
                var_export($token->token);
                echo "\n";
                echo "\n";
            }

            return 0;
        }
        echo "ERROR: Missing parameters!\n";
        $this->usageTokenize();

        return 1;
    }

    public function readStdin()
    {
        $read = array(STDIN);
        $write = array();
        $except = array();

        // Assume there's nothing to be read from STDIN.
        $stdin = null;

        // Try to read from STDIN.  Wait 0.2 second before timing out.
        $result = stream_select($read, $write, $except, 0, 2000);

        if ($result > 0) {
            $stdin = stream_get_contents(STDIN);
        }

        return $stdin;
    }
}
