<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Manages the rendering of pages in PMA
 *
 * @package PhpMyAdmin
 */
namespace PhpMyAdmin;

use PhpMyAdmin\Core;
use PhpMyAdmin\Footer;
use PhpMyAdmin\Header;
use PhpMyAdmin\Message;
use PhpMyAdmin\OutputBuffering;

/**
 * Singleton class used to manage the rendering of pages in PMA
 *
 * @package PhpMyAdmin
 */
class Response
{
    /**
     * Response instance
     *
     * @access private
     * @static
     * @var Response
     */
    private static $_instance;
    /**
     * Header instance
     *
     * @access private
     * @var Header
     */
    private $_header;
    /**
     * HTML data to be used in the response
     *
     * @access private
     * @var string
     */
    private $_HTML;
    /**
     * An array of JSON key-value pairs
     * to be sent back for ajax requests
     *
     * @access private
     * @var array
     */
    private $_JSON;
    /**
     * PhpMyAdmin\Footer instance
     *
     * @access private
     * @var Footer
     */
    private $_footer;
    /**
     * Whether we are servicing an ajax request.
     *
     * @access private
     * @var bool
     */
    private $_isAjax;
    /**
     * Whether response object is disabled
     *
     * @access private
     * @var bool
     */
    private $_isDisabled;
    /**
     * Whether there were any errors during the processing of the request
     * Only used for ajax responses
     *
     * @access private
     * @var bool
     */
    private $_isSuccess;
    /**
     * Workaround for PHP bug
     *
     * @access private
     * @var string|bool
     */
    private $_CWD;

    /**
     * Creates a new class instance
     */
    private function __construct()
    {
        if (! defined('TESTSUITE')) {
            $buffer = OutputBuffering::getInstance();
            $buffer->start();
            register_shutdown_function(array($this, 'response'));
        }
        $this->_header = new Header();
        $this->_HTML   = '';
        $this->_JSON   = array();
        $this->_footer = new Footer();

        $this->_isSuccess  = true;
        $this->_isDisabled = false;
        $this->setAjax(! empty($_REQUEST['ajax_request']));
        $this->_CWD = getcwd();
    }

    /**
     * Set the ajax flag to indicate whether
     * we are servicing an ajax request
     *
     * @param bool $isAjax Whether we are servicing an ajax request
     *
     * @return void
     */
    public function setAjax($isAjax)
    {
        $this->_isAjax = (boolean) $isAjax;
        $this->_header->setAjax($this->_isAjax);
        $this->_footer->setAjax($this->_isAjax);
    }

    /**
     * Returns the singleton Response object
     *
     * @return Response object
     */
    public static function getInstance()
    {
        if (empty(self::$_instance)) {
            self::$_instance = new Response();
        }
        return self::$_instance;
    }

    /**
     * Set the status of an ajax response,
     * whether it is a success or an error
     *
     * @param bool $state Whether the request was successfully processed
     *
     * @return void
     */
    public function setRequestStatus($state)
    {
        $this->_isSuccess = ($state == true);
    }

    /**
     * Returns true or false depending on whether
     * we are servicing an ajax request
     *
     * @return bool
     */
    public function isAjax()
    {
        return $this->_isAjax;
    }

    /**
     * Returns the path to the current working directory
     * Necessary to work around a PHP bug where the CWD is
     * reset after the initial script exits
     *
     * @return string
     */
    public function getCWD()
    {
        return $this->_CWD;
    }

    /**
     * Disables the rendering of the header
     * and the footer in responses
     *
     * @return void
     */
    public function disable()
    {
        $this->_header->disable();
        $this->_footer->disable();
        $this->_isDisabled = true;
    }

    /**
     * Returns a PhpMyAdmin\Header object
     *
     * @return Header
     */
    public function getHeader()
    {
        return $this->_header;
    }

    /**
     * Returns a PhpMyAdmin\Footer object
     *
     * @return Footer
     */
    public function getFooter()
    {
        return $this->_footer;
    }

    /**
     * Add HTML code to the response
     *
     * @param string $content A string to be appended to
     *                        the current output buffer
     *
     * @return void
     */
    public function addHTML($content)
    {
        if (is_array($content)) {
            foreach ($content as $msg) {
                $this->addHTML($msg);
            }
        } elseif ($content instanceof Message) {
            $this->_HTML .= $content->getDisplay();
        } else {
            $this->_HTML .= $content;
        }
    }

    /**
     * Add JSON code to the response
     *
     * @param mixed $json  Either a key (string) or an
     *                     array or key-value pairs
     * @param mixed $value Null, if passing an array in $json otherwise
     *                     it's a string value to the key
     *
     * @return void
     */
    public function addJSON($json, $value = null)
    {
        if (is_array($json)) {
            foreach ($json as $key => $value) {
                $this->addJSON($key, $value);
            }
        } else {
            if ($value instanceof Message) {
                $this->_JSON[$json] = $value->getDisplay();
            } else {
                $this->_JSON[$json] = $value;
            }
        }

    }

