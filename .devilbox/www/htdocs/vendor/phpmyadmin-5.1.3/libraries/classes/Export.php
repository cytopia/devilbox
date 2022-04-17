<?php
/**
 * function for the main export logic
 */

declare(strict_types=1);

namespace PhpMyAdmin;

use PhpMyAdmin\Controllers\Database\ExportController as DatabaseExportController;
use PhpMyAdmin\Controllers\Server\ExportController as ServerExportController;
use PhpMyAdmin\Controllers\Table\ExportController as TableExportController;
use PhpMyAdmin\Plugins\ExportPlugin;
use PhpMyAdmin\Plugins\SchemaPlugin;
use function array_merge_recursive;
use function error_get_last;
use function fclose;
use function file_exists;
use function fopen;
use function function_exists;
use function fwrite;
use function gzencode;
use function header;
use function htmlentities;
use function htmlspecialchars;
use function implode;
use function in_array;
use function ini_get;
use function is_array;
use function is_file;
use function is_numeric;
use function is_object;
use function is_string;
use function is_writable;
use function mb_strlen;
use function mb_strpos;
use function mb_strtolower;
use function mb_substr;
use function ob_list_handlers;
use function preg_match;
use function preg_replace;
use function strlen;
use function strtolower;
use function substr;
use function time;
use function trim;
use function urlencode;

/**
 * PhpMyAdmin\Export class
 */
class Export
{
    /** @var DatabaseInterface */
    private $dbi;

    /**
     * @param DatabaseInterface $dbi DatabaseInterface instance
     */
    public function __construct($dbi)
    {
        $this->dbi = $dbi;
    }

    /**
     * Sets a session variable upon a possible fatal error during export
     */
    public function shutdown(): void
    {
        $error = error_get_last();
        if ($error == null || ! mb_strpos($error['message'], 'execution time')) {
            return;
        }

        //set session variable to check if there was error while exporting
        $_SESSION['pma_export_error'] = $error['message'];
    }

    /**
     * Detect ob_gzhandler
     */
    public function isGzHandlerEnabled(): bool
    {
        return in_array('ob_gzhandler', ob_list_handlers());
    }

    /**
     * Detect whether gzencode is needed; it might not be needed if
     * the server is already compressing by itself
     *
     * @return bool Whether gzencode is needed
     */
    public function gzencodeNeeded(): bool
    {
        /*
         * We should gzencode only if the function exists
         * but we don't want to compress twice, therefore
         * gzencode only if transparent compression is not enabled
         * and gz compression was not asked via $cfg['OBGzip']
         * but transparent compression does not apply when saving to server
         */
        $chromeAndGreaterThan43 = PMA_USR_BROWSER_AGENT == 'CHROME'
            && PMA_USR_BROWSER_VER >= 43; // see bug #4942

        return function_exists('gzencode')
            && ((! ini_get('zlib.output_compression')
                    && ! $this->isGzHandlerEnabled())
                || $GLOBALS['save_on_server']
                || $chromeAndGreaterThan43);
    }

    /**
     * Output handler for all exports, if needed buffering, it stores data into
     * $dump_buffer, otherwise it prints them out.
     *
     * @param string $line the insert statement
     *
     * @return bool Whether output succeeded
     */
    public function outputHandler(?string $line): bool
    {
        global $time_start, $dump_buffer, $dump_buffer_len, $save_filename;

        // Kanji encoding convert feature
        if ($GLOBALS['output_kanji_conversion']) {
            $line = Encoding::kanjiStrConv(
                $line,
                $GLOBALS['knjenc'],
                $GLOBALS['xkana'] ?? ''
            );
        }

        // If we have to buffer data, we will perform everything at once at the end
        if ($GLOBALS['buffer_needed']) {
            $dump_buffer .= $line;
            if ($GLOBALS['onfly_compression']) {
                $dump_buffer_len += strlen((string) $line);

                if ($dump_buffer_len > $GLOBALS['memory_limit']) {
                    if ($GLOBALS['output_charset_conversion']) {
                        $dump_buffer = Encoding::convertString(
                            'utf-8',
                            $GLOBALS['charset'],
                            $dump_buffer
                        );
                    }
                    if ($GLOBALS['compression'] === 'gzip'
                        && $this->gzencodeNeeded()
                    ) {
                        // as a gzipped file
                        // without the optional parameter level because it bugs
                        $dump_buffer = gzencode($dump_buffer);
                    }
                    if ($GLOBALS['save_on_server']) {
                        $write_result = @fwrite($GLOBALS['file_handle'], (string) $dump_buffer);
                        // Here, use strlen rather than mb_strlen to get the length
                        // in bytes to compare against the number of bytes written.
                        if ($write_result != strlen((string) $dump_buffer)) {
                            $GLOBALS['message'] = Message::error(
                                __('Insufficient space to save the file %s.')
                            );
                            $GLOBALS['message']->addParam($save_filename);

                            return false;
                        }
                    } else {
                        echo $dump_buffer;
                    }
                    $dump_buffer = '';
                    $dump_buffer_len = 0;
                }
            } else {
                $time_now = time();
                if ($time_start >= $time_now + 30) {
                    $time_start = $time_now;
                    header('X-pmaPing: Pong');
                }
            }
        } elseif ($GLOBALS['asfile']) {
            if ($GLOBALS['output_charset_conversion']) {
                $line = Encoding::convertString(
                    'utf-8',
                    $GLOBALS['charset'],
                    $line
                );
            }
            if ($GLOBALS['save_on_server'] && mb_strlen((string) $line) > 0) {
                if ($GLOBALS['file_handle'] !== null) {
                    $write_result = @fwrite($GLOBALS['file_handle'], (string) $line);
                } else {
                    $write_result = false;
                }
                // Here, use strlen rather than mb_strlen to get the length
                // in bytes to compare against the number of bytes written.
                if (! $write_result
                    || $write_result != strlen((string) $line)
                ) {
                    $GLOBALS['message'] = Message::error(
                        __('Insufficient space to save the file %s.')
                    );
                    $GLOBALS['message']->addParam($save_filename);

                    return false;
                }
                $time_now = time();
                if ($time_start >= $time_now + 30) {
                    $time_start = $time_now;
                    header('X-pmaPing: Pong');
                }
            } else {
                // We export as file - output normally
                echo $line;
            }
        } else {
            // We export as html - replace special chars
            echo htmlspecialchars((string) $line);
        }

        return true;
    }

