<?php
/**
 * `CHECKSUM` statement.
 */

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Statements;

/**
 * `CHECKSUM` statement.
 *
 * CHECKSUM TABLE tbl_name [, tbl_name] ... [ QUICK | EXTENDED ]
 */
class ChecksumStatement extends MaintenanceStatement
{
    /**
     * Options of this statement.
     *
     * @var array
     */
    public static $OPTIONS = [
        'TABLE' => 1,

        'QUICK' => 2,
        'EXTENDED' => 3,
    ];
}
