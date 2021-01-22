<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Exporting of translated messages from PHP to Javascript
 *
 * @package PhpMyAdmin
 */
declare(strict_types=1);

if (! defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
}

if (! defined('TESTSUITE')) {
    chdir('..');

    // Send correct type:
    header('Content-Type: text/javascript; charset=UTF-8');

    // Cache output in client - the nocache query parameter makes sure that this
    // file is reloaded when config changes
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');

    // Avoid loading the full common.inc.php because this would add many
    // non-js-compatible stuff like DOCTYPE
    define('PMA_MINIMUM_COMMON', true);
    define('PMA_PATH_TO_BASEDIR', '../');
    define('PMA_NO_SESSION', true);
    require_once ROOT_PATH . 'libraries/common.inc.php';
}

// But this one is needed for Sanitize::escapeJsString()
use PhpMyAdmin\Sanitize;

$buffer = PhpMyAdmin\OutputBuffering::getInstance();
$buffer->start();
if (! defined('TESTSUITE')) {
    register_shutdown_function(
        function () {
            echo PhpMyAdmin\OutputBuffering::getInstance()->getContents();
        }
    );
}

/* For confirmations */
$js_messages['strConfirm'] = __('Confirm');
$js_messages['strDoYouReally'] = __('Do you really want to execute "%s"?');
$js_messages['strDropDatabaseStrongWarning']
    = __('You are about to DESTROY a complete database!');
$js_messages['strDatabaseRenameToSameName']
    = __('Cannot rename database to the same name. Change the name and try again');
$js_messages['strDropTableStrongWarning']
    = __('You are about to DESTROY a complete table!');
$js_messages['strTruncateTableStrongWarning']
    = __('You are about to TRUNCATE a complete table!');
$js_messages['strDeleteTrackingData'] = __('Delete tracking data for this table?');
$js_messages['strDeleteTrackingDataMultiple']
    = __('Delete tracking data for these tables?');
$js_messages['strDeleteTrackingVersion']
    = __('Delete tracking data for this version?');
$js_messages['strDeleteTrackingVersionMultiple']
    = __('Delete tracking data for these versions?');
$js_messages['strDeletingTrackingEntry'] = __('Delete entry from tracking report?');
$js_messages['strDeletingTrackingData'] = __('Deleting tracking data');
$js_messages['strDroppingPrimaryKeyIndex'] = __('Dropping Primary Key/Index');
$js_messages['strDroppingForeignKey'] = __('Dropping Foreign key.');
$js_messages['strOperationTakesLongTime']
    = __('This operation could take a long time. Proceed anyway?');
$js_messages['strDropUserGroupWarning']
    = __('Do you really want to delete user group "%s"?');
$js_messages['strConfirmDeleteQBESearch']
    = __('Do you really want to delete the search "%s"?');
$js_messages['strConfirmNavigation']
    = __('You have unsaved changes; are you sure you want to leave this page?');
$js_messages['strConfirmRowChange']
    = __('You are trying to reduce the number of rows, but have already entered data in those rows which will be lost. Do you wish to continue?');
$js_messages['strDropUserWarning']
    = __('Do you really want to revoke the selected user(s) ?');
$js_messages['strDeleteCentralColumnWarning']
    = __('Do you really want to delete this central column?');
$js_messages['strDropRTEitems']
    = __('Do you really want to delete the selected items?');
$js_messages['strDropPartitionWarning'] = __(
    'Do you really want to DROP the selected partition(s)? This will also DELETE ' .
    'the data related to the selected partition(s)!'
);
$js_messages['strTruncatePartitionWarning']
    = __('Do you really want to TRUNCATE the selected partition(s)?');
$js_messages['strRemovePartitioningWarning']
    = __('Do you really want to remove partitioning?');
$js_messages['strResetSlaveWarning'] = __('Do you really want to RESET SLAVE?');
$js_messages['strChangeColumnCollation'] = __(
    'This operation will attempt to convert your data to the new collation. In '
    . 'rare cases, especially where a character doesn\'t exist in the new '
    . 'collation, this process could cause the data to appear incorrectly under '
    . 'the new collation; in this case we suggest you revert to the original '
    . 'collation and refer to the tips at '
)
    . '<a href="%s" target="garbled_data_wiki">' . __('Garbled Data') . '</a>.'
    . '<br><br>'
    . __('Are you sure you wish to change the collation and convert the data?');
$js_messages['strChangeAllColumnCollationsWarning'] = __(
    'Through this operation, MySQL attempts to map the data values between '
    . 'collations. If the character sets are incompatible, there may be data loss '
    . 'and this lost data may <b>NOT</b> be recoverable simply by changing back the '
    . 'column collation(s). <b>To convert existing data, it is suggested to use the '
    . 'column(s) editing feature (the "Change" Link) on the table structure page. '
    . '</b>'
)
. '<br><br>'
. __(
    'Are you sure you wish to change all the column collations and convert the data?'
);

/* For modal dialog buttons */
$js_messages['strSaveAndClose'] = __('Save & close');
$js_messages['strReset'] = __('Reset');
$js_messages['strResetAll'] = __('Reset all');