    /**
     * Returns HTML containing the footer for a displayed export
     *
     * @param string $back_button   the link for going Back
     * @param string $refreshButton the link for refreshing page
     *
     * @return string the HTML output
     */
    public function getHtmlForDisplayedExportFooter(
        string $back_button,
        string $refreshButton
    ): string {
        /**
         * Close the html tags and add the footers for on-screen export
         */
        return '</textarea>'
            . '    </form>'
            . '<br>'
            // bottom back button
            . $back_button
            . $refreshButton
            . '</div>'
            . '<script type="text/javascript">' . "\n"
            . '//<![CDATA[' . "\n"
            . 'var $body = $("body");' . "\n"
            . '$("#textSQLDUMP")' . "\n"
            . '.width($body.width() - 50)' . "\n"
            . '.height($body.height() - 100);' . "\n"
            . '//]]>' . "\n"
            . '</script>' . "\n";
    }

    /**
     * Computes the memory limit for export
     *
     * @return int the memory limit
     */
    public function getMemoryLimit(): int
    {
        $memory_limit = trim((string) ini_get('memory_limit'));
        $memory_limit_num = (int) substr($memory_limit, 0, -1);
        $lowerLastChar = strtolower(substr($memory_limit, -1));
        // 2 MB as default
        if (empty($memory_limit) || $memory_limit == '-1') {
            $memory_limit = 2 * 1024 * 1024;
        } elseif ($lowerLastChar === 'm') {
            $memory_limit = $memory_limit_num * 1024 * 1024;
        } elseif ($lowerLastChar === 'k') {
            $memory_limit = $memory_limit_num * 1024;
        } elseif ($lowerLastChar === 'g') {
            $memory_limit = $memory_limit_num * 1024 * 1024 * 1024;
        } else {
            $memory_limit = (int) $memory_limit;
        }

        // Some of memory is needed for other things and as threshold.
        // During export I had allocated (see memory_get_usage function)
        // approx 1.2MB so this comes from that.
        if ($memory_limit > 1500000) {
            $memory_limit -= 1500000;
        }

        // Some memory is needed for compression, assume 1/3
        $memory_limit /= 8;

        return $memory_limit;
    }

    /**
     * Returns the filename and MIME type for a compression and an export plugin
     *
     * @param ExportPlugin $exportPlugin the export plugin
     * @param string       $compression  compression asked
     * @param string       $filename     the filename
     *
     * @return string[]    the filename and mime type
     */
    public function getFinalFilenameAndMimetypeForFilename(
        ExportPlugin $exportPlugin,
        string $compression,
        string $filename
    ): array {
        // Grab basic dump extension and mime type
        // Check if the user already added extension;
        // get the substring where the extension would be if it was included
        $extensionStartPos = mb_strlen($filename) - mb_strlen(
            $exportPlugin->getProperties()->getExtension()
        ) - 1;
        $userExtension = mb_substr(
            $filename,
            $extensionStartPos,
            mb_strlen($filename)
        );
        $requiredExtension = '.' . $exportPlugin->getProperties()->getExtension();
        if (mb_strtolower($userExtension) != $requiredExtension) {
            $filename  .= $requiredExtension;
        }
        $mime_type  = $exportPlugin->getProperties()->getMimeType();

        // If dump is going to be compressed, set correct mime_type and add
        // compression to extension
        if ($compression === 'gzip') {
            $filename  .= '.gz';
            $mime_type = 'application/x-gzip';
        } elseif ($compression === 'zip') {
            $filename  .= '.zip';
            $mime_type = 'application/zip';
        }

        return [
            $filename,
            $mime_type,
        ];
    }

