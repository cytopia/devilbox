<?php
	/**
	 * Class to manage reports.  Note how this class is designed to use
	 * the functions provided by the database driver exclusively, and hence
	 * will work with any database without modification.
	 *
	 * $Id: Reports.php,v 1.18 2007/04/16 11:02:35 mr-russ Exp $
	 */

	class Reports {

		// A database driver
		var $driver;
		var $conf;

		/* Constructor */
		function __construct(&$conf, &$status) {
			global $misc, $data;

			$this->conf = $conf;

			// Check to see if the reports database exists
			$rs = $data->getDatabase($this->conf['reports_db']);
			if ($rs->recordCount() != 1) $status = -1;
			else {
				// Create a new database access object.
				$this->driver = $misc->getDatabaseAccessor($this->conf['reports_db']);
				// Reports database should have been created in public schema
				$this->driver->setSchema($this->conf['reports_schema']);
				$status = 0;
			}
		}

		/**
		 * Finds all reports
		 * @return A recordset
		 */
		function getReports() {
			global $misc;
			// Filter for owned reports if necessary
			if ($this->conf['owned_reports_only']) {
				$server_info = $misc->getServerInfo();
				$filter['created_by'] = $server_info['username'];
				$ops = array('created_by' => '=');
			}
			else $filter = $ops = array();

			$sql = $this->driver->getSelectSQL($this->conf['reports_table'],
				array('report_id', 'report_name', 'db_name', 'date_created', 'created_by', 'descr', 'report_sql', 'paginate'),
				$filter, $ops, array('db_name' => 'asc', 'report_name' => 'asc'));

			return $this->driver->selectSet($sql);
		}

		/**
		 * Finds a particular report
		 * @param $report_id The ID of the report to find
		 * @return A recordset
		 */
		function getReport($report_id) {
			$sql = $this->driver->getSelectSQL($this->conf['reports_table'],
				array('report_id', 'report_name', 'db_name', 'date_created', 'created_by', 'descr', 'report_sql', 'paginate'),
				array('report_id' => $report_id), array('report_id' => '='), array());

			return $this->driver->selectSet($sql);
		}

		/**
		 * Creates a report
		 * @param $report_name The name of the report
		 * @param $db_name The name of the database
		 * @param $descr The comment on the report
		 * @param $report_sql The SQL for the report
		 * @param $paginate The report should be paginated
		 * @return 0 success
		 */
		function createReport($report_name, $db_name, $descr, $report_sql, $paginate) {
			global $misc;
			$server_info = $misc->getServerInfo();
			$temp = array(
				'report_name' => $report_name,
				'db_name' => $db_name,
				'created_by' => $server_info['username'],
				'report_sql' => $report_sql,
				'paginate' => $paginate ? 'true' : 'false',
			);
			if ($descr != '') $temp['descr'] = $descr;

			return $this->driver->insert($this->conf['reports_table'], $temp);
		}

		/**
		 * Alters a report
		 * @param $report_id The ID of the report
		 * @param $report_name The name of the report
		 * @param $db_name The name of the database
		 * @param $descr The comment on the report
		 * @param $report_sql The SQL for the report
		 * @param $paginate The report should be paginated
		 * @return 0 success
		 */
		function alterReport($report_id, $report_name, $db_name, $descr, $report_sql, $paginate) {
			global $misc;
			$server_info = $misc->getServerInfo();
			$temp = array(
				'report_name' => $report_name,
				'db_name' => $db_name,
				'created_by' => $server_info['username'],
				'report_sql' => $report_sql,
				'paginate' => $paginate ? 'true' : 'false',
				'descr' => $descr
			);

			return $this->driver->update($this->conf['reports_table'], $temp,
							array('report_id' => $report_id));
		}

		/**
		 * Drops a report
		 * @param $report_id The ID of the report to drop
		 * @return 0 success
		 */
		function dropReport($report_id) {
			return $this->driver->delete($this->conf['reports_table'], array('report_id' => $report_id));
		}

	}
?>
