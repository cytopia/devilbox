<?php

namespace PragmaRX\Google2FA\Support;

use PragmaRX\Google2FA\Exceptions\InsecureCallException;

trait QRCode
{
    /**
     * Sending your secret key to Google API is a security issue. Developer must explicitly allow it.
     */
    protected $allowInsecureCallToGoogleApis = false;

    /**
     * Creates a Google QR code url.
     *
     * @param string $company
     * @param string $holder
     * @param string $secret
     * @param int $size
     *
     * @throws \PragmaRX\Google2FA\Exceptions\InsecureCallException
     *
     * @return string
     */
    public function getQRCodeGoogleUrl($company, $holder, $secret, $size = 200)
    {
        if (!$this->allowInsecureCallToGoogleApis) {
            throw new InsecureCallException("It's not secure to send secret keys to Google Apis, you have to explicitly allow it by calling \$google2fa->setAllowInsecureCallToGoogleApis(true).");
        }

        return Url::generateGoogleQRCodeUrl(
            'https://chart.googleapis.com/',
            'chart',
            'chs='.$size.'x'.$size.'&chld=M|0&cht=qr&chl=',
            $this->getQRCodeUrl($company, $holder, $secret)
        );
    }

    /**
     * Creates a QR code url.
     *
     * @param $company
     * @param $holder
     * @param $secret
     *
     * @return string
     */
    public function getQRCodeUrl($company, $holder, $secret)
    {
        return 'otpauth://totp/'.rawurlencode($company).':'.rawurlencode($holder).'?secret='.$secret.'&issuer='.rawurlencode($company).'';
    }

    /**
     * AllowInsecureCallToGoogleApis setter.
     *
     * @param mixed $allowInsecureCallToGoogleApis
     *
     * @return QRCode
     */
    public function setAllowInsecureCallToGoogleApis($allowInsecureCallToGoogleApis)
    {
        $this->allowInsecureCallToGoogleApis = $allowInsecureCallToGoogleApis;

        return $this;
    }
}
