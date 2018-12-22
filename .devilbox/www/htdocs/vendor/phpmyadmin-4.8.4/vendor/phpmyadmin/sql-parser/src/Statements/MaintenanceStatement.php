<?php

/**
 * Maintenance statement.
 */

namespace PhpMyAdmin\SqlParser\Statements;

use PhpMyAdmin\SqlParser\Components\Expression;
use PhpMyAdmin\SqlParser\Components\OptionsArray;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Statement;
use PhpMyAdmin\SqlParser\Token;
use PhpMyAdmin\SqlParser\TokensList;

/**
 * Maintenance statement.
 *
 * They follow the syntax:
 *     STMT [some options] tbl_name [, tbl_name] ... [some more options]
 *
 * @category   Statements
 *
 * @license    https://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 */
class MaintenanceStatement extends Statement
{
    /**
     * Tables maintained.
     *
     * @var Expression[]
     */
    public $tables;

    /**
     * Function called after the token was processed.
     *
     * Parses the additional options from the end.
     *
     * @param Parser     $parser the instance that requests parsing
     * @param TokensList $list   the list of tokens to be parsed
     * @param Token      $token  the token that is being parsed
     */
    public function after(Parser $parser, TokensList $list, Token $token)
    {
        // [some options] is going to be parsed first.
        //
        // There is a parser specified in `Parser::$KEYWORD_PARSERS`
        // which parses the name of the tables.
        //
        // Finally, we parse here [some more options] and that's all.
        ++$list->idx;
        $this->options->merge(
            OptionsArray::parse(
                $parser,
                $list,
                static::$OPTIONS
            )
        );
    }
}
