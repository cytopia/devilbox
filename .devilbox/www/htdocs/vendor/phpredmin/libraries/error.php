<?php

final class error
{
    public function __construct()
    {
        if (App::instance()->config['production']) {
            ini_set('display_errors', 0);
        }

        // Set a custom error Handler
        set_error_handler(array($this, 'errorHandler'));
        // Set a custom Exception Handler
        set_exception_handler(array($this, 'exceptionHandler'));
        // Set Shutdown Handler
        register_shutdown_function(array($this, 'shutdownHandler'));
    }

    public static function shutdownHandler()
    {
        $error = error_get_last();

        if ($error) {
            $type = self::_getError($error['type']);
            Log::factory()->write($type, "{$error['message']} on {$error['file']}:{$error['line']}", 'Shutdown Handler');
        }
    }

    public function exceptionHandler(Exception $e)
    {
        Log::factory()->write(Log::ERROR, "{$e->getMessage()} on {$e->getFile()}:{$e->getLine()}", 'Exception Handler');
    }

    public function errorHandler($no, $str, $file, $line, $context)
    {
        if (!(error_reporting() & $no)) {
            // This error code is not included in error_reporting
            return;
        }

        $type = self::_getError($no);

        Log::factory()->write($type, "{$str} on {$file}:{$line}", 'Error Handler');
    }

    protected static function _getError($type)
    {
        switch ($type) {
            case E_WARNING: // 2 //
                return Log::WARNING;
            case E_NOTICE: // 8 //
                return Log::NOTICE;
            case E_CORE_WARNING: // 32 //
                return Log::WARNING;
            case E_USER_WARNING: // 512 //
                return Log::WARNING;
            case E_USER_NOTICE: // 1024 //
                return Log::NOTICE;
            case E_DEPRECATED: // 8192 //
                return Log::WARNING;
            case E_USER_DEPRECATED: // 16384 //
                return Log::WARNING;
        }

        return Log::ERROR;
    }
}
