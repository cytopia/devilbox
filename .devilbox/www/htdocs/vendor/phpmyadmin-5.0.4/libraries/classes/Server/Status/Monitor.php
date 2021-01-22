<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * functions for displaying server status sub item: monitor
 *
 * @usedby  server_status_monitor.php
 *
 * @package PhpMyAdmin
 */
declare(strict_types=1);

namespace PhpMyAdmin\Server\Status;

use PhpMyAdmin\DatabaseInterface;
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
     * @var DatabaseInterface
     */
    private $dbi;

    /**
     * Monitor constructor.
     * @param DatabaseInterface $dbi DatabaseInterface instance
     */
    public function __construct($dbi)
    {
        $this->dbi = $dbi;
    }

    /**
     * Returns JSON for real-time charting data
     *
     * @param string $requiredData Required data
     *
     * @return array JSON
     */
    public function getJsonForChartingData(string $requiredData): array
    {
        $ret = json_decode($requiredData, true);
        $statusVars = [];
        $serverVars = [];
        $sysinfo = $cpuload = $memory = 0;

        /* Accumulate all required variables and data */
        list($serverVars, $statusVars, $ret) = $this->getJsonForChartingDataGet(
            $ret,
            $serverVars,
            $statusVars,
            $sysinfo,
            $cpuload,
            $memory
        );

        // Retrieve all required status variables
        $statusVarValues = [];
        if (count($statusVars)) {
            $statusVarValues = $this->dbi->fetchResult(
                "SHOW GLOBAL STATUS WHERE Variable_name='"
                . implode("' OR Variable_name='", $statusVars) . "'",
                0,
                1
            );
        }

        // Retrieve all required server variables
        $serverVarValues = [];
        if (count($serverVars)) {
            $serverVarValues = $this->dbi->fetchResult(
                "SHOW GLOBAL VARIABLES WHERE Variable_name='"
                . implode("' OR Variable_name='", $serverVars) . "'",
                0,
                1
            );
        }

        // ...and now assign them
        $ret = $this->getJsonForChartingDataSet($ret, $statusVarValues, $serverVarValues);

        $ret['x'] = (int) (microtime(true) * 1000);
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
    private function getJsonForChartingDataSet(
        array $ret,
        array $statusVarValues,
        array $serverVarValues
    ): array {
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
    private function getJsonForChartingDataGet(
        array $ret,
        array $serverVars,
        array $statusVars,
        $sysinfo,
        $cpuload,
        $memory
    ) {
        // For each chart
        foreach ($ret as $chartId => $chartNodes) {
            // For each data series
            foreach ($chartNodes as $nodeId => $nodeDataPoints) {
                // For each data point in the series (usually just 1)
                foreach ($nodeDataPoints as $pointId => $dataPoint) {
                    list($serverVars, $statusVars, $ret[$chartId][$nodeId][$pointId])
                        = $this->getJsonForChartingDataSwitch(
                            $dataPoint['type'],
                            $dataPoint['name'],
                            $serverVars,
                            $statusVars,
                            $ret[$chartId][$nodeId][$pointId],
                            $sysinfo,
                            $cpuload,
                            $memory
                        );
                } /* foreach */
            } /* foreach */
        }
        return [
            $serverVars,
            $statusVars,
            $ret,
        ];
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
    private function getJsonForChartingDataSwitch(
        $type,
        $pName,
        array $serverVars,
        array $statusVars,
        array $ret,
        $sysinfo,
        $cpuload,
        $memory
    ) {
        switch ($type) {
        /* We only collect the status and server variables here to
         * read them all in one query,
         * and only afterwards assign them.
         * Also do some white list filtering on the names
        */
            case 'servervar':
                if (! preg_match('/[^a-zA-Z_]+/', $pName)) {
                    $serverVars[] = $pName;
                }
                break;

            case 'statusvar':
                if (! preg_match('/[^a-zA-Z_]+/', $pName)) {
                    $statusVars[] = $pName;
                }
                break;

            case 'proc':
                $result = $this->dbi->query('SHOW PROCESSLIST');
                $ret['value'] = $this->dbi->numRows($result);
                break;

            case 'cpu':
                if (! $sysinfo) {
                    $sysinfo = SysInfo::get();
                }
                if (! $cpuload) {
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
                if (! $sysinfo) {
                    $sysinfo = SysInfo::get();
                }
                if (! $memory) {
                    $memory = $sysinfo->memory();
                }

                $ret['value'] = isset($memory[$pName]) ? $memory[$pName] : 0;
                break;
        }

        return [
            $serverVars,
            $statusVars,
            $ret,
        ];
    }

    /**
     * Returns JSON for log data with type: slow
     *
     * @param int $start Unix Time: Start time for query
     * @param int $end   Unix Time: End time for query
     *
     * @return array
     */
    public function getJsonForLogDataTypeSlow(int $start, int $end): array
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

        $result = $this->dbi->tryQuery($query);

        $return = [
            'rows' => [],
            'sum' => [],
        ];

        while ($row = $this->dbi->fetchAssoc($result)) {
            $type = mb_strtolower(
                mb_substr(
                    $row['sql_text'],
                    0,
                    mb_strpos($row['sql_text'], ' ')
                )
            );

            switch ($type) {
                case 'insert':
                case 'update':
                    //Cut off big inserts and updates, but append byte count instead
                    if (mb_strlen($row['sql_text']) > 220) {
                        $implodeSqlText = implode(
                            ' ',
                            Util::formatByteDown(
                                mb_strlen($row['sql_text']),
                                2,
                                2
                            )
                        );
                        $row['sql_text'] = mb_substr($row['sql_text'], 0, 200)
                            . '... [' . $implodeSqlText . ']';
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

        $this->dbi->freeResult($result);
        return $return;
    }

    /**
     * Returns JSon for log data with type: general
     *
     * @param int  $start           Unix Time: Start time for query
     * @param int  $end             Unix Time: End time for query
     * @param bool $isTypesLimited  Whether to limit types or not
     * @param bool $removeVariables Whether to remove variables or not
     *
     * @return array
     */
    public function getJsonForLogDataTypeGeneral(
        int $start,
        int $end,
        bool $isTypesLimited,
        bool $removeVariables
    ): array {
        $limitTypes = '';
        if ($isTypesLimited) {
            $limitTypes = 'AND argument REGEXP \'^(INSERT|SELECT|UPDATE|DELETE)\' ';
        }

        $query = 'SELECT TIME(event_time) as event_time, user_host, thread_id, ';
        $query .= 'server_id, argument, count(argument) as \'#\' ';
        $query .= 'FROM `mysql`.`general_log` ';
        $query .= 'WHERE command_type=\'Query\' ';
        $query .= 'AND event_time > FROM_UNIXTIME(' . $start . ') ';
        $query .= 'AND event_time < FROM_UNIXTIME(' . $end . ') ';
        $query .= $limitTypes . 'GROUP by argument'; // HAVING count > 1';

        $result = $this->dbi->tryQuery($query);

        $return = [
            'rows' => [],
            'sum' => [],
        ];
        $insertTables = [];
        $insertTablesFirst = -1;
        $i = 0;

        while ($row = $this->dbi->fetchAssoc($result)) {
            preg_match('/^(\w+)\s/', $row['argument'], $match);
            $type = mb_strtolower($match[1]);

            if (! isset($return['sum'][$type])) {
                $return['sum'][$type] = 0;
            }
            $return['sum'][$type] += $row['#'];

            switch ($type) {
            /** @noinspection PhpMissingBreakStatementInspection */
                case 'insert':
                    // Group inserts if selected
                    if ($removeVariables
                    && preg_match(
                        '/^INSERT INTO (`|\'|"|)([^\s\\1]+)\\1/i',
                        $row['argument'],
                        $matches
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
                                .= $this->getSuspensionPoints(
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
                        . implode(
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

        $this->dbi->freeResult($result);

        return $return;
    }

    /**
     * Return suspension points if needed
     *
     * @param string $lastChar Last char
     *
     * @return string Return suspension points if needed
     */
    private function getSuspensionPoints(string $lastChar): string
    {
        if ($lastChar != '.') {
            return '<br>...';
        }

        return '';
    }

    /**
     * Returns JSON for logging vars
     *
     * @param string|null $name  Variable name
     * @param string|null $value Variable value
     *
     * @return array JSON
     */
    public function getJsonForLoggingVars(?string $name, ?string $value): array
    {
        if (isset($name) && isset($value)) {
            $escapedValue = $this->dbi->escapeString($value);
            if (! is_numeric($escapedValue)) {
                $escapedValue = "'" . $escapedValue . "'";
            }

            if (! preg_match("/[^a-zA-Z0-9_]+/", $name)) {
                $this->dbi->query(
                    'SET GLOBAL ' . $name . ' = ' . $escapedValue
                );
            }
        }

        $loggingVars = $this->dbi->fetchResult(
            'SHOW GLOBAL VARIABLES WHERE Variable_name IN'
            . ' ("general_log","slow_query_log","long_query_time","log_output")',
            0,
            1
        );
        return $loggingVars;
    }

    /**
     * Returns JSON for query_analyzer
     *
     * @param string $database Database name
     * @param string $query    SQL query
     *
     * @return array JSON
     */
    public function getJsonForQueryAnalyzer(
        string $database,
        string $query
    ): array {
        global $cached_affected_rows;

        $return = [];

        if (strlen($database) > 0) {
            $this->dbi->selectDb($database);
        }

        if ($profiling = Util::profilingSupported()) {
            $this->dbi->query('SET PROFILING=1;');
        }

        // Do not cache query
        $sqlQuery = preg_replace(
            '/^(\s*SELECT)/i',
            '\\1 SQL_NO_CACHE',
            $query
        );

        $this->dbi->tryQuery($sqlQuery);
        $return['affectedRows'] = $cached_affected_rows;

        $result = $this->dbi->tryQuery('EXPLAIN ' . $sqlQuery);
        while ($row = $this->dbi->fetchAssoc($result)) {
            $return['explain'][] = $row;
        }

        // In case an error happened
        $return['error'] = $this->dbi->getError();

        $this->dbi->freeResult($result);

        if ($profiling) {
            $return['profiling'] = [];
            $result = $this->dbi->tryQuery(
                'SELECT seq,state,duration FROM INFORMATION_SCHEMA.PROFILING'
                . ' WHERE QUERY_ID=1 ORDER BY seq'
            );
            while ($row = $this->dbi->fetchAssoc($result)) {
                $return['profiling'][] = $row;
            }
            $this->dbi->freeResult($result);
        }
        return $return;
    }
}
