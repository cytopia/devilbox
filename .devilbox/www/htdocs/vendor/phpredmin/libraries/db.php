<?php
final class db
{
    protected static $_instances = array();

    public static function factory($config, $driver = null)
    {
        $driver = isset($driver) ? $driver : App::instance()->config['database']['driver'];

        $instanceName = $driver . ':' . $config['host'] . ':' . $config['port'];

        if (!isset(self::$_instances[$instanceName])) {
            include_once(App::instance()->drivers.'db/'.(strtolower($driver)).'.php');

            $class = ucwords(strtolower($driver)).'Db';
            self::$_instances[$instanceName] = new $class($config);
        }

        return self::$_instances[$instanceName];
    }
}
