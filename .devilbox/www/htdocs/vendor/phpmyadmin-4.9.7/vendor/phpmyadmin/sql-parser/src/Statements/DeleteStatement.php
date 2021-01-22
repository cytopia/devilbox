<?php

/**
 * `DELETE` statement.
 */

namespace PhpMyAdmin\SqlParser\Statements;

use PhpMyAdmin\SqlParser\Components\ArrayObj;
use PhpMyAdmin\SqlParser\Components\Condition;
use PhpMyAdmin\SqlParser\Components\Expression;
use PhpMyAdmin\SqlParser\Components\ExpressionArray;
use PhpMyAdmin\SqlParser\Components\JoinKeyword;
use PhpMyAdmin\SqlParser\Components\Limit;
use PhpMyAdmin\SqlParser\Components\OptionsArray;
use PhpMyAdmin\SqlParser\Components\OrderKeyword;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Statement;
use PhpMyAdmin\SqlParser\Token;
use PhpMyAdmin\SqlParser\TokensList;

/**
 * `DELETE` statement.
 *
 * DELETE [LOW_PRIORITY] [QUICK] [IGNORE] FROM tbl_name
 *     [PARTITION (partition_name,...)]
 *     [WHERE where_condition]
 *     [ORDER BY ...]
 *     [LIMIT row_count]
 *
 * Multi-table syntax
 *
 * DELETE [LOW_PRIORITY] [QUICK] [IGNORE]
 *   tbl_name[.*] [, tbl_name[.*]] ...
 *   FROM table_references
 *   [WHERE where_condition]
 *
 * OR
 *
 * DELETE [LOW_PRIORITY] [QUICK] [IGNORE]
 *   FROM tbl_name[.*] [, tbl_name[.*]] ...
 *   USING table_references
 *   [WHERE where_condition]
 *
 *
 * @category   Statements
 *
 * @license    https://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 */
class DeleteStatement extends Statement
{
    /**
     * Options for `DELETE` statements.
     *
     * @var array
     */
    public static $OPTIONS = array(
        'LOW_PRIORITY' => 1,
        'QUICK' => 2,
        'IGNORE' => 3
    );

    /**
     * The clauses of this statement, in order.
     *
     * @see Statement::$CLAUSES
     *
     * @var array
     */
    public static $CLAUSES = array(
        'DELETE' => array(
            'DELETE',
            2,
        ),
        // Used for options.
        '_OPTIONS' => array(
            '_OPTIONS',
            1,
        ),
        'FROM' => array(
            'FROM',
            3,
        ),
        'PARTITION' => array(
            'PARTITION',
            3,
        ),
        'USING' => array(
            'USING',
            3,
        ),
        'WHERE' => array(
            'WHERE',
            3,
        ),
        'ORDER BY' => array(
            'ORDER BY',
            3,
        ),
        'LIMIT' => array(
            'LIMIT',
            3,
        )
    );

    /**
     * Table(s) used as sources for this statement.
     *
     * @var Expression[]
     */
    public $from;

    /**
     * Joins.
     *
     * @var JoinKeyword[]
     */
    public $join;

    /**
     * Tables used as sources for this statement.
     *
     * @var Expression[]
     */
    public $using;

    /**
     * Columns used in this statement.
     *
     * @var Expression[]
     */
    public $columns;

    /**
     * Partitions used as source for this statement.
     *
     * @var ArrayObj
     */
    public $partition;

    /**
     * Conditions used for filtering each row of the result set.
     *
     * @var Condition[]
     */
    public $where;

    /**
     * Specifies the order of the rows in the result set.
     *
     * @var OrderKeyword[]
     */
    public $order;

    /**
     * Conditions used for limiting the size of the result set.
     *
     * @var Limit
     */
    public $limit;

    /**
     * @return string
     */
    public function build()
    {
        $ret = 'DELETE ' . OptionsArray::build($this->options);

        if (! is_null($this->columns) && count($this->columns) > 0) {
            $ret .= ' ' . ExpressionArray::build($this->columns);
        }
        if (! is_null($this->from) && count($this->from) > 0) {
            $ret .= ' FROM ' . ExpressionArray::build($this->from);
        }
        if (! is_null($this->join) && count($this->join) > 0) {
            $ret .= ' ' . JoinKeyword::build($this->join);
        }
        if (! is_null($this->using) && count($this->using) > 0) {
            $ret .= ' USING ' . ExpressionArray::build($this->using);
        }
        if (! is_null($this->where) && count($this->where) > 0) {
            $ret .= ' WHERE ' . Condition::build($this->where);
        }
        if (! is_null($this->order) && count($this->order) > 0) {
            $ret .= ' ORDER BY ' . ExpressionArray::build($this->order);
        }
        if (! is_null($this->limit) && strlen($this->limit) > 0) {
            $ret .= ' LIMIT ' . Limit::build($this->limit);
        }

        return $ret;
    }