    /**
     * Return the filename and MIME type for export file
     *
     * @param string       $export_type       type of export
     * @param string       $remember_template whether to remember template
     * @param ExportPlugin $export_plugin     the export plugin
     * @param string       $compression       compression asked
     * @param string       $filename_template the filename template
     *
     * @return string[] the filename template and mime type
     */
    public function getFilenameAndMimetype(
        string $export_type,
        string $remember_template,
        ExportPlugin $export_plugin,
        string $compression,
        string $filename_template
    ): array {
        if ($export_type === 'server') {
            if (! empty($remember_template)) {
                $GLOBALS['PMA_Config']->setUserValue(
                    'pma_server_filename_template',
                    'Export/file_template_server',
                    $filename_template
                );
            }
        } elseif ($export_type === 'database') {
            if (! empty($remember_template)) {
                $GLOBALS['PMA_Config']->setUserValue(
                    'pma_db_filename_template',
                    'Export/file_template_database',
                    $filename_template
                );
            }
        } elseif ($export_type === 'raw') {
            if (! empty($remember_template)) {
                $GLOBALS['PMA_Config']->setUserValue(
                    'pma_raw_filename_template',
                    'Export/file_template_raw',
                    $filename_template
                );
            }
        } else {
            if (! empty($remember_template)) {
                $GLOBALS['PMA_Config']->setUserValue(
                    'pma_table_filename_template',
                    'Export/file_template_table',
                    $filename_template
                );
            }
        }
        $filename = Util::expandUserString($filename_template);
        // remove dots in filename (coming from either the template or already
        // part of the filename) to avoid a remote code execution vulnerability
        $filename = Sanitize::sanitizeFilename($filename, true);

        return $this->getFinalFilenameAndMimetypeForFilename(
            $export_plugin,
            $compression,
            $filename
        );
    }

    /**
     * Open the export file
     *
     * @param string $filename     the export filename
     * @param bool   $quick_export whether it's a quick export or not
     *
     * @return array the full save filename, possible message and the file handle
     */
    public function openFile(string $filename, bool $quick_export): array
    {
        $file_handle = null;
        $message = '';
        $doNotSaveItOver = true;

        if (isset($_POST['quick_export_onserver_overwrite'])) {
            $doNotSaveItOver = $_POST['quick_export_onserver_overwrite'] !== 'saveitover';
        }

        $save_filename = Util::userDir((string) ($GLOBALS['cfg']['SaveDir'] ?? ''))
            . preg_replace('@[/\\\\]@', '_', $filename);

        if (@file_exists($save_filename)
            && ((! $quick_export && empty($_POST['onserver_overwrite']))
            || ($quick_export
            && $doNotSaveItOver))
        ) {
            $message = Message::error(
                __(
                    'File %s already exists on server, '
                    . 'change filename or check overwrite option.'
                )
            );
            $message->addParam($save_filename);
        } elseif (@is_file($save_filename) && ! @is_writable($save_filename)) {
            $message = Message::error(
                __(
                    'The web server does not have permission '
                    . 'to save the file %s.'
                )
            );
            $message->addParam($save_filename);
        } else {
            $file_handle = @fopen($save_filename, 'w');

            if ($file_handle === false) {
                $message = Message::error(
                    __(
                        'The web server does not have permission '
                        . 'to save the file %s.'
                    )
                );
                $message->addParam($save_filename);
            }
        }

        return [
            $save_filename,
            $message,
            $file_handle,
        ];
    }

    /**
     * Close the export file
     *
     * @param resource $file_handle   the export file handle
     * @param string   $dump_buffer   the current dump buffer
     * @param string   $save_filename the export filename
     *
     * @return Message a message object (or empty string)
     */
    public function closeFile(
        $file_handle,
        string $dump_buffer,
        string $save_filename
    ): Message {
        $write_result = @fwrite($file_handle, $dump_buffer);
        fclose($file_handle);
        // Here, use strlen rather than mb_strlen to get the length
        // in bytes to compare against the number of bytes written.
        if (strlen($dump_buffer) > 0
            && (! $write_result || $write_result != strlen($dump_buffer))
        ) {
            $message = new Message(
                __('Insufficient space to save the file %s.'),
                Message::ERROR,
                [$save_filename]
            );
        } else {
            $message = new Message(
                __('Dump has been saved to file %s.'),
                Message::SUCCESS,
                [$save_filename]
            );
        }

        return $message;
    }

