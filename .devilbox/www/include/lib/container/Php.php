<?php
namespace devilbox;

class Php extends BaseClass implements BaseInterface
{

	/*********************************************************************************
	 *
	 * PHP  Select functions
	 *
	 *********************************************************************************/

	public function getConfig($key = null)
	{
		// Get all configs as array
		if ($key === null) {
			return ini_get_all();
		} else {
			return ini_get($key);
		}
	}
	public function getUid()
	{
		$output = loadClass('Helper')->exec('id', $output);

		$uid = loadClass('Helper')->egrep('/uid=[0-9]+/', $output);
		$uid = loadClass('Helper')->egrep('/[0-9]+/', $uid);
		return $uid;
	}
	public function getGid()
	{
		$output = loadClass('Helper')->exec('id', $output);

		$uid = loadClass('Helper')->egrep('/gid=[0-9]+/', $output);
		$uid = loadClass('Helper')->egrep('/[0-9]+/', $uid);
		return $uid;
	}
	public function getGitVersion()
	{
		$output = loadClass('Helper')->exec('git --version 2>/dev/null', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getComposerVersion()
	{
		$output = loadClass('Helper')->exec('composer --version 2>/dev/null', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getDrushVersion($version)
	{
		$output = loadClass('Helper')->exec('drush'.$version.' --version 2>/dev/null', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getDrupalConsoleVersion()
	{
		$output = loadClass('Helper')->exec('drupal --version  2>/dev/null | sed -r "s/\x1B\[([0-9]{1,2}(;[0-9]{1,2})?)?[mGK]//g"', $output);
		return loadClass('Helper')->egrep('/[0-9.]+[-rc0-9.]*/', $output);
	}
	public function getNodeVersion()
	{
		$output = loadClass('Helper')->exec('node --version 2>/dev/null', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getNpmVersion()
	{
		$output = loadClass('Helper')->exec('npm --version 2>/dev/null', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getLaravelVersion()
	{
		$output = loadClass('Helper')->exec('laravel --version 2>/dev/null', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getMdsVersion()
	{
		$output = loadClass('Helper')->exec('mysqldump-secure --version 2>/dev/null', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getPhalconVersion()
	{
		$output = loadClass('Helper')->exec('phalcon --version 2>/dev/null', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getSymfonyVersion()
	{
		$output = loadClass('Helper')->exec('symfony -V 2>/dev/null | tr -d "[:cntrl:]" | sed "s/\[[0-9]*m//g"', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getWpcliVersion()
	{
		$output = loadClass('Helper')->exec('wp --version 2>/dev/null', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}



	/*********************************************************************************
	 *
	 * Interface required functions
	 *
	 *********************************************************************************/

	public function canConnect(&$err, $hostname, $data = array())
	{
		// PHP can always connect, otherwise you could not see anything.
		$err = false;
		return true;
	}

	public function getName($default = 'PHP')
	{
		if (defined('HHVM_VERSION')) {
			return 'HHVM';
		}
		return $default;
	}

	public function getVersion()
	{
		if (defined('HHVM_VERSION')) {
			return HHVM_VERSION . ' php-'.str_replace('-hhvm', '', phpversion());
		} else {
			return phpversion();
		}
	}
}
