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
 * Class RFC4648
 *
 * This class conforms strictly to the RFC
 *
 * @package ParagonIE\ConstantTime
 */
abstract class RFC4648
{
    /**
     * RFC 4648 Base64 encoding
     *
     * "foo" -> "Zm9v"
     *
     * @param string $str
     * @return string
     * @throws \TypeError
     */
    public function base64Encode($str)
    {
        return Base64::encode($str);
    }

    /**
     * RFC 4648 Base64 decoding
     *
     * "Zm9v" -> "foo"
     *
     * @param string $str
     * @return string
     * @throws \TypeError
     */
    public function base64Decode($str)
    {
        return Base64::decode($str);
    }

    /**
     * RFC 4648 Base64 (URL Safe) encoding
     *
     * "foo" -> "Zm9v"
     *
     * @param string $str
     * @return string
     * @throws \TypeError
     */
    public function base64UrlSafeEncode($str)
    {
        return Base64UrlSafe::encode($str);
    }

    /**
     * RFC 4648 Base64 (URL Safe) decoding
     *
     * "Zm9v" -> "foo"
     *
     * @param string $str
     * @return string
     * @throws \TypeError
     */
    public function base64UrlSafeDecode($str)
    {
        return Base64UrlSafe::decode($str);
    }

    /**
     * RFC 4648 Base32 encoding
     *
     * "foo" -> "MZXW6==="
     *
     * @param string $str
     * @return string
     * @throws \TypeError
     */
    public function base32Encode($str)
    {
        return Base32::encodeUpper($str);
    }

    /**
     * RFC 4648 Base32 encoding
     *
     * "MZXW6===" -> "foo"
     *
     * @param string $str
     * @return string
     * @throws \TypeError
     */
    public function base32Decode($str)
    {
        return Base32::decodeUpper($str);
    }

    /**
     * RFC 4648 Base32-Hex encoding
     *
     * "foo" -> "CPNMU==="
     *
     * @param string $str
     * @return string
     * @throws \TypeError
     */
    public function base32HexEncode($str)
    {
        return Base32::encodeUpper($str);
    }

    /**
     * RFC 4648 Base32-Hex decoding
     *
     * "CPNMU===" -> "foo"
     *
     * @param string $str
     * @return string
     * @throws \TypeError
     */
    public function base32HexDecode($str)
    {
        return Base32::decodeUpper($str);
    }

    /**
     * RFC 4648 Base16 decoding
     *
     * "foo" -> "666F6F"
     *
     * @param string $str
     * @return string
     * @throws \TypeError
     */
    public function base16Encode($str)
    {
        return Hex::encodeUpper($str);
    }

    /**
     * RFC 4648 Base16 decoding
     *
     * "666F6F" -> "foo"
     *
     * @param string $str
     * @return string
     */
    public function base16Decode($str)
    {
        return Hex::decode($str);
    }
}