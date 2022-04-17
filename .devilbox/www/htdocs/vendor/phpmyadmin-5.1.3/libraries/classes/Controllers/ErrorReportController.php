<?php
/**
 * Handle error report submission
 */

declare(strict_types=1);

namespace PhpMyAdmin\Controllers;

use PhpMyAdmin\ErrorHandler;
use PhpMyAdmin\ErrorReport;
use PhpMyAdmin\Message;
use PhpMyAdmin\Response;
use PhpMyAdmin\Template;
use PhpMyAdmin\UserPreferences;
use function count;
use function in_array;
use function is_string;
use function json_decode;
use function time;

/**
 * Handle error report submission
 */
class ErrorReportController extends AbstractController
{
    /** @var ErrorReport */
    private $errorReport;

    /** @var ErrorHandler */
    private $errorHandler;

    /**
     * @param Response $response
     */
    public function __construct(
        $response,
        Template $template,
        ErrorReport $errorReport,
        ErrorHandler $errorHandler
    ) {
        parent::__construct($response, $template);
        $this->errorReport = $errorReport;
        $this->errorHandler = $errorHandler;
    }

    public function index(): void
    {
        global $cfg;

        if (! isset($_POST['exception_type'])
            || ! in_array($_POST['exception_type'], ['js', 'php'])
        ) {
            return;
        }

        if (isset($_POST['send_error_report'])
            && ($_POST['send_error_report'] == true
                || $_POST['send_error_report'] == '1')
        ) {
            if ($_POST['exception_type'] === 'php') {
                /**
                 * Prevent infinite error submission.
                 * Happens in case error submissions fails.
                 * If reporting is done in some time interval,
                 *  just clear them & clear json data too.
                 */
                if (isset($_SESSION['prev_error_subm_time'], $_SESSION['error_subm_count'])
                    && $_SESSION['error_subm_count'] >= 3
                    && ($_SESSION['prev_error_subm_time'] - time()) <= 3000
                ) {
                    $_SESSION['error_subm_count'] = 0;
                    $_SESSION['prev_errors'] = '';
                    $this->response->addJSON('stopErrorReportLoop', '1');
                } else {
                    $_SESSION['prev_error_subm_time'] = time();
                    $_SESSION['error_subm_count'] = isset($_SESSION['error_subm_count'])
                        ? $_SESSION['error_subm_count'] + 1
                        : 0;
                }
            }
            $reportData = $this->errorReport->getData($_POST['exception_type']);
            // report if and only if there were 'actual' errors.
            if (count($reportData) > 0) {
                $server_response = $this->errorReport->send($reportData);
                if (! is_string($server_response)) {
                    $success = false;
                } else {
                    $decoded_response = json_decode($server_response, true);
                    $success = ! empty($decoded_response) ?
                        $decoded_response['success'] : false;
                }

                /* Message to show to the user */
                if ($success) {
                    if ((isset($_POST['automatic'])
                            && $_POST['automatic'] === 'true')
                        || $cfg['SendErrorReports'] === 'always'
                    ) {
                        $msg = __(
                            'An error has been detected and an error report has been '
                            . 'automatically submitted based on your settings.'
                        );
                    } else {
                        $msg = __('Thank you for submitting this report.');
                    }
                } else {
                    $msg = __(
                        'An error has been detected and an error report has been '
                        . 'generated but failed to be sent.'
                    );
                    $msg .= ' ';
                    $msg .= __(
                        'If you experience any '
                        . 'problems please submit a bug report manually.'
                    );
                }
                $msg .= ' ' . __('You may want to refresh the page.');

                /* Create message object */
                if ($success) {
                    $msg = Message::notice($msg);
                } else {
                    $msg = Message::error($msg);
                }

                /* Add message to response */
                if ($this->response->isAjax()) {
                    if ($_POST['exception_type'] === 'js') {
                        $this->response->addJSON('message', $msg);
                    } else {
                        $this->response->addJSON('errSubmitMsg', $msg);
                    }
                } elseif ($_POST['exception_type'] === 'php') {
                    $jsCode = 'Functions.ajaxShowMessage(\'<div class="alert alert-danger" role="alert">'
                        . $msg
                        . '</div>\', false);';
                    $this->response->getFooter()->getScripts()->addCode($jsCode);
                }

                if ($_POST['exception_type'] === 'php') {
                    // clear previous errors & save new ones.
                    $this->errorHandler->savePreviousErrors();
                }

                /* Persist always send settings */
                if (isset($_POST['always_send'])
                    && $_POST['always_send'] === 'true'
                ) {
                    $userPreferences = new UserPreferences();
                    $userPreferences->persistOption('SendErrorReports', 'always', 'ask');
                }
            }
        } elseif (! empty($_POST['get_settings'])) {
            $this->response->addJSON('report_setting', $cfg['SendErrorReports']);
        } elseif ($_POST['exception_type'] === 'js') {
            $this->response->addHTML($this->errorReport->getForm());
        } else {
            // clear previous errors & save new ones.
            $this->errorHandler->savePreviousErrors();
        }
    }
}
