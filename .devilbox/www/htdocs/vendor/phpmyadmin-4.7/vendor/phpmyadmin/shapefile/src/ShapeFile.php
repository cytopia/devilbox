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
 * ShapeFile class.
 */
class ShapeFile
{
    const MAGIC = 0x270a;

    public $FileName;

    private $SHPFile = null;
    private $SHXFile = null;
    private $DBFFile = null;

    private $DBFHeader;

    public $lastError = '';

    public $boundingBox = array('xmin' => 0.0, 'ymin' => 0.0, 'xmax' => 0.0, 'ymax' => 0.0);
    private $fileLength = 0;
    public $shapeType = 0;

    public $records = array();

    /**
     * Checks whether dbase manipuations are supported.
     *
     * @return bool
     */
    public static function supports_dbase()
    {
        return extension_loaded('dbase');
    }

    /**
     * @param int $shapeType
     */
    public function __construct($shapeType, $boundingBox = array('xmin' => 0.0, 'ymin' => 0.0, 'xmax' => 0.0, 'ymax' => 0.0), $FileName = null)
    {
        $this->shapeType = $shapeType;
        $this->boundingBox = $boundingBox;
        $this->FileName = $FileName;
        $this->fileLength = 50; // The value for file length is the total length of the file in 16-bit words (including the fifty 16-bit words that make up the header).
    }

    /**
     * Loads shapefile and dbase (if supported).
     *
     * @param string $FileName File mask to load (eg. example.*)
     */
    public function loadFromFile($FileName)
    {
        if (!empty($FileName)) {
            $this->FileName = $FileName;
            $result = $this->_openSHPFile();
        } else {
            /* We operate on buffer emulated by readSHP / eofSHP */
            $result = true;
        }

        if ($result && ($this->_openDBFFile())) {
            if (!$this->_loadHeaders()) {
                $this->_closeSHPFile();
                $this->_closeDBFFile();

                return false;
            }
            if (!$this->_loadRecords()) {
                $this->_closeSHPFile();
                $this->_closeDBFFile();

                return false;
            }
            $this->_closeSHPFile();
            $this->_closeDBFFile();

            return true;
        } else {
            return false;
        }
    }

    /**
     * Saves shapefile.
     *
     * @param string|null $FileName Name of file, otherwise existing is used
     */
    public function saveToFile($FileName = null)
    {
        if (!is_null($FileName)) {
            $this->FileName = $FileName;
        }

        if (($this->_openSHPFile(true)) && ($this->_openSHXFile(true)) && ($this->_createDBFFile())) {
            $this->_saveHeaders();
            $this->_saveRecords();
            $this->_closeSHPFile();
            $this->_closeSHXFile();
            $this->_closeDBFFile();
        } else {
            return false;
        }
    }

    /**
     * Generates filename with given extension.
     *
     * @param string $extension Extension to use (including dot)
     *
     * @return string
     */
    private function _getFilename($extension)
    {
        return str_replace('.*', $extension, $this->FileName);
    }

    /**
     * Updates bounding box based on SHPData.
     *
     * @param string $type Type of box
     * @param array  $data ShapeRecord SHPData
     */
    private function updateBBox($type, $data)
    {
        $min = $type . 'min';
        $max = $type . 'max';

        if (!isset($this->boundingBox[$min]) || $this->boundingBox[$min] == 0.0 || ($this->boundingBox[$min] > $data[$min])) {
            $this->boundingBox[$min] = $data[$min];
        }
        if (!isset($this->boundingBox[$max]) || $this->boundingBox[$max] == 0.0 || ($this->boundingBox[$max] < $data[$max])) {
            $this->boundingBox[$max] = $data[$max];
        }
    }

