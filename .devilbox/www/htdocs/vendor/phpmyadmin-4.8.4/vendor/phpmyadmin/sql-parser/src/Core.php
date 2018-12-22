<?php

/**
 * Defines the core helper infrastructure of the library.
 */

namespace PhpMyAdmin\SqlParser;

class Core
{
    /**
     * Whether errors should throw exceptions or just be stored.
     *
     * @var bool
     *
     * @see static::$errors
     */
    public $strict = false;

    /**
     * List of errors that occurred during lexing.
     *
     * Usually, the lexing does not stop once an error occurred because that
     * error might be false positive or a partial result (even a bad one)
     * might be needed.
     *
     * @var \Exception[]
     *
     * @see Core::error()
     */
    public $errors = array();

    /**
     * Creates a new error log.
     *
     * @param \Exception $error the error exception
     *
     * @throws \Exception throws the exception, if strict mode is enabled
     */
    public function error($error)
    {
        if ($this->strict) {
            throw $error;
        }
        $this->errors[] = $error;
    }
}
