<?php
require_once('./classes/Plugin.php');
require_once('./plugins/Report/classes/Reports.php');

class Report extends Plugin {

	/**
	 * Attributes
	 */
	protected $name = 'Report';
	protected $lang;
	protected $conf = array();
	protected $_reportsdb = null;

	/**
	 * Constructor
	 * Call parent constructor, passing the language that will be used.
	 * @param $language Current phpPgAdmin language. If it was not found in the plugin, English will be used.
	 */
	function __construct($language) {

		/* loads $this->lang and $this->conf */
		parent::__construct($language);

		/* default values */
		if (! isset($this->conf['reports_db'])) {
			$this->conf['reports_db'] = 'phppgadmin';
		}
		if (! isset($this->conf['reports_schema'])) {
			$this->conf['reports_schema'] = 'public';
		}
		if (! isset($this->conf['reports_table'])) {
			$this->conf['reports_table'] = 'ppa_reports';
		}
		if (! isset($this->conf['owned_reports_only'])) {
			$this->conf['owned_reports_only'] = false;
		}
	}

	function get_reportsdb() {
		if ($this->_reportsdb === null) {
			$status = 0;
			$this->_reportsdb = new Reports($this->conf, $status);

			if ($status !== 0) {
				global $misc;
				$misc->printHeader($this->lang['strreports']);
				$misc->printBody();
				$misc->printTrail('server');
				$misc->printTabs('server','reports');
				$misc->printMsg($this->lang['strnoreportsdb']);
				$misc->printFooter();
				exit;
			}
		}

		return $this->_reportsdb;
	}

	/**
	 * This method returns the functions that will hook in the phpPgAdmin core.
	 * To include a function just put in the $hooks array the following code:
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
			'tabs' => array('add_plugin_tabs'),
			'trail' => array('add_plugin_trail'),
			'navlinks' => array('plugin_navlinks')
		);
		return $hooks;
	}

	/**
	 * This method returns the functions that will be used as actions.
	 * To include a function that will be used as action, just put in the $actions array the following code:
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
			'save_edit',
			'edit',
			'properties',
			'save_create',
			'create',
			'drop',
			'confirm_drop',
			'execute',
			'default_action'
		);
		return $actions;
	}

	/**
	 * Add plugin in the tabs
	 * @param $plugin_functions_parameters
	 */
	function add_plugin_tabs(&$plugin_functions_parameters) {
		global $misc;

		$tabs = &$plugin_functions_parameters['tabs'];

		if ($plugin_functions_parameters['section'] == 'server') {
			$tabs['report_plugin'] = array (
				'title' => $this->lang['strplugindescription'],
				'url' => 'plugin.php',
				'urlvars' => array(
					'subject' => 'server',
					'action' => 'default_action',
					'plugin' => $this->name
				),
				'hide' => false,
				'icon' => $this->icon('Report')
			);
		}

		if ($plugin_functions_parameters['section'] == 'report') {
			$tabs['report_plugin'] = array (
				'title' => $this->lang['strplugindescription'],
				'url' => 'plugin.php',
				'urlvars' => array(
					'subject' => 'server',
					'action' => 'default_action',
					'plugin' => $this->name
				),
				'hide' => false,
				'icon' => $this->icon('Report')
			);
		}
	}

