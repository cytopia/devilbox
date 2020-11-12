<?php

namespace PragmaRX\Google2FAQRCode\Tests;

use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\Image\Png;
use PHPUnit\Framework\TestCase;
use PragmaRX\Google2FAQRCode\Google2FA;
use Zxing\QrReader;

class Google2FATest extends TestCase
{
    const EMAIL = 'acr+pragmarx@antoniocarlosribeiro.com';

    const OTP_URL = 'otpauth://totp/PragmaRX:acr+pragmarx@antoniocarlosribeiro.com?secret=ADUMJO5634NPDEKW&issuer=PragmaRX';

    public function setUp()
    {
        $this->google2fa = new Google2FA();
    }

    public function readQRCode($data)
    {
        list(, $data) = explode(';', $data);

        list(, $data) = explode(',', $data);

        return rawurldecode((new QrReader(base64_decode($data), QrReader::SOURCE_TYPE_BLOB))->text());
    }

    public function testQrcodeInline()
    {
        $this->assertEquals(
            static::OTP_URL,
            $this->readQRCode($this->google2fa->getQRCodeInline('PragmaRX', static::EMAIL, Constants::SECRET))
        );

        if ($this->google2fa->getBaconQRCodeVersion() === 1) {
            $google2fa = new Google2FA(new Png());
            $this->assertEquals(
                static::OTP_URL,
                $this->readQRCode($google2fa->getQRCodeInline('PragmaRX', static::EMAIL, Constants::SECRET))
            );
        } else {
            $google2fa = new Google2FA(new ImagickImageBackEnd());
            $this->assertEquals(
                static::OTP_URL,
                $this->readQRCode($google2fa->getQRCodeInline('PragmaRX', static::EMAIL, Constants::SECRET))
            );
        }
    }
}
