<?php


// Check if redis is using a password
$REDIS_ROOT_PASSWORD = '';

$_REDIS_ARGS = getenv('REDIS_ARGS');
$_REDIS_PASS = preg_split("/--requirepass\s+/",  $_REDIS_ARGS);
if (is_array($_REDIS_PASS) && count($_REDIS_PASS)) {
	// In case the option is specified multiple times, use the last effective one.
	$_REDIS_PASS = $_REDIS_PASS[count($_REDIS_PASS)-1];
	if (strlen($_REDIS_PASS) > 0) {
		$REDIS_ROOT_PASSWORD = $_REDIS_PASS;
	}
}

$config = array(
    'default_controller' => 'Welcome',
    'default_action'     => 'Index',
    'production'         => true,
    'default_layout'     => 'layout',
//    'timezone'           => 'Europe/Amsterdam',
//    'auth' => array(
//        'username' => 'admin',
//        'password' => password_hash('admin', PASSWORD_DEFAULT)
//	),
    'log' => array(
        'driver'    => 'file',
        'threshold' => 0, /* 0: Disable Logging 1: Error 2: Notice 3: Info 4: Warning 5: Debug */
        'file'      => array(
            'directory' => 'logs'
        )
    ),
    'database'  => array(
        'driver' => 'redis',
        'mysql'  => array(
            'host'     => 'mysql',
            'username' => 'root',
            'password' => getenv('MYSQL_ROOT_PASSWORD')
        ),
        'redis' => array(
            array(
                'host'     => 'redis',
                'port'     => '6379',
                'password' => $REDIS_ROOT_PASSWORD,
                'database' => 0,
                'max_databases' => 16, /* Manual configuration of max databases for Redis < 2.6 */
                'stats'    => array(
                    'enable'   => 1,
                    'database' => 0,
                ),
                'dbNames' => array( /* Name databases. key should be database id and value is the name */
                ),
            ),
        ),
    ),
    'session' => array(
        'lifetime'       => 7200,
        'gc_probability' => 2,
        'name'           => 'phpredminsession'
    ),
    'gearman' => array(
        'host' => '127.0.0.1',
        'port' => 4730
    ),
    'terminal' => array(
        'enable'  => true,
        'history' => 200
    )
);

return $config;
