<?php
namespace devilbox;

/**
 * Interface
 */
interface _iBase
{
	/**
	 * Get singleton instance
	 * @return Object
	 */
	public static function getInstance($host, $user, $pass);

	public static function isAvailable($hostname);
	public static function testConnection(&$err, $host, $user, $pass);
	public static function getIpAddress($hostname);


	public function getName($default);
	public function getVersion();
}
