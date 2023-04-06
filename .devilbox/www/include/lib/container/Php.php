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
	public function getAngularCliVersion()
	{
		$output = loadClass('Helper')->exec('ng version 2>/dev/null | grep -i "^Angular CLI" | head -1', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getAsgardCmsInstallerVersion()
	{
		$output = loadClass('Helper')->exec('asgardcms --version --no-ansi 2>/dev/null', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getCodeceptionVersion()
	{
		$output = loadClass('Helper')->exec('codecept --version --no-ansi 2>/dev/null | grep -i ^Codecept', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getComposerVersion()
	{
		$output = loadClass('Helper')->exec('composer --version 2>/dev/null', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getDeployerVersion()
	{
		$output = loadClass('Helper')->exec('dep --version --no-ansi 2>/dev/null | grep -i ^Deploy', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getGitVersion()
	{
		$output = loadClass('Helper')->exec('git --version 2>/dev/null', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getGruntCliVersion()
	{
		$output = loadClass('Helper')->exec('grunt --version 2>/dev/null', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getGulpVersion()
	{
		$output = loadClass('Helper')->exec('gulp --version --no-color 2>/dev/null | head -1', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getLaravelInstallerVersion()
	{
		$output = loadClass('Helper')->exec('laravel --version --no-ansi 2>/dev/null', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getLaravelLumenVersion()
	{
		$output = loadClass('Helper')->exec('lumen --version --no-ansi 2>/dev/null', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getMdsVersion()
	{
		$output = loadClass('Helper')->exec('mysqldump-secure --version 2>/dev/null', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getMupdfToolsVersion()
	{
		$output = loadClass('Helper')->exec('mutool -v 2>&1', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
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
	public function getPhalconDevtoolsVersion()
	{
		$output = loadClass('Helper')->exec('phalcon --version 2>/dev/null', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getPhpunitVersion()
	{
		$output = loadClass('Helper')->exec('phpunit --version 2>/dev/null', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getStylelintVersion()
	{
		$output = loadClass('Helper')->exec('stylelint --version 2>/dev/null', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getSymfonyCliVersion()
	{
		$output = loadClass('Helper')->exec('symfony -V 2>/dev/null | tr -d "[:cntrl:]" | sed "s/\[[0-9]*m//g"', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getVueCliVersion()
	{
		$output = loadClass('Helper')->exec('vue --version 2>/dev/null', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getWebpackCliVersion()
	{
		$output = loadClass('Helper')->exec('webpack-cli --version --no-stats --no-target --no-watch --no-color 2>/dev/null | grep webpack-cli', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getWpcliVersion()
	{
		$output = loadClass('Helper')->exec('wp --version 2>/dev/null | grep -i ^WP', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getWscatVersion()
	{
		$output = loadClass('Helper')->exec('wscat --version 2>/dev/null | head -1', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	public function getYarnVersion()
	{
		$output = loadClass('Helper')->exec('yarn --version 2>/dev/null', $output);
		return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	}
	//public function getDrushVersion($version)
	//{
	//	$output = loadClass('Helper')->exec('drush'.$version.' --version 2>/dev/null', $output);
	//	return loadClass('Helper')->egrep('/[0-9.]+/', $output);
	//}
	//public function getDrupalConsoleVersion()
	//{
	//	$output = loadClass('Helper')->exec('drupal --version  2>/dev/null | sed -r "s/\x1B\[([0-9]{1,2}(;[0-9]{1,2})?)?[mGK]//g"', $output);
	//	return loadClass('Helper')->egrep('/[0-9.]+[-rc0-9.]*/', $output);
	//}



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
