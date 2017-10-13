<?php
namespace devilbox;

/**
 * @requires devilbox::Logger
 */
class Rabbit extends BaseClass implements BaseInterface
{
	/*********************************************************************************
	 *
	 * Private Variables
	 *
	 *********************************************************************************/

	private $_host = null;
	private $_port = null;
	private $_mgmt_port = null;
	private $_user = null;
	private $_pass = null;
	private $_vhost = null;


	/*********************************************************************************
	 *
	 * Constructor Overwrite
	 *
	 *********************************************************************************/

	/**
	 * Use singleton getInstance() instead.
	 *
	 * @param string $user Username
	 * @param string $pass Password
	 * @param string $host Host
	 */
	public function __construct($hostname, $data = array())
	{
		parent::__construct($hostname, $data);

		// Silence errors and try to connect
		//error_reporting(0);

		$this->_host = $hostname;
		$this->_port = $data['port'];
		$this->_mgmt_port = $data['mgmt_port'];
		$this->_user = $data['user'];
		$this->_pass = $data['pass'];
		$this->_vhost = $data['vhost'];

		$err = '';
		if (!$this->canConnect($err, $hostname)) {
			$this->setConnectError('Failed to connect to RabbitMQ host on '.$hostname);
			$this->setConnectErrno(1);
		}
		//error_reporting(-1);
	}

	/**
	 * Destructor
	 */
	public function __destruct()
	{
	}



	/*********************************************************************************
	 *
	 * Select functions
	 *
	 *********************************************************************************/

	public function getInfo()
	{
		$url = 'http://'.$this->_user.':'.$this->_pass.'@'.$this->_host.':'.$this->_mgmt_port.'/api/overview';
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $url);
		curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT, 1);
		curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER, 1);
		$buffer = curl_exec($curl_handle);
		curl_close($curl_handle);

		if (empty($buffer)) {
			return array();
		} else {
			return json_decode($buffer);
		}
	}



	/*********************************************************************************
	 *
	 * Interface required functions
	 *
	 *********************************************************************************/

	private $_can_connect = array();
	private $_can_connect_err = array();

	private $_name = null;
	private $_version = null;

	public function canConnect(&$err, $hostname, $data = array())
	{
		// Return default if not available
		if (!$this->isAvailable()) {
			return false;
		}

		$err = false;

		// Return if already cached
		if (isset($this->_can_connect[$hostname])) {
			// Assume error for unset error message
			$err = isset($this->_can_connect_err[$hostname]) ? $this->_can_connect_err[$hostname] : true;
			return $this->_can_connect[$hostname];
		}


		$url = 'http://'.$this->_user.':'.$this->_pass.'@'.$hostname.':'.$this->_mgmt_port.'/api/overview';
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $url);
		curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT, 1);
		curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER, 1);
		$buffer = curl_exec($curl_handle);
		curl_close($curl_handle);

		if (empty($buffer)) {
			$err = 'Failed to connect to RabbitMQ host on '.$hostname;
			$this->_can_connect[$hostname] = false;
		} else {
			$info = json_decode($buffer);
			if (isset($info->rabbitmq_version)) {
				$this->_can_connect[$hostname] = true;
			} else {
				$err = 'Failed to connect to RabbitMQ host on '.$hostname;
				$this->_can_connect[$hostname] = false;
			}
		}

		$this->_can_connect_err[$hostname] = $err;
		return $this->_can_connect[$hostname];
	}

	public function getName($default = 'RabbitMQ')
	{
		return $default;
	}

	public function getVersion()
	{
		// Return if already cached
		if ($this->_version !== null) {
			return $this->_version;
		}

		// Return empty if not available
		if (!$this->isAvailable()) {
			$this->_version = '';
			return $this->_version;
		}

		$info = $this->getInfo();
		if (!isset($info->rabbitmq_version)) {
			loadClass('Logger')->error('Could not get RabbitMQ version');
			$this->_version = '';
		} else {
			$this->_version = $info->rabbitmq_version;
		}

		return $this->_version;
	}
}
