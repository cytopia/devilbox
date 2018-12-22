<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * functions for displaying server status sub item: monitor
 *
 * @usedby  server_status_monitor.php
 *
 * @package PhpMyAdmin
 */
namespace PhpMyAdmin\Server\Status;

use PhpMyAdmin\Sanitize;
use PhpMyAdmin\Server\Status\Data;
use PhpMyAdmin\SysInfo;
use PhpMyAdmin\Util;

/**
 * functions for displaying server status sub item: monitor
 *
 * @package PhpMyAdmin
 */
class Monitor
{
    /**
     * Prints html with monitor
     *
     * @param Data $serverStatusData Server status data
     *
     * @return string
     */
    public static function getHtmlForMonitor(Data $serverStatusData)
    {
        $retval  = self::getHtmlForTabLinks();

        $retval .= self::getHtmlForSettingsDialog();

        $retval .= self::getHtmlForInstructionsDialog();

        $retval .= self::getHtmlForAddChartDialog();

        $retval .= self::getHtmlForAnalyseDialog();

        $retval .= '<table class="clearfloat tdblock" id="chartGrid"></table>';
        $retval .= '<div id="logTable">';
        $retval .= '<br/>';
        $retval .= '</div>';

        $retval .= '<script type="text/javascript">';
        $retval .= 'variableNames = [ ';
        $i = 0;
        foreach ($serverStatusData->status as $name=>$value) {
            if (is_numeric($value)) {
                if ($i++ > 0) {
                    $retval .= ", ";
                }
                $retval .= Sanitize::formatJsVal($name);
            }
        }
        $retval .= '];';
        $retval .= '</script>';

        return $retval;
    }

    /**
     * Returns html for Analyse Dialog
     *
     * @return string
     */
    public static function getHtmlForAnalyseDialog()
    {
        $retval  = '<div id="logAnalyseDialog" title="';
        $retval .= __('Log statistics') . '" class="hide">';
        $retval .= '<p>' . __('Selected time range:');
        $retval .= '<input type="text" name="dateStart"'
            . ' class="datetimefield" value="" /> - ';
        $retval .= '<input type="text" name="dateEnd" class="datetimefield" value="" />';
        $retval .= '</p>';
        $retval .= '<input type="checkbox" id="limitTypes"'
            . ' value="1" checked="checked" />';
        $retval .= '<label for="limitTypes">';
        $retval .= __('Only retrieve SELECT,INSERT,UPDATE and DELETE Statements');
        $retval .= '</label>';
        $retval .= '<br/>';
        $retval .= '<input type="checkbox" id="removeVariables"'
            . ' value="1" checked="checked" />';
        $retval .= '<label for="removeVariables">';
        $retval .= __('Remove variable data in INSERT statements for better grouping');
        $retval .= '</label>';
        $retval .= '<p>';
        $retval .= __(
            'Choose from which log you want the statistics to be generated from.'
        );
        $retval .= '</p>';
        $retval .= '<p>';
        $retval .= __('Results are grouped by query text.');
        $retval .= '</p>';
        $retval .= '</div>';
        $retval .= '<div id="queryAnalyzerDialog" title="';
        $retval .= __('Query analyzer') . '" class="hide">';
        $retval .= '<textarea id="sqlquery"> </textarea>';
        $retval .= '<p></p>';
        $retval .= '<div class="placeHolder"></div>';
        $retval .= '</div>';

        return $retval;
    }

