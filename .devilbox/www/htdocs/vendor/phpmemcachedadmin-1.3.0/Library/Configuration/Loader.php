<?php

/**
 * Copyright 2010 Cyrille Mahieux
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations
 * under the License.
 *
 * ><)))°> ><)))°> ><)))°> ><)))°> ><)))°> ><)))°> ><)))°> ><)))°> ><)))°>
 *
 * Configuration class for editing, saving, ...
 *
 * @author elijaa@free.fr
 * @since 19/05/2010
 */
class Library_Configuration_Loader
{
    # Singleton
    protected static $_instance = null;

    # Configuration file
    protected static $_iniPath = './Config/Memcache.php';

    # Configuration needed keys and default values
    protected static $_iniKeys = array('stats_api' => 'Server',
        'slabs_api' => 'Server',
        'items_api' => 'Server',
        'get_api' => 'Server',
        'set_api' => 'Server',
        'delete_api' => 'Server',
        'flush_all_api' => 'Server',
        'connection_timeout' => 1,
        'max_item_dump' => 100,
        'refresh_rate' => 2,
        'memory_alert' => 80,
        'hit_rate_alert' => 90,
        'eviction_alert' => 0,
        'file_path' => 'Temp/',
        'servers' => array('Default' => array('127.0.0.1:11211' => array('hostname' => '127.0.0.1', 'port' => 11211))));

    # Storage
    protected static $_ini = array();

    /**
     * Constructor, load configuration file and parse server list
     *
     * @return Void
     */
    protected function __construct()
    {
        # Checking ini File
        if (file_exists(self::$_iniPath)) {
            # Opening ini file
            self::$_ini = require self::$_iniPath;
        } else {
            # Fallback
            self::$_ini = self::$_iniKeys;
        }
    }

    /**
     * Get Library_Configuration_Loader singleton
     *
     * @return Library_Configuration_Loader
     */
    public static function singleton()
    {
        if (! isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Config key to retrieve
     * Return the value, or false if does not exists
     *
     * @param String $key Key to get
     *
     * @return Mixed
     */
    public function get($key)
    {
        if (isset(self::$_ini[$key])) {
            return self::$_ini[$key];
        }
        return false;
    }

    /**
     * Servers to retrieve from cluster
     * Return the value, or false if does not exists
     *
     * @param String $cluster Cluster to retreive
     *
     * @return Array
     */
    public function cluster($cluster)
    {
        if (isset(self::$_ini['servers'][$cluster])) {
            return self::$_ini['servers'][$cluster];
        }
        return array();
    }

    /**
     * Check and return server data
     * Return the value, or false if does not exists
     *
     * @param String $server Server to retreive
     *
     * @return Array
     */
    public function server($server)
    {
        foreach (self::$_ini['servers'] as $cluster => $servers) {
            if (isset(self::$_ini['servers'][$cluster][$server])) {
                return self::$_ini['servers'][$cluster][$server];
            }
        }
        return array();
    }

    /**
     * Config key to set
     *
     * @param String $key Key to set
     * @param Mixed $value Value to set
     *
     * @return Boolean
     */
    public function set($key, $value)
    {
        self::$_ini[$key] = $value;
    }

    /**
     * Return actual ini file path
     *
     * @return String
     */
    public function path()
    {
        return self::$_iniPath;
    }

    /**
     * Check if every ini keys are set
     * Return true if ini is correct, false otherwise
     *
     * @return Boolean
     */
    public function check()
    {
        # Checking configuration keys
        foreach (array_keys(self::$_iniKeys) as $iniKey) {
            # Ini file key not set
            if (isset(self::$_ini[$iniKey]) === false) {
                return false;
            }
        }
        return true;
    }

    /**
     * Write ini file
     * Return true if written, false otherwise
     *
     * @return Boolean
     */
    public function write()
    {
        if ($this->check()) {
            return is_numeric(file_put_contents(self::$_iniPath, '<?php' . PHP_EOL . 'return ' . var_export(self::$_ini, true) . ';'));
        }
        return false;
    }
}