	/**
	 * Add plugin in the trail
	 * @param $plugin_functions_parameters
	 */
	function add_plugin_trail(&$plugin_functions_parameters) {
		global $misc;
		$trail = &$plugin_functions_parameters['trail'];
		$done = false;
		$subject = '';
		if (isset($_REQUEST['subject'])) {
			$subject = $_REQUEST['subject'];
		}

		$action = '';
		if (isset($_REQUEST['action'])) {
			$action = $_REQUEST['action'];
		}

		if (isset($_REQUEST['plugin']) and $_REQUEST['plugin'] == 'Report') {
			$url = array (
				'url' => 'plugin.php',
				'urlvars' => array (
					'plugin' => $this->name,
					'action' => 'default_action'
				)
			);
			$trail['report_plugin'] = array (
				'title' => $this->lang['strreport'],
				'text' => $this->lang['strreport'],
				'url'   => $misc->getActionUrl($url, $_REQUEST, null, false),
				'icon' => $this->icon('Reports')
			);
		}

		if (isset($_REQUEST['plugin'])
			and $_REQUEST['plugin'] == 'Report'
			and $action != 'default_action'
			and in_array($action, $this->get_actions())
		) {

			$url = array (
				'url' => 'plugin.php',
				'urlvars' => array (
					'plugin' => $this->name,
					'action' => 'properties',
					'report_id' => field('report_id'),
				)
			);

			if (isset($_REQUEST['report']))
				$url['urlvars']['report'] = field('report');

			$trail['report_plugin_name'] = array (
				'title' => $this->lang['strreport'],
				'text' => $this->lang['strreport'],
				'url'   => $misc->getActionUrl($url, $_REQUEST, null, false),
				'icon' => $this->icon('Report')
			);

			if (isset($_REQUEST['report']))
				$trail['report_plugin_name']['text'] = $_REQUEST['report'];

		}
	}

	/**
	 * Add plugin in the navlinks
	 * @param $plugin_functions_parameters
	 */
	function plugin_navlinks(&$params) {
		global $misc, $lang;

		if (
			($params['place'] == 'sql-form'
				or $params['place'] == 'display-browse')
			and ( isset($params['env']['rs'])
				 and is_object($params['env']['rs'])
				 and $params['env']['rs']->recordCount() > 0))
		{
			if ( ! (isset($_REQUEST['plugin'])
					and $_REQUEST['plugin'] == $this->name)
			) {
				/* ResultSet doesn't come from a plugin:
				 * show a create report link. */
				$params['navlinks']['report_link'] = array (
					'attr'=> array (
						'href' => array (
							'url' => 'plugin.php',
							'urlvars' => array (
								'plugin' => $this->name,
								'action' => 'create',
								'server' => $_REQUEST['server'],
								'database' => $_REQUEST['database'],
							)
						)
					),
					'content' => $this->lang['strcreatereport']
				);

				if (isset($_REQUEST['paginate']))
				$params['navlinks']['report_link']['attr']['href']['urlvars']['paginate']
					= $_REQUEST['paginate'];

				if (!empty($_SESSION['sqlquery'])) {
					$params['navlinks']['report_link']['attr']['href']['urlvars']['fromsql']
						= 1;
				}
				else {
					if (isset($_REQUEST['subject'])
						and isset($_REQUEST[$_REQUEST['subject']]))
					{
						$params['navlinks']['report_link']['attr']['href']['urlvars']['subject']
							= $_REQUEST['subject'];
						$params['navlinks']['report_link']['attr']['href']['urlvars'][$_REQUEST['subject']]
							= $_REQUEST[$_REQUEST['subject']];

						$params['navlinks']['report_link']['attr']['href']['urlvars']['sortkey']
							= isset($_REQUEST['sortkey']) ? $_REQUEST['sortkey'] : '';

						$params['navlinks']['report_link']['attr']['href']['urlvars']['sortdir']
							= isset($_REQUEST['sortdir']) ? $_REQUEST['sortdir'] : '';
					}
					else {
						unset($params['navlinks']['report_link']);
					}
				}
			}
			else {
				/* ResultSet comes from a plugin:
				 * show a edit report link. */
				$params['navlinks']['report_link'] = array (
					'attr'=> array (
						'href' => array (
							'url' => 'plugin.php',
							'urlvars' => array (
								'plugin' => $this->name,
								'action' => 'edit',
								'server' => $_REQUEST['server'],
								'database' => $_REQUEST['database'],
								'report_id' => $_REQUEST['report_id']
							)
						)
					),
					'content' => $this->lang['streditreport']
				);

				/* edit collapse link to add report related vars */
				$params['navlinks']['collapse']['attr']['href']['urlvars']
					['plugin'] = $this->name;
				$params['navlinks']['collapse']['attr']['href']['urlvars']
					['report_id'] = $_REQUEST['report_id'];
				$params['navlinks']['collapse']['attr']['href']['urlvars']
					['report'] = $_REQUEST['report'];

				/* edit refresh link to add report related vars */
				$params['navlinks']['refresh']['attr']['href']['urlvars']
					['plugin'] = $this->name;
				$params['navlinks']['refresh']['attr']['href']['urlvars']
					['report_id'] = $_REQUEST['report_id'];
				$params['navlinks']['refresh']['attr']['href']['urlvars']
					['report'] = $_REQUEST['report'];

				if (isset($_REQUEST['action'])) {
					$params['navlinks']['collapse']['attr']['href']['urlvars']
						['action'] = $_REQUEST['action'];

					$params['navlinks']['refresh']['attr']['href']['urlvars']
						['action'] = $_REQUEST['action'];
				}
			}

			if (isset($_REQUEST['schema']))
				$params['navlinks']['report_link']['attr']['href']['urlvars']['schema']
					= $_REQUEST['schema'];
		}
	}

