<?php
/**
 * This is a PHP library that handles calling reCAPTCHA.
 *
 * @copyright Copyright (c) 2015, Google Inc.
 * @link      https://www.google.com/recaptcha
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace ReCaptcha\RequestMethod;

use ReCaptcha\ReCaptcha;
use ReCaptcha\RequestMethod;
use ReCaptcha\RequestParameters;

/**
 * Sends a POST request to the reCAPTCHA service, but makes use of fsockopen()
 * instead of get_file_contents(). This is to account for people who may be on
 * servers where allow_url_open is disabled.
 */
class SocketPost implements RequestMethod
{
    /**
     * Socket to the reCAPTCHA service
     * @var Socket
     */
    private $socket;

    /**
     * Only needed if you want to override the defaults
     *
     * @param \ReCaptcha\RequestMethod\Socket $socket optional socket, injectable for testing
     * @param string $siteVerifyUrl URL for reCAPTCHA sitevrerify API
     */
    public function __construct(Socket $socket = null, $siteVerifyUrl = null)
    {
        $this->socket = (is_null($socket)) ? new Socket() : $socket;
        $this->siteVerifyUrl = (is_null($siteVerifyUrl)) ? ReCaptcha::SITE_VERIFY_URL : $siteVerifyUrl;
    }

    /**
     * Submit the POST request with the specified parameters.
     *
     * @param RequestParameters $params Request parameters
     * @return string Body of the reCAPTCHA response
     */
    public function submit(RequestParameters $params)
    {
        $errno = 0;
        $errstr = '';
        $urlParsed = parse_url($this->siteVerifyUrl);

        if (false === $this->socket->fsockopen('ssl://' . $urlParsed['host'], 443, $errno, $errstr, 30)) {
            return '{"success": false, "error-codes": ["'.ReCaptcha::E_CONNECTION_FAILED.'"]}';
        }

        $content = $params->toQueryString();

        $request = "POST " . $urlParsed['path'] . " HTTP/1.1\r\n";
        $request .= "Host: " . $urlParsed['host'] . "\r\n";
        $request .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $request .= "Content-length: " . strlen($content) . "\r\n";
        $request .= "Connection: close\r\n\r\n";
        $request .= $content . "\r\n\r\n";

        $this->socket->fwrite($request);
        $response = '';

        while (!$this->socket->feof()) {
            $response .= $this->socket->fgets(4096);
        }

        $this->socket->fclose();

        if (0 !== strpos($response, 'HTTP/1.1 200 OK')) {
            return '{"success": false, "error-codes": ["'.ReCaptcha::E_BAD_RESPONSE.'"]}';
        }

        $parts = preg_split("#\n\s*\n#Uis", $response);

        return $parts[1];
    }
}