/* For indexes */
$js_messages['strFormEmpty'] = __('Missing value in the form!');
$js_messages['strRadioUnchecked'] = __('Select at least one of the options!');
$js_messages['strEnterValidNumber'] = __('Please enter a valid number!');
$js_messages['strEnterValidLength'] = __('Please enter a valid length!');
$js_messages['strAddIndex'] = __('Add index');
$js_messages['strEditIndex'] = __('Edit index');
$js_messages['strAddToIndex'] = __('Add %s column(s) to index');
$js_messages['strCreateSingleColumnIndex'] = __('Create single-column index');
$js_messages['strCreateCompositeIndex'] = __('Create composite index');
$js_messages['strCompositeWith'] = __('Composite with:');
$js_messages['strMissingColumn'] = __('Please select column(s) for the index.');

/* For Preview SQL*/
$js_messages['strPreviewSQL'] = __('Preview SQL');

/* For Simulate DML*/
$js_messages['strSimulateDML'] = __('Simulate query');
$js_messages['strMatchedRows'] = __('Matched rows:');
$js_messages['strSQLQuery'] = __('SQL query:');

/* Charts */
/* l10n: Default label for the y-Axis of Charts */
$js_messages['strYValues'] = __('Y values');

/* Database multi-table query */
$js_messages['strEmptyQuery'] = __('Please enter the SQL query first.');

/* For server/privileges.js */
$js_messages['strHostEmpty'] = __('The host name is empty!');
$js_messages['strUserEmpty'] = __('The user name is empty!');
$js_messages['strPasswordEmpty'] = __('The password is empty!');
$js_messages['strPasswordNotSame'] = __('The passwords aren\'t the same!');
$js_messages['strRemovingSelectedUsers'] = __('Removing Selected Users');
$js_messages['strClose'] = __('Close');

/* For export.js */
$js_messages['strTemplateCreated'] = __('Template was created.');
$js_messages['strTemplateLoaded'] = __('Template was loaded.');
$js_messages['strTemplateUpdated'] = __('Template was updated.');
$js_messages['strTemplateDeleted'] = __('Template was deleted.');

/* l10n: Other, small valued, queries */
$js_messages['strOther'] = __('Other');
/* l10n: Thousands separator */
$js_messages['strThousandsSeparator'] = __(',');
/* l10n: Decimal separator */
$js_messages['strDecimalSeparator'] = __('.');

$js_messages['strChartConnectionsTitle'] = __('Connections / Processes');

/* server status monitor */
$js_messages['strIncompatibleMonitorConfig']
    = __('Local monitor configuration incompatible!');
$js_messages['strIncompatibleMonitorConfigDescription'] = __(
    'The chart arrangement configuration in your browsers local storage is not '
    . 'compatible anymore to the newer version of the monitor dialog. It is very '
    . 'likely that your current configuration will not work anymore. Please reset '
    . 'your configuration to default in the <i>Settings</i> menu.'
);

$js_messages['strQueryCacheEfficiency'] = __('Query cache efficiency');
$js_messages['strQueryCacheUsage'] = __('Query cache usage');
$js_messages['strQueryCacheUsed'] = __('Query cache used');

$js_messages['strSystemCPUUsage'] = __('System CPU usage');
$js_messages['strSystemMemory'] = __('System memory');
$js_messages['strSystemSwap'] = __('System swap');

$js_messages['strAverageLoad'] = __('Average load');
$js_messages['strTotalMemory'] = __('Total memory');
$js_messages['strCachedMemory'] = __('Cached memory');
$js_messages['strBufferedMemory'] = __('Buffered memory');
$js_messages['strFreeMemory'] = __('Free memory');
$js_messages['strUsedMemory'] = __('Used memory');

$js_messages['strTotalSwap'] = __('Total swap');
$js_messages['strCachedSwap'] = __('Cached swap');
$js_messages['strUsedSwap'] = __('Used swap');
$js_messages['strFreeSwap'] = __('Free swap');

$js_messages['strBytesSent'] = __('Bytes sent');
$js_messages['strBytesReceived'] = __('Bytes received');
$js_messages['strConnections'] = __('Connections');
$js_messages['strProcesses'] = __('Processes');

/* summary row */
$js_messages['strB'] = __('B');
$js_messages['strKiB'] = __('KiB');
$js_messages['strMiB'] = __('MiB');
$js_messages['strGiB'] = __('GiB');
$js_messages['strTiB'] = __('TiB');
$js_messages['strPiB'] = __('PiB');
$js_messages['strEiB'] = __('EiB');
$js_messages['strNTables'] = __('%d table(s)');

/* l10n: Questions is the name of a MySQL Status variable */
$js_messages['strQuestions'] = __('Questions');
$js_messages['strTraffic'] = __('Traffic');
$js_messages['strSettings'] = __('Settings');
$js_messages['strAddChart'] = __('Add chart to grid');
$js_messages['strClose'] = __('Close');
$js_messages['strAddOneSeriesWarning']
    = __('Please add at least one variable to the series!');
