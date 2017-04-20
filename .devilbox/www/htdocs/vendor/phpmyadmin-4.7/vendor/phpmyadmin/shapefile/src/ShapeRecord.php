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

/**
 * ShapeFile record class.
 */
class ShapeRecord
{
    private $SHPFile = null;
    private $DBFFile = null;
    private $ShapeFile = null;

    private $size = 0;
    private $read = 0;

    public $recordNumber = null;
    public $shapeType = null;

    public $lastError = '';

    public $SHPData = array();
    public $DBFData = array();

    /**
     * @param int $shapeType
     */
    public function __construct($shapeType)
    {
        $this->shapeType = $shapeType;
    }

    /**
     * Loads record from files.
     *
     * @param ShapeFile $ShapeFile
     * @param file      &$SHPFile  Opened SHP file
     * @param file      &$DBFFile  Opened DBF file
     */
    public function loadFromFile(&$ShapeFile, &$SHPFile, &$DBFFile)
    {
        $this->ShapeFile = $ShapeFile;
        $this->SHPFile = $SHPFile;
        $this->DBFFile = $DBFFile;
        $this->_loadHeaders();

        /* No header read */
        if ($this->read == 0) {
            return;
        }

        switch ($this->shapeType) {
            case 0:
                $this->_loadNullRecord();
                break;
            case 1:
                $this->_loadPointRecord();
                break;
            case 21:
                $this->_loadPointMRecord();
                break;
            case 11:
                $this->_loadPointZRecord();
                break;
            case 3:
                $this->_loadPolyLineRecord();
                break;
            case 23:
                $this->_loadPolyLineMRecord();
                break;
            case 13:
                $this->_loadPolyLineZRecord();
                break;
            case 5:
                $this->_loadPolygonRecord();
                break;
            case 25:
                $this->_loadPolygonMRecord();
                break;
            case 15:
                $this->_loadPolygonZRecord();
                break;
            case 8:
                $this->_loadMultiPointRecord();
                break;
            case 28:
                $this->_loadMultiPointMRecord();
                break;
            case 18:
                $this->_loadMultiPointZRecord();
                break;
            default:
                $this->setError(sprintf('The Shape Type "%s" is not supported.', $this->shapeType));
                break;
        }

        /* We need to skip rest of the record */
        while ($this->read < $this->size) {
            $this->_loadData('V', 4);
        }

        /* Check if we didn't read too much */
        if ($this->read != $this->size) {
            $this->setError(sprintf('Failed to parse record, read=%d, size=%d', $this->read, $this->size));
        }

        if (ShapeFile::supports_dbase() && isset($this->DBFFile)) {
            $this->_loadDBFData();
        }
    }

    /**
     * Saves record to files.
     *
     * @param file &$SHPFile     Opened SHP file
     * @param file &$DBFFile     Opened DBF file
     * @param int  $recordNumber Record number
     */
    public function saveToFile(&$SHPFile, &$DBFFile, $recordNumber)
    {
        $this->SHPFile = $SHPFile;
        $this->DBFFile = $DBFFile;
        $this->recordNumber = $recordNumber;
        $this->_saveHeaders();

        switch ($this->shapeType) {
            case 0:
                // Nothing to save
                break;
            case 1:
                $this->_savePointRecord();
                break;
            case 21:
                $this->_savePointMRecord();
                break;
            case 11:
                $this->_savePointZRecord();
                break;
            case 3:
                $this->_savePolyLineRecord();
                break;
            case 23:
                $this->_savePolyLineMRecord();
                break;
            case 13:
                $this->_savePolyLineZRecord();
                break;
            case 5:
                $this->_savePolygonRecord();
                break;
            case 25:
                $this->_savePolygonMRecord();
                break;
            case 15:
                $this->_savePolygonZRecord();
                break;
            case 8:
                $this->_saveMultiPointRecord();
                break;
            case 28:
                $this->_saveMultiPointMRecord();
                break;
            case 18:
                $this->_saveMultiPointZRecord();
                break;
            default:
                $this->setError(sprintf('The Shape Type "%s" is not supported.', $this->shapeType));
                break;
        }
        if (ShapeFile::supports_dbase() && !is_null($this->DBFFile)) {
            $this->_saveDBFData();
        }
    }

    /**
     * Updates DBF data to match header.
     *
     * @param array $header DBF structure header
     */
    public function updateDBFInfo($header)
    {
        $tmp = $this->DBFData;
        unset($this->DBFData);
        $this->DBFData = array();
        foreach ($header as $value) {
            $this->DBFData[$value[0]] = (isset($tmp[$value[0]])) ? $tmp[$value[0]] : '';
        }
    }