	function get_subject_params() {
		$vars = array();
		
		if (! isset($_REQUEST['action']))
			return $vars;

		$action = $_REQUEST['action'];

		switch ($action) {
			case 'execute':
				$vars = array(
					'report_id' => $_REQUEST['report_id'],
					'report' => $_REQUEST['report'],
					'action' => 'properties' /*defaults to properties*/
				);
				if (isset($_REQUEST['back']))
					$vars['action'] = $_REQUEST['back'];
			break;
		}

		return $vars;
	}

	function edit($msg = '') {
		global $data, $misc, $lang;

		$reportsdb = $this->get_reportsdb();

		$misc->printHeader($this->lang['strreports']);
		$misc->printBody();
		$misc->printTrail('server');
		$misc->printTabs('server', 'report_plugin');
		$misc->printMsg($msg);

		// If it's a first, load then get the data from the database
		$report = $reportsdb->getReport($_REQUEST['report_id']);
		
		if ($_REQUEST['action'] == 'edit') {			
			$_POST['report_name'] = $report->fields['report_name'];
			$_POST['db_name'] = $report->fields['db_name'];
			$_POST['descr'] = $report->fields['descr'];
			$_POST['report_sql'] = $report->fields['report_sql'];
			if ($report->fields['paginate'] == 't') {
				$_POST['paginate'] = true;
			}
		}

		// Get a list of available databases
		$databases = $data->getDatabases();

		$_REQUEST['report'] = $report->fields['report_name'];

		echo "<form action=\"plugin.php?plugin={$this->name}\" method=\"post\">\n";
		echo $misc->form;
		echo "<table style=\"width: 100%\">\n";
		echo "<tr><th class=\"data left required\">{$lang['strname']}</th>\n";
		echo "<td class=\"data1\"><input name=\"report_name\" size=\"32\" maxlength=\"{$data->_maxNameLen}\" value=\"",
			htmlspecialchars($_POST['report_name']), "\" /></td></tr>\n";
		echo "<tr><th class=\"data left required\">{$lang['strdatabase']}</th>\n";
		echo "<td class=\"data1\"><select name=\"db_name\">\n";
		while (!$databases->EOF) {
			$dbname = $databases->fields['datname'];
			echo "<option value=\"", htmlspecialchars($dbname), "\"",
			($dbname == $_POST['db_name']) ? ' selected="selected"' : '', ">",
				htmlspecialchars($dbname), "</option>\n";
			$databases->moveNext();
		}
		echo "</select></td></tr>\n";
		echo "<tr><th class=\"data left\">{$lang['strcomment']}</th>\n";
		echo "<td class=\"data1\"><textarea style=\"width:100%;\" rows=\"5\" cols=\"50\" name=\"descr\">",
			htmlspecialchars($_POST['descr']), "</textarea></td></tr>\n";
		echo "<tr><th class=\"data left required\">{$lang['strsql']}</th>\n";
		echo "<td class=\"data1\"><textarea style=\"width:100%;\" rows=\"15\" cols=\"50\" name=\"report_sql\">",
			htmlspecialchars($_POST['report_sql']), "</textarea></td></tr>\n";
		echo "</table>\n";
		echo "<label for=\"paginate\"><input type=\"checkbox\" id=\"paginate\" name=\"paginate\"", (isset($_POST['paginate']) ? ' checked="checked"' : ''), " />&nbsp;{$lang['strpaginate']}</label>\n";
		echo "<p><input type=\"hidden\" name=\"action\" value=\"save_edit\" />\n";
		echo "<input type=\"submit\" value=\"{$lang['strsave']}\" />\n";
		echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
		echo "<input type=\"hidden\" name=\"report_id\" value=\"{$report->fields['report_id']}\" />\n";
		echo "</form>\n";
		$misc->printFooter();
	}