    /**
     * Returns html for Instructions Dialog
     *
     * @return string
     */
    public static function getHtmlForInstructionsDialog()
    {
        $retval  = '<div id="monitorInstructionsDialog" title="';
        $retval .= __('Monitor Instructions') . '" class="hide">';
        $retval .= __(
            'The phpMyAdmin Monitor can assist you in optimizing the server'
            . ' configuration and track down time intensive queries. For the latter you'
            . ' will need to set log_output to \'TABLE\' and have either the'
            . ' slow_query_log or general_log enabled. Note however, that the'
            . ' general_log produces a lot of data and increases server load'
            . ' by up to 15%.'
        );

        $retval .= '<p></p>';
        $retval .= '<img class="ajaxIcon" src="';
        $retval .= $GLOBALS['pmaThemeImage'] . 'ajax_clock_small.gif"';
        $retval .= ' alt="' . __('Loading…') . '" />';
        $retval .= '<div class="ajaxContent"></div>';
        $retval .= '<div class="monitorUse hide">';
        $retval .= '<p></p>';
        $retval .= '<strong>';
        $retval .= __('Using the monitor:');
        $retval .= '</strong><p>';
        $retval .= __(
            'Your browser will refresh all displayed charts in a regular interval.'
            . ' You may add charts and change the refresh rate under \'Settings\','
            . ' or remove any chart using the cog icon on each respective chart.'
        );
        $retval .= '</p><p>';
        $retval .= __(
            'To display queries from the logs, select the relevant time span on any'
            . ' chart by holding down the left mouse button and panning over the'
            . ' chart. Once confirmed, this will load a table of grouped queries,'
            . ' there you may click on any occurring SELECT statements to further'
            . ' analyze them.'
        );
        $retval .= '</p>';
        $retval .= '<p>';
        $retval .= Util::getImage('s_attention');
        $retval .= '<strong>';
        $retval .= __('Please note:');
        $retval .= '</strong><br />';
        $retval .= __(
            'Enabling the general_log may increase the server load by'
            . ' 5-15%. Also be aware that generating statistics from the logs is a'
            . ' load intensive task, so it is advisable to select only a small time'
            . ' span and to disable the general_log and empty its table once'
            . ' monitoring is not required any more.'
        );
        $retval .= '</p>';
        $retval .= '</div>';
        $retval .= '</div>';

        return $retval;
    }

    /**
     * Returns html for addChartDialog
     *
     * @return string
     */
    public static function getHtmlForAddChartDialog()
    {
        $retval  = '<div id="addChartDialog" title="'
            . __('Add chart') . '" class="hide">';
        $retval .= '<div id="tabGridVariables">';
        $retval .= '<p><input type="text" name="chartTitle" value="'
            . __('Chart Title') . '" /></p>';
        $retval .= '<input type="radio" name="chartType"'
            . ' value="preset" id="chartPreset" />';
        $retval .= '<label for="chartPreset">' . __('Preset chart') . '</label>';
        $retval .= '<select name="presetCharts"></select><br/>';
        $retval .= '<input type="radio" name="chartType" value="variable" '
            . 'id="chartStatusVar" checked="checked" />';
        $retval .= '<label for="chartStatusVar">';
        $retval .= __('Status variable(s)');
        $retval .= '</label><br/>';
        $retval .= '<div id="chartVariableSettings">';
        $retval .= '<label for="chartSeries">' . __('Select series:') . '</label><br />';
        $retval .= '<select id="chartSeries" name="varChartList" size="1">';
        $retval .= '<option>' . __('Commonly monitored') . '</option>';
        $retval .= '<option>Processes</option>';
        $retval .= '<option>Questions</option>';
        $retval .= '<option>Connections</option>';
        $retval .= '<option>Bytes_sent</option>';
        $retval .= '<option>Bytes_received</option>';
        $retval .= '<option>Threads_connected</option>';
        $retval .= '<option>Created_tmp_disk_tables</option>';
        $retval .= '<option>Handler_read_first</option>';
        $retval .= '<option>Innodb_buffer_pool_wait_free</option>';
        $retval .= '<option>Key_reads</option>';
        $retval .= '<option>Open_tables</option>';
        $retval .= '<option>Select_full_join</option>';
        $retval .= '<option>Slow_queries</option>';
        $retval .= '</select><br />';
        $retval .= '<label for="variableInput">';
        $retval .= __('or type variable name:');
        $retval .= ' </label>';
        $retval .= '<input type="text" name="variableInput" id="variableInput" />';
        $retval .= '<p></p>';
        $retval .= '<input type="checkbox" name="differentialValue"'
            . ' id="differentialValue" value="differential" checked="checked" />';
        $retval .= '<label for="differentialValue">';
        $retval .= __('Display as differential value');
        $retval .= '</label><br />';
        $retval .= '<input type="checkbox" id="useDivisor"'
            . ' name="useDivisor" value="1" />';
        $retval .= '<label for="useDivisor">' . __('Apply a divisor') . '</label>';
        $retval .= '<span class="divisorInput hide">';
        $retval .= '<input type="text" name="valueDivisor" size="4" value="1" />';
        $retval .= '(<a href="#kibDivisor">' . __('KiB') . '</a>, ';
        $retval .= '<a href="#mibDivisor">' . __('MiB') . '</a>)';
        $retval .= '</span><br />';
        $retval .= '<input type="checkbox" id="useUnit" name="useUnit" value="1" />';
        $retval .= '<label for="useUnit">';
        $retval .= __('Append unit to data values');
        $retval .= '</label>';
        $retval .= '<span class="unitInput hide">';
        $retval .= '<input type="text" name="valueUnit" size="4" value="" />';
        $retval .= '</span>';
        $retval .= '<p>';
        $retval .= '<a href="#submitAddSeries"><b>' . __('Add this series') . '</b></a>';
        $retval .= '<span id="clearSeriesLink" class="hide">';
        $retval .= ' | <a href="#submitClearSeries">' . __('Clear series') . '</a>';
        $retval .= '</span>';
        $retval .= '</p>';
        $retval .= __('Series in chart:');
        $retval .= '<br/>';
        $retval .= '<span id="seriesPreview">';
        $retval .= '<i>' . __('None') . '</i>';
        $retval .= '</span>';
        $retval .= '</div>';
        $retval .= '</div>';
        $retval .= '</div>';

        return $retval;
    }