    /**
     * Renders the HTML response text
     *
     * @return string
     */
    private function _getDisplay()
    {
        // The header may contain nothing at all,
        // if its content was already rendered
        // and, in this case, the header will be
        // in the content part of the request
        $retval  = $this->_header->getDisplay();
        $retval .= $this->_HTML;
        $retval .= $this->_footer->getDisplay();
        return $retval;
    }

    /**
     * Sends an HTML response to the browser
     *
     * @return void
     */
    private function _htmlResponse()
    {
        echo $this->_getDisplay();
    }

    /**
     * Sends a JSON response to the browser
     *
     * @return void
     */
    private function _ajaxResponse()
    {
        /* Avoid wrapping in case we're disabled */
        if ($this->_isDisabled) {
            echo $this->_getDisplay();
            return;
        }

        if (! isset($this->_JSON['message'])) {
            $this->_JSON['message'] = $this->_getDisplay();
        } elseif ($this->_JSON['message'] instanceof Message) {
            $this->_JSON['message'] = $this->_JSON['message']->getDisplay();
        }

        if ($this->_isSuccess) {
            $this->_JSON['success'] = true;
        } else {
            $this->_JSON['success'] = false;
            $this->_JSON['error']   = $this->_JSON['message'];
            unset($this->_JSON['message']);
        }

        if ($this->_isSuccess) {
            $this->addJSON('_title', $this->getHeader()->getTitleTag());

            if (isset($GLOBALS['dbi'])) {
                $menuHash = $this->getHeader()->getMenu()->getHash();
                $this->addJSON('_menuHash', $menuHash);
                $hashes = array();
                if (isset($_REQUEST['menuHashes'])) {
                    $hashes = explode('-', $_REQUEST['menuHashes']);
                }
                if (! in_array($menuHash, $hashes)) {
                    $this->addJSON(
                        '_menu',
                        $this->getHeader()
                            ->getMenu()
                            ->getDisplay()
                    );
                }
            }

            $this->addJSON('_scripts', $this->getHeader()->getScripts()->getFiles());
            $this->addJSON('_selflink', $this->getFooter()->getSelfUrl());
            $this->addJSON('_displayMessage', $this->getHeader()->getMessage());

            $debug = $this->_footer->getDebugMessage();
            if (empty($_REQUEST['no_debug'])
                && strlen($debug) > 0
            ) {
                $this->addJSON('_debug', $debug);
            }

            $errors = $this->_footer->getErrorMessages();
            if (strlen($errors) > 0) {
                $this->addJSON('_errors', $errors);
            }
            $promptPhpErrors = $GLOBALS['error_handler']->hasErrorsForPrompt();
            $this->addJSON('_promptPhpErrors', $promptPhpErrors);

            if (empty($GLOBALS['error_message'])) {
                // set current db, table and sql query in the querywindow
                // (this is for the bottom console)
                $query = '';
                $maxChars = $GLOBALS['cfg']['MaxCharactersInDisplayedSQL'];
                if (isset($GLOBALS['sql_query'])
                    && mb_strlen($GLOBALS['sql_query']) < $maxChars
                ) {
                    $query = $GLOBALS['sql_query'];
                }
                $this->addJSON(
                    '_reloadQuerywindow',
                    array(
                        'db' => Core::ifSetOr($GLOBALS['db'], ''),
                        'table' => Core::ifSetOr($GLOBALS['table'], ''),
                        'sql_query' => $query
                    )
                );
                if (! empty($GLOBALS['focus_querywindow'])) {
                    $this->addJSON('_focusQuerywindow', $query);
                }
                if (! empty($GLOBALS['reload'])) {
                    $this->addJSON('_reloadNavigation', 1);
                }
                $this->addJSON('_params', $this->getHeader()->getJsParams());
            }
        }

        // Set the Content-Type header to JSON so that jQuery parses the
        // response correctly.
        Core::headerJSON();

        $result = json_encode($this->_JSON);
        if ($result === false) {
            switch (json_last_error()) {
                case JSON_ERROR_NONE:
                    $error = 'No errors';
                break;
                case JSON_ERROR_DEPTH:
                    $error = 'Maximum stack depth exceeded';
                break;
                case JSON_ERROR_STATE_MISMATCH:
                    $error = 'Underflow or the modes mismatch';
                break;
                case JSON_ERROR_CTRL_CHAR:
                    $error = 'Unexpected control character found';
                break;
                case JSON_ERROR_SYNTAX:
                    $error = 'Syntax error, malformed JSON';
                break;
                case JSON_ERROR_UTF8:
                    $error = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
                case JSON_ERROR_RECURSION:
                    $error = 'One or more recursive references in the value to be encoded';
                break;
                case JSON_ERROR_INF_OR_NAN:
                    $error = 'One or more NAN or INF values in the value to be encoded';
                break;
                case JSON_ERROR_UNSUPPORTED_TYPE:
                    $error = 'A value of a type that cannot be encoded was given';
                default:
                    $error = 'Unknown error';
                break;
            }
            echo json_encode(
                array(
                    'success' => false,
                    'error' => 'JSON encoding failed: ' . $error,
                )
            );
        } else {
            echo $result;
        }
    }