	/**
	 * Saves changes to a report
	 */
	function save_edit() {
		$reportsdb = $this->get_reportsdb();

		if (isset($_REQUEST['cancel'])) {
			$this->default_action();
			exit;
		}

		if (!isset($_POST['report_name'])) $_POST['report_name'] = '';
		if (!isset($_POST['db_name'])) $_POST['db_name'] = '';
		if (!isset($_POST['descr'])) $_POST['descr'] = '';
		if (!isset($_POST['report_sql'])) $_POST['report_sql'] = '';

		// Check that they've given a name and a definition
		if ($_POST['report_name'] == '') {
			$this->edit($this->lang['strreportneedsname']);
		} elseif ($_POST['report_sql'] == '') {
			$this->edit($this->lang['strreportneedsdef']);
		} else {
			$status = $reportsdb->alterReport($_POST['report_id'], $_POST['report_name'], $_POST['db_name'],
				$_POST['descr'], $_POST['report_sql'], isset($_POST['paginate']));
			if ($status == 0)
				$this->default_action($this->lang['strreportcreated']);
			else
				$this->edit($this->lang['strreportcreatedbad']);
		}
	}

	/**
	 * Display read-only properties of a report
	 */
	function properties($msg = '') {
		global $data, $reportsdb, $misc;
		global $lang;

		$reportsdb = $this->get_reportsdb();
		
		$misc->printHeader($this->lang['strreports']);
		$misc->printBody();
		$misc->printTrail('server');
		$misc->printTabs('server', 'report_plugin');
		$misc->printMsg($msg);

		$report = $reportsdb->getReport($_REQUEST['report_id']);

		$_REQUEST['report'] = $report->fields['report_name'];

		if ($report->recordCount() == 1) {
			echo "<table>\n";
			echo "<tr><th class=\"data left\">{$lang['strname']}</th>\n";
			echo "<td class=\"data1\">", $misc->printVal($report->fields['report_name']), "</td></tr>\n";
			echo "<tr><th class=\"data left\">{$lang['strdatabase']}</th>\n";
			echo "<td class=\"data1\">", $misc->printVal($report->fields['db_name']), "</td></tr>\n";
			echo "<tr><th class=\"data left\">{$lang['strcomment']}</th>\n";
			echo "<td class=\"data1\">", $misc->printVal($report->fields['descr']), "</td></tr>\n";
			echo "<tr><th class=\"data left\">{$lang['strpaginate']}</th>\n";
			echo "<td class=\"data1\">", $misc->printVal($report->fields['paginate'], 'yesno', array('align' => 'left')), "</td></tr>\n";
			echo "<tr><th class=\"data left\">{$lang['strsql']}</th>\n";
			echo "<td class=\"data1\">", $misc->printVal($report->fields['report_sql']), "</td></tr>\n";
			echo "</table>\n";
		}
		else echo "<p>{$lang['strinvalidparam']}</p>\n";

		$urlvars = array (
			'plugin' => $this->name,
			'server' => $_REQUEST['server']
		);
		if (isset($_REQUEST['schema'])) $urlvars['schema'] = $_REQUEST['schema'];
		if (isset($_REQUEST['schema'])) $urlvars['database'] = $_REQUEST['schema'];
		
		$navlinks = array (
			'showall' => array (
				'attr'=> array (
					'href' => array (
						'url' => 'plugin.php',
						'urlvars' => array_merge($urlvars, array('action' => 'default_action'))
					)
				),
				'content' => $this->lang['strshowallreports']
			),
			'edit' => array (
				'attr'=> array (
					'href' => array (
						'url' => 'plugin.php',
						'urlvars' => array_merge($urlvars, array(
							'action' => 'edit',
							'report_id' => $report->fields['report_id'])
						)
					)
				),
				'content' => $lang['stredit']
			),
			'execute' => array (
				'attr'=> array (
					'href' => array (
						'url' => 'plugin.php',
						'urlvars' => array_merge($urlvars, array(
							'action' => 'execute',
							'report' => $report->fields['report_name'],
							'database' => $report->fields['db_name'],
							'report_id' => $report->fields['report_id'],
							'paginate' => $report->fields['paginate'],
							'nohistory' => 't',
							'return' => 'plugin',
							'back' => 'properties'
						))
					)
				),
				'content' => $lang['strexecute']
			)
		);
		$misc->printNavLinks($navlinks, 'reports-properties');
	}