    /**
     * Reads data.
     *
     * @param string $type  type for unpack()
     * @param int    $count number of bytes
     *
     * @return mixed
     */
    private function _loadData($type, $count)
    {
        $data = $this->ShapeFile->readSHP($count);
        if ($data === false) {
            return false;
        }
        $this->read += strlen($data);

        return Util::loadData($type, $data);
    }

    /**
     * Loads metadata header from a file.
     */
    private function _loadHeaders()
    {
        $this->shapeType = false;
        $this->recordNumber = $this->_loadData('N', 4);
        if ($this->recordNumber === false) {
            return;
        }
        // We read the length of the record
        $this->size = $this->_loadData('N', 4);
        if ($this->size === false) {
            return;
        }
        $this->size = $this->size * 2 + 8;
        $this->shapeType = $this->_loadData('V', 4);
    }

    /**
     * Saves metadata header to a file.
     */
    private function _saveHeaders()
    {
        fwrite($this->SHPFile, pack('N', $this->recordNumber));
        fwrite($this->SHPFile, pack('N', $this->getContentLength()));
        fwrite($this->SHPFile, pack('V', $this->shapeType));
    }

    private function _loadPoint()
    {
        $data = array();

        $data['x'] = $this->_loadData('d', 8);
        $data['y'] = $this->_loadData('d', 8);

        return $data;
    }

    private function _loadPointM()
    {
        $data = $this->_loadPoint();

        $data['m'] = $this->_loadData('d', 8);

        return $data;
    }

    private function _loadPointZ()
    {
        $data = $this->_loadPoint();

        $data['z'] = $this->_loadData('d', 8);
        $data['m'] = $this->_loadData('d', 8);

        return $data;
    }

    private function _savePoint($data)
    {
        fwrite($this->SHPFile, Util::packDouble($data['x']));
        fwrite($this->SHPFile, Util::packDouble($data['y']));
    }

    private function _savePointM($data)
    {
        fwrite($this->SHPFile, Util::packDouble($data['x']));
        fwrite($this->SHPFile, Util::packDouble($data['y']));
        fwrite($this->SHPFile, Util::packDouble($data['m']));
    }

    private function _savePointZ($data)
    {
        fwrite($this->SHPFile, Util::packDouble($data['x']));
        fwrite($this->SHPFile, Util::packDouble($data['y']));
        fwrite($this->SHPFile, Util::packDouble($data['z']));
        fwrite($this->SHPFile, Util::packDouble($data['m']));
    }

    private function _loadNullRecord()
    {
        $this->SHPData = array();
    }

    private function _loadPointRecord()
    {
        $this->SHPData = $this->_loadPoint();
    }

    private function _loadPointMRecord()
    {
        $this->SHPData = $this->_loadPointM();
    }

    private function _loadPointZRecord()
    {
        $this->SHPData = $this->_loadPointZ();
    }

    private function _savePointRecord()
    {
        $this->_savePoint($this->SHPData);
    }

    private function _savePointMRecord()
    {
        $this->_savePointM($this->SHPData);
    }

    private function _savePointZRecord()
    {
        $this->_savePointZ($this->SHPData);
    }

    private function _loadBBox()
    {
        $this->SHPData['xmin'] = $this->_loadData('d', 8);
        $this->SHPData['ymin'] = $this->_loadData('d', 8);
        $this->SHPData['xmax'] = $this->_loadData('d', 8);
        $this->SHPData['ymax'] = $this->_loadData('d', 8);
    }

    private function _loadMultiPointRecord()
    {
        $this->SHPData = array();
        $this->_loadBBox();

        $this->SHPData['numpoints'] = $this->_loadData('V', 4);

        for ($i = 0; $i < $this->SHPData['numpoints']; ++$i) {
            $this->SHPData['points'][] = $this->_loadPoint();
        }
    }

    /**
     * @param string $type
     */
    private function _loadMultiPointMZRecord($type)
    {
        /* The m dimension is optional, depends on bounding box data */
        if ($type == 'm' && !$this->ShapeFile->hasMeasure()) {
            return;
        }

        $this->SHPData[$type . 'min'] = $this->_loadData('d', 8);
        $this->SHPData[$type . 'max'] = $this->_loadData('d', 8);

        for ($i = 0; $i < $this->SHPData['numpoints']; ++$i) {
            $this->SHPData['points'][$i][$type] = $this->_loadData('d', 8);
        }
    }

    private function _loadMultiPointMRecord()
    {
        $this->_loadMultiPointRecord();

        $this->_loadMultiPointMZRecord('m');
    }

