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

$MYSQL_HOST_NAME	= 'db';
$MYSQL_HOST_ADDR	= gethostbyname($MYSQL_HOST_NAME);


//
// Load files
//
require $LIB_DIR . DIRECTORY_SEPARATOR . 'Logger.php';
require $LIB_DIR . DIRECTORY_SEPARATOR . 'Docker.php';
require $LIB_DIR . DIRECTORY_SEPARATOR . 'Mysql.php';


//
// Instantiate Basics
//
$Logger	= \devilbox\Logger::getInstance();
$Docker = \devilbox\Docker::getInstance();
$MySQL	= \devilbox\Mysql::getInstance('root', $Docker->getEnv('MYSQL_ROOT_PASSWORD'), $MYSQL_HOST_ADDR);




// VirtualHost DNS check
// Temporarily disable due to:
// https://github.com/cytopia/devilbox/issues/8
$ENABLE_VHOST_DNS_CHECK = false;
