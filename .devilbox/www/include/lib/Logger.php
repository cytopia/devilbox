<?php

namespace devilbox;

/**
 * Log devilbox intranet errors.
 * If logging is not possible, it will generate emails
 * that are handled by mail.php
 */

/**
 * This is going to log into the PHP docker's /tmp directory
 */
class Logger
{

	/*********************************************************************************
	 *
	 * Statics
	 *
	 *********************************************************************************/

	/**
	 * Mysql instance
	 * @var Mysql|null
	 */
	protected static $instance = null;


	/**
	 * Singleton Instance getter.
	 *
	 * @return object|null
	 */
	public static function getInstance()
	{
		if (!isset(static::$instance)) {
			static::$instance = new static();
		}
		return static::$instance;
	}



	/*********************************************************************************
	 *
	 * Private Variables
	 *
	 *********************************************************************************/

	/**
	 * File pointer.
	 * @var resource
	 */
	private $_fp = null;

	/**
	 * Logfile path
	 * @var string
	 */
	private $_logfile = '/tmp/devilbox-intranet/error.log';



	/*********************************************************************************
	 *
	 * Construct/Destructor
	 *
	 *********************************************************************************/

	/**
	 * Private Constructor, so nobody can call it.
	 * Use singleton getInstance() instead.
	 */
	private function __construct()
	{
		// Silence errors
		error_reporting(0);

		$logdir = dirname($this->_logfile);

		// Create log directory if it does not exist
		if (!is_dir($logdir)) {
			if (!mkdir($logdir, 0777, true)) {
				$this->error('Cannot create log directory: '. $logdir);
			}
		}

		// Open logfile in append mode
		if (!($this->_fp = fopen($this->_logfile, 'a+'))) {
			$this->error('Error opening logfile: '. $this->_logfile);
		}

		// Re-enable error reporting
		error_reporting(-1);
	}

	/**
	 * Destructor - closes logfile
	 */
	public function __destruct()
	{
		if ($this->_fp) {
			fclose($this->_fp);
		}
	}


	/*********************************************************************************
	 *
	 * Public functions
	 *
	 *********************************************************************************/



	/**
	 * Log errors
	 * @param  string $message The message to log
	 */
	public function error($message)
	{
		$mail_body = $message."\r\n";

		if (!$this->_fp) {
			return mail('apache@localhost', 'devilbox error', $mail_body);
		}

		$message = date('Y-m-d H:i') . "\n" . $message;
		$message = str_replace("\n", '<br/>', $message);
		$message = $message . "\n";

		if (fwrite($this->_fp, $message) === false) {
			return mail('apache@localhost', 'devilbox error', $mail_body);
		}
	}


	/**
	 * Get all logged messages as array.
	 *
	 * @return false|mixed[] Array of error messages or false if logfile is not writeable
	 */
	public function getAll()
	{
		$lines = array();

		if ($this->_fp) {
			rewind($this->_fp);
			while (($buffer = fgets($this->_fp)) !== false) {
				$lines[] = $buffer;
			}
			return $lines;
		}
		return false;
	}
}
