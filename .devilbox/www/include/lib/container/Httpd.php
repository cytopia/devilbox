<?php
namespace devilbox;

/**
 * @requires devilbox::Logger
 */
class Httpd extends BaseClass implements BaseInterface
{

	private $_docRoot = '/shared/httpd';

	/*********************************************************************************
	 *
	 * Select/Check Functions
	 *
	 *********************************************************************************/

	/**
	 * Check single mass virtual host
	 *
	 * @param  string $vhost Virtual Host name
	 * @return string Errors
	 */
	public function checkVirtualHost($vhost)
	{
		$htdocs		= $this->_docRoot . DIRECTORY_SEPARATOR . $vhost . DIRECTORY_SEPARATOR . 'htdocs';
		$domain		= $vhost . '.' . $this->getTldSuffix();
		$url		= 'http://'.$domain;
		$error		= array();

		// 1. Check htdocs folder
		if (!$this->_is_valid_dir($htdocs)) {
			$error[] = 'Missing <strong>htdocs</strong> directory in: <strong>'.loadClass('Helper')->getEnv('HOST_PATH_HTTPD_DATADIR').'/'.$vhost.'/</strong>';
		}

		if ($GLOBALS['ENABLE_VHOST_DNS_CHECK']) {

			// 2. Check /etc/resolv DNS entry
			$output;
			if (loadClass('Helper')->exec('getent hosts '.$domain, $output) !== 0) {
				$error[] = 'Missing entry in <strong>/etc/hosts</strong>:<br/><code>127.0.0.1 '.$domain.'</code>';
			}


			// 3. Check correct /etc/resolv entry
			$dns_ip = '127.0.0.1';
			if (isset($output[0])) {
				$tmp = explode(' ', $output[0]);
				if (isset($tmp[0])) {
					$dns_ip = $tmp[0];
				}
			}
			if ($dns_ip != '127.0.0.1') {
				$error[] = 'Error in <strong>/etc/hosts</strong><br/>'.
							'Found:<br/>'.
							'<code>'.$dns_ip.' '.$domain.'</code><br/>'.
							'But it should be:<br/>'.
							'<code>127.0.0.1 '.$domain.'</code><br/>';
			}
		}

		if (is_array($error) && count($error)) {
			return implode('<br/>', $error);
		} else {
			return '';
		}
	}



	/**
	 * Get all mass virtual Hosts by directories
	 *
	 * @return mixed[]
	 */
	public function getVirtualHosts()
	{
		$docRoot	= $this->_docRoot;
		$vhosts		= array();

		if ($handle = opendir($docRoot)) {
			while (false !== ($directory = readdir($handle))) {
				if ($this->_is_valid_dir($docRoot . DIRECTORY_SEPARATOR . $directory) && $directory != '.' && $directory != '..') {

					$vhosts[] = array(
						'name'		=> $directory,
						'domain'	=> $directory .'.' . $this->getTldSuffix(),
						'href'		=> 'http://' . $directory . '.' . $this->getTldSuffix()
					);
				}
			}
		}
		return $vhosts;
	}


	public function getTldSuffix()
	{
		return loadClass('Helper')->getEnv('TLD_SUFFIX');
	}

	/**
	 * Get HTTP port.
	 *
	 * @return string
	 */
	public function getPort()
	{
		$port = loadClass('Helper')->getEnv('HOST_PORT_HTTPD');

		if ( empty($port) ) {
			return '';
		}

		if ( $port == 80 ) {
			return '';
		}

		return ":$port";
	}

	/*********************************************************************************
	 *
	 * Interface required functions
	 *
	 *********************************************************************************/

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

		// Silence errors and try to connect
		$url = 'http://'.$hostname.'/'.$GLOBALS['DEVILBOX_API_PAGE'];
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($http_code == 0) {
			$err = 'Failed to connect to Httpd host on '.$hostname;
			$this->_can_connect[$hostname] = false;
		} else {
			$this->_can_connect[$hostname] = true;
		}

		$this->_can_connect_err[$hostname] = $err;
		return $this->_can_connect[$hostname];
	}

	public function getName($default = 'Httpd')
	{
		$name = loadClass('Helper')->egrep('/[a-zA-Z0-9]+/', $_SERVER['SERVER_SOFTWARE']);
		if (!$name) {
			loadClass('Logger')->error('Could not get Httpd name');
			return $default;
		}
		return $name;
	}

	public function getVersion()
	{
		$version = loadClass('Helper')->egrep('/[.0-9]+/', $_SERVER['SERVER_SOFTWARE']);
		if (!$version) {
			loadClass('Logger')->error('Could not get Httpd version');
			return '';
		}
		return $version;
	}



	/*********************************************************************************
	 *
	 * Private functions
	 *
	 *********************************************************************************/

	/**
	 * Check if the directory exists or
	 * in case of a symlink the target is an
	 * existing directory.
	 *
	 * @param  string $path The path.
	 * @return boolean
	 */
	private function _is_valid_dir($path)
	{
		return (is_dir($path) || (is_link($path) && is_dir(readlink($path))));
	}
}