    /**
     * Compress the export buffer
     *
     * @param array|string $dump_buffer the current dump buffer
     * @param string       $compression the compression mode
     * @param string       $filename    the filename
     *
     * @return array|string|bool
     */
    public function compress($dump_buffer, string $compression, string $filename)
    {
        if ($compression === 'zip' && function_exists('gzcompress')) {
            $zipExtension = new ZipExtension();
            $filename = substr($filename, 0, -4); // remove extension (.zip)
            $dump_buffer = $zipExtension->createFile($dump_buffer, $filename);
        } elseif ($compression === 'gzip' && $this->gzencodeNeeded() && is_string($dump_buffer)) {
            // without the optional parameter level because it bugs
            $dump_buffer = gzencode($dump_buffer);
        }

        return $dump_buffer;
    }

    /**
     * Saves the dump_buffer for a particular table in an array
     * Used in separate files export
     *
     * @param string $object_name the name of current object to be stored
     * @param bool   $append      optional boolean to append to an existing index or not
     */
    public function saveObjectInBuffer(string $object_name, bool $append = false): void
    {
        global $dump_buffer_objects, $dump_buffer, $dump_buffer_len;

        if (! empty($dump_buffer)) {
            if ($append && isset($dump_buffer_objects[$object_name])) {
                $dump_buffer_objects[$object_name] .= $dump_buffer;
            } else {
                $dump_buffer_objects[$object_name] = $dump_buffer;
            }
        }

        // Re - initialize
        $dump_buffer = '';
        $dump_buffer_len = 0;
    }

    /**
     * Returns HTML containing the header for a displayed export
     *
     * @param string $export_type the export type
     * @param string $db          the database name
     * @param string $table       the table name
     *
     * @return string[] the generated HTML and back button
     */
    public function getHtmlForDisplayedExportHeader(
        string $export_type,
        string $db,
        string $table
    ): array {
        $html = '<div>';

        /**
         * Displays a back button with all the $_POST data in the URL
         * (store in a variable to also display after the textarea)
         */
        $back_button = '<p id="export_back_button">[ <a href="';
        if ($export_type === 'server') {
            $back_button .= Url::getFromRoute('/server/export') . '" data-post="' . Url::getCommon([], '', false);
        } elseif ($export_type === 'database') {
            $back_button .= Url::getFromRoute('/database/export') . '" data-post="' . Url::getCommon(
                ['db' => $db],
                '',
                false
            );
        } else {
            $back_button .= Url::getFromRoute('/table/export') . '" data-post="' . Url::getCommon(
                ['db' => $db, 'table' => $table],
                '',
                false
            );
        }

        // Convert the multiple select elements from an array to a string
        if ($export_type === 'database') {
            $structOrDataForced = empty($_POST['structure_or_data_forced']);
            if ($structOrDataForced && ! isset($_POST['table_structure'])) {
                $_POST['table_structure'] = [];
            }
            if ($structOrDataForced && ! isset($_POST['table_data'])) {
                $_POST['table_data'] = [];
            }
        }

        foreach ($_POST as $name => $value) {
            if (is_array($value)) {
                continue;
            }

            $back_button .= '&amp;' . urlencode((string) $name) . '=' . urlencode((string) $value);
        }
        $back_button .= '&amp;repopulate=1">' . __('Back') . '</a> ]</p>';
        $html .= '<br>';
        $html .= $back_button;
        $refreshButton = '<form id="export_refresh_form" method="POST" action="'
            . Url::getFromRoute('/export') . '" class="disableAjax">';
        $refreshButton .= '[ <a class="disableAjax export_refresh_btn">' . __('Refresh') . '</a> ]';
        foreach ($_POST as $name => $value) {
            if (is_array($value)) {
                foreach ($value as $val) {
                    $refreshButton .= '<input type="hidden" name="' . htmlentities((string) $name)
                        . '[]" value="' . htmlentities((string) $val) . '">';
                }
            } else {
                $refreshButton .= '<input type="hidden" name="' . htmlentities((string) $name)
                    . '" value="' . htmlentities((string) $value) . '">';
            }
        }
        $refreshButton .= '</form>';
        $html .= $refreshButton
            . '<br>'
            . '<form name="nofunction">'
            . '<textarea name="sqldump" cols="50" rows="30" '
            . 'id="textSQLDUMP" wrap="OFF">';

        return [
            $html,
            $back_button,
            $refreshButton,
        ];
    }

