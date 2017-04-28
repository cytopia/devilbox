<?PHP
// Measure time
$TIME_START = microtime(true);

// PHP Error reporting
error_reporting(-1);


//
// Set Directories
//
$CONF_DIR	= dirname(__FILE__);
$INCL_DIR	= $CONF_DIR . DIRECTORY_SEPARATOR . 'include';
$LIB_DIR	= $INCL_DIR . DIRECTORY_SEPARATOR . 'lib';
$VEN_DIR	= $INCL_DIR . DIRECTORY_SEPARATOR . 'vendor';
$LOG_DIR	= dirname(dirname($CONF_DIR)) . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . 'devilbox';



/**
 * TLD suffix for mass virtual hosts.
 *
 * This is currently hardcoded and must be changed here
 * as well as in the webserver config.
 * @var string
 */
$TLD_SUFFIX	= 'loc';


//
// Set Docker addresses
//
$HTTPD_HOST_NAME	= 'httpd';
$HTTPD_HOST_ADDR	= gethostbyname($HTTPD_HOST_NAME);

$PHP_HOST_NAME		= 'php';
$PHP_HOST_ADDR		= gethostbyname($PHP_HOST_NAME);

$MYSQL_HOST_NAME	= 'mysql';
$MYSQL_HOST_ADDR	= gethostbyname($MYSQL_HOST_NAME);

$POSTGRES_HOST_NAME	= 'postgres';
$POSTGRES_HOST_ADDR	= gethostbyname($POSTGRES_HOST_NAME);

//
// Lazy Loader
//
function loadClass($class) {

	global $MYSQL_HOST_ADDR;
	global $POSTGRES_HOST_ADDR;

	static $_LOADED_LIBS;
	$LIB_DIR = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'lib';


	if (isset($_LOADED_LIBS[$class])) {
		return $_LOADED_LIBS[$class];
	} else {
		switch($class) {

			case 'Logger':
				require $LIB_DIR . DIRECTORY_SEPARATOR . $class . '.php';
				$_LOADED_LIBS[$class] = \devilbox\Logger::getInstance();
				break;

			case 'Docker':
				require $LIB_DIR . DIRECTORY_SEPARATOR . $class . '.php';
				$_LOADED_LIBS[$class] = \devilbox\Docker::getInstance();
				break;

			case 'Mysql':
				require $LIB_DIR . DIRECTORY_SEPARATOR . $class . '.php';
				$Docker = loadClass('Docker');
				$_LOADED_LIBS[$class] = \devilbox\Mysql::getInstance('root', $Docker->getEnv('MYSQL_ROOT_PASSWORD'), $MYSQL_HOST_ADDR);
				break;

			case 'Postgres':
				require $LIB_DIR . DIRECTORY_SEPARATOR . $class . '.php';
				$Docker = loadClass('Docker');
				$_LOADED_LIBS[$class] = \devilbox\Postgres::getInstance($Docker->getEnv('POSTGRES_USER'), $Docker->getEnv('POSTGRES_PASSWORD'), $POSTGRES_HOST_ADDR);
				break;

			// Get optional docker classes
			default:
				// Redis
				if ($class == 'Redis' && loadClass('Docker')->getEnv('COMPOSE_OPTIONAL') == 1) {
					require $LIB_DIR . DIRECTORY_SEPARATOR . $class . '.php';
					$_LOADED_LIBS[$class] = \devilbox\Redis::getInstance('redis');
					break;

				} else {
					exit('Class does not exist: '.$class);
				}
		}
		return $_LOADED_LIBS[$class];
	}
}



// VirtualHost DNS check
// Temporarily disable due to:
// https://github.com/cytopia/devilbox/issues/8
$ENABLE_VHOST_DNS_CHECK = false;
