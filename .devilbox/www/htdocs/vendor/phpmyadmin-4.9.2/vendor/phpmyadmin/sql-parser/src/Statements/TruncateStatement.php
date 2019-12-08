<?php

/**
 * `TRUNCATE` statement.
 */

namespace PhpMyAdmin\SqlParser\Statements;

use PhpMyAdmin\SqlParser\Components\Expression;
use PhpMyAdmin\SqlParser\Statement;

/**
 * `TRUNCATE` statement.
 *
 * @category   Statements
 *
 * @license    https://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 */
class TruncateStatement extends Statement
{
    /**
     * Options for `TRUNCATE` statements.
     *
     * @var array
     */
    public static $OPTIONS = array(
        'TABLE' => 1
    );

    /**
     * The name of the truncated table.
     *
     * @var Expression
     */
    public $table;

    /**
     * Special build method for truncate statement as Statement::build would return empty string.
     *
     * @return string
     */
    public function build()
    {
        return 'TRUNCATE TABLE ' . $this->table . ';';
    }
}