    /**
     * Export at the server level
     *
     * @param string|array $db_select       the selected databases to export
     * @param string       $whatStrucOrData structure or data or both
     * @param ExportPlugin $export_plugin   the selected export plugin
     * @param string       $crlf            end of line character(s)
     * @param string       $err_url         the URL in case of error
     * @param string       $export_type     the export type
     * @param bool         $do_relation     whether to export relation info
     * @param bool         $do_comments     whether to add comments
     * @param bool         $do_mime         whether to add MIME info
     * @param bool         $do_dates        whether to add dates
     * @param array        $aliases         alias information for db/table/column
     * @param string       $separate_files  whether it is a separate-files export
     */
    public function exportServer(
        $db_select,
        string $whatStrucOrData,
        ExportPlugin $export_plugin,
        string $crlf,
        string $err_url,
        string $export_type,
        bool $do_relation,
        bool $do_comments,
        bool $do_mime,
        bool $do_dates,
        array $aliases,
        string $separate_files
    ): void {
        if (! empty($db_select)) {
            $tmp_select = implode('|', $db_select);
            $tmp_select = '|' . $tmp_select . '|';
        }
        // Walk over databases
        foreach ($GLOBALS['dblist']->databases as $current_db) {
            if (! isset($tmp_select)
                || ! mb_strpos(' ' . $tmp_select, '|' . $current_db . '|')
            ) {
                continue;
            }

            $tables = $this->dbi->getTables($current_db);
            $this->exportDatabase(
                $current_db,
                $tables,
                $whatStrucOrData,
                $tables,
                $tables,
                $export_plugin,
                $crlf,
                $err_url,
                $export_type,
                $do_relation,
                $do_comments,
                $do_mime,
                $do_dates,
                $aliases,
                $separate_files === 'database' ? $separate_files : ''
            );
            if ($separate_files !== 'server') {
                continue;
            }

            $this->saveObjectInBuffer($current_db);
        }
    }

    /**
     * Export at the database level
     *
     * @param string       $db              the database to export
     * @param array        $tables          the tables to export
     * @param string       $whatStrucOrData structure or data or both
     * @param array        $table_structure whether to export structure for each table
     * @param array        $table_data      whether to export data for each table
     * @param ExportPlugin $export_plugin   the selected export plugin
     * @param string       $crlf            end of line character(s)
     * @param string       $err_url         the URL in case of error
     * @param string       $export_type     the export type
     * @param bool         $do_relation     whether to export relation info
     * @param bool         $do_comments     whether to add comments
     * @param bool         $do_mime         whether to add MIME info
     * @param bool         $do_dates        whether to add dates
     * @param array        $aliases         Alias information for db/table/column
     * @param string       $separate_files  whether it is a separate-files export
     */
    public function exportDatabase(
        string $db,
        array $tables,
        string $whatStrucOrData,
        array $table_structure,
        array $table_data,
        ExportPlugin $export_plugin,
        string $crlf,
        string $err_url,
        string $export_type,
        bool $do_relation,
        bool $do_comments,
        bool $do_mime,
        bool $do_dates,
        array $aliases,
        string $separate_files
    ): void {
        $db_alias = ! empty($aliases[$db]['alias'])
            ? $aliases[$db]['alias'] : '';

        if (! $export_plugin->exportDBHeader($db, $db_alias)) {
            return;
        }
        if (! $export_plugin->exportDBCreate($db, $export_type, $db_alias)) {
            return;
        }
        if ($separate_files === 'database') {
            $this->saveObjectInBuffer('database', true);
        }

        if (($GLOBALS['sql_structure_or_data'] === 'structure'
            || $GLOBALS['sql_structure_or_data'] === 'structure_and_data')
            && isset($GLOBALS['sql_procedure_function'])
        ) {
            $export_plugin->exportRoutines($db, $aliases);

            if ($separate_files === 'database') {
                $this->saveObjectInBuffer('routines');
            }
        }

        $views = [];

        foreach ($tables as $table) {
            $_table = new Table($table, $db);
            // if this is a view, collect it for later;
            // views must be exported after the tables
            $is_view = $_table->isView();
            if ($is_view) {
                $views[] = $table;
            }
            if (($whatStrucOrData === 'structure'
                || $whatStrucOrData === 'structure_and_data')
                && in_array($table, $table_structure)
            ) {
                // for a view, export a stand-in definition of the table
                // to resolve view dependencies (only when it's a single-file export)
                if ($is_view) {
                    if ($separate_files == ''
                        && isset($GLOBALS['sql_create_view'])
                        && ! $export_plugin->exportStructure(
                            $db,
                            $table,
                            $crlf,
                            $err_url,
                            'stand_in',
                            $export_type,
                            $do_relation,
                            $do_comments,
                            $do_mime,
                            $do_dates,
                            $aliases
                        )
                    ) {
                        break;
                    }
                } elseif (isset($GLOBALS['sql_create_table'])) {
                    $table_size = $GLOBALS['maxsize'];
                    // Checking if the maximum table size constrain has been set
                    // And if that constrain is a valid number or not
                    if ($table_size !== '' && is_numeric($table_size)) {
                        // This obtains the current table's size
                        $query = 'SELECT data_length + index_length
                              from information_schema.TABLES
                              WHERE table_schema = "' . $this->dbi->escapeString($db) . '"
                              AND table_name = "' . $this->dbi->escapeString($table) . '"';

                        $size = $this->dbi->fetchValue($query);
                        //Converting the size to MB
                        $size /= 1024 / 1024;
                        if ($size > $table_size) {
                            continue;
                        }
                    }

                    if (! $export_plugin->exportStructure(
                        $db,
                        $table,
                        $crlf,
                        $err_url,
                        'create_table',
                        $export_type,
                        $do_relation,
                        $do_comments,
                        $do_mime,
                        $do_dates,
                        $aliases
                    )) {
                        break;
                    }
                }
            }
            // if this is a view or a merge table, don't export data
            if (($whatStrucOrData === 'data' || $whatStrucOrData === 'structure_and_data')
                && in_array($table, $table_data)
                && ! $is_view
            ) {
                $tableObj = new Table($table, $db);
                $nonGeneratedCols = $tableObj->getNonGeneratedColumns(true);

                $local_query  = 'SELECT ' . implode(', ', $nonGeneratedCols)
                    . ' FROM ' . Util::backquote($db)
                    . '.' . Util::backquote($table);

                if (! $export_plugin->exportData(
                    $db,
                    $table,
                    $crlf,
                    $err_url,
                    $local_query,
                    $aliases
                )) {
                    break;
                }
            }

            // this buffer was filled, we save it and go to the next one
            if ($separate_files === 'database') {
                $this->saveObjectInBuffer('table_' . $table);
            }

            // now export the triggers (needs to be done after the data because
            // triggers can modify already imported tables)
            if (! isset($GLOBALS['sql_create_trigger']) || ($whatStrucOrData !== 'structure'
                && $whatStrucOrData !== 'structure_and_data')
                || ! in_array($table, $table_structure)
            ) {
                continue;
            }

            if (! $export_plugin->exportStructure(
                $db,
                $table,
                $crlf,
                $err_url,
                'triggers',
                $export_type,
                $do_relation,
                $do_comments,
                $do_mime,
                $do_dates,
                $aliases
            )) {
                break;
            }

            if ($separate_files !== 'database') {
                continue;
            }

            $this->saveObjectInBuffer('table_' . $table, true);
        }

        if (isset($GLOBALS['sql_create_view'])) {
            foreach ($views as $view) {
                // no data export for a view
                if ($whatStrucOrData !== 'structure'
                    && $whatStrucOrData !== 'structure_and_data'
                ) {
                    continue;
                }

                if (! $export_plugin->exportStructure(
                    $db,
                    $view,
                    $crlf,
                    $err_url,
                    'create_view',
                    $export_type,
                    $do_relation,
                    $do_comments,
                    $do_mime,
                    $do_dates,
                    $aliases
                )) {
                    break;
                }

                if ($separate_files !== 'database') {
                    continue;
                }

                $this->saveObjectInBuffer('view_' . $view);
            }
        }

        if (! $export_plugin->exportDBFooter($db)) {
            return;
        }

        // export metadata related to this db
        if (isset($GLOBALS['sql_metadata'])) {
            // Types of metadata to export.
            // In the future these can be allowed to be selected by the user
            $metadataTypes = $this->getMetadataTypes();
            $export_plugin->exportMetadata($db, $tables, $metadataTypes);

            if ($separate_files === 'database') {
                $this->saveObjectInBuffer('metadata');
            }
        }

        if ($separate_files === 'database') {
            $this->saveObjectInBuffer('extra');
        }

        if (($GLOBALS['sql_structure_or_data'] !== 'structure'
            && $GLOBALS['sql_structure_or_data'] !== 'structure_and_data')
            || ! isset($GLOBALS['sql_procedure_function'])
        ) {
            return;
        }

        $export_plugin->exportEvents($db);

        if ($separate_files !== 'database') {
            return;
        }

        $this->saveObjectInBuffer('events');
    }

