<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Import progress bar backend
 *
 * @package PhpMyAdmin
 */
declare(strict_types=1);

use PhpMyAdmin\Core;
use PhpMyAdmin\Display\ImportAjax;

if (! defined('ROOT_PATH')) {
    define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);
}

/* PHP 5.4 stores upload progress data only in the default session.
 * After calling session_name(), we won't find the progress data anymore.
 *
 * https://bugs.php.net/bug.php?id=64075
 *
 * The bug should be somewhere in
 * https://github.com/php/php-src/blob/master/ext/session/session.c#L2342
 *
 * Until this is fixed, we need to load the default session to load the data,
 * export the upload progress information from there,
 * and re-import after switching to our session.
 *
 * However, since https://github.com/phpmyadmin/phpmyadmin/commit/063a2d99
 * we have deactivated this feature, so the corresponding code is now
 * commented out.
 */

/*
if (ini_get('session.upload_progress.enabled')) {

    $sessionupload = array();
    define('UPLOAD_PREFIX', ini_get('session.upload_progress.prefix'));

    session_start();
    foreach ($_SESSION as $key => $value) {
        // only copy session-prefixed data
        if (substr($key, 0, strlen(UPLOAD_PREFIX))
            == UPLOAD_PREFIX) {
            $sessionupload[$key] = $value;
        }
    }
    // PMA will kill all variables, so let's use a constant
    define('SESSIONUPLOAD', serialize($sessionupload));
    session_write_close();

    // The cookie name is not good anymore since PR #15273
    session_name('phpMyAdmin');
    session_id($_COOKIE['phpMyAdmin']);
}
 */

define('PMA_MINIMUM_COMMON', 1);

require_once ROOT_PATH . 'libraries/common.inc.php';
list(
    $SESSION_KEY,
    $upload_id,
    $plugins
) = ImportAjax::uploadProgressSetup();

/*
if (defined('SESSIONUPLOAD')) {
    // write sessionupload back into the loaded PMA session

    $sessionupload = unserialize(SESSIONUPLOAD);
    foreach ($sessionupload as $key => $value) {
        $_SESSION[$key] = $value;
    }

    // remove session upload data that are not set anymore
    foreach ($_SESSION as $key => $value) {
        if (substr($key, 0, strlen(UPLOAD_PREFIX))
            == UPLOAD_PREFIX
            && ! isset($sessionupload[$key])
        ) {
            unset($_SESSION[$key]);
        }
    }
}
 */

// $_GET["message"] is used for asking for an import message
if (isset($_GET["message"]) && $_GET["message"]) {
    // AJAX requests can't be cached!
    Core::noCacheHeader();

    header('Content-type: text/html');

    // wait 0.3 sec before we check for $_SESSION variable,
    // which is set inside import.php
    usleep(300000);

    $maximumTime = ini_get('max_execution_time');
    $timestamp = time();
    // wait until message is available
    while ($_SESSION['Import_message']['message'] == null) {
        // close session before sleeping
        session_write_close();
        // sleep
        usleep(250000); // 0.25 sec
        // reopen session
        session_start();

        if ((time() - $timestamp) > $maximumTime) {
            $_SESSION['Import_message']['message'] = PhpMyAdmin\Message::error(
                __('Could not load the progress of the import.')
            )->getDisplay();
            break;
        }
    }

    if (isset($_SESSION['Import_message']['message'])) {
        echo $_SESSION['Import_message']['message'];
    }
    if (isset($_SESSION['Import_message']['go_back_url'])) {
        echo '<fieldset class="tblFooters">' , "\n";
        echo '    [ <a href="' , $_SESSION['Import_message']['go_back_url']
            . '">' , __('Back') , '</a> ]' , "\n";
        echo '</fieldset>' , "\n";
    }
} else {
    ImportAjax::status($_GET["id"]);
}