$js_messages['strNone'] = __('None');
$js_messages['strResumeMonitor'] = __('Resume monitor');
$js_messages['strPauseMonitor'] = __('Pause monitor');
$js_messages['strStartRefresh'] = __('Start auto refresh');
$js_messages['strStopRefresh'] = __('Stop auto refresh');
/* Monitor: Instructions Dialog */
$js_messages['strBothLogOn'] = __('general_log and slow_query_log are enabled.');
$js_messages['strGenLogOn'] = __('general_log is enabled.');
$js_messages['strSlowLogOn'] = __('slow_query_log is enabled.');
$js_messages['strBothLogOff'] = __('slow_query_log and general_log are disabled.');
$js_messages['strLogOutNotTable'] = __('log_output is not set to TABLE.');
$js_messages['strLogOutIsTable'] = __('log_output is set to TABLE.');
$js_messages['strSmallerLongQueryTimeAdvice'] = __(
    'slow_query_log is enabled, but the server logs only queries that take longer '
    . 'than %d seconds. It is advisable to set this long_query_time 0-2 seconds, '
    . 'depending on your system.'
);
$js_messages['strLongQueryTimeSet'] = __('long_query_time is set to %d second(s).');
$js_messages['strSettingsAppliedGlobal'] = __(
    'Following settings will be applied globally and reset to default on server '
    . 'restart:'
);
/* l10n: %s is FILE or TABLE */
$js_messages['strSetLogOutput'] = __('Set log_output to %s');
/* l10n: Enable in this context means setting a status variable to ON */
$js_messages['strEnableVar'] = __('Enable %s');
/* l10n: Disable in this context means setting a status variable to OFF */
$js_messages['strDisableVar'] = __('Disable %s');
/* l10n: %d seconds */
$js_messages['setSetLongQueryTime'] = __('Set long_query_time to %d seconds.');
$js_messages['strNoSuperUser'] = __(
    'You can\'t change these variables. Please log in as root or contact'
    . ' your database administrator.'
);
$js_messages['strChangeSettings'] = __('Change settings');
$js_messages['strCurrentSettings'] = __('Current settings');

$js_messages['strChartTitle'] = __('Chart title');
/* l10n: As in differential values */
$js_messages['strDifferential'] = __('Differential');
$js_messages['strDividedBy'] = __('Divided by %s');
$js_messages['strUnit'] = __('Unit');

$js_messages['strFromSlowLog'] = __('From slow log');
$js_messages['strFromGeneralLog'] = __('From general log');
$js_messages['strServerLogError'] = __(
    'The database name is not known for this query in the server\'s logs.'
);
$js_messages['strAnalysingLogsTitle'] = __('Analysing logs');
$js_messages['strAnalysingLogs']
    = __('Analysing & loading logs. This may take a while.');
$js_messages['strCancelRequest'] = __('Cancel request');
$js_messages['strCountColumnExplanation'] = __(
    'This column shows the amount of identical queries that are grouped together. '
    . 'However only the SQL query itself has been used as a grouping criteria, so '
    . 'the other attributes of queries, such as start time, may differ.'
);
$js_messages['strMoreCountColumnExplanation'] = __(
    'Since grouping of INSERTs queries has been selected, INSERT queries into the '
    . 'same table are also being grouped together, disregarding of the inserted '
    . 'data.'
);
$js_messages['strLogDataLoaded']
    = __('Log data loaded. Queries executed in this time span:');

$js_messages['strJumpToTable'] = __('Jump to Log table');
$js_messages['strNoDataFoundTitle'] = __('No data found');
$js_messages['strNoDataFound']
    = __('Log analysed, but no data found in this time span.');

$js_messages['strAnalyzing'] = __('Analyzing…');
$js_messages['strExplainOutput'] = __('Explain output');
$js_messages['strStatus'] = __('Status');
$js_messages['strTime'] = __('Time');
$js_messages['strTotalTime'] = __('Total time:');
$js_messages['strProfilingResults'] = __('Profiling results');
$js_messages['strTable'] = _pgettext('Display format', 'Table');
$js_messages['strChart'] = __('Chart');

$js_messages['strAliasDatabase'] = _pgettext('Alias', 'Database');
$js_messages['strAliasTable'] = _pgettext('Alias', 'Table');
$js_messages['strAliasColumn'] = _pgettext('Alias', 'Column');

/* l10n: A collection of available filters */
$js_messages['strFiltersForLogTable'] = __('Log table filter options');
/* l10n: Filter as in "Start Filtering" */
$js_messages['strFilter'] = __('Filter');
$js_messages['strFilterByWordRegexp'] = __('Filter queries by word/regexp:');
$js_messages['strIgnoreWhereAndGroup']
    = __('Group queries, ignoring variable data in WHERE clauses');
$js_messages['strSumRows'] = __('Sum of grouped rows:');
$js_messages['strTotal'] = __('Total:');

$js_messages['strLoadingLogs'] = __('Loading logs');
$js_messages['strRefreshFailed'] = __('Monitor refresh failed');
$js_messages['strInvalidResponseExplanation'] = __(
    'While requesting new chart data the server returned an invalid response. This '
    . 'is most likely because your session expired. Reloading the page and '
    . 'reentering your credentials should help.'
);
$js_messages['strReloadPage'] = __('Reload page');

$js_messages['strAffectedRows'] = __('Affected rows:');

$js_messages['strFailedParsingConfig'] = __(
    'Failed parsing config file. It doesn\'t seem to be valid JSON code.'
);
$js_messages['strFailedBuildingGrid'] = __(
    'Failed building chart grid with imported config. Resetting to default config…'
);
$js_messages['strImport'] = __('Import');
$js_messages['strImportDialogTitle'] = __('Import monitor configuration');
$js_messages['strImportDialogMessage']
    = __('Please select the file you want to import.');
$js_messages['strTableNameDialogMessage']
    = __('Please enter a valid table name.');
$js_messages['strDBNameDialogMessage']
    = __('Please enter a valid database name.');
$js_messages['strNoImportFile'] = __('No files available on server for import!');

$js_messages['strAnalyzeQuery'] = __('Analyse query');

/* Server status advisor */

$js_messages['strAdvisorSystem'] = __('Advisor system');
$js_messages['strPerformanceIssues'] = __('Possible performance issues');
$js_messages['strIssuse'] = __('Issue');
$js_messages['strRecommendation'] = __('Recommendation');
$js_messages['strRuleDetails'] = __('Rule details');
$js_messages['strJustification'] = __('Justification');
$js_messages['strFormula'] = __('Used variable / formula');
$js_messages['strTest'] = __('Test');