    /**
     * Export a raw query
     *
     * @param string       $whatStrucOrData whether to export structure for each table or raw
     * @param ExportPlugin $export_plugin   the selected export plugin
     * @param string       $crlf            end of line character(s)
     * @param string       $err_url         the URL in case of error
     * @param string       $sql_query       the query to be executed
     * @param string       $export_type     the export type
     */
    public static function exportRaw(
        string $whatStrucOrData,
        ExportPlugin $export_plugin,
        string $crlf,
        string $err_url,
        string $sql_query,
        string $export_type
    ): void {
        // In case the we need to dump just the raw query
        if ($whatStrucOrData !== 'raw') {
            return;
        }

        if (! $export_plugin->exportRawQuery(
            $err_url,
            $sql_query,
            $crlf
        )) {
            $GLOBALS['message'] = Message::error(
                // phpcs:disable Generic.Files.LineLength.TooLong
                /* l10n: A query written by the user is a "raw query" that could be using no tables or databases in particular */
                __('Exporting a raw query is not supported for this export method.')
            );

            return;
        }
    }

    /**
     * Export at the table level
     *
     * @param string       $db              the database to export
     * @param string       $table           the table to export
     * @param string       $whatStrucOrData structure or data or both
     * @param ExportPlugin $export_plugin   the selected export plugin
     * @param string       $crlf            end of line character(s)
     * @param string       $err_url         the URL in case of error
     * @param string       $export_type     the export type
     * @param bool         $do_relation     whether to export relation info
     * @param bool         $do_comments     whether to add comments
     * @param bool         $do_mime         whether to add MIME info
     * @param bool         $do_dates        whether to add dates
     * @param string|null  $allrows         whether "dump all rows" was ticked
     * @param string       $limit_to        upper limit
     * @param string       $limit_from      starting limit
     * @param string       $sql_query       query for which exporting is requested
     * @param array        $aliases         Alias information for db/table/column
     */
    public function exportTable(
        string $db,
        string $table,
        string $whatStrucOrData,
        ExportPlugin $export_plugin,
        string $crlf,
        string $err_url,
        string $export_type,
        bool $do_relation,
        bool $do_comments,
        bool $do_mime,
        bool $do_dates,
        ?string $allrows,
        string $limit_to,
        string $limit_from,
        string $sql_query,
        array $aliases
    ): void {
        $db_alias = ! empty($aliases[$db]['alias'])
            ? $aliases[$db]['alias'] : '';
        if (! $export_plugin->exportDBHeader($db, $db_alias)) {
            return;
        }
        if (isset($allrows)
            && $allrows == '0'
            && $limit_to > 0
            && $limit_from >= 0
        ) {
            $add_query  = ' LIMIT '
                        . ($limit_from > 0 ? $limit_from . ', ' : '')
                        . $limit_to;
        } else {
            $add_query  = '';
        }

        $_table = new Table($table, $db);
        $is_view = $_table->isView();
        if ($whatStrucOrData === 'structure'
            || $whatStrucOrData === 'structure_and_data'
        ) {
            if ($is_view) {
                if (isset($GLOBALS['sql_create_view'])) {
                    if (! $export_plugin->exportStructure(
                        $db,
                        $table,
                        $crlf,
                        $err_url,
                        'create_view',
                        $export_type,
                        $do_relation,
                        $do_comments,
                        $do_mime,
                        $do_dates,
                        $aliases
                    )) {
                        return;
                    }
                }
            } elseif (isset($GLOBALS['sql_create_table'])) {
                if (! $export_plugin->exportStructure(
                    $db,
                    $table,
                    $crlf,
                    $err_url,
                    'create_table',
                    $export_type,
                    $do_relation,
                    $do_comments,
                    $do_mime,
                    $do_dates,
                    $aliases
                )) {
                    return;
                }
            }
        }
        // If this is an export of a single view, we have to export data;
        // for example, a PDF report
        // if it is a merge table, no data is exported
        if ($whatStrucOrData === 'data'
            || $whatStrucOrData === 'structure_and_data'
        ) {
            if (! empty($sql_query)) {
                // only preg_replace if needed
                if (! empty($add_query)) {
                    // remove trailing semicolon before adding a LIMIT
                    $sql_query = preg_replace('%;\s*$%', '', $sql_query);
                }
                $local_query = $sql_query . $add_query;
                $this->dbi->selectDb($db);
            } else {
                // Data is exported only for Non-generated columns
                $tableObj = new Table($table, $db);
                $nonGeneratedCols = $tableObj->getNonGeneratedColumns(true);

                $local_query  = 'SELECT ' . implode(', ', $nonGeneratedCols)
                    . ' FROM ' . Util::backquote($db)
                    . '.' . Util::backquote($table) . $add_query;
            }
            if (! $export_plugin->exportData(
                $db,
                $table,
                $crlf,
                $err_url,
                $local_query,
                $aliases
            )) {
                return;
            }
        }
        // now export the triggers (needs to be done after the data because
        // triggers can modify already imported tables)
        if (isset($GLOBALS['sql_create_trigger']) && ($whatStrucOrData === 'structure'
            || $whatStrucOrData === 'structure_and_data')
        ) {
            if (! $export_plugin->exportStructure(
                $db,
                $table,
                $crlf,
                $err_url,
                'triggers',
                $export_type,
                $do_relation,
                $do_comments,
                $do_mime,
                $do_dates,
                $aliases
            )) {
                return;
            }
        }
        if (! $export_plugin->exportDBFooter($db)) {
            return;
        }

        if (! isset($GLOBALS['sql_metadata'])) {
            return;
        }

        // Types of metadata to export.
        // In the future these can be allowed to be selected by the user
        $metadataTypes = $this->getMetadataTypes();
        $export_plugin->exportMetadata($db, $table, $metadataTypes);
    }

