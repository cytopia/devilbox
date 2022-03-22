<?php
/**
 * `BACKUP` statement.
 */

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Statements;

/**
 * `BACKUP` statement.
 *
 * BACKUP TABLE tbl_name [, tbl_name] ... TO '/path/to/backup/directory'
 */
class BackupStatement extends MaintenanceStatement
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

        'TO' => [
            4,
            'var',
        ],
    ];
}