    private function _loadMultiPointZRecord()
    {
        $this->_loadMultiPointRecord();

        $this->_loadMultiPointMZRecord('z');
        $this->_loadMultiPointMZRecord('m');
    }

    private function _saveMultiPointRecord()
    {
        fwrite($this->SHPFile, pack('dddd', $this->SHPData['xmin'], $this->SHPData['ymin'], $this->SHPData['xmax'], $this->SHPData['ymax']));

        fwrite($this->SHPFile, pack('V', $this->SHPData['numpoints']));

        for ($i = 0; $i < $this->SHPData['numpoints']; ++$i) {
            $this->_savePoint($this->SHPData['points'][$i]);
        }
    }

    /**
     * @param string $type
     */
    private function _saveMultiPointMZRecord($type)
    {
        fwrite($this->SHPFile, pack('dd', $this->SHPData[$type . 'min'], $this->SHPData[$type . 'max']));

        for ($i = 0; $i < $this->SHPData['numpoints']; ++$i) {
            fwrite($this->SHPFile, Util::packDouble($this->SHPData['points'][$i][$type]));
        }
    }

    private function _saveMultiPointMRecord()
    {
        $this->_saveMultiPointRecord();

        $this->_saveMultiPointMZRecord('m');
    }

    private function _saveMultiPointZRecord()
    {
        $this->_saveMultiPointRecord();

        $this->_saveMultiPointMZRecord('z');
        $this->_saveMultiPointMZRecord('m');
    }

    private function _loadPolyLineRecord()
    {
        $this->SHPData = array();
        $this->_loadBBox();

        $this->SHPData['numparts'] = $this->_loadData('V', 4);
        $this->SHPData['numpoints'] = $this->_loadData('V', 4);

        $numparts = $this->SHPData['numparts'];
        $numpoints = $this->SHPData['numpoints'];

        for ($i = 0; $i < $numparts; ++$i) {
            $this->SHPData['parts'][$i] = $this->_loadData('V', 4);
        }

        $part = 0;
        for ($i = 0; $i < $numpoints; ++$i) {
            if ($part + 1 < $numparts && $this->SHPData['parts'][$part + 1] == $i) {
                ++$part;
            }
            if (!isset($this->SHPData['parts'][$part]['points']) || !is_array($this->SHPData['parts'][$part]['points'])) {
                $this->SHPData['parts'][$part] = array('points' => array());
            }
            $this->SHPData['parts'][$part]['points'][] = $this->_loadPoint();
        }
    }

    /**
     * @param string $type
     */
    private function _loadPolyLineMZRecord($type)
    {
        /* The m dimension is optional, depends on bounding box data */
        if ($type == 'm' && !$this->ShapeFile->hasMeasure()) {
            return;
        }

        $this->SHPData[$type . 'min'] = $this->_loadData('d', 8);
        $this->SHPData[$type . 'max'] = $this->_loadData('d', 8);

        $numparts = $this->SHPData['numparts'];
        $numpoints = $this->SHPData['numpoints'];

        $part = 0;
        for ($i = 0; $i < $numpoints; ++$i) {
            if ($part + 1 < $numparts && $this->SHPData['parts'][$part + 1] == $i) {
                ++$part;
            }
            $this->SHPData['parts'][$part]['points'][$i][$type] = $this->_loadData('d', 8);
        }
    }

    private function _loadPolyLineMRecord()
    {
        $this->_loadPolyLineRecord();

        $this->_loadPolyLineMZRecord('m');
    }

    private function _loadPolyLineZRecord()
    {
        $this->_loadPolyLineRecord();

        $this->_loadPolyLineMZRecord('z');
        $this->_loadPolyLineMZRecord('m');
    }

    private function _savePolyLineRecord()
    {
        fwrite($this->SHPFile, pack('dddd', $this->SHPData['xmin'], $this->SHPData['ymin'], $this->SHPData['xmax'], $this->SHPData['ymax']));

        fwrite($this->SHPFile, pack('VV', $this->SHPData['numparts'], $this->SHPData['numpoints']));

        $part_index = 0;
        for ($i = 0; $i < $this->SHPData['numparts']; ++$i) {
            fwrite($this->SHPFile, pack('V', $part_index));
            $part_index += count($this->SHPData['parts'][$i]['points']);
        }

        foreach ($this->SHPData['parts'] as $partData) {
            foreach ($partData['points'] as $pointData) {
                $this->_savePoint($pointData);
            }
        }
    }