    /**
     * Loads correct page after doing export
     */
    public function showPage(string $exportType): void
    {
        global $active_page, $containerBuilder;

        if ($exportType === 'server') {
            $active_page = Url::getFromRoute('/server/export');
            /** @var ServerExportController $controller */
            $controller = $containerBuilder->get(ServerExportController::class);
            $controller->index();

            return;
        }

        if ($exportType === 'database') {
            $active_page = Url::getFromRoute('/database/export');
            /** @var DatabaseExportController $controller */
            $controller = $containerBuilder->get(DatabaseExportController::class);
            $controller->index();

            return;
        }

        $active_page = Url::getFromRoute('/table/export');
        /** @var TableExportController $controller */
        $controller = $containerBuilder->get(TableExportController::class);
        $controller->index();
    }

    /**
     * Merge two alias arrays, if array1 and array2 have
     * conflicting alias then array2 value is used if it
     * is non empty otherwise array1 value.
     *
     * @param array $aliases1 first array of aliases
     * @param array $aliases2 second array of aliases
     *
     * @return array resultant merged aliases info
     */
    public function mergeAliases(array $aliases1, array $aliases2): array
    {
        // First do a recursive array merge
        // on aliases arrays.
        $aliases = array_merge_recursive($aliases1, $aliases2);
        // Now, resolve conflicts in aliases, if any
        foreach ($aliases as $db_name => $db) {
            // If alias key is an array then
            // it is a merge conflict.
            if (isset($db['alias']) && is_array($db['alias'])) {
                $val1 = $db['alias'][0];
                $val2 = $db['alias'][1];
                // Use aliases2 alias if non empty
                $aliases[$db_name]['alias']
                    = empty($val2) ? $val1 : $val2;
            }
            if (! isset($db['tables'])) {
                continue;
            }
            foreach ($db['tables'] as $tbl_name => $tbl) {
                if (isset($tbl['alias']) && is_array($tbl['alias'])) {
                    $val1 = $tbl['alias'][0];
                    $val2 = $tbl['alias'][1];
                    // Use aliases2 alias if non empty
                    $aliases[$db_name]['tables'][$tbl_name]['alias']
                        = empty($val2) ? $val1 : $val2;
                }
                if (! isset($tbl['columns'])) {
                    continue;
                }
                foreach ($tbl['columns'] as $col => $col_as) {
                    if (! isset($col_as) || ! is_array($col_as)) {
                        continue;
                    }

                    $val1 = $col_as[0];
                    $val2 = $col_as[1];
                    // Use aliases2 alias if non empty
                    $aliases[$db_name]['tables'][$tbl_name]['columns'][$col]
                        = empty($val2) ? $val1 : $val2;
                }
            }
        }

        return $aliases;
    }

