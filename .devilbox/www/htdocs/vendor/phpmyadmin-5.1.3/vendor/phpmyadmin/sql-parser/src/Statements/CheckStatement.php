<?php
/**
 * `CHECK` statement.
 */

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Statements;

/**
 * `CHECK` statement.
 *
 * CHECK TABLE tbl_name [, tbl_name] ... [option] ...
 */
class CheckStatement extends MaintenanceStatement
{
    /**
     * Options of this statement.
     *
     * @var array
     */
    public static $OPTIONS = [
        'TABLE' => 1,

        'FOR UPGRADE' => 2,
        'QUICK' => 3,
        'FAST' => 4,
        'MEDIUM' => 5,
        'EXTENDED' => 6,
        'CHANGED' => 7,
    ];
}