    /**
     * Adds record to shape file.
     *
     * @param ShapeRecord $record
     *
     * @return int Number of added record
     */
    public function addRecord($record)
    {
        if ((isset($this->DBFHeader)) && (is_array($this->DBFHeader))) {
            $record->updateDBFInfo($this->DBFHeader);
        }

        $this->fileLength += ($record->getContentLength() + 4);
        $this->records[] = $record;
        $this->records[count($this->records) - 1]->recordNumber = count($this->records);

        $this->updateBBox('x', $record->SHPData);
        $this->updateBBox('y', $record->SHPData);

        if (in_array($this->shapeType, array(11, 13, 15, 18, 21, 23, 25, 28))) {
            $this->updateBBox('m', $record->SHPData);
        }

        if (in_array($this->shapeType, array(11, 13, 15, 18))) {
            $this->updateBBox('z', $record->SHPData);
        }

        return count($this->records) - 1;
    }

    /**
     * Deletes record from shapefile.
     *
     * @param int $index
     */
    public function deleteRecord($index)
    {
        if (isset($this->records[$index])) {
            $this->fileLength -= ($this->records[$index]->getContentLength() + 4);
            $count = count($this->records) - 1;
            for ($i = $index; $i < $count; ++$i) {
                $this->records[$i] = $this->records[$i + 1];
            }
            unset($this->records[count($this->records) - 1]);
            $this->_deleteRecordFromDBF($index);
        }
    }

    /**
     * Returns array defining fields in DBF file.
     *
     * @return array see setDBFHeader for more information
     */
    public function getDBFHeader()
    {
        return $this->DBFHeader;
    }

    /**
     * Changes array defining fields in DBF file, used in dbase_create call.
     *
     * @param array $header An array of arrays, each array describing the
     *                      format of one field of the database. Each
     *                      field consists of a name, a character indicating
     *                      the field type, and optionally, a length,
     *                      a precision and a nullable flag.
     */
    public function setDBFHeader($header)
    {
        $this->DBFHeader = $header;

        $count = count($this->records);
        for ($i = 0; $i < $count; ++$i) {
            $this->records[$i]->updateDBFInfo($header);
        }
    }

    /**
     * Lookups value in the DBF file and returs index.
     *
     * @param string $field Field to match
     * @param mixed  $value Value to match
     *
     * @return int
     */
    public function getIndexFromDBFData($field, $value)
    {
        foreach ($this->records as $index => $record) {
            if (isset($record->DBFData[$field]) &&
                (trim(strtoupper($record->DBFData[$field])) == strtoupper($value))
            ) {
                return $index;
            }
        }

        return -1;
    }

    /**
     * Loads DBF metadata.
     */
    private function _loadDBFHeader()
    {
        $DBFFile = fopen($this->_getFilename('.dbf'), 'r');

        $result = array();
        $i = 1;
        $inHeader = true;

        while ($inHeader) {
            if (!feof($DBFFile)) {
                $buff32 = fread($DBFFile, 32);
                if ($i > 1) {
                    if (substr($buff32, 0, 1) == chr(13)) {
                        $inHeader = false;
                    } else {
                        $pos = strpos(substr($buff32, 0, 10), chr(0));
                        $pos = ($pos == 0 ? 10 : $pos);

                        $fieldName = substr($buff32, 0, $pos);
                        $fieldType = substr($buff32, 11, 1);
                        $fieldLen = ord(substr($buff32, 16, 1));
                        $fieldDec = ord(substr($buff32, 17, 1));

                        array_push($result, array($fieldName, $fieldType, $fieldLen, $fieldDec));
                    }
                }
                ++$i;
            } else {
                $inHeader = false;
            }
        }

        fclose($DBFFile);

        return $result;
    }

    /**
     * Deletes record from the DBF file.
     *
     * @param int $index
     */
    private function _deleteRecordFromDBF($index)
    {
        if (@dbase_delete_record($this->DBFFile, $index)) {
            dbase_pack($this->DBFFile);
        }
    }

