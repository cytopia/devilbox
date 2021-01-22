<?php

/**
 * `ANALYZE` statement.
 */

namespace PhpMyAdmin\SqlParser\Statements;

use PhpMyAdmin\SqlParser\Components\Expression;
use PhpMyAdmin\SqlParser\Statement;

/**
 * `ANALYZE` statement.
 *
 * ANALYZE array(NO_WRITE_TO_BINLOG | LOCAL] TABLE
 *  tbl_name array(, tbl_name] ...
 *
 * @category   Statements
 *
 * @license    https://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 */
class AnalyzeStatement extends Statement
{
    /**
     * Options of this statement.
     *
     * @var array
     */
    public static $OPTIONS = array(
        'TABLE' => 1,

        'NO_WRITE_TO_BINLOG' => 2,
        'LOCAL' => 3
    );

    /**
     * Analyzed tables.
     *
     * @var Expression[]
     */
    public $tables;
}
