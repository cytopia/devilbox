<?php

/**
 * `SET` statement.
 */

namespace PhpMyAdmin\SqlParser\Statements;

use PhpMyAdmin\SqlParser\Components\OptionsArray;
use PhpMyAdmin\SqlParser\Components\SetOperation;
use PhpMyAdmin\SqlParser\Statement;

/**
 * `SET` statement.
 *
 * @category   Statements
 *
 * @license    https://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 */
class SetStatement extends Statement
{
    /**
     * The clauses of this statement, in order.
     *
     * @see Statement::$CLAUSES
     *
     * @var array
     */
    public static $CLAUSES = array(
        'SET' => array(
            'SET',
            3
        ),
        '_END_OPTIONS' => array(
            '_END_OPTIONS',
            1
        )
    );

    /**
     * Possible exceptions in SET statment.
     *
     * @var array
     */
    public static $OPTIONS = array(
        'CHARSET' => array(
            3,
            'var',
        ),
        'CHARACTER SET' => array(
            3,
            'var',
        ),
        'NAMES' => array(
            3,
            'var',
        ),
        'PASSWORD' => array(
            3,
            'expr',
        )
    );

    public static $END_OPTIONS = array(
        'COLLATE' => array(
            1,
            'var',
        ),
        'DEFAULT' => 1
    );

    /**
     * Options used in current statement.
     *
     * @var OptionsArray[]
     */
    public $options;

    /**
     * The end options of this query.
     *
     * @var OptionsArray
     *
     * @see static::$END_OPTIONS
     */
    public $end_options;

    /**
     * The updated values.
     *
     * @var SetOperation[]
     */
    public $set;

    /**
     * @return string
     */
    public function build()
    {
        $ret = 'SET ' . OptionsArray::build($this->options)
            . ' ' . SetOperation::build($this->set)
            . ' ' . OptionsArray::build($this->end_options);

        return trim($ret);
    }
}