	/**
	 * Displays a screen where they can enter a new report
	 */
	function create($msg = '') {
		global $data, $reportsdb, $misc;
		global $lang;

		$misc->printHeader($this->lang['strreports']);
		$misc->printBody();
		$misc->printTrail('server');
		$misc->printTabs('server', 'report_plugin');
		$misc->printMsg($msg);
		
		if (!isset($_REQUEST['report_name'])) $_REQUEST['report_name'] = '';
		if (!isset($_REQUEST['db_name'])) $_REQUEST['db_name'] = '';
		if (!isset($_REQUEST['descr'])) $_REQUEST['descr'] = '';
		if (!isset($_REQUEST['report_sql'])) {
			// Set the query from session if linked from a user query result
			if (isset($_REQUEST['fromsql']) and $_REQUEST['fromsql'] == 1 ) {
				$_REQUEST['report_sql'] = $_SESSION['sqlquery'];
			}
			else {
				$_REQUEST['sortkey'] = isset($_REQUEST['sortkey']) ? $_REQUEST['sortkey'] : '';
				if (preg_match('/^[0-9]+$/', $_REQUEST['sortkey']) && $_REQUEST['sortkey'] > 0) $orderby = array($_REQUEST['sortkey'] => $_REQUEST['sortdir']);
					else $orderby = array();

				$subject = isset($_REQUEST['subject']) && isset($_REQUEST[$_REQUEST['subject']])
					? $_REQUEST[$_REQUEST['subject']]
					: '';

				$_REQUEST['report_sql'] = $data->getSelectSQL($subject, array(), array(), array(), $orderby);
			}
		}

		if (isset($_REQUEST['database'])) {
			$_REQUEST['db_name'] = $_REQUEST['database'];
			unset($_REQUEST['database']);
			$misc->setForm();
		}
		
		$databases = $data->getDatabases();

		echo "<form action=\"plugin.php?plugin={$this->name}\" method=\"post\">\n";
		echo $misc->form;
		echo "<table style=\"width: 100%\">\n";
		echo "<tr><th class=\"data left required\">{$lang['strname']}</th>\n";
		echo "<td class=\"data1\"><input name=\"report_name\" size=\"32\" maxlength=\"{$data->_maxNameLen}\" value=\"",
			htmlspecialchars($_REQUEST['report_name']), "\" /></td></tr>\n";
		echo "<tr><th class=\"data left required\">{$lang['strdatabase']}</th>\n";
		echo "<td class=\"data1\"><select name=\"db_name\">\n";
		while (!$databases->EOF) {
			$dbname = $databases->fields['datname'];
			echo "<option value=\"", htmlspecialchars($dbname), "\"",
			($dbname == $_REQUEST['db_name']) ? ' selected="selected"' : '', ">",
				htmlspecialchars($dbname), "</option>\n";
			$databases->moveNext();
		}
		echo "</select></td></tr>\n";
		echo "<tr><th class=\"data left\">{$lang['strcomment']}</th>\n";
		echo "<td class=\"data1\"><textarea style=\"width:100%;\" rows=\"5\" cols=\"50\" name=\"descr\">",
			htmlspecialchars($_REQUEST['descr']), "</textarea></td></tr>\n";
		echo "<tr><th class=\"data left required\">{$lang['strsql']}</th>\n";
		echo "<td class=\"data1\"><textarea style=\"width:100%;\" rows=\"15\" cols=\"50\" name=\"report_sql\">",
			htmlspecialchars($_REQUEST['report_sql']), "</textarea></td></tr>\n";
		echo "</table>\n";
		echo "<label for=\"paginate\"><input type=\"checkbox\" id=\"paginate\" name=\"paginate\"", (isset($_REQUEST['paginate']) ? ' checked="checked"' : ''), " />&nbsp;{$lang['strpaginate']}</label>\n";
		echo "<p><input type=\"hidden\" name=\"action\" value=\"save_create\" />\n";
		echo "<input type=\"submit\" value=\"{$lang['strsave']}\" />\n";
		echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" /></p>\n";
		echo "</form>\n";
		$misc->printFooter();
	}

