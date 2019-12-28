<?php

namespace PragmaRX\Google2FAQRCode;

use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Renderer\Image\RendererInterface;
use BaconQrCode\Writer as BaconQrCodeWriter;
use PragmaRX\Google2FA\Google2FA as Google2FAPackage;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImageBackEndInterface;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class Google2FA extends Google2FAPackage
{
    /**
     * @var ImageBackEndInterface|RendererInterface|null $imageBackEnd
     */
    protected $imageBackEnd;

    /**
     * Google2FA constructor.
     *
     * @param ImageBackEndInterface|RendererInterface|null $imageBackEnd
     */
    public function __construct($imageBackEnd = null)
    {
        if ($this->getBaconQRCodeVersion() === 1) {
            if ($imageBackEnd instanceof RendererInterface) {
                $this->imageBackEnd = $imageBackEnd;
            } else {
                $this->imageBackEnd = new Png();
            }
        } else {
            if ($imageBackEnd instanceof ImageBackEndInterface) {
                $this->imageBackEnd = $imageBackEnd;
            } else {
                $this->imageBackEnd = new ImagickImageBackEnd();
            }
        }
    }

    /**
     * Generates a QR code data url to display inline.
     *
     * @param string $company
     * @param string $holder
     * @param string $secret
     * @param int    $size
     * @param string $encoding Default to UTF-8
     *
     * @return string
     */
    public function getQRCodeInline($company, $holder, $secret, $size = 200, $encoding = 'utf-8')
    {
        return $this->getBaconQRCodeVersion() === 1
            ? $this->getQRCodeInlineV1($company, $holder, $secret, $size, $encoding)
            : $this->getQRCodeInlineV2($company, $holder, $secret, $size, $encoding);
    }

    /**
     * Generates a QR code data url to display inline for Bacon QRCode v1
     *
     * @param string $company
     * @param string $holder
     * @param string $secret
     * @param int    $size
     * @param string $encoding Default to UTF-8
     *
     * @return string
     */
    public function getQRCodeInlineV1($company, $holder, $secret, $size = 200, $encoding = 'utf-8')
    {
        $url = $this->getQRCodeUrl($company, $holder, $secret);

        $renderer = $this->imageBackEnd;
        $renderer->setWidth($size);
        $renderer->setHeight($size);

        $bacon = new BaconQrCodeWriter($renderer);
        $data = $bacon->writeString($url, $encoding);

        if ($this->imageBackEnd instanceof Png) {
            return 'data:image/png;base64,'.base64_encode($data);
        }
        return $data;
    }

    /**
     * Generates a QR code data url to display inline for Bacon QRCode v2
     *
     * @param string $company
     * @param string $holder
     * @param string $secret
     * @param int    $size
     * @param string $encoding Default to UTF-8
     *
     * @return string
     */
    public function getQRCodeInlineV2($company, $holder, $secret, $size = 200, $encoding = 'utf-8')
    {
        $renderer = new ImageRenderer(
            (new RendererStyle($size))->withSize($size),
            $this->imageBackEnd
        );

        $bacon = new Writer($renderer);

        $data = $bacon->writeString(
            $this->getQRCodeUrl($company, $holder, $secret),
            $encoding
        );

        if ($this->imageBackEnd instanceof ImagickImageBackEnd) {
            return 'data:image/png;base64,'.base64_encode($data);
        }

        return $data;
    }

    /**
     * Get Bacon QRCode current version
     *
     * @return int
     */
    public function getBaconQRCodeVersion()
    {
        return class_exists('BaconQrCode\Renderer\Image\Png') && class_exists('BaconQrCode\Writer')
            ? 1
            : 2;
    }
}