    /**
     * @param string $type
     */
    private function _savePolyLineMZRecord($type)
    {
        fwrite($this->SHPFile, pack('dd', $this->SHPData[$type . 'min'], $this->SHPData[$type . 'max']));

        foreach ($this->SHPData['parts'] as $partData) {
            foreach ($partData['points'] as $pointData) {
                fwrite($this->SHPFile, Util::packDouble($pointData[$type]));
            }
        }
    }

    private function _savePolyLineMRecord()
    {
        $this->_savePolyLineRecord();

        $this->_savePolyLineMZRecord('m');
    }

    private function _savePolyLineZRecord()
    {
        $this->_savePolyLineRecord();

        $this->_savePolyLineMZRecord('z');
        $this->_savePolyLineMZRecord('m');
    }

    private function _loadPolygonRecord()
    {
        $this->_loadPolyLineRecord();
    }

    private function _loadPolygonMRecord()
    {
        $this->_loadPolyLineMRecord();
    }

    private function _loadPolygonZRecord()
    {
        $this->_loadPolyLineZRecord();
    }

    private function _savePolygonRecord()
    {
        $this->_savePolyLineRecord();
    }

    private function _savePolygonMRecord()
    {
        $this->_savePolyLineMRecord();
    }

    private function _savePolygonZRecord()
    {
        $this->_savePolyLineZRecord();
    }

    private function _adjustBBox($point)
    {
        // Adjusts bounding box based on point
        $directions = array('x', 'y', 'z', 'm');
        foreach ($directions as $direction) {
            if (!isset($point[$direction])) {
                continue;
            }
            $min = $direction . 'min';
            $max = $direction . 'max';
            if (!isset($this->SHPData[$min]) || ($this->SHPData[$min] > $point[$direction])) {
                $this->SHPData[$min] = $point[$direction];
            }
            if (!isset($this->SHPData[$max]) || ($this->SHPData[$max] < $point[$direction])) {
                $this->SHPData[$max] = $point[$direction];
            }
        }
    }

    /**
     * Sets dimension to 0 if not set.
     *
     * @param array  $point     Point to check
     * @param string $dimension Dimension to check
     *
     * @return array
     */
    private function _fixPoint($point, $dimension)
    {
        if (!isset($point[$dimension])) {
            $point[$dimension] = 0.0; // no_value
        }

        return $point;
    }

    /**
     * Adjust point and bounding box when adding point.
     *
     * @param array $point Point data
     *
     * @return array Fixed point data
     */
    private function _adjustPoint($point)
    {
        $type = $this->shapeType / 10;
        if ($type >= 2) {
            $point = $this->_fixPoint($point, 'm');
        } elseif ($type >= 1) {
            $point = $this->_fixPoint($point, 'z');
            $point = $this->_fixPoint($point, 'm');
        }

        return $point;
    }

    /**
     * Adds point to a record.
     *
     * @param array $point     Point data
     * @param int   $partIndex Part index
     */
    public function addPoint($point, $partIndex = 0)
    {
        $point = $this->_adjustPoint($point);
        switch ($this->shapeType) {
            case 0:
                //Don't add anything
                return;
            case 1:
            case 11:
            case 21:
                //Substitutes the value of the current point
                $this->SHPData = $point;
                break;
            case 3:
            case 5:
            case 13:
            case 15:
            case 23:
            case 25:
                //Adds a new point to the selected part
                $this->SHPData['parts'][$partIndex]['points'][] = $point;
                $this->SHPData['numparts'] = count($this->SHPData['parts']);
                $this->SHPData['numpoints'] = 1 + (isset($this->SHPData['numpoints']) ? $this->SHPData['numpoints'] : 0);
                break;
            case 8:
            case 18:
            case 28:
                //Adds a new point
                $this->SHPData['points'][] = $point;
                $this->SHPData['numpoints'] = 1 + (isset($this->SHPData['numpoints']) ? $this->SHPData['numpoints'] : 0);
                break;
            default:
                $this->setError(sprintf('The Shape Type "%s" is not supported.', $this->shapeType));

                return;
        }
        $this->_adjustBBox($point);
    }

