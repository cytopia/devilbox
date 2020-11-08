<?php
/**
 * `TRUNCATE` statement.
 */

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Statements;

use PhpMyAdmin\SqlParser\Components\Expression;
use PhpMyAdmin\SqlParser\Statement;

/**
 * `TRUNCATE` statement.
 */
class TruncateStatement extends Statement
{
    /**
     * Options for `TRUNCATE` statements.
     *
     * @var array
     */
    public static $OPTIONS = ['TABLE' => 1];

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