/* For query editor */
$js_messages['strFormatting'] = __('Formatting SQL…');
$js_messages['strNoParam'] = __('No parameters found!');

/* For inline query editing */
$js_messages['strGo'] = __('Go');
$js_messages['strCancel'] = __('Cancel');

/* For page-related settings */
$js_messages['strPageSettings'] = __('Page-related settings');
$js_messages['strApply'] = __('Apply');

/* For Ajax Notifications */
$js_messages['strLoading'] = __('Loading…');
$js_messages['strAbortedRequest'] = __('Request aborted!!');
$js_messages['strProcessingRequest'] = __('Processing request');
$js_messages['strRequestFailed'] = __('Request failed!!');
$js_messages['strErrorProcessingRequest'] = __('Error in processing request');
$js_messages['strErrorCode'] = __('Error code: %s');
$js_messages['strErrorText'] = __('Error text: %s');
$js_messages['strErrorConnection'] = __(
    'It seems that the connection to server has been lost. Please check your ' .
    'network connectivity and server status.'
);
$js_messages['strNoDatabasesSelected'] = __('No databases selected.');
$js_messages['strNoAccountSelected'] = __('No accounts selected.');
$js_messages['strDroppingColumn'] = __('Dropping column');
$js_messages['strAddingPrimaryKey'] = __('Adding primary key');
$js_messages['strOK'] = __('OK');
$js_messages['strDismiss'] = __('Click to dismiss this notification');

/* For database/operations.js */
$js_messages['strRenamingDatabases'] = __('Renaming databases');
$js_messages['strCopyingDatabase'] = __('Copying database');
$js_messages['strChangingCharset'] = __('Changing charset');
$js_messages['strNo'] = __('No');

/* For Foreign key checks */
$js_messages['strForeignKeyCheck'] = __('Enable foreign key checks');

/* For db_stucture.js */
$js_messages['strErrorRealRowCount'] = __('Failed to get real row count.');

/* For database/search.js */
$js_messages['strSearching'] = __('Searching');
$js_messages['strHideSearchResults'] = __('Hide search results');
$js_messages['strShowSearchResults'] = __('Show search results');
$js_messages['strBrowsing'] = __('Browsing');
$js_messages['strDeleting'] = __('Deleting');
$js_messages['strConfirmDeleteResults'] = __('Delete the matches for the %s table?');

/* For db_routines.js */
$js_messages['MissingReturn']
    = __('The definition of a stored function must contain a RETURN statement!');
$js_messages['strExport'] = __('Export');
$js_messages['NoExportable']
    = __('No routine is exportable. Required privileges may be lacking.');

/* For ENUM/SET editor*/
$js_messages['enum_editor'] = __('ENUM/SET editor');
$js_messages['enum_columnVals'] = __('Values for column %s');
$js_messages['enum_newColumnVals'] = __('Values for a new column');
$js_messages['enum_hint'] = __('Enter each value in a separate field.');
$js_messages['enum_addValue'] = __('Add %d value(s)');

/* For import.js */
$js_messages['strImportCSV'] = __(
    'Note: If the file contains multiple tables, they will be combined into one.'
);

/* For sql.js */
$js_messages['strHideQueryBox'] = __('Hide query box');
$js_messages['strShowQueryBox'] = __('Show query box');
$js_messages['strEdit'] = __('Edit');
$js_messages['strDelete'] = __('Delete');
$js_messages['strNotValidRowNumber'] = __('%d is not valid row number.');
$js_messages['strBrowseForeignValues'] = __('Browse foreign values');
$js_messages['strNoAutoSavedQuery'] = __('No previously auto-saved query is available. Loading default query.');
$js_messages['strPreviousSaveQuery'] = __('You have a previously saved query. Click Get auto-saved query to load the query.');
$js_messages['strBookmarkVariable'] = __('Variable %d:');

/* For Central list of columns */
$js_messages['pickColumn'] = __('Pick');
$js_messages['pickColumnTitle'] = __('Column selector');
$js_messages['searchList'] = __('Search this list');
$js_messages['strEmptyCentralList'] = __(
    'No columns in the central list. Make sure the Central columns list for '
    . 'database %s has columns that are not present in the current table.'
);
$js_messages['seeMore'] = __('See more');
$js_messages['confirmTitle'] = __('Are you sure?');
$js_messages['makeConsistentMessage'] = __(
    'This action may change some of the columns definition.<br>Are you sure you '
    . 'want to continue?'
);
$js_messages['strContinue'] = __('Continue');

/** For normalization */
$js_messages['strAddPrimaryKey'] = __('Add primary key');
$js_messages['strPrimaryKeyAdded'] = __('Primary key added.');
$js_messages['strToNextStep'] = __('Taking you to next step…');
$js_messages['strFinishMsg']
    = __("The first step of normalization is complete for table '%s'.");
$js_messages['strEndStep'] = __("End of step");
$js_messages['str2NFNormalization'] = __('Second step of normalization (2NF)');
$js_messages['strDone'] = __('Done');
$js_messages['strConfirmPd'] = __('Confirm partial dependencies');
$js_messages['strSelectedPd'] = __('Selected partial dependencies are as follows:');
$js_messages['strPdHintNote'] = __(
    'Note: a, b -> d,f implies values of columns a and b combined together can '
    . 'determine values of column d and column f.'
);
$js_messages['strNoPdSelected'] = __('No partial dependencies selected!');
$js_messages['strBack'] = __('Back');
$js_messages['strShowPossiblePd']
    = __('Show me the possible partial dependencies based on data in the table');
