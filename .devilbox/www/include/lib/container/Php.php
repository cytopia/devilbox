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
		$output = loadClass('Helper')->exec('git --version', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getComposerVersion()
	{
		$output = loadClass('Helper')->exec('composer --version', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getDrushVersion()
	{
		$output = loadClass('Helper')->exec('drush --version', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getDrushConsoleVersion()
	{
		$output = loadClass('Helper')->exec('drush-console --version', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getNodeVersion()
	{
		$output = loadClass('Helper')->exec('node --version', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getNpmVersion()
	{
		$output = loadClass('Helper')->exec('npm --version', $output);
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