    /**
     * Loads SHP file metadata.
     *
     * @return bool
     */
    private function _loadHeaders()
    {
        if (Util::loadData('N', $this->readSHP(4)) != self::MAGIC) {
            $this->setError('Not a SHP file (file code mismatch)');

            return false;
        }

        /* Skip 20 unused bytes */
        $this->readSHP(20);

        $this->fileLength = Util::loadData('N', $this->readSHP(4));

        /* We currently ignore version */
        $this->readSHP(4);

        $this->shapeType = Util::loadData('V', $this->readSHP(4));

        $this->boundingBox = array();
        $this->boundingBox['xmin'] = Util::loadData('d', $this->readSHP(8));
        $this->boundingBox['ymin'] = Util::loadData('d', $this->readSHP(8));
        $this->boundingBox['xmax'] = Util::loadData('d', $this->readSHP(8));
        $this->boundingBox['ymax'] = Util::loadData('d', $this->readSHP(8));
        $this->boundingBox['zmin'] = Util::loadData('d', $this->readSHP(8));
        $this->boundingBox['zmax'] = Util::loadData('d', $this->readSHP(8));
        $this->boundingBox['mmin'] = Util::loadData('d', $this->readSHP(8));
        $this->boundingBox['mmax'] = Util::loadData('d', $this->readSHP(8));

        if (self::supports_dbase()) {
            $this->DBFHeader = $this->_loadDBFHeader();
        }

        return true;
    }

    /**
     * Saves bounding box record, possibly using 0 instead of not set values.
     *
     * @param file   $file File object
     * @param string $type Bounding box dimension (eg. xmax, mmin...)
     */
    private function _saveBBoxRecord($file, $type)
    {
        fwrite($file, Util::packDouble(
            isset($this->boundingBox[$type]) ? $this->boundingBox[$type] : 0)
        );
    }

    /**
     * Saves bounding box to a file.
     *
     * @param file $file File object
     */
    private function _saveBBox($file)
    {
        $this->_saveBBoxRecord($file, 'xmin');
        $this->_saveBBoxRecord($file, 'ymin');
        $this->_saveBBoxRecord($file, 'xmax');
        $this->_saveBBoxRecord($file, 'ymax');
        $this->_saveBBoxRecord($file, 'zmin');
        $this->_saveBBoxRecord($file, 'zmax');
        $this->_saveBBoxRecord($file, 'mmin');
        $this->_saveBBoxRecord($file, 'mmax');
    }

    /**
     * Saves SHP and SHX file metadata.
     */
    private function _saveHeaders()
    {
        fwrite($this->SHPFile, pack('NNNNNN', self::MAGIC, 0, 0, 0, 0, 0));
        fwrite($this->SHPFile, pack('N', $this->fileLength));
        fwrite($this->SHPFile, pack('V', 1000));
        fwrite($this->SHPFile, pack('V', $this->shapeType));
        $this->_saveBBox($this->SHPFile);

        fwrite($this->SHXFile, pack('NNNNNN', self::MAGIC, 0, 0, 0, 0, 0));
        fwrite($this->SHXFile, pack('N', 50 + 4 * count($this->records)));
        fwrite($this->SHXFile, pack('V', 1000));
        fwrite($this->SHXFile, pack('V', $this->shapeType));
        $this->_saveBBox($this->SHXFile);
    }

    /**
     * Loads records from SHP file (and DBF).
     *
     * @return bool
     */
    private function _loadRecords()
    {
        /* Need to start at offset 100 */
        while (!$this->eofSHP()) {
            $record = new ShapeRecord(-1);
            $record->loadFromFile($this, $this->SHPFile, $this->DBFFile);
            if ($record->lastError != '') {
                $this->setError($record->lastError);

                return false;
            }
            if (($record->shapeType === false || $record->shapeType === '') && $this->eofSHP()) {
                break;
            }

            $this->records[] = $record;
        }

        return true;
    }

    /**
     * Saves records to SHP and SHX files.
     */
    private function _saveRecords()
    {
        $offset = 50;
        if (is_array($this->records) && (count($this->records) > 0)) {
            foreach ($this->records as $index => $record) {
                //Save the record to the .shp file
                $record->saveToFile($this->SHPFile, $this->DBFFile, $index + 1);

                //Save the record to the .shx file
                fwrite($this->SHXFile, pack('N', $offset));
                fwrite($this->SHXFile, pack('N', $record->getContentLength()));
                $offset += (4 + $record->getContentLength());
            }
        }
    }