    /**
     * @param Parser     $parser the instance that requests parsing
     * @param TokensList $list   the list of tokens to be parsed
     */
    public function parse(Parser $parser, TokensList $list)
    {
        ++$list->idx; // Skipping `DELETE`.

        // parse any options if provided
        $this->options = OptionsArray::parse(
            $parser,
            $list,
            static::$OPTIONS
        );
        ++$list->idx;

        /**
         * The state of the parser.
         *
         * Below are the states of the parser.
         *
         *      0 ---------------------------------[ FROM ]----------------------------------> 2
         *      0 ------------------------------[ table[.*] ]--------------------------------> 1
         *      1 ---------------------------------[ FROM ]----------------------------------> 2
         *      2 --------------------------------[ USING ]----------------------------------> 3
         *      2 --------------------------------[ WHERE ]----------------------------------> 4
         *      2 --------------------------------[ ORDER ]----------------------------------> 5
         *      2 --------------------------------[ LIMIT ]----------------------------------> 6
         *
         * @var int
         */
        $state = 0;

        /**
         * If the query is multi-table or not.
         *
         * @var bool
         */
        $multiTable = false;

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

            if ($state === 0) {
                if ($token->type === Token::TYPE_KEYWORD) {
                    if ($token->keyword !== 'FROM') {
                        $parser->error('Unexpected keyword.', $token);
                        break;
                    } else {
                        ++$list->idx; // Skip 'FROM'
                        $this->from = ExpressionArray::parse($parser, $list);

                        $state = 2;
                    }
                } else {
                    $this->columns = ExpressionArray::parse($parser, $list);
                    $state = 1;
                }
            } elseif ($state === 1) {
                if ($token->type === Token::TYPE_KEYWORD) {
                    if ($token->keyword !== 'FROM') {
                        $parser->error('Unexpected keyword.', $token);
                        break;
                    } else {
                        ++$list->idx; // Skip 'FROM'
                        $this->from = ExpressionArray::parse($parser, $list);

                        $state = 2;
                    }
                } else {
                    $parser->error('Unexpected token.', $token);
                    break;
                }
            } elseif ($state === 2) {
                if ($token->type === Token::TYPE_KEYWORD) {
                    if (stripos($token->keyword, 'JOIN') !== false) {
                        ++$list->idx;
                        $this->join = JoinKeyword::parse($parser, $list);

                        // remain in state = 2
                    } else {
                        switch ($token->keyword) {
                            case 'USING':
                                ++$list->idx; // Skip 'USING'
                                $this->using = ExpressionArray::parse($parser, $list);
                                $state = 3;

                                $multiTable = true;
                                break;
                            case 'WHERE':
                                ++$list->idx; // Skip 'WHERE'
                                $this->where = Condition::parse($parser, $list);
                                $state = 4;
                                break;
                            case 'ORDER BY':
                                ++$list->idx; // Skip 'ORDER BY'
                                $this->order = OrderKeyword::parse($parser, $list);
                                $state = 5;
                                break;
                            case 'LIMIT':
                                ++$list->idx; // Skip 'LIMIT'
                                $this->limit = Limit::parse($parser, $list);
                                $state = 6;
                                break;
                            default:
                                $parser->error('Unexpected keyword.', $token);
                                break 2;
                        }
                    }
                }
            } elseif ($state === 3) {
                if ($token->type === Token::TYPE_KEYWORD) {
                    if ($token->keyword === 'WHERE') {
                        ++$list->idx; // Skip 'WHERE'
                        $this->where = Condition::parse($parser, $list);
                        $state = 4;
                    } else {
                        $parser->error('Unexpected keyword.', $token);
                        break;
                    }
                } else {
                    $parser->error('Unexpected token.', $token);
                    break;
                }
            } elseif ($state === 4) {
                if ($multiTable === true
                    && $token->type === Token::TYPE_KEYWORD
                ) {
                    $parser->error(
                        'This type of clause is not valid in Multi-table queries.',
                        $token
                    );
                    break;
                }

                if ($token->type === Token::TYPE_KEYWORD) {
                    switch ($token->keyword) {
                        case 'ORDER BY':
                            ++$list->idx; // Skip 'ORDER  BY'
                            $this->order = OrderKeyword::parse($parser, $list);
                            $state = 5;
                            break;
                        case 'LIMIT':
                            ++$list->idx; // Skip 'LIMIT'
                            $this->limit = Limit::parse($parser, $list);
                            $state = 6;
                            break;
                        default:
                            $parser->error('Unexpected keyword.', $token);
                            break 2;
                    }
                }
            } elseif ($state === 5) {
                if ($token->type === Token::TYPE_KEYWORD) {
                    if ($token->keyword === 'LIMIT') {
                        ++$list->idx; // Skip 'LIMIT'
                        $this->limit = Limit::parse($parser, $list);
                        $state = 6;
                    } else {
                        $parser->error('Unexpected keyword.', $token);
                        break;
                    }
                }
            }
        }

        if ($state >= 2) {
            foreach ($this->from as $from_expr) {
                $from_expr->database = $from_expr->table;
                $from_expr->table = $from_expr->column;
                $from_expr->column = null;
            }
        }

        --$list->idx;
    }
}
