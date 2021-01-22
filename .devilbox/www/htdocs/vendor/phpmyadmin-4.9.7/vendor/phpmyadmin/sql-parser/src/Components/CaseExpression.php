<?php

/**
 * Parses a reference to a CASE expression.
 */

namespace PhpMyAdmin\SqlParser\Components;

use PhpMyAdmin\SqlParser\Component;
use PhpMyAdmin\SqlParser\Context;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Token;
use PhpMyAdmin\SqlParser\TokensList;

/**
 * Parses a reference to a CASE expression.
 *
 * @category   Components
 *
 * @license    https://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 */
class CaseExpression extends Component
{
    /**
     * The value to be compared.
     *
     * @var Expression
     */
    public $value;

    /**
     * The conditions in WHEN clauses.
     *
     * @var array
     */
    public $conditions = array();

    /**
     * The results matching with the WHEN clauses.
     *
     * @var array
     */
    public $results = array();

    /**
     * The values to be compared against.
     *
     * @var array
     */
    public $compare_values = array();

    /**
     * The result in ELSE section of expr.
     *
     * @var Expression
     */
    public $else_result;

    /**
     * The alias of this CASE statement.
     *
     * @var string
     */
    public $alias;

    /**
     * The sub-expression.
     *
     * @var string
     */
    public $expr = '';

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param Parser     $parser  the parser that serves as context
     * @param TokensList $list    the list of tokens that are being parsed
     * @param array      $options parameters for parsing
     *
     * @return CaseExpression
     */
    public static function parse(Parser $parser, TokensList $list, array $options = array())
    {
        $ret = new self();

        /**
         * State of parser.
         *
         * @var int
         */
        $state = 0;

        /**
         * Syntax type (type 0 or type 1).
         *
         * @var int
         */
        $type = 0;

        ++$list->idx; // Skip 'CASE'

        for (; $list->idx < $list->count; ++$list->idx) {
            /**
             * Token parsed at this moment.
             *
             * @var Token
             */
            $token = $list->tokens[$list->idx];

            // Skipping whitespaces and comments.
            if (($token->type === Token::TYPE_WHITESPACE)
                || ($token->type === Token::TYPE_COMMENT)
            ) {
                continue;
            }

            if ($state === 0) {
                if ($token->type === Token::TYPE_KEYWORD) {
                    switch ($token->keyword) {
                        case 'WHEN':
                            ++$list->idx; // Skip 'WHEN'
                            $new_condition = Condition::parse($parser, $list);
                            $type = 1;
                            $state = 1;
                            $ret->conditions[] = $new_condition;
                            break;
                        case 'ELSE':
                            ++$list->idx; // Skip 'ELSE'
                            $ret->else_result = Expression::parse($parser, $list);
                            $state = 0; // last clause of CASE expression
                            break;
                        case 'END':
                            $state = 3; // end of CASE expression
                            ++$list->idx;
                            break 2;
                        default:
                            $parser->error('Unexpected keyword.', $token);
                            break 2;
                    }
                } else {
                    $ret->value = Expression::parse($parser, $list);
                    $type = 0;
                    $state = 1;
                }
            } elseif ($state === 1) {
                if ($type === 0) {
                    if ($token->type === Token::TYPE_KEYWORD) {
                        switch ($token->keyword) {
                            case 'WHEN':
                                ++$list->idx; // Skip 'WHEN'
                                $new_value = Expression::parse($parser, $list);
                                $state = 2;
                                $ret->compare_values[] = $new_value;
                                break;
                            case 'ELSE':
                                ++$list->idx; // Skip 'ELSE'
                                $ret->else_result = Expression::parse($parser, $list);
                                $state = 0; // last clause of CASE expression
                                break;
                            case 'END':
                                $state = 3; // end of CASE expression
                                ++$list->idx;
                                break 2;
                            default:
                                $parser->error('Unexpected keyword.', $token);
                                break 2;
                        }
                    }
                } else {
                    if ($token->type === Token::TYPE_KEYWORD
                        && $token->keyword === 'THEN'
                    ) {
                        ++$list->idx; // Skip 'THEN'
                        $new_result = Expression::parse($parser, $list);
                        $state = 0;
                        $ret->results[] = $new_result;
                    } elseif ($token->type === Token::TYPE_KEYWORD) {
                        $parser->error('Unexpected keyword.', $token);
                        break;
                    }
                }
            } elseif ($state === 2) {
                if ($type === 0) {
                    if ($token->type === Token::TYPE_KEYWORD
                        && $token->keyword === 'THEN'
                    ) {
                        ++$list->idx; // Skip 'THEN'
                        $new_result = Expression::parse($parser, $list);
                        $ret->results[] = $new_result;
                        $state = 1;
                    } elseif ($token->type === Token::TYPE_KEYWORD) {
                        $parser->error('Unexpected keyword.', $token);
                        break;
                    }
                }
            }
        }

        if ($state !== 3) {
            $parser->error(
                'Unexpected end of CASE expression',
                $list->tokens[$list->idx - 1]
            );
        } else {
            // Parse for alias of CASE expression
            $asFound = false;
            for (; $list->idx < $list->count; ++$list->idx) {
                $token = $list->tokens[$list->idx];

                // End of statement.
                if ($token->type === Token::TYPE_DELIMITER) {
                    break;
                }

                // Skipping whitespaces and comments.
                if (($token->type === Token::TYPE_WHITESPACE)
                    || ($token->type === Token::TYPE_COMMENT)
                ) {
                    continue;
                }

                // Handle optional AS keyword before alias
                if ($token->type === Token::TYPE_KEYWORD
                    && $token->keyword === 'AS') {
                    if ($asFound || ! empty($ret->alias)) {
                        $parser->error('Potential duplicate alias of CASE expression.', $token);
                        break;
                    }
                    $asFound = true;
                    continue;
                }

                if ($asFound
                    && $token->type === Token::TYPE_KEYWORD
                    && ($token->flags & Token::FLAG_KEYWORD_RESERVED || $token->flags & Token::FLAG_KEYWORD_FUNCTION)) {
                    $parser->error('An alias expected after AS but got ' . $token->value, $token);
                    $asFound = false;
                    break;
                }

                if ($asFound
                    || $token->type === Token::TYPE_STRING
                    || ($token->type === Token::TYPE_SYMBOL && ! $token->flags & Token::FLAG_SYMBOL_VARIABLE)
                    || $token->type === Token::TYPE_NONE
                ) {
                    // An alias is expected (the keyword `AS` was previously found).
                    if (! empty($ret->alias)) {
                        $parser->error('An alias was previously found.', $token);
                        break;
                    }
                    $ret->alias = $token->value;
                    $asFound = false;

                    continue;
                }

                break;
            }
            if ($asFound) {
                $parser->error('An alias was expected after AS.', $list->tokens[$list->idx - 1]);
            }

            $ret->expr = self::build($ret);
        }

        --$list->idx;

        return $ret;
    }

