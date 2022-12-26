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
		$htdocs		= loadClass('Helper')->getEnv('HTTPD_DOCROOT_DIR');
		$docroot	= $this->_docRoot . DIRECTORY_SEPARATOR . $vhost . DIRECTORY_SEPARATOR . $htdocs;
		$domain		= $vhost . '.' . $this->getTldSuffix();
		$url		= 'http://'.$domain;
		$error		= array();


		$backend    = $this->getVhostBackend($vhost);
		$pos_def    = strpos($backend, 'default');
		$pos_phpfpm = strpos($backend, 'tcp://');

		// Only if backend 'default' or 'phpfpm', we need a htdocs/ directory
		if ( ($pos_def !== false && $pos_def == 0) || ($pos_phpfpm !== false && $pos_phpfpm == 0) ) {
			// 1. Check htdocs folder
			if (!$this->_is_valid_dir($docroot)) {
				$error[] = 'error';
				$error[] = 'Missing <strong>'.$htdocs.'</strong> directory in: <strong>'.loadClass('Helper')->getEnv('HOST_PATH_HTTPD_DATADIR').'/'.$vhost.'/</strong>';
			}
		}

		// 2. Check internal DNS server
		$err = false;
		if (!$this->canConnect($err, $domain)) {
			$error[] = 'warning';
			$error[] = 'Cannot connect to '.$domain.'.<br/>DNS server not running?<br/>You won\'t be able to work inside the PHP container.';
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

					$vhosts[$directory] = array(
						'name'		=> $directory,
						'domain'	=> $directory .'.' . $this->getTldSuffix(),
						'href'		=> 'http://' . $directory . '.' . $this->getTldSuffix()
					);
				}
			}
		}

		ksort($vhosts);

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

	public function getVhostgenTemplateName()
	{
		$httpd = strtolower($this->getName());
		if ($httpd == 'nginx') {
			return 'nginx.yml';
		}
		$version = $this->getVersion();

		if (preg_match('/^2\.2.*/', $version)) {
			return 'apache22.yml';
		} elseif (preg_match('/^2\.4.*/', $version)) {
			return 'apache24.yml';
		} else {
			return false;
		}
	}

	public function getVhostgenTemplatePath($vhost)
	{
		if (!($name = $this->getVhostgenTemplateName())) {
			return false;
		}
		$dir = loadClass('Helper')->getEnv('HTTPD_TEMPLATE_DIR');
		$cfg = '/shared/httpd/'.$vhost.'/'.$dir.'/'.$name;

		if (is_file($cfg)) {
			return $cfg;
		}
		return false;
	}

	public function getVhostBackend($vhost)
	{
		$dir = loadClass('Helper')->getEnv('HTTPD_TEMPLATE_DIR');
		$name = 'backend.cfg';
		$file = '/shared/httpd/'.$vhost.'/'.$dir.'/'.$name;
		if (!file_exists($file)) {
			return 'default';
		}

		$fp = fopen($file, 'r');
		$cont = stream_get_contents($fp);
		fclose($fp);

		// conf:<type>:<proto>:<server>:<port>
		$arr = explode(':', $cont);

		$type = $arr[1];
		$prot = $arr[2];
		$addr = '';  // this may contain ':' itself due to IPv6 addresses
		for ($i=3; $i<(count($arr)-1); $i++) {
			$addr .= $arr[$i];
		}
		$port = $arr[count($arr) - 1];

		return $prot.'://'.$addr.':'.$port;
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