    /**
     * Returns html with Tab Links
     *
     * @return string
     */
    public static function getHtmlForTabLinks()
    {
        $retval  = '<div class="tabLinks">';
        $retval .= '<a href="#pauseCharts">';
        $retval .= Util::getImage('play') . __('Start Monitor');
        $retval .= '</a>';
        $retval .= '<a href="#settingsPopup" class="popupLink">';
        $retval .= Util::getImage('s_cog') .  __('Settings');
        $retval .= '</a>';
        $retval .= '<a href="#monitorInstructionsDialog">';
        $retval .= Util::getImage('b_help') . __('Instructions/Setup');
        $retval .= '<a href="#endChartEditMode" class="hide">';
        $retval .= Util::getImage('s_okay');
        $retval .= __('Done dragging (rearranging) charts');
        $retval .= '</a>';
        $retval .= '</div>';

        return $retval;
    }

    /**
     * Returns html with Settings dialog
     *
     * @return string
     */
    public static function getHtmlForSettingsDialog()
    {
        $retval  = '<div class="popupContent settingsPopup">';
        $retval .= '<a href="#addNewChart">';
        $retval .= Util::getImage('b_chart') . __('Add chart');
        $retval .= '</a>';
        $retval .= '<a href="#rearrangeCharts">';
        $retval .= Util::getImage('b_tblops')
            . __('Enable charts dragging');
        $retval .= '</a>';
        $retval .= '<div class="clearfloat paddingtop"></div>';
        $retval .= '<div class="floatleft">';
        $retval .= __('Refresh rate') . '<br />';
        $retval .= Data::getHtmlForRefreshList(
            'gridChartRefresh',
            5,
            Array(2, 3, 4, 5, 10, 20, 40, 60, 120, 300, 600, 1200)
        );
        $retval .= '<br />';
        $retval .= '</div>';
        $retval .= '<div class="floatleft">';
        $retval .= __('Chart columns');
        $retval .= '<br />';
        $retval .= '<select name="chartColumns">';
        $retval .= '<option>1</option>';
        $retval .= '<option>2</option>';
        $retval .= '<option>3</option>';
        $retval .= '<option>4</option>';
        $retval .= '<option>5</option>';
        $retval .= '<option>6</option>';
        $retval .= '</select>';
        $retval .= '</div>';
        $retval .= '<div class="clearfloat paddingtop">';
        $retval .= '<b>' . __('Chart arrangement') . '</b> ';
        $retval .= Util::showHint(
            __(
                'The arrangement of the charts is stored to the browsers local storage. '
                . 'You may want to export it if you have a complicated set up.'
            )
        );
        $retval .= '<br/>';
        $retval .= '<a class="ajax" href="#importMonitorConfig">';
        $retval .= __('Import');
        $retval .= '</a>';
        $retval .= '&nbsp;&nbsp;';
        $retval .= '<a class="disableAjax" href="#exportMonitorConfig">';
        $retval .= __('Export');
        $retval .= '</a>';
        $retval .= '&nbsp;&nbsp;';
        $retval .= '<a href="#clearMonitorConfig">';
        $retval .= __('Reset to default');
        $retval .= '</a>';
        $retval .= '</div>';
        $retval .= '</div>';

        return $retval;
    }