$js_messages['strHidePd'] = __('Hide partial dependencies list');
$js_messages['strWaitForPd'] = __(
    'Sit tight! It may take few seconds depending on data size and column count of '
    . 'the table.'
);
$js_messages['strStep'] = __('Step');
$js_messages['strMoveRepeatingGroup']
    = '<ol><b>' . __('The following actions will be performed:') . '</b>'
    . '<li>' . __('DROP columns %s from the table %s') . '</li>'
    . '<li>' . __('Create the following table') . '</li>';
$js_messages['strNewTablePlaceholder'] = 'Enter new table name';
$js_messages['strNewColumnPlaceholder'] = 'Enter column name';
$js_messages['str3NFNormalization'] = __('Third step of normalization (3NF)');
$js_messages['strConfirmTd'] = __('Confirm transitive dependencies');
$js_messages['strSelectedTd'] = __('Selected dependencies are as follows:');
$js_messages['strNoTdSelected'] = __('No dependencies selected!');

/* For server/variables.js */
$js_messages['strSave'] = __('Save');

/* For table/select.js */
$js_messages['strHideSearchCriteria'] = __('Hide search criteria');
$js_messages['strShowSearchCriteria'] = __('Show search criteria');
$js_messages['strRangeSearch'] = __('Range search');
$js_messages['strColumnMax'] = __('Column maximum:');
$js_messages['strColumnMin'] = __('Column minimum:');
$js_messages['strMinValue'] = __('Minimum value:');
$js_messages['strMaxValue'] = __('Maximum value:');

/* For table/find_replace.js */
$js_messages['strHideFindNReplaceCriteria'] = __('Hide find and replace criteria');
$js_messages['strShowFindNReplaceCriteria'] = __('Show find and replace criteria');

/* For table/zoom_plot_jqplot.js */
$js_messages['strDisplayHelp'] = '<ul><li>'
    . __('Each point represents a data row.')
    . '</li><li>'
    . __('Hovering over a point will show its label.')
    . '</li><li>'
    . __('To zoom in, select a section of the plot with the mouse.')
    . '</li><li>'
    . __('Click reset zoom button to come back to original state.')
    . '</li><li>'
    . __('Click a data point to view and possibly edit the data row.')
    . '</li><li>'
    . __('The plot can be resized by dragging it along the bottom right corner.')
    . '</li></ul>';
$js_messages['strHelpTitle'] = 'Zoom search instructions';
$js_messages['strInputNull'] = '<strong>' . __('Select two columns') . '</strong>';
$js_messages['strSameInputs'] = '<strong>'
    . __('Select two different columns')
    . '</strong>';
$js_messages['strDataPointContent'] = __('Data point content');

/* For table/change.js */
$js_messages['strIgnore'] = __('Ignore');
$js_messages['strCopy'] = __('Copy');
$js_messages['strX'] = __('X');
$js_messages['strY'] = __('Y');
$js_messages['strPoint'] = __('Point');
$js_messages['strPointN'] = __('Point %d');
$js_messages['strLineString'] = __('Linestring');
$js_messages['strPolygon'] = __('Polygon');
$js_messages['strGeometry'] = __('Geometry');
$js_messages['strInnerRing'] = __('Inner ring');
$js_messages['strOuterRing'] = __('Outer ring');
$js_messages['strAddPoint'] = __('Add a point');
$js_messages['strAddInnerRing'] = __('Add an inner ring');
$js_messages['strYes'] = __('Yes');
$js_messages['strCopyEncryptionKey'] = __('Do you want to copy encryption key?');
$js_messages['strEncryptionKey'] = __('Encryption key');

/* For Tip to be shown on Time field */
$js_messages['strMysqlAllowedValuesTipTime'] = __(
    'MySQL accepts additional values not selectable by the slider;'
    . ' key in those values directly if desired'
);

/* For Tip to be shown on Date field */
$js_messages['strMysqlAllowedValuesTipDate'] = __(
    'MySQL accepts additional values not selectable by the datepicker;'
    . ' key in those values directly if desired'
);

/* For Lock symbol Tooltip */
$js_messages['strLockToolTip'] = __(
    'Indicates that you have made changes to this page;'
    . ' you will be prompted for confirmation before abandoning changes'
);

/* Designer (js/designer/move.js) */
$js_messages['strSelectReferencedKey'] = __('Select referenced key');
$js_messages['strSelectForeignKey'] = __('Select Foreign Key');
$js_messages['strPleaseSelectPrimaryOrUniqueKey']
    = __('Please select the primary key or a unique key!');
$js_messages['strChangeDisplay'] = __('Choose column to display');
$js_messages['strLeavingDesigner'] = __(
    'You haven\'t saved the changes in the layout. They will be lost if you'
    . ' don\'t save them. Do you want to continue?'
);
$js_messages['strQueryEmpty'] = __('value/subQuery is empty');
$js_messages['strAddTables'] = __('Add tables from other databases');
$js_messages['strPageName'] = __('Page name');
$js_messages['strSavePage'] = __('Save page');
$js_messages['strSavePageAs'] = __('Save page as');
$js_messages['strOpenPage'] = __('Open page');
$js_messages['strDeletePage'] = __('Delete page');
$js_messages['strUntitled'] = __('Untitled');
$js_messages['strSelectPage'] = __('Please select a page to continue');
$js_messages['strEnterValidPageName'] = __('Please enter a valid page name');
$js_messages['strLeavingPage']
    = __('Do you want to save the changes to the current page?');
