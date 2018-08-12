<?php

/* New Autoloader funtion */
spl_autoload_register(function($class) {
    $path = '../';
    if (preg_match('/^(.*)_Controller$/', $class, $matches)) {
        $class = $matches[1];
        $dir   = 'controllers';
    } elseif (preg_match('/^(.*)_Model$/', $class, $matches)) {
        $class = $matches[1];
        $dir   = 'models';
    } elseif (preg_match('/^(.*)_Helper$/', $class, $matches)) {
        $class = $matches[1];
        $dir   = 'helpers';
    } else {
        $dir = 'libraries';
    }
    include_once($path.$dir.'/'.(strtolower($class)).'.php');
});

/* Check if Redis is available */
$REDIS_HOST_NAME = 'redis';
$REDIS_HOST_ADDR = gethostbyname($REDIS_HOST_NAME.'');

if (filter_var($REDIS_HOST_ADDR, FILTER_VALIDATE_IP) === false) {
	echo 'Redis container is not running.';
	exit(0);
}

/* Route to Framework */
Router::instance()->route();