    /**
     * Define some data and links needed on the client side
     *
     * @param Data $serverStatusData Server status data
     *
     * @return string
     */
    public static function getHtmlForClientSideDataAndLinks(Data $serverStatusData)
    {
        /**
         * Define some data needed on the client side
         */
        $input = '<input type="hidden" name="%s" value="%s" />';
        $form  = '<form id="js_data" class="hide">';
        $form .= sprintf($input, 'server_time', microtime(true) * 1000);
        $form .= sprintf($input, 'server_os', SysInfo::getOs());
        $form .= sprintf($input, 'is_superuser', $GLOBALS['dbi']->isSuperuser());
        $form .= sprintf($input, 'server_db_isLocal', $serverStatusData->db_isLocal);
        $form .= '</form>';
        /**
         * Define some links used on client side
         */
        $links  = '<div id="profiling_docu" class="hide">';
        $links .= Util::showMySQLDocu('general-thread-states');
        $links .= '</div>';
        $links .= '<div id="explain_docu" class="hide">';
        $links .= Util::showMySQLDocu('explain-output');
        $links .= '</div>';

        return $form . $links;
    }

    /***************************Ajax request function***********************************/

    /**
     * Returns JSon for real-time charting data
     *
     * @return array
     */
    public static function getJsonForChartingData()
    {
        $ret = json_decode($_POST['requiredData'], true);
        $statusVars = array();
        $serverVars = array();
        $sysinfo = $cpuload = $memory = 0;

        /* Accumulate all required variables and data */
        list($serverVars, $statusVars, $ret) = self::getJsonForChartingDataGet(
            $ret, $serverVars, $statusVars, $sysinfo, $cpuload, $memory
        );

        // Retrieve all required status variables
        if (count($statusVars)) {
            $statusVarValues = $GLOBALS['dbi']->fetchResult(
                "SHOW GLOBAL STATUS WHERE Variable_name='"
                . implode("' OR Variable_name='", $statusVars) . "'",
                0,
                1
            );
        } else {
            $statusVarValues = array();
        }

        // Retrieve all required server variables
        if (count($serverVars)) {
            $serverVarValues = $GLOBALS['dbi']->fetchResult(
                "SHOW GLOBAL VARIABLES WHERE Variable_name='"
                . implode("' OR Variable_name='", $serverVars) . "'",
                0,
                1
            );
        } else {
            $serverVarValues = array();
        }

        // ...and now assign them
        $ret = self::getJsonForChartingDataSet($ret, $statusVarValues, $serverVarValues);

        $ret['x'] = microtime(true) * 1000;
        return $ret;
    }

    /**
     * Assign the variables for real-time charting data
     *
     * @param array $ret             Real-time charting data
     * @param array $statusVarValues Status variable values
     * @param array $serverVarValues Server variable values
     *
     * @return array
     */
    public static function getJsonForChartingDataSet(array $ret, array $statusVarValues, array $serverVarValues)
    {
        foreach ($ret as $chart_id => $chartNodes) {
            foreach ($chartNodes as $node_id => $nodeDataPoints) {
                foreach ($nodeDataPoints as $point_id => $dataPoint) {
                    switch ($dataPoint['type']) {
                    case 'statusvar':
                        $ret[$chart_id][$node_id][$point_id]['value']
                            = $statusVarValues[$dataPoint['name']];
                        break;
                    case 'servervar':
                        $ret[$chart_id][$node_id][$point_id]['value']
                            = $serverVarValues[$dataPoint['name']];
                        break;
                    }
                }
            }
        }
        return $ret;
    }

