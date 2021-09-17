<?php

declare(strict_types=1);

namespace PhpMyAdmin\Import;

use PhpMyAdmin\Core;
use function extension_loaded;
use function function_exists;
use function ini_get;
use function json_encode;
use function ucwords;
use function uniqid;

/**
 * Handles plugins that show the upload progress.
 */
final class Ajax
{
    /**
     * Sets up some variables for upload progress
     */
    public static function uploadProgressSetup(): array
    {
        /**
         * constant for differentiating array in $_SESSION variable
         */
        $SESSION_KEY = '__upload_status';

        /**
         * sets default plugin for handling the import process
         */
        $_SESSION[$SESSION_KEY]['handler'] = '';

        /**
         * unique ID for each upload
         */
        $upload_id = uniqid('');

        /**
         * list of available plugins
         */
        $plugins = [
            // in PHP 5.4 session-based upload progress was problematic, see closed bug 3964
            //"session",
            'progress',
            'apc',
            'noplugin',
        ];

        // select available plugin
        foreach ($plugins as $plugin) {
            $check = $plugin . 'Check';

            if (self::$check()) {
                $upload_class = 'PhpMyAdmin\Plugins\Import\Upload\Upload' . ucwords(
                    $plugin
                );
                $_SESSION[$SESSION_KEY]['handler'] = $upload_class;
                break;
            }
        }

        return [
            $SESSION_KEY,
            $upload_id,
            $plugins,
        ];
    }

    /**
     * Checks if APC bar extension is available and configured correctly.
     *
     * @return bool true if APC extension is available and if rfc1867 is enabled,
     * false if it is not
     */
    public static function apcCheck()
    {
        if (! extension_loaded('apc')
            || ! function_exists('apc_fetch')
            || ! function_exists('getallheaders')
        ) {
            return false;
        }

        return ini_get('apc.enabled') && ini_get('apc.rfc1867');
    }

    /**
     * Checks if PhpMyAdmin\Plugins\Import\Upload\UploadProgress bar extension is
     * available.
     *
     * @return bool true if PhpMyAdmin\Plugins\Import\Upload\UploadProgress
     * extension is available, false if it is not
     */
    public static function progressCheck(): bool
    {
        return function_exists('uploadprogress_get_info')
            && function_exists('getallheaders');
    }

    /**
     * Checks if PHP 5.4 session upload-progress feature is available.
     *
     * @return bool true if PHP 5.4 session upload-progress is available,
     * false if it is not
     */
    public static function sessionCheck(): bool
    {
        return ini_get('session.upload_progress.enabled') === '1';
    }

    /**
     * Default plugin for handling import.
     * If no other plugin is available, noplugin is used.
     *
     * @return true
     */
    public static function nopluginCheck(): bool
    {
        return true;
    }

    /**
     * The function outputs json encoded status of uploaded.
     * It uses PMA_getUploadStatus, which is defined in plugin's file.
     *
     * @param string $id ID of transfer, usually $upload_id
     */
    public static function status($id): void
    {
        Core::headerJSON();
        echo json_encode(
            $_SESSION[$GLOBALS['SESSION_KEY']]['handler']::getUploadStatus($id)
        );
    }
}