    /**
     * Sends an HTML response to the browser
     *
     * @return void
     */
    public function response()
    {
        chdir($this->getCWD());
        $buffer = OutputBuffering::getInstance();
        if (empty($this->_HTML)) {
            $this->_HTML = $buffer->getContents();
        }
        if ($this->isAjax()) {
            $this->_ajaxResponse();
        } else {
            $this->_htmlResponse();
        }
        $buffer->flush();
        exit;
    }

    /**
     * Wrapper around PHP's header() function.
     *
     * @param string $text header string
     *
     * @return void
     */
    public function header($text)
    {
        header($text);
    }

    /**
     * Wrapper around PHP's headers_sent() function.
     *
     * @return bool
     */
    public function headersSent()
    {
        return headers_sent();
    }

    /**
     * Wrapper around PHP's http_response_code() function.
     *
     * @param int $response_code will set the response code.
     *
     * @return void
     */
    public function httpResponseCode($response_code)
    {
        http_response_code($response_code);
    }

    /**
     * Sets http response code.
     *
     * @param int $response_code will set the response code.
     *
     * @return void
     */
    public function setHttpResponseCode($response_code)
    {
        $this->httpResponseCode($response_code);
        switch ($response_code) {
            case 100: $httpStatusMsg = ' Continue'; break;
            case 101: $httpStatusMsg = ' Switching Protocols'; break;
            case 200: $httpStatusMsg = ' OK'; break;
            case 201: $httpStatusMsg = ' Created'; break;
            case 202: $httpStatusMsg = ' Accepted'; break;
            case 203: $httpStatusMsg = ' Non-Authoritative Information'; break;
            case 204: $httpStatusMsg = ' No Content'; break;
            case 205: $httpStatusMsg = ' Reset Content'; break;
            case 206: $httpStatusMsg = ' Partial Content'; break;
            case 300: $httpStatusMsg = ' Multiple Choices'; break;
            case 301: $httpStatusMsg = ' Moved Permanently'; break;
            case 302: $httpStatusMsg = ' Moved Temporarily'; break;
            case 303: $httpStatusMsg = ' See Other'; break;
            case 304: $httpStatusMsg = ' Not Modified'; break;
            case 305: $httpStatusMsg = ' Use Proxy'; break;
            case 400: $httpStatusMsg = ' Bad Request'; break;
            case 401: $httpStatusMsg = ' Unauthorized'; break;
            case 402: $httpStatusMsg = ' Payment Required'; break;
            case 403: $httpStatusMsg = ' Forbidden'; break;
            case 404: $httpStatusMsg = ' Not Found'; break;
            case 405: $httpStatusMsg = ' Method Not Allowed'; break;
            case 406: $httpStatusMsg = ' Not Acceptable'; break;
            case 407: $httpStatusMsg = ' Proxy Authentication Required'; break;
            case 408: $httpStatusMsg = ' Request Time-out'; break;
            case 409: $httpStatusMsg = ' Conflict'; break;
            case 410: $httpStatusMsg = ' Gone'; break;
            case 411: $httpStatusMsg = ' Length Required'; break;
            case 412: $httpStatusMsg = ' Precondition Failed'; break;
            case 413: $httpStatusMsg = ' Request Entity Too Large'; break;
            case 414: $httpStatusMsg = ' Request-URI Too Large'; break;
            case 415: $httpStatusMsg = ' Unsupported Media Type'; break;
            case 500: $httpStatusMsg = ' Internal Server Error'; break;
            case 501: $httpStatusMsg = ' Not Implemented'; break;
            case 502: $httpStatusMsg = ' Bad Gateway'; break;
            case 503: $httpStatusMsg = ' Service Unavailable'; break;
            case 504: $httpStatusMsg = ' Gateway Time-out'; break;
            case 505: $httpStatusMsg = ' HTTP Version not supported'; break;
            default: $httpStatusMsg  = ' Web server is down'; break;
        }
        if (php_sapi_name() !== 'cgi-fcgi') {
            $this->header('status: ' . $response_code . $httpStatusMsg);
        }
    }

   /**
     * Generate header for 303
     *
     * @param string $location will set location to redirect.
     *
     * @return void
     */
    public function generateHeader303($location)
    {
        $this->setHttpResponseCode(303);
        $this->header('Location: '.$location);
        if (!defined('TESTSUITE')) {
            exit;
        }
    }

    /**
     * Configures response for the login page
     *
     * @return bool Whether caller should exit
     */
    public function loginPage()
    {
        /* Handle AJAX redirection */
        if ($this->isAjax()) {
            $this->setRequestStatus(false);
            // redirect_flag redirects to the login page
            $this->addJSON('redirect_flag', '1');
            return true;
        }

        $this->getFooter()->setMinimal();
        $header = $this->getHeader();
        $header->setBodyId('loginform');
        $header->setTitle('phpMyAdmin');
        $header->disableMenuAndConsole();
        $header->disableWarnings();
        return false;
    }
}
