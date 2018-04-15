<?php
/**
 * phpMyAdmin ShapeFile library
 * <https://github.com/phpmyadmin/shapefile/>.
 *
 * Copyright 2006-2007 Ovidio <ovidio AT users.sourceforge.net>
 * Copyright 2016 - 2017 Michal Čihař <michal@cihar.com>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, you can download one from
 * https://www.gnu.org/copyleft/gpl.html.
 */

namespace PhpMyAdmin\ShapeFile;

class Util
{
    private static $little_endian = null;

    private static $shape_names = array(
        0 => 'Null Shape',
        1 => 'Point',
        3 => 'PolyLine',
        5 => 'Polygon',
        8 => 'MultiPoint',
        11 => 'PointZ',
        13 => 'PolyLineZ',
        15 => 'PolygonZ',
        18 => 'MultiPointZ',
        21 => 'PointM',
        23 => 'PolyLineM',
        25 => 'PolygonM',
        28 => 'MultiPointM',
        31 => 'MultiPatch',
    );

    /**
     * Reads data.
     *
     * @param string $type type for unpack()
     * @param string $data Data to process
     *
     * @return mixed
     */
    public static function loadData($type, $data)
    {
        if ($data === false || strlen($data) == 0) {
            return false;
        }
        $tmp = unpack($type, $data);

        return current($tmp);
    }

    /**
     * Changes endianity.
     *
     * @param string $binValue Binary value
     *
     * @return string
     */
    public static function swap($binValue)
    {
        $result = $binValue[strlen($binValue) - 1];
        for ($i = strlen($binValue) - 2; $i >= 0; --$i) {
            $result .= $binValue[$i];
        }

        return $result;
    }

    /**
     * Encodes double value to correct endianity.
     *
     * @param float $value Value to pack
     *
     * @return string
     */
    public static function packDouble($value)
    {
        $bin = pack('d', (float) $value);

        if (is_null(self::$little_endian)) {
            self::$little_endian = (pack('L', 1) == pack('V', 1));
        }

        if (self::$little_endian) {
            return $bin;
        }

        return self::swap($bin);
    }

    /**
     * Returns shape name.
     *
     * @param int $type
     *
     * @return string
     */
    public static function nameShape($type)
    {
        if (isset(self::$shape_names[$type])) {
            return self::$shape_names[$type];
        }

        return sprintf('Shape %d', $type);
    }
}