    /**
     * Generic interface to open files.
     *
     * @param bool   $toWrite   Whether file should be opened for writing
     * @param string $extension File extension
     * @param string $name      Verbose file name to report errors
     *
     * @return file|false File handle
     */
    private function _openFile($toWrite, $extension, $name)
    {
        $shp_name = $this->_getFilename($extension);
        $result = @fopen($shp_name, ($toWrite ? 'wb+' : 'rb'));
        if (!$result) {
            $this->setError(sprintf('It wasn\'t possible to open the %s file "%s"', $name, $shp_name));

            return false;
        }

        return $result;
    }

    /**
     * Opens SHP file.
     *
     * @param bool $toWrite Whether file should be opened for writing
     *
     * @return bool
     */
    private function _openSHPFile($toWrite = false)
    {
        $this->SHPFile = $this->_openFile($toWrite, '.shp', 'Shape');
        if (!$this->SHPFile) {
            return false;
        }

        return true;
    }

    /**
     * Closes SHP file.
     */
    private function _closeSHPFile()
    {
        if ($this->SHPFile) {
            fclose($this->SHPFile);
            $this->SHPFile = null;
        }
    }

    /**
     * Opens SHX file.
     *
     * @param bool $toWrite Whether file should be opened for writing
     *
     * @return bool
     */
    private function _openSHXFile($toWrite = false)
    {
        $this->SHXFile = $this->_openFile($toWrite, '.shx', 'Index');
        if (!$this->SHXFile) {
            return false;
        }

        return true;
    }

    /**
     * Closes SHX file.
     */
    private function _closeSHXFile()
    {
        if ($this->SHXFile) {
            fclose($this->SHXFile);
            $this->SHXFile = null;
        }
    }

    /**
     * Creates DBF file.
     *
     * @return bool
     */
    private function _createDBFFile()
    {
        if (!self::supports_dbase() || !is_array($this->DBFHeader) || count($this->DBFHeader) == 0) {
            $this->DBFFile = null;

            return true;
        }
        $dbf_name = $this->_getFilename('.dbf');

        /* Unlink existing file */
        if (file_exists($dbf_name)) {
            unlink($dbf_name);
        }

        /* Create new file */
        $this->DBFFile = @dbase_create($dbf_name, $this->DBFHeader);
        if ($this->DBFFile === false) {
            $this->setError(sprintf('It wasn\'t possible to create the DBase file "%s"', $dbf_name));

            return false;
        }

        return true;
    }

    /**
     * Loads DBF file if supported.
     *
     * @return bool
     */
    private function _openDBFFile()
    {
        if (!self::supports_dbase()) {
            $this->DBFFile = null;

            return true;
        }
        $dbf_name = $this->_getFilename('.dbf');
        if (is_readable($dbf_name)) {
            $this->DBFFile = @dbase_open($dbf_name, 0);
            if (!$this->DBFFile) {
                $this->setError(sprintf('It wasn\'t possible to open the DBase file "%s"', $dbf_name));

                return false;
            }
        } else {
            $this->setError(sprintf('It wasn\'t possible to find the DBase file "%s"', $dbf_name));

            return false;
        }

        return true;
    }

    /**
     * Closes DBF file.
     */
    private function _closeDBFFile()
    {
        if ($this->DBFFile) {
            dbase_close($this->DBFFile);
            $this->DBFFile = null;
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
     * Reads given number of bytes from SHP file.
     *
     * @param int $bytes
     *
     * @return string
     */
    public function readSHP($bytes)
    {
        return fread($this->SHPFile, $bytes);
    }

    /**
     * Checks whether file is at EOF.
     *
     * @return bool
     */
    public function eofSHP()
    {
        return feof($this->SHPFile);
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

    /**
     * Check whether file contains measure data.
     *
     * For some reason this is distinguished by zero bouding box in the
     * specification.
     *
     * @return bool
     */
    public function hasMeasure()
    {
        return $this->boundingBox['mmin'] != 0 || $this->boundingBox['mmax'] != 0;
    }
}