$js_messages['strSuccessfulPageDelete'] = __('Successfully deleted the page');
$js_messages['strExportRelationalSchema'] = __('Export relational schema');
$js_messages['strModificationSaved'] = __('Modifications have been saved');

/* Visual query builder (js/designer/move.js) */
$js_messages['strObjectsCreated'] = __('%d object(s) created.');
$js_messages['strColumnName'] = __('Column name');
$js_messages['strSubmit'] = __('Submit');

/* For makegrid.js (column reordering, show/hide column, grid editing) */
$js_messages['strCellEditHint'] = __('Press escape to cancel editing.');
$js_messages['strSaveCellWarning'] = __(
    'You have edited some data and they have not been saved. Are you sure you want '
    . 'to leave this page before saving the data?'
);
$js_messages['strColOrderHint'] = __('Drag to reorder.');
$js_messages['strSortHint'] = __('Click to sort results by this column.');
$js_messages['strMultiSortHint'] = __(
    'Shift+Click to add this column to ORDER BY clause or to toggle ASC/DESC.'
    . '<br>- Ctrl+Click or Alt+Click (Mac: Shift+Option+Click) to remove column '
    . 'from ORDER BY clause'
);
$js_messages['strColMarkHint'] = __('Click to mark/unmark.');
$js_messages['strColNameCopyHint'] = __('Double-click to copy column name.');
$js_messages['strColVisibHint'] = __(
    'Click the drop-down arrow<br>to toggle column\'s visibility.'
);
$js_messages['strShowAllCol'] = __('Show all');
$js_messages['strAlertNonUnique'] = __(
    'This table does not contain a unique column. Features related to the grid '
    . 'edit, checkbox, Edit, Copy and Delete links may not work after saving.'
);
$js_messages['strEnterValidHex']
    = __('Please enter a valid hexadecimal string. Valid characters are 0-9, A-F.');
$js_messages['strShowAllRowsWarning'] = __(
    'Do you really want to see all of the rows? For a big table this could crash '
    . 'the browser.'
);
$js_messages['strOriginalLength'] = __('Original length');

/** Drag & Drop sql import messages */
$js_messages['dropImportMessageCancel'] = __('cancel');
$js_messages['dropImportMessageAborted'] = __('Aborted');
$js_messages['dropImportMessageFailed'] = __('Failed');
$js_messages['dropImportMessageSuccess'] = __('Success');
$js_messages['dropImportImportResultHeader'] = __('Import status');
$js_messages['dropImportDropFiles'] = __('Drop files here');
$js_messages['dropImportSelectDB'] = __('Select database first');

/* For Print view */
$js_messages['print'] = __('Print');
$js_messages['back'] = __('Back');

// this approach does not work when the parameter is changed via user prefs
switch ($GLOBALS['cfg']['GridEditing']) {
    case 'double-click':
        $js_messages['strGridEditFeatureHint'] = __(
            'You can also edit most values<br>by double-clicking directly on them.'
        );
        break;
    case 'click':
        $js_messages['strGridEditFeatureHint'] = __(
            'You can also edit most values<br>by clicking directly on them.'
        );
        break;
    default:
        break;
}
$js_messages['strGoToLink'] = __('Go to link:');
$js_messages['strColNameCopyTitle'] = __('Copy column name.');
$js_messages['strColNameCopyText']
    = __('Right-click the column name to copy it to your clipboard.');

/* password generation */
$js_messages['strGeneratePassword'] = __('Generate password');
$js_messages['strGenerate'] = __('Generate');
$js_messages['strChangePassword'] = __('Change password');

/* navigation tabs */
$js_messages['strMore'] = __('More');

/* navigation panel */
$js_messages['strShowPanel'] = __('Show panel');
$js_messages['strHidePanel'] = __('Hide panel');
$js_messages['strUnhideNavItem'] = __('Show hidden navigation tree items.');
$js_messages['linkWithMain'] = __('Link with main panel');
$js_messages['unlinkWithMain'] = __('Unlink from main panel');

/* microhistory */
$js_messages['strInvalidPage']
    = __('The requested page was not found in the history, it may have expired.');

/* update */
$js_messages['strNewerVersion'] = __(
    'A newer version of phpMyAdmin is available and you should consider upgrading. '
    . 'The newest version is %s, released on %s.'
);
/* l10n: Latest available phpMyAdmin version */
$js_messages['strLatestAvailable'] = __(', latest stable version:');
$js_messages['strUpToDate'] = __('up to date');

$js_messages['strCreateView'] = __('Create view');

/* Error Reporting */
$js_messages['strSendErrorReport'] = __("Send error report");
$js_messages['strSubmitErrorReport'] = __("Submit error report");
$js_messages['strErrorOccurred'] = __(
    "A fatal JavaScript error has occurred. Would you like to send an error report?"
);
$js_messages['strChangeReportSettings'] = __("Change report settings");
$js_messages['strShowReportDetails'] = __("Show report details");
$js_messages['strIgnore'] = __("Ignore");
$js_messages['strTimeOutError'] = __(
    "Your export is incomplete, due to a low execution time limit at the PHP level!"
);

$js_messages['strTooManyInputs'] = __(
    "Warning: a form on this page has more than %d fields. On submission, "
    . "some of the fields might be ignored, due to PHP's "
    . "max_input_vars configuration."
);