    /**
     * Get called to get JSON for charting data
     *
     * @param array $ret        Real-time charting data
     * @param array $serverVars Server variable values
     * @param array $statusVars Status variable values
     * @param mixed $sysinfo    System info
     * @param mixed $cpuload    CPU load
     * @param mixed $memory     Memory
     *
     * @return array
     */
    public static function getJsonForChartingDataGet(
        array $ret, array $serverVars, array $statusVars, $sysinfo, $cpuload, $memory
    ) {
        // For each chart
        foreach ($ret as $chart_id => $chartNodes) {
            // For each data series
            foreach ($chartNodes as $node_id => $nodeDataPoints) {
                // For each data point in the series (usually just 1)
                foreach ($nodeDataPoints as $point_id => $dataPoint) {
                    list($serverVars, $statusVars, $ret[$chart_id][$node_id][$point_id])
                        = self::getJsonForChartingDataSwitch(
                            $dataPoint['type'], $dataPoint['name'], $serverVars,
                            $statusVars, $ret[$chart_id][$node_id][$point_id],
                            $sysinfo, $cpuload, $memory
                        );
                } /* foreach */
            } /* foreach */
        }
        return array($serverVars, $statusVars, $ret);
    }

    /**
     * Switch called to get JSON for charting data
     *
     * @param string $type       Type
     * @param string $pName      Name
     * @param array  $serverVars Server variable values
     * @param array  $statusVars Status variable values
     * @param array  $ret        Real-time charting data
     * @param mixed  $sysinfo    System info
     * @param mixed  $cpuload    CPU load
     * @param mixed  $memory     Memory
     *
     * @return array
     */
    public static function getJsonForChartingDataSwitch(
        $type, $pName, array $serverVars, array $statusVars, array $ret,
        $sysinfo, $cpuload, $memory
    ) {
        switch ($type) {
        /* We only collect the status and server variables here to
         * read them all in one query,
         * and only afterwards assign them.
         * Also do some white list filtering on the names
        */
        case 'servervar':
            if (!preg_match('/[^a-zA-Z_]+/', $pName)) {
                $serverVars[] = $pName;
            }
            break;

        case 'statusvar':
            if (!preg_match('/[^a-zA-Z_]+/', $pName)) {
                $statusVars[] = $pName;
            }
            break;

        case 'proc':
            $result = $GLOBALS['dbi']->query('SHOW PROCESSLIST');
            $ret['value'] = $GLOBALS['dbi']->numRows($result);
            break;

        case 'cpu':
            if (!$sysinfo) {
                $sysinfo = SysInfo::get();
            }
            if (!$cpuload) {
                $cpuload = $sysinfo->loadavg();
            }

            if (SysInfo::getOs() == 'Linux') {
                $ret['idle'] = $cpuload['idle'];
                $ret['busy'] = $cpuload['busy'];
            } else {
                $ret['value'] = $cpuload['loadavg'];
            }

            break;

        case 'memory':
            if (!$sysinfo) {
                $sysinfo = SysInfo::get();
            }
            if (!$memory) {
                $memory = $sysinfo->memory();
            }

            $ret['value'] = isset($memory[$pName]) ? $memory[$pName] : 0;
            break;
        }

        return array($serverVars, $statusVars, $ret);
    }

