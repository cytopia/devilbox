<?php

/**
 * `SELECT` statement.
 */

namespace PhpMyAdmin\SqlParser\Statements;

use PhpMyAdmin\SqlParser\Components\ArrayObj;
use PhpMyAdmin\SqlParser\Components\Condition;
use PhpMyAdmin\SqlParser\Components\Expression;
use PhpMyAdmin\SqlParser\Components\FunctionCall;
use PhpMyAdmin\SqlParser\Components\IntoKeyword;
use PhpMyAdmin\SqlParser\Components\JoinKeyword;
use PhpMyAdmin\SqlParser\Components\Limit;
use PhpMyAdmin\SqlParser\Components\OptionsArray;
use PhpMyAdmin\SqlParser\Components\OrderKeyword;
use PhpMyAdmin\SqlParser\Statement;

/**
 * `SELECT` statement.
 *
 * SELECT
 *     [ALL | DISTINCT | DISTINCTROW ]
 *       [HIGH_PRIORITY]
 *       [MAX_STATEMENT_TIME = N]
 *       [STRAIGHT_JOIN]
 *       [SQL_SMALL_RESULT] [SQL_BIG_RESULT] [SQL_BUFFER_RESULT]
 *       [SQL_CACHE | SQL_NO_CACHE] [SQL_CALC_FOUND_ROWS]
 *     select_expr [, select_expr ...]
 *     [FROM table_references
 *       [PARTITION partition_list]
 *     [WHERE where_condition]
 *     [GROUP BY {col_name | expr | position}
 *       [ASC | DESC], ... [WITH ROLLUP]]
 *     [HAVING where_condition]
 *     [ORDER BY {col_name | expr | position}
 *       [ASC | DESC], ...]
 *     [LIMIT {[offset,] row_count | row_count OFFSET offset}]
 *     [PROCEDURE procedure_name(argument_list)]
 *     [INTO OUTFILE 'file_name'
 *         [CHARACTER SET charset_name]
 *         export_options
 *       | INTO DUMPFILE 'file_name'
 *       | INTO var_name [, var_name]]
 *     [FOR UPDATE | LOCK IN SHARE MODE]]
 *
 * @category   Statements
 *
 * @license    https://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 */
class SelectStatement extends Statement
{
    /**
     * Options for `SELECT` statements and their slot ID.
     *
     * @var array
     */
    public static $OPTIONS = array(
        'ALL' => 1,
        'DISTINCT' => 1,
        'DISTINCTROW' => 1,
        'HIGH_PRIORITY' => 2,
        'MAX_STATEMENT_TIME' => array(3, 'var='),
        'STRAIGHT_JOIN' => 4,
        'SQL_SMALL_RESULT' => 5,
        'SQL_BIG_RESULT' => 6,
        'SQL_BUFFER_RESULT' => 7,
        'SQL_CACHE' => 8,
        'SQL_NO_CACHE' => 8,
        'SQL_CALC_FOUND_ROWS' => 9,
    );

    public static $END_OPTIONS = array(
        'FOR UPDATE' => 1,
        'LOCK IN SHARE MODE' => 1,
    );

    /**
     * The clauses of this statement, in order.
     *
     * @see Statement::$CLAUSES
     *
     * @var array
     */
    public static $CLAUSES = array(
        'SELECT' => array('SELECT', 2),
        // Used for options.
        '_OPTIONS' => array('_OPTIONS', 1),
        // Used for selected expressions.
        '_SELECT' => array('SELECT', 1),
        'INTO' => array('INTO', 3),
        'FROM' => array('FROM', 3),
        'PARTITION' => array('PARTITION', 3),

        'JOIN' => array('JOIN', 1),
        'FULL JOIN' => array('FULL JOIN', 1),
        'INNER JOIN' => array('INNER JOIN', 1),
        'LEFT JOIN' => array('LEFT JOIN', 1),
        'LEFT OUTER JOIN' => array('LEFT OUTER JOIN', 1),
        'RIGHT JOIN' => array('RIGHT JOIN', 1),
        'RIGHT OUTER JOIN' => array('RIGHT OUTER JOIN', 1),
        'NATURAL JOIN' => array('NATURAL JOIN', 1),
        'NATURAL LEFT JOIN' => array('NATURAL LEFT JOIN', 1),
        'NATURAL RIGHT JOIN' => array('NATURAL RIGHT JOIN', 1),
        'NATURAL LEFT OUTER JOIN' => array('NATURAL LEFT OUTER JOIN', 1),
        'NATURAL RIGHT OUTER JOIN' => array('NATURAL RIGHT JOIN', 1),

        'WHERE' => array('WHERE', 3),
        'GROUP BY' => array('GROUP BY', 3),
        'HAVING' => array('HAVING', 3),
        'ORDER BY' => array('ORDER BY', 3),
        'LIMIT' => array('LIMIT', 3),
        'PROCEDURE' => array('PROCEDURE', 3),
        'UNION' => array('UNION', 1),
        'EXCEPT' => array('EXCEPT', 1),
        'INTERSECT' => array('INTERSECT', 1),
        '_END_OPTIONS' => array('_END_OPTIONS', 1),
        // These are available only when `UNION` is present.
        // 'ORDER BY'                      => array('ORDER BY', 3),
        // 'LIMIT'                         => array('LIMIT', 3),
    );

    /**
     * Expressions that are being selected by this statement.
     *
     * @var Expression[]
     */
    public $expr = array();

    /**
     * Tables used as sources for this statement.
     *
     * @var Expression[]
     */
    public $from = array();

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
     * Conditions used for grouping the result set.
     *
     * @var OrderKeyword[]
     */
    public $group;

    /**
     * Conditions used for filtering the result set.
     *
     * @var Condition[]
     */
    public $having;

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
     * Procedure that should process the data in the result set.
     *
     * @var FunctionCall
     */
    public $procedure;

    /**
     * Destination of this result set.
     *
     * @var IntoKeyword
     */
    public $into;

    /**
     * Joins.
     *
     * @var JoinKeyword[]
     */
    public $join;

    /**
     * Unions.
     *
     * @var SelectStatement[]
     */
    public $union = array();

    /**
     * The end options of this query.
     *
     * @var OptionsArray
     *
     * @see static::$END_OPTIONS
     */
    public $end_options;

    /**
     * Gets the clauses of this statement.
     *
     * @return array
     */
    public function getClauses()
    {
        // This is a cheap fix for `SELECT` statements that contain `UNION`.
        // The `ORDER BY` and `LIMIT` clauses should be at the end of the
        // statement.
        if (!empty($this->union)) {
            $clauses = static::$CLAUSES;
            unset($clauses['ORDER BY']);
            unset($clauses['LIMIT']);
            $clauses['ORDER BY'] = array('ORDER BY', 3);
            $clauses['LIMIT'] = array('LIMIT', 3);

            return $clauses;
        }

        return static::$CLAUSES;
    }
}
