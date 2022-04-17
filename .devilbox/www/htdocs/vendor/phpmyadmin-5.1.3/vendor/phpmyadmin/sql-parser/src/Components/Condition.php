<?php
/**
 * `WHERE` keyword parser.
 */

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Components;

use PhpMyAdmin\SqlParser\Component;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Token;
use PhpMyAdmin\SqlParser\TokensList;

use function implode;
use function in_array;
use function is_array;
use function trim;

/**
 * `WHERE` keyword parser.
 *
 * @final
 */
class Condition extends Component
{
    /**
     * Logical operators that can be used to delimit expressions.
     *
     * @var array
     */
    public static $DELIMITERS = [
        '&&',
        '||',
        'AND',
        'OR',
        'XOR',
    ];

    /**
     * List of allowed reserved keywords in conditions.
     *
     * @var array
     */
    public static $ALLOWED_KEYWORDS = [
        'ALL' => 1,
        'AND' => 1,
        'BETWEEN' => 1,
        'EXISTS' => 1,
        'IF' => 1,
        'IN' => 1,
        'INTERVAL' => 1,
        'IS' => 1,
        'LIKE' => 1,
        'MATCH' => 1,
        'NOT IN' => 1,
        'NOT NULL' => 1,
        'NOT' => 1,
        'NULL' => 1,
        'OR' => 1,
        'REGEXP' => 1,
        'RLIKE' => 1,
        'SOUNDS' => 1,
        'XOR' => 1,
    ];

    /**
     * Identifiers recognized.
     *
     * @var array
     */
    public $identifiers = [];

    /**
     * Whether this component is an operator.
     *
     * @var bool
     */
    public $isOperator = false;

    /**
     * The condition.
     *
     * @var string
     */
    public $expr;

    /**
     * @param string $expr the condition or the operator
     */
    public function __construct($expr = null)
    {
        $this->expr = trim((string) $expr);
    }

    /**
     * @param Parser     $parser  the parser that serves as context
     * @param TokensList $list    the list of tokens that are being parsed
     * @param array      $options parameters for parsing
     *
     * @return Condition[]
     */
    public static function parse(Parser $parser, TokensList $list, array $options = [])
    {
        $ret = [];

        $expr = new static();

        /**
         * Counts brackets.
         *
         * @var int
         */
        $brackets = 0;

        /**
         * Whether there was a `BETWEEN` keyword before or not.
         *
         * It is required to keep track of them because their structure contains
         * the keyword `AND`, which is also an operator that delimits
         * expressions.
         *
         * @var bool
         */
        $betweenBefore = false;

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
            if ($token->type === Token::TYPE_COMMENT) {
                continue;
            }

            // Replacing all whitespaces (new lines, tabs, etc.) with a single
            // space character.
            if ($token->type === Token::TYPE_WHITESPACE) {
                $expr->expr .= ' ';
                continue;
            }

            // Conditions are delimited by logical operators.
            if (in_array($token->value, static::$DELIMITERS, true)) {
                if ($betweenBefore && ($token->value === 'AND')) {
                    // The syntax of keyword `BETWEEN` is hard-coded.
                    $betweenBefore = false;
                } else {
                    // The expression ended.
                    $expr->expr = trim($expr->expr);
                    if (! empty($expr->expr)) {
                        $ret[] = $expr;
                    }

                    // Adding the operator.
                    $expr = new static($token->value);
                    $expr->isOperator = true;
                    $ret[] = $expr;

                    // Preparing to parse another condition.
                    $expr = new static();
                    continue;
                }
            }

            if (
                ($token->type === Token::TYPE_KEYWORD)
                && ($token->flags & Token::FLAG_KEYWORD_RESERVED)
                && ! ($token->flags & Token::FLAG_KEYWORD_FUNCTION)
            ) {
                if ($token->value === 'BETWEEN') {
                    $betweenBefore = true;
                }

                if (($brackets === 0) && empty(static::$ALLOWED_KEYWORDS[$token->value])) {
                    break;
                }
            }

            if ($token->type === Token::TYPE_OPERATOR) {
                if ($token->value === '(') {
                    ++$brackets;
                } elseif ($token->value === ')') {
                    if ($brackets === 0) {
                        break;
                    }

                    --$brackets;
                }
            }

            $expr->expr .= $token->token;
            if (
                ($token->type !== Token::TYPE_NONE)
                && (($token->type !== Token::TYPE_KEYWORD)
                || ($token->flags & Token::FLAG_KEYWORD_RESERVED))
                && ($token->type !== Token::TYPE_STRING)
                && ($token->type !== Token::TYPE_SYMBOL)
            ) {
                continue;
            }

            if (in_array($token->value, $expr->identifiers)) {
                continue;
            }

            $expr->identifiers[] = $token->value;
        }

        // Last iteration was not processed.
        $expr->expr = trim($expr->expr);
        if (! empty($expr->expr)) {
            $ret[] = $expr;
        }

        --$list->idx;

        return $ret;
    }

    /**
     * @param Condition[] $component the component to be built
     * @param array       $options   parameters for building
     *
     * @return string
     */
    public static function build($component, array $options = [])
    {
        if (is_array($component)) {
            return implode(' ', $component);
        }

        return $component->expr;
    }
}
