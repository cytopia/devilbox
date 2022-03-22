<?php
/**
 * `WITH` statement.
 */

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Statements;

use PhpMyAdmin\SqlParser\Components\Array2d;
use PhpMyAdmin\SqlParser\Components\OptionsArray;
use PhpMyAdmin\SqlParser\Components\WithKeyword;
use PhpMyAdmin\SqlParser\Exceptions\ParserException;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Statement;
use PhpMyAdmin\SqlParser\Token;
use PhpMyAdmin\SqlParser\TokensList;
use PhpMyAdmin\SqlParser\Translator;

use function array_slice;
use function count;

/**
 * `WITH` statement.

 *  WITH [RECURSIVE] query_name [ (column_name [,...]) ] AS (SELECT ...) [, ...]
 */
final class WithStatement extends Statement
{
    /**
     * Options for `WITH` statements and their slot ID.
     *
     * @var mixed[]
     */
    public static $OPTIONS = ['RECURSIVE' => 1];

    /**
     * The clauses of this statement, in order.
     *
     * @see Statement::$CLAUSES
     *
     * @var mixed[]
     */
    public static $CLAUSES = [
        'WITH' => [
            'WITH',
            2,
        ],
        // Used for options.
        '_OPTIONS' => [
            '_OPTIONS',
            1,
        ],
        'AS' => [
            'AS',
            2,
        ],
    ];

    /** @var WithKeyword[] */
    public $withers = [];

    /**
     * @param Parser     $parser the instance that requests parsing
     * @param TokensList $list   the list of tokens to be parsed
     */
    public function parse(Parser $parser, TokensList $list)
    {
        ++$list->idx; // Skipping `WITH`.

        // parse any options if provided
        $this->options = OptionsArray::parse($parser, $list, static::$OPTIONS);
        ++$list->idx;

        /**
         * The state of the parser.
         *
         * Below are the states of the parser.
         *
         *      0 ---------------- [ name ] -----------------> 1
         *      1 -------------- [( columns )] AS ----------------> 2
         *      2 ------------------ [ , ] --------------------> 0
         *
         * @var int
         */
        $state = 0;
        $wither = null;

        for (; $list->idx < $list->count; ++$list->idx) {
            /**
             * Token parsed at this moment.
             *
             * @var Token
             */
            $token = $list->tokens[$list->idx];

            // Skipping whitespaces and comments.
            if ($token->type === Token::TYPE_WHITESPACE || $token->type === Token::TYPE_COMMENT) {
                continue;
            }

            if ($token->type === Token::TYPE_NONE) {
                $wither = $token->value;
                $this->withers[$wither] = new WithKeyword($wither);
                $state = 1;
                continue;
            }

            if ($state === 1) {
                if ($token->value === '(') {
                    $this->withers[$wither]->columns = Array2d::parse($parser, $list);
                    continue;
                }

                if ($token->keyword === 'AS') {
                    ++$list->idx;
                    $state = 2;
                    continue;
                }
            } elseif ($state === 2) {
                if ($token->value === '(') {
                    ++$list->idx;
                    $subList = $this->getSubTokenList($list);
                    if ($subList instanceof ParserException) {
                        $parser->errors[] = $subList;
                        continue;
                    }

                    $subParser = new Parser($subList);

                    if (count($subParser->errors)) {
                        foreach ($subParser->errors as $error) {
                            $parser->errors[] = $error;
                        }
                    }

                    $this->withers[$wither]->statement = $subParser;
                    continue;
                }

                // There's another WITH expression to parse, go back to state=0
                if ($token->value === ',') {
                    $list->idx++;
                    $state = 0;
                    continue;
                }

                // No more WITH expressions, we're done with this statement
                break;
            }
        }

        --$list->idx;
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $str = 'WITH ';

        foreach ($this->withers as $wither) {
            $str .= $str === 'WITH ' ? '' : ', ';
            $str .= WithKeyword::build($wither);
        }

        return $str;
    }

    /**
     * Get tokens within the WITH expression to use them in another parser
     *
     * @return ParserException|TokensList
     */
    private function getSubTokenList(TokensList $list)
    {
        $idx = $list->idx;
        /** @var Token $token */
        $token = $list->tokens[$list->idx];
        $openParenthesis = 0;

        while ($list->idx < $list->count) {
            if ($token->value === '(') {
                ++$openParenthesis;
            } elseif ($token->value === ')') {
                if (--$openParenthesis === -1) {
                    break;
                }
            }

            ++$list->idx;
            if (! isset($list->tokens[$list->idx])) {
                break;
            }

            $token = $list->tokens[$list->idx];
        }

        // performance improvement: return the error to avoid a try/catch in the loop
        if ($list->idx === $list->count) {
            --$list->idx;

            return new ParserException(
                Translator::gettext('A closing bracket was expected.'),
                $token
            );
        }

        $length = $list->idx - $idx;

        return new TokensList(array_slice($list->tokens, $idx, $length), $length);
    }
}
