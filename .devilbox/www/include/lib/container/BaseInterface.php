<?php
namespace devilbox;

/**
 * Interface
 *
 * Must be implemented by all devilbox service classes.
 * @see _Base.php: Most functions will have a default implementationin the mother class
 */
interface BaseInterface
{
	/**
	 * Get singleton instance
	 *
	 * @param  string $hostname Internal Hostname of docker (as described in docker-compose.yml)
	 * @param  array  $data     Additional data required for the container (username, password, db...)
	 * @return object           Returns class object
	 */
	public static function getInstance($hostname, $data = array());


	/**
	 * The constructor of this type must be implemented by each class.
	 * Note, the constructor MUST set the hostname!
	 *
	 * @param  string $hostname Internal Hostname of docker (as described in docker-compose.yml)
	 * @param  array  $data     Additional data required for the container (username, password, db...)
	 */
	public function __construct($hostname, $data = array());


	/**
	 * Check if the PHP container can connect to the specified service.
	 * Additional arguments (user, pass, port, etc) can be specified
	 * withing the $data array.
	 *
	 * @param  string|bool &$err     Connection error string or false
	 * @param  string      $hostname Hostname to connect to (127.0.0.1, host, IP, ...)
	 * @param  array       $data     Optional data (user, pass, port...)
	 * @return bool             Can we connect?
	 */
	public function canConnect(&$err, $hostname, $data = array());


	/**
	 * Check if the container is running/available
	 *
	 * @return boolean Running state
	 */
	public function isAvailable();


	/**
	 * Get the IP Address of the container
	 *
	 * @return string|boolean IP Address or false if it cannot be determined
	 */
	public function getIpAddress();


	/**
	 * Retrieve name of the service the docker container provides.
	 *
	 * @param  string $default Default name if it can't be found
	 * @return string          Name
	 */
	public function getName($default);


	/**
	 * Get version string of the service the docker container provides.
	 *
	 * @return string Version string
	 */
	public function getVersion();
}