$js_messages['phpErrorsFound'] = '<div class="error">'
    . __('Some errors have been detected on the server!')
    . '<br>'
    . __('Please look at the bottom of this window.')
    . '<div>'
    . '<input id="pma_ignore_errors_popup" type="submit" value="'
    . __('Ignore')
    . '" class="btn btn-secondary floatright message_errors_found">'
    . '<input id="pma_ignore_all_errors_popup" type="submit" value="'
    . __('Ignore All')
    . '" class="btn btn-secondary floatright message_errors_found">'
    . '</div></div>';

$js_messages['phpErrorsBeingSubmitted'] = '<div class="error">'
    . __('Some errors have been detected on the server!')
    . '<br>'
    . __(
        'As per your settings, they are being submitted currently, please be '
        . 'patient.'
    )
    . '<br>'
    . '<img src="'
    . $GLOBALS['PMA_Theme']->getImgPath('ajax_clock_small.gif')
    . '" width="16" height="16" alt="ajax clock">'
    . '</div>';
$js_messages['strCopyQueryButtonSuccess'] = __('Successfully copied!');
$js_messages['strCopyQueryButtonFailure'] = __('Copying failed!');

// For console
$js_messages['strConsoleRequeryConfirm'] = __('Execute this query again?');
$js_messages['strConsoleDeleteBookmarkConfirm']
    = __('Do you really want to delete this bookmark?');
$js_messages['strConsoleDebugError']
    = __('Some error occurred while getting SQL debug info.');
$js_messages['strConsoleDebugSummary']
    = __('%s queries executed %s times in %s seconds.');
$js_messages['strConsoleDebugArgsSummary'] = __('%s argument(s) passed');
$js_messages['strConsoleDebugShowArgs'] = __('Show arguments');
$js_messages['strConsoleDebugHideArgs'] = __('Hide arguments');
$js_messages['strConsoleDebugTimeTaken'] = __('Time taken:');
$js_messages['strNoLocalStorage'] = __('There was a problem accessing your browser storage, some features may not work properly for you. It is likely that the browser doesn\'t support storage or the quota limit has been reached. In Firefox, corrupted storage can also cause such a problem, clearing your "Offline Website Data" might help. In Safari, such problem is commonly caused by "Private Mode Browsing".');
// For modals in db_structure.php
$js_messages['strCopyTablesTo'] = __('Copy tables to');
$js_messages['strAddPrefix'] = __('Add table prefix');
$js_messages['strReplacePrefix'] = __('Replace table with prefix');
$js_messages['strCopyPrefix'] = __('Copy table with prefix');

/* For password strength simulation */
$js_messages['strExtrWeak'] = __('Extremely weak');
$js_messages['strVeryWeak'] = __('Very weak');
$js_messages['strWeak'] = __('Weak');
$js_messages['strGood'] = __('Good');
$js_messages['strStrong'] = __('Strong');

/* U2F errors */
$js_messages['strU2FTimeout'] = __('Timed out waiting for security key activation.');
$js_messages['strU2FError'] = __('Failed security key activation (%s).');

/* Designer */
$js_messages['strTableAlreadyExists'] = _pgettext('The table already exists in the designer and can not be added once more.', 'Table %s already exists!');
$js_messages['strHide'] = __('Hide');
$js_messages['strShow'] = __('Show');
$js_messages['strStructure'] = __('Structure');

echo "var Messages = [];\n";
foreach ($js_messages as $name => $js_message) {
    Sanitize::printJsValue("Messages." . $name . "", $js_message);
}

/* Image path */
echo "var pmaThemeImage = '" , $GLOBALS['pmaThemeImage'] , "';\n";

echo "var mysqlDocTemplate = '" , PhpMyAdmin\Util::getMySQLDocuURL('%s')
    , "';\n";

//Max input vars allowed by PHP.
$maxInputVars = ini_get('max_input_vars');
echo 'var maxInputVars = '
    , (false === $maxInputVars || '' == $maxInputVars ? 'false' : (int) $maxInputVars)
    , ';' . "\n";