    /**
     * Locks tables
     *
     * @param string $db       database name
     * @param array  $tables   list of table names
     * @param string $lockType lock type; "[LOW_PRIORITY] WRITE" or "READ [LOCAL]"
     *
     * @return mixed result of the query
     */
    public function lockTables(string $db, array $tables, string $lockType = 'WRITE')
    {
        $locks = [];
        foreach ($tables as $table) {
            $locks[] = Util::backquote($db) . '.'
                . Util::backquote($table) . ' ' . $lockType;
        }

        $sql = 'LOCK TABLES ' . implode(', ', $locks);

        return $this->dbi->tryQuery($sql);
    }

    /**
     * Releases table locks
     *
     * @return mixed result of the query
     */
    public function unlockTables()
    {
        return $this->dbi->tryQuery('UNLOCK TABLES');
    }

    /**
     * Returns all the metadata types that can be exported with a database or a table
     *
     * @return string[] metadata types.
     */
    public function getMetadataTypes(): array
    {
        return [
            'column_info',
            'table_uiprefs',
            'tracking',
            'bookmark',
            'relation',
            'table_coords',
            'pdf_pages',
            'savedsearches',
            'central_columns',
            'export_templates',
        ];
    }

    /**
     * Returns the checked clause, depending on the presence of key in array
     *
     * @param string $key   the key to look for
     * @param array  $array array to verify
     *
     * @return string the checked clause
     */
    public function getCheckedClause(string $key, array $array): string
    {
        if (in_array($key, $array)) {
            return ' checked="checked"';
        }

        return '';
    }

    /**
     * get all the export options and verify
     * call and include the appropriate Schema Class depending on $export_type
     *
     * @param string|null $export_type format of the export
     */
    public function processExportSchema(?string $export_type): void
    {
        /**
         * default is PDF, otherwise validate it's only letters a-z
         */
        if (! isset($export_type) || ! preg_match('/^[a-zA-Z]+$/', $export_type)) {
            $export_type = 'pdf';
        }

        // sanitize this parameter which will be used below in a file inclusion
        $export_type = Core::securePath($export_type);

        // get the specific plugin
        /** @var SchemaPlugin $export_plugin */
        $export_plugin = Plugins::getPlugin(
            'schema',
            $export_type,
            'libraries/classes/Plugins/Schema/'
        );

        // Check schema export type
        if ($export_plugin === null || ! is_object($export_plugin)) {
            Core::fatalError(__('Bad type!'));
        }

        $this->dbi->selectDb($_POST['db']);
        $export_plugin->exportSchema($_POST['db']);
    }
}
