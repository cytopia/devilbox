<?PHP
// Measure time
$TIME_START = microtime(true);

// Start session
session_start();

// Turn on all PHP errors
error_reporting(-1);


// Shorten DNS timeouts for gethostbyname in case DNS server is down
putenv('RES_OPTIONS=retrans:1 retry:1 timeout:1 attempts:1');


$DEVILBOX_VERSION = 'v3.0.0-beta-0.3';
$DEVILBOX_DATE = '2023-01-02';
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
				// Check if redis is using a password
				$REDIS_ROOT_PASSWORD = '';

				$_REDIS_ARGS = loadClass('Helper')->getEnv('REDIS_ARGS');

				/*
				 * This pattern will match optional quoted string, 'my password' or "my password"
				 * or if there aren't any quotes, it will match up until the next space.
				 */
				$_REDIS_PASS = array();
				preg_match_all('/--requirepass\s+("|\')?(?(1)(.*)|([^\s]*))(?(1)\1|)/', $_REDIS_ARGS, $_REDIS_PASS, PREG_SET_ORDER);

				if (! empty($_REDIS_PASS)) {
					/*
					 * In case the option is specified multiple times, use the last effective one.
					 *
					 * preg_match_all returns a multi-dimensional array, the first level array is in order of which was matched first,
					 * and the password string is either matched in group 2 or group 3 which is always the end of the sub-array.
					 */
					$_REDIS_PASS = end(end($_REDIS_PASS));

					if (strlen($_REDIS_PASS) > 0) {
						$REDIS_ROOT_PASSWORD = $_REDIS_PASS;
					}
				}

				loadFile($class, $cnt_dir);
				if ($REDIS_ROOT_PASSWORD == '') {
					$_LOADED_LIBS[$class] = \devilbox\Redis::getInstance($GLOBALS['REDIS_HOST_NAME']);
				} else {
					$_LOADED_LIBS[$class] = \devilbox\Redis::getInstance($GLOBALS['REDIS_HOST_NAME'], array(
						'pass' => $REDIS_ROOT_PASSWORD,
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
				// Unknown class
				exit('Class does not exist: '.$class);
		}
		return $_LOADED_LIBS[$class];
	}
}
