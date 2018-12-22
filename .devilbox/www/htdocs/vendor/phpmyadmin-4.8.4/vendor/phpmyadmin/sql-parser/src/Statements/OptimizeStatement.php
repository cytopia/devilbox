<?php

/**
 * `OPTIMIZE` statement.
 */

namespace PhpMyAdmin\SqlParser\Statements;

use PhpMyAdmin\SqlParser\Components\Expression;
use PhpMyAdmin\SqlParser\Statement;

/**
 * `OPTIMIZE` statement.
 *
 * OPTIMIZE [NO_WRITE_TO_BINLOG | LOCAL] TABLE
 *  tbl_name [, tbl_name] ...
 *
 * @category   Statements
 *
 * @license    https://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 */
class OptimizeStatement extends Statement
{
    /**
     * Options of this statement.
     *
     * @var array
     */
    public static $OPTIONS = array(
        'TABLE' => 1,

        'NO_WRITE_TO_BINLOG' => 2,
        'LOCAL' => 3,
    );

    /**
     * Optimized tables.
     *
     * @var Expression[]
     */
    public $tables;
}
