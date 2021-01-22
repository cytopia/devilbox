<?php
require_once('classes/Plugin.php');

class GuiControl extends Plugin {

	/**
	 * Attributes
	 */
	protected $name = 'GuiControl';
	protected $lang;
	protected $conf;

	/**
	 * Constructor
	 * Call parent constructor, passing the language that will be used.
	 * @param $language Current phpPgAdmin language. If it was not found in the plugin, English will be used.
	 */
	function __construct($language) {
		parent::__construct($language);
	}

	/**
	 * This method returns the functions that will hook in the phpPgAdmin core.
	 * To do include a function just put in the $hooks array the following code:
	 * 'hook' => array('function1', 'function2').
	 *
	 * Example:
	 * $hooks = array(
	 *	'toplinks' => array('add_plugin_toplinks'),
	 *	'tabs' => array('add_tab_entry'),
	 *  'action_buttons' => array('add_more_an_entry')
	 * );
	 *
	 * @return $hooks
	 */
	function get_hooks() {
		$hooks = array(
			'toplinks' => array('filer_toplinks'),
			'tabs' => array('filter_tabs'),
			'trail' => array('filter_trail'),
			'navlinks' => array('filter_navlinks'),
			'actionbuttons' => array('filter_actionbuttons'),
			'tree' => array('filter_tree')
		);
		return $hooks;
	}

	/**
	 * This method returns the functions that will be used as actions.
	 * To do include a function that will be used as action, just put in the $actions array the following code:
	 *
	 * $actions = array(
	 *	'show_page',
	 *	'show_error',
	 * );
	 *
	 * @return $actions
	 */
	function get_actions() {
		$actions = array(
			'filer_toplinks',
			'filter_tabs',
			'filter_trail',
			'filter_navlinks',
			'filter_actionbuttons',
			'filter_tree',
		);
		return $actions;
	}

    function filer_toplinks(&$f_params) {
        if (!isset($this->conf['top_links']))
            return;

        $top_links = &$f_params['toplinks'];

        foreach ($this->conf['top_links'] as $link => $enabled)
            if (isset ($top_links[$link])
                && ($enabled === false)
            )
                unset($top_links[$link]);

        return;
    }

	function filter_tabs(&$f_params) {
        $section = $f_params['section'];
        $tabs = &$f_params['tabs'];

        if (!isset($this->conf['tab_links'][$section]))
            return;

        foreach ($this->conf['tab_links'][$section] as $link => $enabled)
            if (isset ($tabs[$link])
                && ($enabled === false)
            )
                unset($tabs[$link]);
        return;
    }
   
	function filter_trail(&$f_params) {
        if (!isset($this->conf['trail_links']))
            return;

        if ($this->conf['trail_links'] === false)
            $f_params['trail'] = array();

        return;
    }
   
	function filter_navlinks(&$f_params) {
        $place = $f_params['place'];
        $navlinks = &$f_params['navlinks'];

        if (! isset($this->conf['navlinks'][$place]))
            return;

        foreach ($this->conf['navlinks'][$place] as $link => $enabled)
            if (isset ($navlinks[$link])
                && ($enabled === false)
            )
                unset($navlinks[$link]);
        return;
    }
   
	function filter_actionbuttons(&$f_params) {
        $place = $f_params['place'];
        $actions = &$f_params['actionbuttons'];

        if (! isset($this->conf['actionbuttons'][$place]))
            return;

        foreach ($this->conf['actionbuttons'][$place] as $link => $enabled)
            if (isset ($actions[$link])
                && ($enabled === false)
            )
                unset($actions[$link]);
        return;
    }

	function filter_tree() {
        return;
    }
}
?>