	/**
	 * Actually creates the new report in the database
	 */
	function save_create() {
		if (isset($_REQUEST['cancel'])) {
			$this->default_action();
			exit;
		}

		$reportsdb = $this->get_reportsdb();

		if (!isset($_POST['report_name'])) $_POST['report_name'] = '';
		if (!isset($_POST['db_name'])) $_POST['db_name'] = '';
		if (!isset($_POST['descr'])) $_POST['descr'] = '';
		if (!isset($_POST['report_sql'])) $_POST['report_sql'] = '';

		// Check that they've given a name and a definition
		if ($_POST['report_name'] == '') $this->create($this->lang['strreportneedsname']);
		elseif ($_POST['report_sql'] == '') $this->create($this->lang['strreportneedsdef']);
		else {
			$status = $reportsdb->createReport($_POST['report_name'], $_POST['db_name'],
					$_POST['descr'], $_POST['report_sql'], isset($_POST['paginate']));
			if ($status == 0)
				$this->default_action($this->lang['strreportcreated']);
			else
				$this->create($this->lang['strreportcreatedbad']);
		}
	}

	/**
	 * Show confirmation of drop and perform actual drop
	 */
	function drop() {
		global $reportsdb, $misc;
		global $lang;

		$confirm = false;
		if (isset($_REQUEST['confirm'])) $confirm = true;

		$reportsdb = $this->get_reportsdb();

		$misc->printHeader($this->lang['strreports']);
		$misc->printBody();
		
		if (isset($_REQUEST['cancel'])) {
			$this->default_action();
			exit;
		}

		if ($confirm) {
			// Fetch report from the database
			$report = $reportsdb->getReport($_REQUEST['report_id']);

			$_REQUEST['report'] = $report->fields['report_name'];
			$misc->printTrail('report');
			$misc->printTitle($lang['strdrop']);

			echo "<p>", sprintf($this->lang['strconfdropreport'], $misc->printVal($report->fields['report_name'])), "</p>\n";

			echo "<form action=\"plugin.php?plugin={$this->name}\" method=\"post\">\n";
			echo $misc->form;
			echo "<input type=\"hidden\" name=\"action\" value=\"drop\" />\n";
			echo "<input type=\"hidden\" name=\"report_id\" value=\"", htmlspecialchars($_REQUEST['report_id']), "\" />\n";
			echo "<input type=\"submit\" name=\"drop\" value=\"{$lang['strdrop']}\" />\n";
			echo "<input type=\"submit\" name=\"cancel\" value=\"{$lang['strcancel']}\" />\n";
			echo "</form>\n";
		} else {
			$status = $reportsdb->dropReport($_POST['report_id']);
			if ($status == 0)
				$this->default_action($this->lang['strreportdropped']);
			else
				$this->default_action($this->lang['strreportdroppedbad']);
		}

		$misc->printFooter();
	}

