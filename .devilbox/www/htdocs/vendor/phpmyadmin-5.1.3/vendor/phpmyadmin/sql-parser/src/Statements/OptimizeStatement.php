<?php
/**
 * `OPTIMIZE` statement.
 */

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Statements;

use PhpMyAdmin\SqlParser\Components\Expression;
use PhpMyAdmin\SqlParser\Statement;

/**
 * `OPTIMIZE` statement.
 *
 * OPTIMIZE [NO_WRITE_TO_BINLOG | LOCAL] TABLE
 *  tbl_name [, tbl_name] ...
 */
class OptimizeStatement extends Statement
{
    /**
     * Options of this statement.
     *
     * @var array
     */
    public static $OPTIONS = [
        'TABLE' => 1,

        'NO_WRITE_TO_BINLOG' => 2,
        'LOCAL' => 3,
    ];

    /**
     * Optimized tables.
     *
     * @var Expression[]
     */
    public $tables;
}
