<?php
namespace devilbox;

class Dns extends BaseClass implements BaseInterface
{

	/*********************************************************************************
	 *
	 * Interface required functions
	 *
	 *********************************************************************************/

	private $_version = null;
	private $_can_connect = array();
	private $_can_connect_err = array();


	public function canConnect(&$err, $hostname, $data = array())
	{
		$err = false;

		// Return if already cached
		if (isset($this->_can_connect[$hostname])) {
			// Assume error for unset error message
			$err = isset($this->_can_connect_err[$hostname]) ? $this->_can_connect_err[$hostname] : true;
			return $this->_can_connect[$hostname];
		}

		$version = $this->getVersion();

		if (strlen($version)) {
			$this->_can_connect[$hostname] = true;
		} else {
			$err = 'Could not connect to Bind via hostname: '.$hostname;
			$this->_can_connect[$hostname] = false;
		}

		$this->_can_connect_err[$hostname] = $err;
		return $this->_can_connect[$hostname];
	}

	public function getName($default = 'Bind')
	{
		return $default;
	}

	public function getVersion()
	{
		// Return if already cached
		if ($this->_version !== null) {
			return $this->_version;
		}

		$cmd = 'dig +time=1 +tries=1 @'.$this->getIpAddress().' version.bind chaos TXT | grep -iE "^version\.bind.*TXT"';

		$output = loadClass('Helper')->exec($cmd);

		$version = loadClass('Helper')->egrep('/"[0-9.-]+.*"/', $output);
		$version = loadClass('Helper')->egrep('/[0-9.-]+[0-9]+/', $version);

		// Cache and return
		$this->_version = $version ? $version : '';
		return $this->_version;
	}
}
