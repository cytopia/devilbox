<?php
final class app
{
    protected static $_instance = null;

    protected $_data = array();

    protected function __construct()
    {
        $this->_data['config']  = include_once(file_exists('../config.php') ? '../config.php' : '../config.dist.php');
		$this->_data['drivers'] = 'drivers/';

		$this->readEnvConfig();
    }

    public static function instance()
    {
        if (!self::$_instance) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public function __get($key)
    {
        return isset($this->_data[$key]) ? $this->_data[$key] : null;
    }

    public function __set($key, $value)
    {
        $this->_data[$key] = $value;
	}

	protected function readEnvConfig() {
		$envConf = preg_grep('/^PHPREDMIN_/', array_keys($_ENV));

		if (!empty($envConf)) {
			foreach ($envConf as $conf) {
				$keys = explode('_', $conf);

				if (!empty($keys)) {
					array_shift($keys);

					self::setConfig($this->_data['config'], $keys, $_ENV[$conf]);
				}
			}
		}
	}

	protected static function setConfig(&$config, $keys, $value) {
		$key = array_shift($keys);

		$key = strtolower($key);

		if (isset($config[$key])) {
			if (is_array($config[$key])) {
				return self::setConfig($config[$key], $keys, $value);
			} else {
				$config[$key] = $value;
				return True;
			}
		}

		return False;
	}
}