	function execute() {
		global $misc, $data;

		$reportsdb = $this->get_reportsdb();

		$report = $reportsdb->getReport($_REQUEST['report_id']);
		
		$_POST['query'] = $report->fields['report_sql'];

		include('./sql.php');
	}

	/**
	 * Show default list of reports in the database
	 */
	function default_action($msg = '') {
		global $data, $misc, $lang;

		$reportsdb = $this->get_reportsdb();

		$misc->printHeader($this->lang['strreports']);
		$misc->printBody();
		$misc->printTrail('server');
		$misc->printTabs('server', 'report_plugin');
		$misc->printMsg($msg);
		
		$reports = $reportsdb->getReports();

		$columns = array(
			'report' => array(
				'title' => $this->lang['strreport'],
				'field' => field('report_name'),
				'url'   => "plugin.php?plugin={$this->name}&amp;action=properties&amp;{$misc->href}&amp;",
				'vars'  => array(
					'report_id' => 'report_id',
					'report' => 'report_name'
				),
			),
			'database' => array(
				'title' => $lang['strdatabase'],
				'field' => field('db_name'),
			),
			'created' => array(
				'title' => $lang['strcreated'],
				'field' => field('date_created'),
			),
			'paginate' => array(
				'title' => $lang['strpaginate'],
				'field' => field('paginate'),
				'type' => 'yesno',
			),
			'actions' => array(
				'title' => $lang['stractions'],
			),
			'comment' => array(
				'title' => $lang['strcomment'],
				'field' => field('descr'),
			),
		);
		
		//$return_url = urlencode("plugin.php?plugin={$this->name}&amp;{$misc->href}");
		$urlvars = $misc->getRequestVars();
		
		$actions = array(
			'run' => array (
				'content' => $lang['strexecute'],
				'attr'=> array (
					'href' => array (
						'url' => 'plugin.php',
						'urlvars' => array_merge($urlvars, array (
							'plugin' => $this->name,
							'action' => 'execute',
							'report' => field('report_name'),
							'database' => field('db_name'),
							'report_id' => field('report_id'),
							'paginate' => field('paginate'),
							'nohistory' => 't',
							'return' => 'plugin',
							'back' => 'default_action'
						))
					)
				)
			),
			'edit' => array (
				'content' => $lang['stredit'],
				'attr'=> array (
					'href' => array (
						'url' => 'plugin.php',
						'urlvars' => array_merge($urlvars, array (
							'plugin' => $this->name,
							'action' => 'edit',
							'report_id' => field('report_id'),
						))
					)
				)
			),
			'drop' => array(
				'content' => $lang['strdrop'],
				'attr'=> array (
					'href' => array (
						'url' => 'plugin.php',
						'urlvars' => array_merge($urlvars, array (
							'plugin' => $this->name,
							'action' => 'drop',
							'confirm' => 'true',
							'report_id' => field('report_id'),
						))
					)
				)
			),
		);
		
		$misc->printTable($reports, $columns, $actions, 'reports-reports', $this->lang['strnoreports']);

		$navlinks = array (
			array (
				'attr'=> array (
					'href' => array (
						'url' => 'plugin.php',
						'urlvars' => array (
							'plugin' => $this->name, 
							'server' => field('server'),
							'action' => 'create')
					)
				),
				'content' => $this->lang['strcreatereport']
			)
		);
		$misc->printNavLinks($navlinks, 'reports-reports');
		$misc->printFooter();
	}
}
?>
