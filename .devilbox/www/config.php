<?PHP
// Measure time
$TIME_START = microtime(true);

// Start session
session_start();

// Turn on all PHP errors
error_reporting(-1);


// Shorten DNS timeouts for gethostbyname in case DNS server is down
putenv('RES_OPTIONS=retrans:1 retry:1 timeout:1 attempts:1');


$DEVILBOX_VERSION = 'v0.15';
$DEVILBOX_DATE = '2018-05-12';
$DEVILBOX_API_PAGE = 'devilbox-api/status.json';

//
// Set Directories
//
$CONF_DIR	= dirname(__FILE__);
$LIB_DIR	= $CONF_DIR . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR .'lib';
$VEN_DIR	= $CONF_DIR . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR .'vendor';
$LOG_DIR	= dirname(dirname($CONF_DIR)) . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . 'devilbox';


//
// Load Base classes
//
require $LIB_DIR . DIRECTORY_SEPARATOR . 'container' . DIRECTORY_SEPARATOR .'BaseClass.php';
require $LIB_DIR . DIRECTORY_SEPARATOR . 'container' . DIRECTORY_SEPARATOR .'BaseInterface.php';



//
// Set Docker addresses
//
$DNS_HOST_NAME		= 'bind';
$PHP_HOST_NAME		= 'php';
$HTTPD_HOST_NAME	= 'httpd';
$MYSQL_HOST_NAME	= 'mysql';
$PGSQL_HOST_NAME	= 'pgsql';
$REDIS_HOST_NAME	= 'redis';
$MEMCD_HOST_NAME	= 'memcd';
$MONGO_HOST_NAME	= 'mongo';


//
// Lazy Container Loader
//
function loadFile($class, $base_path) {
	static $_LOADED_FILE;

	if (isset($_LOADED_FILE[$class])) {
		return;
	}

	require $base_path . DIRECTORY_SEPARATOR . $class . '.php';
	$_LOADED_FILE[$class] = true;
	return;
}
function loadClass($class) {

	static $_LOADED_LIBS;

	if (isset($_LOADED_LIBS[$class])) {
		return $_LOADED_LIBS[$class];
	} else {

		$lib_dir = $GLOBALS['LIB_DIR'];
		$cnt_dir = $GLOBALS['LIB_DIR'] . DIRECTORY_SEPARATOR . 'container';

		switch($class) {
			//
			// Lib Classes
			//
			case 'Logger':
				loadFile($class, $lib_dir);
				$_LOADED_LIBS[$class] = \devilbox\Logger::getInstance();
				break;

			case 'Html':
				loadFile($class, $lib_dir);
				$_LOADED_LIBS[$class] = \devilbox\Html::getInstance();
				break;

			case 'Helper':
				loadFile($class, $lib_dir);
				$_LOADED_LIBS[$class] = \devilbox\Helper::getInstance();
				break;

			//
			// Docker Container Classes
			//
			case 'Php':
				loadFile($class, $cnt_dir);
				$_LOADED_LIBS[$class] = \devilbox\Php::getInstance($GLOBALS['PHP_HOST_NAME']);
				break;

			case 'Dns':
				loadFile($class, $cnt_dir);
				$_LOADED_LIBS[$class] = \devilbox\Dns::getInstance($GLOBALS['DNS_HOST_NAME']);
				break;

			case 'Httpd':
				loadFile($class, $cnt_dir);
				$_LOADED_LIBS[$class] = \devilbox\Httpd::getInstance($GLOBALS['HTTPD_HOST_NAME']);
				break;

			case 'Mysql':
				loadFile($class, $cnt_dir);
				$_LOADED_LIBS[$class] = \devilbox\Mysql::getInstance($GLOBALS['MYSQL_HOST_NAME'], array(
					'user' => 'root',
					'pass' => loadClass('Helper')->getEnv('MYSQL_ROOT_PASSWORD')
				));
				break;

			case 'Pgsql':
				loadFile($class, $cnt_dir);
				$_LOADED_LIBS[$class] = \devilbox\Pgsql::getInstance($GLOBALS['PGSQL_HOST_NAME'], array(
					'user' => loadClass('Helper')->getEnv('PGSQL_ROOT_USER'),
					'pass' => loadClass('Helper')->getEnv('PGSQL_ROOT_PASSWORD'),
					'db' => 'postgres'
				));
				break;

			case 'Redis':
				loadFile($class, $cnt_dir);
				if(loadClass('Helper')->getEnv('REDIS_ROOT_PASSWORD') == ''){
					$_LOADED_LIBS[$class] = \devilbox\Redis::getInstance($GLOBALS['REDIS_HOST_NAME']);
				}else{
					$_LOADED_LIBS[$class] = \devilbox\Redis::getInstance($GLOBALS['REDIS_HOST_NAME'], array(
						'pass' => loadClass('Helper')->getEnv('REDIS_ROOT_PASSWORD'),
					));
				}
				break;

			case 'Memcd':
				loadFile($class, $cnt_dir);
				$_LOADED_LIBS[$class] = \devilbox\Memcd::getInstance($GLOBALS['MEMCD_HOST_NAME']);
				break;

			case 'Mongo':
				loadFile($class, $cnt_dir);
				$_LOADED_LIBS[$class] = \devilbox\Mongo::getInstance($GLOBALS['MONGO_HOST_NAME']);
				break;

			// Get optional docker classes
			default:
				// Redis
				exit('Class does not exist: '.$class);
		}
		return $_LOADED_LIBS[$class];
	}
}
