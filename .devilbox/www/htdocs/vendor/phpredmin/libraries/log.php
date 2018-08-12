<?php

final class log
{
    protected static $_instances = array();
    protected static $_instance  = null;

    protected $_levels = array(
        'error'   => 1,
        'notice'  => 2,
        'info'    => 3,
        'warning' => 4,
        'debug'   => 5
    );

    const INFO    = 'info';
    const DEBUG   = 'debug';
    const ERROR   = 'error';
    const WARNING = 'warning';
    const NOTICE  = 'notice';

    public static function instance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public static function factory($driver = null)
    {
        $driver = isset($driver) ? $driver : App::instance()->config['log']['driver'];

        if (!isset(self::$_instances[$driver])) {
            include_once(App::instance()->drivers.'log/'.(strtolower($driver)).'.php');

            $class  = ucwords(strtolower($driver)).'Log';
            self::$_instances[$driver] = new $class;
        }

        return self::$_instances[$driver];
    }

    public function __get($key)
    {
        return isset($this->_levels[$key]) ? $this->_levels[$key] : 0;
    }
}