    /**
     * @param CaseExpression $component the component to be built
     * @param array          $options   parameters for building
     *
     * @return string
     */
    public static function build($component, array $options = array())
    {
        $ret = 'CASE ';
        if (isset($component->value)) {
            // Syntax type 0
            $ret .= $component->value . ' ';
            $val_cnt = count($component->compare_values);
            $res_cnt = count($component->results);
            for ($i = 0; $i < $val_cnt && $i < $res_cnt; ++$i) {
                $ret .= 'WHEN ' . $component->compare_values[$i] . ' ';
                $ret .= 'THEN ' . $component->results[$i] . ' ';
            }
        } else {
            // Syntax type 1
            $val_cnt = count($component->conditions);
            $res_cnt = count($component->results);
            for ($i = 0; $i < $val_cnt && $i < $res_cnt; ++$i) {
                $ret .= 'WHEN ' . Condition::build($component->conditions[$i]) . ' ';
                $ret .= 'THEN ' . $component->results[$i] . ' ';
            }
        }
        if (isset($component->else_result)) {
            $ret .= 'ELSE ' . $component->else_result . ' ';
        }
        $ret .= 'END';

        if ($component->alias) {
            $ret .= ' AS ' . Context::escape($component->alias);
        }

        return $ret;
    }
}
