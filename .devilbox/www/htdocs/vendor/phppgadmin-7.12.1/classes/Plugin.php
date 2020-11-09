<?php
abstract class Plugin {

	/**
	 * Constructor
	 * Register the plugin's functions in hooks of PPA.
	 * @param $language Current phpPgAdmin language.
	 */
	function __construct($language) {
		// Set the plugin's language
		$plugin_directory = "plugins/". $this->get_name();

		if (file_exists("{$plugin_directory}/lang")) {
			if (file_exists("{$plugin_directory}/lang/english.php")) {
				require_once("{$plugin_directory}/lang/english.php");
			}

			if (file_exists("{$plugin_directory}/lang/{$language}.php")) {
				include_once("{$plugin_directory}/lang/{$language}.php");
			}

			$this->lang = $plugin_lang;
		}

		if (file_exists("{$plugin_directory}/conf/config.inc.php")) {
			include_once("{$plugin_directory}/conf/config.inc.php");
			$this->conf = $plugin_conf;
		}
	}

	abstract function get_hooks();

	abstract function get_actions();

	/**
	 * In some page (display, sql, ...), a "return" link will show up if
	 * $_GET['return'] = 'plugin' is given. The "get_subject_params" method
	 * of the plugin designated by $_GET['plugin'] is then called to add needed
	 * parameters in the href URL.
	 * This method can returns parameters based on context from $_REQUEST. See
	 * plugin Report as example.
	 *
	 * @returns an associative of parameter_name => value
	 */
	function get_subject_params() {
		$vars = array();
		return $vars;
	}

	/**
	 * Get the plugin name, that will be used as identification
	 * @return $name
	 */
	function get_name() {
		return $this->name;
	}

	/**
	 * Returns the structure suitable for the method $misc->icon() to print
	 * the given icon.
	 * @param $img - The icon name
	 * @return the information suitable for the method $misc->icon()
	 */
	function icon($img) {
		return array($this->name, $img);
	}
}
?>
