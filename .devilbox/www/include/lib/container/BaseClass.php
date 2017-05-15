<?php
namespace devilbox;

/**
 * @requires devilbox::Logger
 */
class BaseClass
{

	/*********************************************************************************
	 *
	 * Statics
	 *
	 *********************************************************************************/

	/**
	 * Class instance
	 * @var array
	 */
	private static $_instance = array();


	/**
	 * Generic singleton instance getter.
	 * Make sure to overwrite this in your class
	 * for a more complex initialization.
	 *
	 * @param string $hostname Hostname
	 * @param array  $data Additional data (if required)
	 * @return object|null
	 */
	public static function getInstance($hostname, $data = array())
	{
		if (!isset(self::$_instance[$hostname])) {
			self::$_instance[$hostname] = new static($hostname, $data);
		}
		return self::$_instance[$hostname];
	}



	/*********************************************************************************
	 *
	 * Instance
	 *
	 *********************************************************************************/

	/**
	 * Hostname
	 * @var string
	 */
	private $_hostname = null;

	/**
	 * Container is running
	 * @var boolean
	 */
	private $_available = null;

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



	/*********************************************************************************
	 *
	 * Private constructor for singleton
	 *
	 *********************************************************************************/

	/**
	 * DO NOT CALL ME!
	 * Use singleton getInstance() instead.
	 *
	 * @param string $user Username
	 * @param string $pass Password
	 * @param string $host Host
	 */
	public function __construct($hostname, $data = array())
	{
		$this->_hostname = $hostname;
	}



	/*********************************************************************************
	 *
	 * Public Default Interface Implementations
	 *
	 *********************************************************************************/

	/**
	 * Check if this container is available.
	 * Note, this is a very basic check to see if its IP address
	 * can be resolved. Implement a better check in the actual class
	 * if it is required.
	 *
	 * @return boolean available
	 */
	public function isAvailable()
	{
		// Request was already done before and is cached
		if (isset($this->_available[$this->_hostname])) {
			return $this->_available[$this->_hostname];
		}

		// New request, check if hostname was set
		if ($this->_hostname === null) {
			loadClass('Logger')->error('Hostname has not been initialized. Cannot determine if it is available.');
			$this->_available = false;
			return false;
		}

		// New request, generic check
		$ip = $this->getIpAddress();

		$this->_available[$this->_hostname] = ($ip && $ip != $this->_hostname) ? true : false;

		return $this->_available[$this->_hostname];
	}

	/**
	 * Retrieve the IP address of the container.
	 *
	 * @return string|boolean IP address or false
	 */
	public function getIpAddress()
	{
		// Check if hostname was set
		if ($this->_hostname === null) {
			loadClass('Logger')->error('Hostname has not been initialized. Cannot determine IP address.');
			return false;
		}

		return loadClass('Helper')->getIpAddress($this->_hostname);
	}



	/*********************************************************************************
	 *
	 * Connection Error Getter/Setter
	 *
	 *********************************************************************************/

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
