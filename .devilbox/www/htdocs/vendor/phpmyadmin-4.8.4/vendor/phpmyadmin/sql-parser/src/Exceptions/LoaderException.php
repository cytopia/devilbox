<?php

/**
 * Exception thrown by the lexer.
 */

namespace PhpMyAdmin\SqlParser\Exceptions;

/**
 * Exception thrown by the lexer.
 *
 * @category   Exceptions
 *
 * @license    https://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 */
class LoaderException extends \Exception
{
    /**
     * The failed load name.
     *
     * @var string
     */
    public $name;

    /**
     * Constructor.
     *
     * @param string $msg  the message of this exception
     * @param string $name   the character that produced this exception
     * @param int    $code the code of this error
     */
    public function __construct($msg = '', $name = '', $code = 0)
    {
        parent::__construct($msg, $code);
        $this->name = $name;
    }
}
