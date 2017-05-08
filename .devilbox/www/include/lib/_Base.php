<?php
namespace devilbox;

/**
 * @requires devilbox::Logger
 */
class _Base
{
	/*********************************************************************************
	 *
	 * Statics
	 *
	 *********************************************************************************/


	/** TODO: isAvailable (make instance) */

	private static $_available = array();
	private static $_hostname = array();

	public static function isAvailable($hostname)
	{
		if (!isset(self::$_available[$hostname])) {
			$ip = gethostbyname($hostname);
			self::$_available[$hostname] = ($ip == $hostname) ? false : true;
		}
		return self::$_available[$hostname];
	}

	public static function getIpAddress($hostname)
	{
		if (!isset(self::$_hostname[$hostname])) {
			self::$_hostname[$hostname] = gethostbyname($hostname);
		}
		return self::$_hostname[$hostname];
	}



	/*********************************************************************************
	 *
	 * Instance
	 *
	 *********************************************************************************/

	/**
	 * Connection error string
	 * @var string
	 */
	private $_connect_error = '';

	/**
	 * Connection error code
	 * @var integer
	 */
	private $_connect_errno = 0;

	/**
	 * Error string
	 * @var string
	 */
	private $_error = '';

	/**
	 * Error code
	 * @var integer
	 */
	private $_errno = 0;



	/**
	 * Shorter version to regex select a string.
	 *
	 * @param  string $regex  Regex
	 * @param  string $string String to look in to
	 * @return bool|string    Returns false on error otherwise the string
	 */
	public function egrep($regex, $string)
	{
		$match = array();
		$error = preg_match($regex, $string, $match);

		if ($error === false) {
			return false;
		}

		return isset($match[0]) ? $match[0] : false;
	}


	/**
	 * Set Connection Error Message.
	 *
	 * @param string $error Error Message
	 */
	public function setConnectError($error)
	{
		$this->_connect_error = $error;
	}

	/**
	 * Set Connection Error Code.
	 *
	 * @param integer $errno Error Code
	 */
	public function setConnectErrno($errno)
	{
		$this->_connect_erro = $errno;
	}

	/**
	 * Set Error Message.
	 *
	 * @param string $error Error message
	 */
	public function setError($error)
	{
		$this->_error = $error;
	}

	/**
	 * Set Error Code.
	 *
	 * @param integer $errno Error Code
	 */
	public function setErrno($errno)
	{
		$this->_erro = $errno;
	}

	/**
	 * Return connection error message.
	 *
	 * @return string Error message
	 */
	public function getConnectError()
	{
		return $this->_connect_error;
	}

	/**
	 * Return connection errno code.
	 *
	 * @return integer Error code
	 */
	public function getConnectErrno()
	{
		return $this->_connect_errno;
	}

	/**
	 * Return error message.
	 *
	 * @return string Error message
	 */
	public function getError()
	{
		return $this->_error;
	}

	/**
	 * Return errno code.
	 *
	 * @return integer Error code
	 */
	public function getErrno()
	{
		return $this->_errno;
	}
}
