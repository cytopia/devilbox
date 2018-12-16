<?php
namespace ParagonIE\ConstantTime;

/**
 *  Copyright (c) 2016 - 2017 Paragon Initiative Enterprises.
 *  Copyright (c) 2014 Steve "Sc00bz" Thomas (steve at tobtu dot com)
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is
 *  furnished to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 *  SOFTWARE.
 */

/**
 * Class Encoding
 * @package ParagonIE\ConstantTime
 */
abstract class Encoding
{
    /**
     * RFC 4648 Base32 encoding
     *
     * @param string $str
     * @return string
     */
    public static function base32Encode($str)
    {
        return Base32::encode($str);
    }

    /**
     * RFC 4648 Base32 encoding
     *
     * @param string $str
     * @return string
     */
    public static function base32EncodeUpper($str)
    {
        return Base32::encodeUpper($str);
    }

    /**
     * RFC 4648 Base32 decoding
     *
     * @param string $str
     * @return string
     */
    public static function base32Decode($str)
    {
        return Base32::decode($str);
    }

    /**
     * RFC 4648 Base32 decoding
     *
     * @param string $str
     * @return string
     */
    public static function base32DecodeUpper($str)
    {
        return Base32::decodeUpper($str);
    }

    /**
     * RFC 4648 Base32 encoding
     *
     * @param string $str
     * @return string
     */
    public static function base32HexEncode($str)
    {
        return Base32Hex::encode($str);
    }


    /**
     * RFC 4648 Base32 encoding
     *
     * @param string $str
     * @return string
     */
    public static function base32HexEncodeUpper($str)
    {
        return Base32Hex::encodeUpper($str);
    }

    /**
     * RFC 4648 Base32 decoding
     *
     * @param string $str
     * @return string
     */
    public static function base32HexDecode($str)
    {
        return Base32Hex::decode($str);
    }

    /**
     * RFC 4648 Base32 decoding
     *
     * @param string $str
     * @return string
     */
    public static function base32HexDecodeUpper($str)
    {
        return Base32Hex::decodeUpper($str);
    }

    /**
     * RFC 4648 Base64 encoding
     *
     * @param string $str
     * @return string
     */
    public static function base64Encode($str)
    {
        return Base64::encode($str);
    }

    /**
     * RFC 4648 Base32 decoding
     *
     * @param string $str
     * @return string
     */
    public static function base64Decode($str)
    {
        return Base64::decode($str);
    }

    /**
     * Encode into Base64
     *
     * Base64 character set "./[A-Z][a-z][0-9]"
     * @param string $src
     * @return string
     */
    public static function base64EncodeDotSlash($src)
    {
        return Base64DotSlash::encode($src);
    }

    /**
     * Decode from base64 to raw binary
     *
     * Base64 character set "./[A-Z][a-z][0-9]"
     *
     * @param string $src
     * @return string
     * @throws \RangeException
     */
    public static function base64DecodeDotSlash($src)
    {
        return Base64DotSlash::decode($src);
    }

    /**
     * Encode into Base64
     *
     * Base64 character set "[.-9][A-Z][a-z]" or "./[0-9][A-Z][a-z]"
     * @param string $src
     * @return string
     */
    public static function base64EncodeDotSlashOrdered($src)
    {
        return Base64DotSlashOrdered::encode($src);
    }

    /**
     * Decode from base64 to raw binary
     *
     * Base64 character set "[.-9][A-Z][a-z]" or "./[0-9][A-Z][a-z]"
     *
     * @param string $src
     * @return string
     * @throws \RangeException
     */
    public static function base64DecodeDotSlashOrdered($src)
    {
        return Base64DotSlashOrdered::decode($src);
    }

    /**
     * Convert a binary string into a hexadecimal string without cache-timing
     * leaks
     *
     * @param string $bin_string (raw binary)
     * @return string
     */
    public static function hexEncode($bin_string)
    {
        return Hex::encode($bin_string);
    }

    /**
     * Convert a hexadecimal string into a binary string without cache-timing
     * leaks
     *
     * @param string $hex_string
     * @return string (raw binary)
     * @throws \RangeException
     */
    public static function hexDecode($hex_string)
    {
        return Hex::decode($hex_string);
    }

    /**
     * Convert a binary string into a hexadecimal string without cache-timing
     * leaks
     *
     * @param string $bin_string (raw binary)
     * @return string
     */
    public static function hexEncodeUpper($bin_string)
    {
        return Hex::encodeUpper($bin_string);
    }

    /**
     * Convert a binary string into a hexadecimal string without cache-timing
     * leaks
     *
     * @param string $bin_string (raw binary)
     * @return string
     */
    public static function hexDecodeUpper($bin_string)
    {
        return Hex::decode($bin_string);
    }
}
