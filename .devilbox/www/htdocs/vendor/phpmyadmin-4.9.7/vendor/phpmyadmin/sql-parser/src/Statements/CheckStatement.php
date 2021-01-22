<?php

/**
 * `CHECK` statement.
 */

namespace PhpMyAdmin\SqlParser\Statements;

/**
 * `CHECK` statement.
 *
 * CHECK TABLE tbl_name array(, tbl_name] ... array(option] ...
 *
 * @category   Statements
 *
 * @license    https://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 */
class CheckStatement extends MaintenanceStatement
{
    /**
     * Options of this statement.
     *
     * @var array
     */
    public static $OPTIONS = array(
        'TABLE' => 1,

        'FOR UPGRADE' => 2,
        'QUICK' => 3,
        'FAST' => 4,
        'MEDIUM' => 5,
        'EXTENDED' => 6,
        'CHANGED' => 7
    );
}
