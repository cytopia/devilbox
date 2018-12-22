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
        'SET' => array('SET', 3),
    );

    /**
     * Possible exceptions in SET statment.
     *
     * @var array
     */
    public static $OPTIONS = array(
        'CHARSET' => array(3, 'var'),
        'CHARACTER SET' => array(3, 'var'),
        'NAMES' => array(3, 'var'),
        'PASSWORD' => array(3, 'expr'),
    );

    /**
     * Options used in current statement.
     *
     * @var OptionsArray[]
     */
    public $options;

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
        return 'SET ' . OptionsArray::build($this->options)
            . ' ' . SetOperation::build($this->set);
    }
}
