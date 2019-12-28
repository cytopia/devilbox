# Google2FA QRCode

<p align="center">
    <a href="https://packagist.org/packages/pragmarx/google2fa-qrcode"><img alt="Latest Stable Version" src="https://img.shields.io/packagist/v/pragmarx/google2fa-qrcode.svg?style=flat-square"></a>
    <a href="LICENSE.md"><img alt="License" src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square"></a>
    <a href="https://scrutinizer-ci.com/g/antonioribeiro/google2fa/?branch=master"><img alt="Code Quality" src="https://img.shields.io/scrutinizer/g/antonioribeiro/google2fa.svg?style=flat-square"></a>
    <a href="https://travis-ci.org/antonioribeiro/google2fa"><img alt="Build" src="https://img.shields.io/travis/antonioribeiro/google2fa.svg?style=flat-square"></a>
</p>
<p align="center">
    <a href="https://packagist.org/packages/pragmarx/google2fa-qrcode"><img alt="Downloads" src="https://img.shields.io/packagist/dt/pragmarx/google2fa-qrcode.svg?style=flat-square"></a>
    <a href="https://scrutinizer-ci.com/g/antonioribeiro/google2fa/?branch=master"><img alt="Coverage" src="https://img.shields.io/scrutinizer/coverage/g/antonioribeiro/google2fa.svg?style=flat-square"></a>
    <a href="https://styleci.io/repos/24296182"><img alt="StyleCI" src="https://styleci.io/repos/24296182/shield"></a>
    <a href="https://travis-ci.org/antonioribeiro/google2fa"><img alt="PHP" src="https://img.shields.io/badge/PHP-5.4%20--%207.2-brightgreen.svg?style=flat-square"></a>
</p>

### QRCode For Google2FA

This is package is [Goole2FA](https://github.com/antonioribeiro/google2fa) integrated with a QRCode generator, providing an easy way to plot QRCode for your two factor authentication. For documentation related to Google2FA, please check the [documentation of the main package](https://github.com/antonioribeiro/google2fa).  
 
## Requirements

- PHP 5.4+

## Installing

Use Composer to install it:

```
composer require pragmarx/google2fa-qrcode
```

## Using It

### Instantiate it directly

```php
use PragmaRX\Google2FAQRCode\Google2FA;
    
$google2fa = new Google2FA();
    
return $google2fa->generateSecretKey();
```

## Generating QRCodes

The securer way of creating QRCode is to do it yourself or using a library. First you have to install the BaconQrCode package, as stated above, then you just have to generate the inline string using:
 
```php
$inlineUrl = $google2fa->getQRCodeInline(
    $companyName,
    $companyEmail,
    $secretKey
);
```

And use it in your blade template this way:

```html
<img src="{{ $inlineUrl }}">
```

```php
$secretKey = $google2fa->generateSecretKey(16, $userId);
```

## Show the QR Code to your user, via Google Apis

It's insecure to use it via Google Apis, so you have to enable it before using it.

```php
$google2fa->setAllowInsecureCallToGoogleApis(true);

$google2fa_url = $google2fa->getQRCodeGoogleUrl(
    'YourCompany',
    $user->email,
    $user->google2fa_secret
);

/// and in your view:

<img src="{{ $google2fa_url }}" alt="">
```

And they should see and scan the QR code to their applications:

![QRCode](https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=otpauth%3A%2F%2Ftotp%2FPragmaRX%3Aacr%2Bpragmarx%40antoniocarlosribeiro.com%3Fsecret%3DADUMJO5634NPDEKW%26issuer%3DPragmaRX)

And to verify, you just have to:

```php
$secret = $request->input('secret');

$valid = $google2fa->verifyKey($user->google2fa_secret, $secret);
```

## Tests

The package tests were written with [PHPUnit](https://phpunit.de/).

## Authors

- [Antonio Carlos Ribeiro](http://twitter.com/iantonioribeiro)
- [All Contributors](https://github.com/antonioribeiro/google2fa/graphs/contributors)

## License

Google2FAQRCode is licensed under the MIT License - see the [LICENSE](LICENSE.md) file for details.

## Contributing

Pull requests and issues are more than welcome.
