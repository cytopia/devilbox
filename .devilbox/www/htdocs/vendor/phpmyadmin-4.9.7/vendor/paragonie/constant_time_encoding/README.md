# Constant-Time Encoding

[![Build Status](https://travis-ci.org/paragonie/constant_time_encoding.svg?branch=v1.x)](https://travis-ci.org/paragonie/constant_time_encoding)

Based on the [constant-time base64 implementation made by Steve "Sc00bz" Thomas](https://github.com/Sc00bz/ConstTimeEncoding),
this library aims to offer character encoding functions that do not leak
information about what you are encoding/decoding via processor cache 
misses. Further reading on [cache-timing attacks](http://blog.ircmaxell.com/2014/11/its-all-about-time.html).

Our fork offers the following enchancements:

* `mbstring.func_overload` resistance
* Unit tests
* Composer- and Packagist-ready
* Base16 encoding
* Base32 encoding
* Uses `pack()` and `unpack()` instead of `chr()` and `ord()`

## PHP Version Requirements

This library should work on any [supported version of PHP](https://secure.php.net/supported-versions.php).
It *may* work on earlier versions, but we **do not** guarantee it. If it
doesn't, we **will not** fix it to work on earlier versions of PHP.

## How to Install

```sh
composer require paragonie/constant_time_encoding
```

## How to Use

```php
use \ParagonIE\ConstantTime\Encoding;

// possibly (if applicable): 
// require 'vendor/autoload.php';

$data = random_bytes(32);
echo Encoding::base64Encode($data), "\n";
echo Encoding::base32EncodeUpper($data), "\n";
echo Encoding::base32Encode($data), "\n";
echo Encoding::hexEncode($data), "\n";
echo Encoding::hexEncodeUpper($data), "\n";
```

Example output:
 
```
1VilPkeVqirlPifk5scbzcTTbMT2clp+Zkyv9VFFasE=
2VMKKPSHSWVCVZJ6E7SONRY3ZXCNG3GE6ZZFU7TGJSX7KUKFNLAQ====
2vmkkpshswvcvzj6e7sonry3zxcng3ge6zzfu7tgjsx7kukfnlaq====
d558a53e4795aa2ae53e27e4e6c71bcdc4d36cc4f6725a7e664caff551456ac1
D558A53E4795AA2AE53E27E4E6C71BDCC4D36CC4F6725A7E664CAFF551456AC1
```

If you only need a particular variant, you can just reference the 
required class like so:

```php
use \ParagonIE\ConstantTime\Base64;
use \ParagonIE\ConstantTime\Base32;

$data = random_bytes(32);
echo Base64::encode($data), "\n";
echo Base32::encode($data), "\n";
```

Example output:

```
1VilPkeVqirlPifk5scbzcTTbMT2clp+Zkyv9VFFasE=
2vmkkpshswvcvzj6e7sonry3zxcng3ge6zzfu7tgjsx7kukfnlaq====
```