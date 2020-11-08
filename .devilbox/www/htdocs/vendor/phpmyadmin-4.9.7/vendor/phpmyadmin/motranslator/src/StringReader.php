<?php
/*
    Copyright (c) 2003, 2005, 2006, 2009 Danilo Segan <danilo@kvota.net>.
    Copyright (c) 2016 Michal Čihař <michal@cihar.com>

    This file is part of MoTranslator.

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License along
    with this program; if not, write to the Free Software Foundation, Inc.,
    51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

namespace PhpMyAdmin\MoTranslator;

/**
 * Simple wrapper around string buffer for
 * random access and values parsing.
 */
class StringReader
{
    private $_str;
    private $_len;

    /**
     * Constructor.
     *
     * @param string $filename Name of file to load
     */
    public function __construct($filename)
    {
        $this->_str = file_get_contents($filename);
        $this->_len = strlen($this->_str);
    }

    /**
     * Read number of bytes from given offset.
     *
     * @param int $pos   Offset
     * @param int $bytes Number of bytes to read
     *
     * @return string
     */
    public function read($pos, $bytes)
    {
        if ($pos + $bytes > $this->_len) {
            throw new ReaderException('Not enough bytes!');
        }

        return substr($this->_str, $pos, $bytes);
    }

    /**
     * Reads a 32bit integer from the stream.
     *
     * @param string $unpack Unpack string
     * @param int    $pos    Position
     *
     * @return int Ingerer from the stream
     */
    public function readint($unpack, $pos)
    {
        $data = unpack($unpack, $this->read($pos, 4));
        $result = $data[1];

        /* We're reading unsigned int, but PHP will happily
         * give us negative number on 32-bit platforms.
         *
         * See also documentation:
         * https://secure.php.net/manual/en/function.unpack.php#refsect1-function.unpack-notes
         */
        return $result < 0 ? PHP_INT_MAX : $result;
    }

    /**
     * Reads an array of integers from the stream.
     *
     * @param string $unpack Unpack string
     * @param int    $pos    Position
     * @param int    $count  How many elements should be read
     *
     * @return array Array of Integers
     */
    public function readintarray($unpack, $pos, $count)
    {
        return unpack($unpack . $count, $this->read($pos, 4 * $count));
    }
}
