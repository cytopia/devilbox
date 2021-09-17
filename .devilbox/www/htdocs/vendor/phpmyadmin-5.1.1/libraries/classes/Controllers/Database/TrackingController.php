<?php

declare(strict_types=1);

namespace PhpMyAdmin\Controllers\Database;

use PhpMyAdmin\CheckUserPrivileges;
use PhpMyAdmin\DatabaseInterface;
use PhpMyAdmin\Html\Generator;
use PhpMyAdmin\Message;
use PhpMyAdmin\Response;
use PhpMyAdmin\Template;
use PhpMyAdmin\Tracker;
use PhpMyAdmin\Tracking;
use PhpMyAdmin\Url;
use PhpMyAdmin\Util;
use function count;
use function htmlspecialchars;
use function sprintf;

/**
 * Tracking configuration for database.
 */
class TrackingController extends AbstractController
{
    /** @var Tracking */
    private $tracking;

    /** @var DatabaseInterface */
    private $dbi;

    /**
     * @param Response          $response
     * @param string            $db       Database name.
     * @param DatabaseInterface $dbi
     */
    public function __construct($response, Template $template, $db, Tracking $tracking, $dbi)
    {
        parent::__construct($response, $template, $db);
        $this->tracking = $tracking;
        $this->dbi = $dbi;
    }

    public function index(): void
    {
        global $db, $text_dir, $url_params, $tables, $num_tables, $PMA_Theme;
        global $total_num_tables, $sub_part, $pos, $data, $cfg;
        global $tooltip_truename, $tooltip_aliasname, $err_url;

        $this->addScriptFiles(['vendor/jquery/jquery.tablesorter.js', 'database/tracking.js']);

        Util::checkParameters(['db']);

        $err_url = Util::getScriptNameForOption($cfg['DefaultTabDatabase'], 'database');
        $err_url .= Url::getCommon(['db' => $db], '&');

        if (! $this->hasDatabase()) {
            return;
        }

        $url_params['goto'] = Url::getFromRoute('/table/tracking');
        $url_params['back'] = Url::getFromRoute('/database/tracking');

        // Get the database structure
        $sub_part = '_structure';

        [
            $tables,
            $num_tables,
            $total_num_tables,
            $sub_part,,
            $isSystemSchema,
            $tooltip_truename,
            $tooltip_aliasname,
            $pos,
        ] = Util::getDbInfo($db, $sub_part ?? '');

        if (isset($_POST['delete_tracking'], $_POST['table'])) {
            Tracker::deleteTracking($db, $_POST['table']);
            echo Message::success(
                __('Tracking data deleted successfully.')
            )->getDisplay();
        } elseif (isset($_POST['submit_create_version'])) {
            $this->tracking->createTrackingForMultipleTables($_POST['selected']);
            echo Message::success(
                sprintf(
                    __(
                        'Version %1$s was created for selected tables,'
                        . ' tracking is active for them.'
                    ),
                    htmlspecialchars($_POST['version'])
                )
            )->getDisplay();
        } elseif (isset($_POST['submit_mult'])) {
            if (! empty($_POST['selected_tbl'])) {
                if ($_POST['submit_mult'] === 'delete_tracking') {
                    foreach ($_POST['selected_tbl'] as $table) {
                        Tracker::deleteTracking($db, $table);
                    }
                    echo Message::success(
                        __('Tracking data deleted successfully.')
                    )->getDisplay();
                } elseif ($_POST['submit_mult'] === 'track') {
                    echo $this->template->render('create_tracking_version', [
                        'route' => '/database/tracking',
                        'url_params' => $url_params,
                        'last_version' => 0,
                        'db' => $db,
                        'selected' => $_POST['selected_tbl'],
                        'type' => 'both',
                        'default_statements' => $cfg['Server']['tracking_default_statements'],
                    ]);

                    return;
                }
            } else {
                echo Message::notice(
                    __('No tables selected.')
                )->getDisplay();
            }
        }

        // Get tracked data about the database
        $data = Tracker::getTrackedData($db, '', '1');

        // No tables present and no log exist
        if ($num_tables == 0 && count($data['ddlog']) === 0) {
            echo '<p>' , __('No tables found in database.') , '</p>' , "\n";

            if (empty($isSystemSchema)) {
                $checkUserPrivileges = new CheckUserPrivileges($this->dbi);
                $checkUserPrivileges->getPrivileges();

                echo $this->template->render('database/create_table', ['db' => $db]);
            }

            return;
        }

        echo $this->tracking->getHtmlForDbTrackingTables(
            $db,
            $url_params,
            $PMA_Theme->getImgPath(),
            $text_dir
        );

        // If available print out database log
        if (count($data['ddlog']) <= 0) {
            return;
        }

        $log = '';
        foreach ($data['ddlog'] as $entry) {
            $log .= '# ' . $entry['date'] . ' ' . $entry['username'] . "\n"
                . $entry['statement'] . "\n";
        }
        echo Generator::getMessage(__('Database Log'), $log);
    }
}
