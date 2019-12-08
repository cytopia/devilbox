<?php

/**
 * `PURGE` statement.
 */

namespace PhpMyAdmin\SqlParser\Statements;

use PhpMyAdmin\SqlParser\Components\Expression;
use PhpMyAdmin\SqlParser\Components\OptionsArray;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Statement;
use PhpMyAdmin\SqlParser\Token;
use PhpMyAdmin\SqlParser\TokensList;

/**
 * `PURGE` statement.
 *
 * PURGE { BINARY | MASTER } LOGS
 *   { TO 'log_name' | BEFORE datetime_expr }
 *
 * @category   Statements
 *
 * @license    https://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 */
class PurgeStatement extends Statement
{
    /**
     * The type of logs
     *
     * @var String
     */
    public $log_type;

    /**
     * The end option of this query.
     *
     * @var String
     */
    public $end_option;

    /**
     * The end expr of this query.
     *
     * @var String
     */
    public $end_expr;

    /**
     * @return string
     */
    public function build()
    {
        $ret = 'PURGE ' . $this->log_type . ' ' . 'LOGS '
            . ($this->end_option !== null ? ($this->end_option . ' ' . $this->end_expr) : '');
        return trim($ret);
    }

    /**
     * @param Parser     $parser the instance that requests parsing
     * @param TokensList $list   the list of tokens to be parsed
     */
    public function parse(Parser $parser, TokensList $list)
    {
        ++$list->idx; // Skipping `PURGE`.

        /**
         * The state of the parser.
         *
         * @var int
         */
        $state = 0;

        for (; $list->idx < $list->count; ++$list->idx) {
            /**
             * Token parsed at this moment.
             *
             * @var Token
             */
            $token = $list->tokens[$list->idx];

            // End of statement.
            if ($token->type === Token::TYPE_DELIMITER) {
                break;
            }

            // Skipping whitespaces and comments.
            if (($token->type === Token::TYPE_WHITESPACE) || ($token->type === Token::TYPE_COMMENT)) {
                continue;
            }

            switch ($state) {
                case 0:
                    // parse `{ BINARY | MASTER }`
                    $this->log_type = self::parseExpectedKeyword($parser, $token, array('BINARY', 'MASTER'));
                    break;
                case 1:
                    // parse `LOGS`
                    self::parseExpectedKeyword($parser, $token, array('LOGS'));
                    break;
                case 2:
                    // parse `{ TO | BEFORE }`
                    $this->end_option = self::parseExpectedKeyword($parser, $token, array('TO', 'BEFORE'));
                    break;
                case 3:
                    // parse `expr`
                    $this->end_expr = Expression::parse($parser, $list, array());
                    break;
                default:
                    $parser->error('Unexpected token.', $token);
                    break;
            }
            $state++;
            $prevToken = $token;
        }

        // Only one possible end state
        if ($state != 4) {
            $parser->error('Unexpected token.', $prevToken);
        }
    }

    /**
     * Parse expected keyword (or throw relevant error)
     *
     * @param Parser $parser            the instance that requests parsing
     * @param Token  $token             token to be parsed
     * @param Array  $expected_keywords array of possibly expected keywords at this point
     */
    private static function parseExpectedKeyword($parser, $token, $expected_keywords)
    {
        if ($token->type === Token::TYPE_KEYWORD) {
            if (in_array($token->keyword, $expected_keywords)) {
                return $token->keyword;
            } else {
                $parser->error('Unexpected keyword', $token);
            }
        } else {
            $parser->error('Unexpected token.', $token);
        }
        return null;
    }
}
