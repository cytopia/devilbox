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

	private $_record_separator = "====================\n";

	/*********************************************************************************
	 *
	 * Statics
	 *
	 *********************************************************************************/

	/**
	 * Logger instance
	 * @var Logger|null
	 */
	private static $_instance = null;


	/**
	 * Singleton Instance getter.
	 *
	 * @return object|null
	 */
	public static function getInstance()
	{
		if (self::$_instance === null) {
			self::$_instance = new self();
		}
		return self::$_instance;
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
		ob_start();
		$backtrace = debug_backtrace();
		// Remove current (first) trace from array
		for ($i=1; $i<count($backtrace); $i++) {
			$backtrace[$i-1] = $backtrace[$i];
		}
		var_dump($backtrace);
		$body = ob_get_contents();
		ob_end_clean();

		if ($body) {
			$mail_body = $message."\r\n".$body."\r\n";
		} else {
			$mail_body = $message."\r\n";
		}

		if (!$this->_fp) {
			return mail('apache@localhost', 'devilbox error', $mail_body);
		}

		$message = date('Y-m-d H:i') . "\n" .
						$message. "\n" .
						$body . "\n" .
						$this->_record_separator;

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

		$pos = 0;
		$num = 0;

		$handle = fopen($this->_logfile, 'r');
		if ($handle) {
			while (($buffer = fgets($handle)) !== false) {
				if ($pos == 0) {
					$lines[$num]['date'] = $buffer;
					$pos++;
				} else if ($pos == 1) {
					$lines[$num]['head'] = $buffer;
					$pos++;
				} else {
					// New entry
					if (substr_count($buffer, $this->_record_separator)) {
						$num++;
						$pos = 0;
						continue;
					// Still current entry, but body part
					} else {
						$lines[$num]['body'][] = $buffer;
					}
				}
			}
		}
		fclose($handle);
		return $lines;
	}

	public function countErrors()
	{
		$count = 0;
		$handle = fopen($this->_logfile, 'r');
		while (!feof($handle)) {
			$line = fgets($handle);
			if (substr_count($line, $this->_record_separator)) {
				$count++;
			}
		}
		fclose($handle);
		return $count;
	}
}
