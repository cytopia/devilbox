<?php

require __DIR__.'/../vendor/autoload.php';

if (defined('PHP_WINDOWS_VERSION_BUILD')) {
    $server = array_change_key_case($_SERVER, true);
    $_SERVER['GIT_ENVS'] = array();
    foreach (array('PATH', 'SYSTEMROOT') as $key) {
        if (isset($server[$key])) {
            $_SERVER['GIT_ENVS'][$key] = $server[$key];
        }
    }
    unset($server);
}