echo "if ($.datepicker) {\n";
/* l10n: Display text for calendar close link */
Sanitize::printJsValue("$.datepicker.regional['']['closeText']", __('Done'));
/* l10n: Display text for previous month link in calendar */
Sanitize::printJsValue(
    "$.datepicker.regional['']['prevText']",
    _pgettext('Previous month', 'Prev')
);
/* l10n: Display text for next month link in calendar */
Sanitize::printJsValue(
    "$.datepicker.regional['']['nextText']",
    _pgettext('Next month', 'Next')
);
/* l10n: Display text for current month link in calendar */
Sanitize::printJsValue("$.datepicker.regional['']['currentText']", __('Today'));
Sanitize::printJsValue(
    "$.datepicker.regional['']['monthNames']",
    [
        __('January'),
        __('February'),
        __('March'),
        __('April'),
        __('May'),
        __('June'),
        __('July'),
        __('August'),
        __('September'),
        __('October'),
        __('November'),
        __('December'),
    ]
);
Sanitize::printJsValue(
    "$.datepicker.regional['']['monthNamesShort']",
    [
        /* l10n: Short month name */
        __('Jan'),
        /* l10n: Short month name */
        __('Feb'),
        /* l10n: Short month name */
        __('Mar'),
        /* l10n: Short month name */
        __('Apr'),
        /* l10n: Short month name */
        _pgettext('Short month name', 'May'),
        /* l10n: Short month name */
        __('Jun'),
        /* l10n: Short month name */
        __('Jul'),
        /* l10n: Short month name */
        __('Aug'),
        /* l10n: Short month name */
        __('Sep'),
        /* l10n: Short month name */
        __('Oct'),
        /* l10n: Short month name */
        __('Nov'),
        /* l10n: Short month name */
        __('Dec'),
    ]
);
Sanitize::printJsValue(
    "$.datepicker.regional['']['dayNames']",
    [
        __('Sunday'),
        __('Monday'),
        __('Tuesday'),
        __('Wednesday'),
        __('Thursday'),
        __('Friday'),
        __('Saturday'),
    ]
);
Sanitize::printJsValue(
    "$.datepicker.regional['']['dayNamesShort']",
    [
        /* l10n: Short week day name for Sunday */
        __('Sun'),
        /* l10n: Short week day name for Monday */
        __('Mon'),
        /* l10n: Short week day name for Tuesday */
        __('Tue'),
        /* l10n: Short week day name for Wednesday */
        __('Wed'),
        /* l10n: Short week day name for Thursday */
        __('Thu'),
        /* l10n: Short week day name for Friday */
        __('Fri'),
        /* l10n: Short week day name for Saturday */
        __('Sat'),
    ]
);
Sanitize::printJsValue(
    "$.datepicker.regional['']['dayNamesMin']",
    [
        /* l10n: Minimal week day name for Sunday */
        __('Su'),
        /* l10n: Minimal week day name for Monday */
        __('Mo'),
        /* l10n: Minimal week day name for Tuesday */
        __('Tu'),
        /* l10n: Minimal week day name for Wednesday */
        __('We'),
        /* l10n: Minimal week day name for Thursday */
        __('Th'),
        /* l10n: Minimal week day name for Friday */
        __('Fr'),
        /* l10n: Minimal week day name for Saturday */
        __('Sa'),
    ]
);
/* l10n: Column header for week of the year in calendar */
Sanitize::printJsValue("$.datepicker.regional['']['weekHeader']", __('Wk'));

Sanitize::printJsValue(
    "$.datepicker.regional['']['showMonthAfterYear']",
    /* l10n: Month-year order for calendar, use either "calendar-month-year"
    * or "calendar-year-month".
    */
    __('calendar-month-year') == 'calendar-year-month'
);
/* l10n: Year suffix for calendar, "none" is empty. */
$year_suffix = _pgettext('Year suffix', 'none');
Sanitize::printJsValue(
    "$.datepicker.regional['']['yearSuffix']",
    ($year_suffix == 'none' ? '' : $year_suffix)
);
?>
$.extend($.datepicker._defaults, $.datepicker.regional['']);
} /* if ($.datepicker) */

<?php
echo "if ($.timepicker) {\n";
Sanitize::printJsValue("$.timepicker.regional['']['timeText']", __('Time'));
Sanitize::printJsValue("$.timepicker.regional['']['hourText']", __('Hour'));
Sanitize::printJsValue("$.timepicker.regional['']['minuteText']", __('Minute'));
Sanitize::printJsValue("$.timepicker.regional['']['secondText']", __('Second'));
?>
$.extend($.timepicker._defaults, $.timepicker.regional['']);
} /* if ($.timepicker) */

<?php
/* Form validation */

echo "function extendingValidatorMessages() {\n";
echo "$.extend($.validator.messages, {\n";
/* Default validation functions */
Sanitize::printJsValueForFormValidation('required', __('This field is required'));
Sanitize::printJsValueForFormValidation('remote', __('Please fix this field'));
Sanitize::printJsValueForFormValidation('email', __('Please enter a valid email address'));
Sanitize::printJsValueForFormValidation('url', __('Please enter a valid URL'));
Sanitize::printJsValueForFormValidation('date', __('Please enter a valid date'));
Sanitize::printJsValueForFormValidation(
    'dateISO',
    __('Please enter a valid date ( ISO )')
);
Sanitize::printJsValueForFormValidation('number', __('Please enter a valid number'));
Sanitize::printJsValueForFormValidation(
    'creditcard',
    __('Please enter a valid credit card number')
);
Sanitize::printJsValueForFormValidation('digits', __('Please enter only digits'));
Sanitize::printJsValueForFormValidation(
    'equalTo',
    __('Please enter the same value again')
);
Sanitize::printJsValueForFormValidation(
    'maxlength',
    __('Please enter no more than {0} characters'),
    true
);
Sanitize::printJsValueForFormValidation(
    'minlength',
    __('Please enter at least {0} characters'),
    true
);
Sanitize::printJsValueForFormValidation(
    'rangelength',
    __('Please enter a value between {0} and {1} characters long'),
    true
);
Sanitize::printJsValueForFormValidation(
    'range',
    __('Please enter a value between {0} and {1}'),
    true
);
Sanitize::printJsValueForFormValidation(
    'max',
    __('Please enter a value less than or equal to {0}'),
    true
);
Sanitize::printJsValueForFormValidation(
    'min',
    __('Please enter a value greater than or equal to {0}'),
    true
);
/* customed functions */
Sanitize::printJsValueForFormValidation(
    'validationFunctionForDateTime',
    __('Please enter a valid date or time'),
    true
);
Sanitize::printJsValueForFormValidation(
    'validationFunctionForHex',
    __('Please enter a valid HEX input'),
    true
);
Sanitize::printJsValueForFormValidation(
    'validationFunctionForFuns',
    __('Error'),
    true,
    false
);
echo "\n});";
echo "\n} /* if ($.validator) */";
?>