    /**
     * Returns JSon for log data with type: slow
     *
     * @param int $start Unix Time: Start time for query
     * @param int $end   Unix Time: End time for query
     *
     * @return array
     */
    public static function getJsonForLogDataTypeSlow($start, $end)
    {
        $query  = 'SELECT start_time, user_host, ';
        $query .= 'Sec_to_Time(Sum(Time_to_Sec(query_time))) as query_time, ';
        $query .= 'Sec_to_Time(Sum(Time_to_Sec(lock_time))) as lock_time, ';
        $query .= 'SUM(rows_sent) AS rows_sent, ';
        $query .= 'SUM(rows_examined) AS rows_examined, db, sql_text, ';
        $query .= 'COUNT(sql_text) AS \'#\' ';
        $query .= 'FROM `mysql`.`slow_log` ';
        $query .= 'WHERE start_time > FROM_UNIXTIME(' . $start . ') ';
        $query .= 'AND start_time < FROM_UNIXTIME(' . $end . ') GROUP BY sql_text';

        $result = $GLOBALS['dbi']->tryQuery($query);

        $return = array('rows' => array(), 'sum' => array());

        while ($row = $GLOBALS['dbi']->fetchAssoc($result)) {
            $type = mb_strtolower(
                mb_substr(
                    $row['sql_text'],
                    0,
                    mb_strpos($row['sql_text'], ' ')
                )
            );

            switch($type) {
            case 'insert':
            case 'update':
                //Cut off big inserts and updates, but append byte count instead
                if (mb_strlen($row['sql_text']) > 220) {
                    $implode_sql_text = implode(
                        ' ',
                        Util::formatByteDown(
                            mb_strlen($row['sql_text']), 2, 2
                        )
                    );
                    $row['sql_text'] = mb_substr($row['sql_text'], 0, 200)
                        . '... [' . $implode_sql_text . ']';
                }
                break;
            default:
                break;
            }

            if (! isset($return['sum'][$type])) {
                $return['sum'][$type] = 0;
            }
            $return['sum'][$type] += $row['#'];
            $return['rows'][] = $row;
        }

        $return['sum']['TOTAL'] = array_sum($return['sum']);
        $return['numRows'] = count($return['rows']);

        $GLOBALS['dbi']->freeResult($result);
        return $return;
    }

    /**
     * Returns JSon for log data with type: general
     *
     * @param int $start Unix Time: Start time for query
     * @param int $end   Unix Time: End time for query
     *
     * @return array
     */
    public static function getJsonForLogDataTypeGeneral($start, $end)
    {
        $limitTypes = '';
        if (isset($_POST['limitTypes']) && $_POST['limitTypes']) {
            $limitTypes
                = 'AND argument REGEXP \'^(INSERT|SELECT|UPDATE|DELETE)\' ';
        }

        $query = 'SELECT TIME(event_time) as event_time, user_host, thread_id, ';
        $query .= 'server_id, argument, count(argument) as \'#\' ';
        $query .= 'FROM `mysql`.`general_log` ';
        $query .= 'WHERE command_type=\'Query\' ';
        $query .= 'AND event_time > FROM_UNIXTIME(' . $start . ') ';
        $query .= 'AND event_time < FROM_UNIXTIME(' . $end . ') ';
        $query .= $limitTypes . 'GROUP by argument'; // HAVING count > 1';

        $result = $GLOBALS['dbi']->tryQuery($query);

        $return = array('rows' => array(), 'sum' => array());
        $insertTables = array();
        $insertTablesFirst = -1;
        $i = 0;
        $removeVars = isset($_POST['removeVariables'])
            && $_POST['removeVariables'];

        while ($row = $GLOBALS['dbi']->fetchAssoc($result)) {
            preg_match('/^(\w+)\s/', $row['argument'], $match);
            $type = mb_strtolower($match[1]);

            if (! isset($return['sum'][$type])) {
                $return['sum'][$type] = 0;
            }
            $return['sum'][$type] += $row['#'];

            switch($type) {
            /** @noinspection PhpMissingBreakStatementInspection */
            case 'insert':
                // Group inserts if selected
                if ($removeVars
                    && preg_match(
                        '/^INSERT INTO (`|\'|"|)([^\s\\1]+)\\1/i',
                        $row['argument'], $matches
                    )
                ) {
                    $insertTables[$matches[2]]++;
                    if ($insertTables[$matches[2]] > 1) {
                        $return['rows'][$insertTablesFirst]['#']
                            = $insertTables[$matches[2]];

                        // Add a ... to the end of this query to indicate that
                        // there's been other queries
                        $temp = $return['rows'][$insertTablesFirst]['argument'];
                        $return['rows'][$insertTablesFirst]['argument']
                            .= self::getSuspensionPoints(
                                $temp[strlen($temp) - 1]
                            );

                        // Group this value, thus do not add to the result list
                        continue 2;
                    } else {
                        $insertTablesFirst = $i;
                        $insertTables[$matches[2]] += $row['#'] - 1;
                    }
                }
                // No break here

            case 'update':
                // Cut off big inserts and updates,
                // but append byte count therefor
                if (mb_strlen($row['argument']) > 220) {
                    $row['argument'] = mb_substr($row['argument'], 0, 200)
                        . '... ['
                        .  implode(
                            ' ',
                            Util::formatByteDown(
                                mb_strlen($row['argument']),
                                2,
                                2
                            )
                        )
                        . ']';
                }
                break;

            default:
                break;
            }

            $return['rows'][] = $row;
            $i++;
        }

        $return['sum']['TOTAL'] = array_sum($return['sum']);
        $return['numRows'] = count($return['rows']);

        $GLOBALS['dbi']->freeResult($result);

        return $return;
    }