    /**
     * Deletes point from a record.
     *
     * @param int $pointIndex Point index
     * @param int $partIndex  Part index
     */
    public function deletePoint($pointIndex = 0, $partIndex = 0)
    {
        switch ($this->shapeType) {
            case 0:
                //Don't delete anything
                break;
            case 1:
            case 11:
            case 21:
                //Sets the value of the point to zero
                $this->SHPData['x'] = 0.0;
                $this->SHPData['y'] = 0.0;
                if (in_array($this->shapeType, array(11, 21))) {
                    $this->SHPData['m'] = 0.0;
                }
                if (in_array($this->shapeType, array(11))) {
                    $this->SHPData['z'] = 0.0;
                }
                break;
            case 3:
            case 5:
            case 13:
            case 15:
            case 23:
            case 25:
                //Deletes the point from the selected part, if exists
                if (isset($this->SHPData['parts'][$partIndex]) && isset($this->SHPData['parts'][$partIndex]['points'][$pointIndex])) {
                    $count = count($this->SHPData['parts'][$partIndex]['points']) - 1;
                    for ($i = $pointIndex; $i < $count; ++$i) {
                        $this->SHPData['parts'][$partIndex]['points'][$i] = $this->SHPData['parts'][$partIndex]['points'][$i + 1];
                    }
                    unset($this->SHPData['parts'][$partIndex]['points'][count($this->SHPData['parts'][$partIndex]['points']) - 1]);

                    $this->SHPData['numparts'] = count($this->SHPData['parts']);
                    --$this->SHPData['numpoints'];
                }
                break;
            case 8:
            case 18:
            case 28:
                //Deletes the point, if exists
                if (isset($this->SHPData['points'][$pointIndex])) {
                    $count = count($this->SHPData['points']) - 1;
                    for ($i = $pointIndex; $i < $count; ++$i) {
                        $this->SHPData['points'][$i] = $this->SHPData['points'][$i + 1];
                    }
                    unset($this->SHPData['points'][count($this->SHPData['points']) - 1]);

                    --$this->SHPData['numpoints'];
                }
                break;
            default:
                $this->setError(sprintf('The Shape Type "%s" is not supported.', $this->shapeType));
                break;
        }
    }

    /**
     * Returns length of content.
     *
     * @return int
     */
    public function getContentLength()
    {
        // The content length for a record is the length of the record contents section measured in 16-bit words.
        // one coordinate makes 4 16-bit words (64 bit double)
        switch ($this->shapeType) {
            case 0:
                $result = 0;
                break;
            case 1:
                $result = 10;
                break;
            case 21:
                $result = 10 + 4;
                break;
            case 11:
                $result = 10 + 8;
                break;
            case 3:
            case 5:
                $count = count($this->SHPData['parts']);
                $result = 22 + 2 * $count;
                for ($i = 0; $i < $count; ++$i) {
                    $result += 8 * count($this->SHPData['parts'][$i]['points']);
                }
                break;
            case 23:
            case 25:
                $count = count($this->SHPData['parts']);
                $result = 22 + (2 * 4) + 2 * $count;
                for ($i = 0; $i < $count; ++$i) {
                    $result += (8 + 4) * count($this->SHPData['parts'][$i]['points']);
                }
                break;
            case 13:
            case 15:
                $count = count($this->SHPData['parts']);
                $result = 22 + (4 * 4) + 2 * $count;
                for ($i = 0; $i < $count; ++$i) {
                    $result += (8 + 8) * count($this->SHPData['parts'][$i]['points']);
                }
                break;
            case 8:
                $result = 20 + 8 * count($this->SHPData['points']);
                break;
            case 28:
                $result = 20 + (2 * 4) + (8 + 4) * count($this->SHPData['points']);
                break;
            case 18:
                $result = 20 + (4 * 4) + (8 + 8) * count($this->SHPData['points']);
                break;
            default:
                $result = false;
                $this->setError(sprintf('The Shape Type "%s" is not supported.', $this->shapeType));
                break;
        }

        return $result;
    }

    private function _loadDBFData()
    {
        $this->DBFData = @dbase_get_record_with_names($this->DBFFile, $this->recordNumber);
        unset($this->DBFData['deleted']);
    }

    private function _saveDBFData()
    {
        if (count($this->DBFData) == 0) {
            return;
        }
        unset($this->DBFData['deleted']);
        if ($this->recordNumber <= dbase_numrecords($this->DBFFile)) {
            if (!dbase_replace_record($this->DBFFile, array_values($this->DBFData), $this->recordNumber)) {
                $this->setError('I wasn\'t possible to update the information in the DBF file.');
            }
        } else {
            if (!dbase_add_record($this->DBFFile, array_values($this->DBFData))) {
                $this->setError('I wasn\'t possible to add the information to the DBF file.');
            }
        }
    }

    /**
     * Sets error message.
     *
     * @param string $error
     */
    public function setError($error)
    {
        $this->lastError = $error;
    }

    /**
     * Returns shape name.
     *
     * @return string
     */
    public function getShapeName()
    {
        return Util::nameShape($this->shapeType);
    }
}