    /**
     * Return suspension points if needed
     *
     * @param string $lastChar Last char
     *
     * @return null|string Return suspension points if needed
     */
    public static function getSuspensionPoints($lastChar)
    {
        if ($lastChar != '.') {
            return '<br/>...';
        }

        return null;
    }

    /**
     * Returns JSon for logging vars
     *
     * @return array
     */
    public static function getJsonForLoggingVars()
    {
        if (isset($_POST['varName']) && isset($_POST['varValue'])) {
            $value = $GLOBALS['dbi']->escapeString($_POST['varValue']);
            if (! is_numeric($value)) {
                $value="'" . $value . "'";
            }

            if (! preg_match("/[^a-zA-Z0-9_]+/", $_POST['varName'])) {
                $GLOBALS['dbi']->query(
                    'SET GLOBAL ' . $_POST['varName'] . ' = ' . $value
                );
            }

        }

        $loggingVars = $GLOBALS['dbi']->fetchResult(
            'SHOW GLOBAL VARIABLES WHERE Variable_name IN'
            . ' ("general_log","slow_query_log","long_query_time","log_output")',
            0,
            1
        );
        return $loggingVars;
    }

    /**
     * Returns JSon for query_analyzer
     *
     * @return array
     */
    public static function getJsonForQueryAnalyzer()
    {
        $return = array();

        if (strlen($_POST['database']) > 0) {
            $GLOBALS['dbi']->selectDb($_POST['database']);
        }

        if ($profiling = Util::profilingSupported()) {
            $GLOBALS['dbi']->query('SET PROFILING=1;');
        }

        // Do not cache query
        $query = preg_replace(
            '/^(\s*SELECT)/i',
            '\\1 SQL_NO_CACHE',
            $_POST['query']
        );

        $GLOBALS['dbi']->tryQuery($query);
        $return['affectedRows'] = $GLOBALS['cached_affected_rows'];

        $result = $GLOBALS['dbi']->tryQuery('EXPLAIN ' . $query);
        while ($row = $GLOBALS['dbi']->fetchAssoc($result)) {
            $return['explain'][] = $row;
        }

        // In case an error happened
        $return['error'] = $GLOBALS['dbi']->getError();

        $GLOBALS['dbi']->freeResult($result);

        if ($profiling) {
            $return['profiling'] = array();
            $result = $GLOBALS['dbi']->tryQuery(
                'SELECT seq,state,duration FROM INFORMATION_SCHEMA.PROFILING'
                . ' WHERE QUERY_ID=1 ORDER BY seq'
            );
            while ($row = $GLOBALS['dbi']->fetchAssoc($result)) {
                $return['profiling'][]= $row;
            }
            $GLOBALS['dbi']->freeResult($result);
        }
        return $return;
    }
}
