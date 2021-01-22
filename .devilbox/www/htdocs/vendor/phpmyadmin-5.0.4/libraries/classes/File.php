<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * file upload functions
 *
 * @package PhpMyAdmin
 */
declare(strict_types=1);

namespace PhpMyAdmin;

use PhpMyAdmin\Core;
use PhpMyAdmin\Message;
use PhpMyAdmin\Util;
use PhpMyAdmin\ZipExtension;
use ZipArchive;

/**
 * File wrapper class
 *
 * @todo when uploading a file into a blob field, should we also consider using
 *       chunks like in import? UPDATE `table` SET `field` = `field` + [chunk]
 *
 * @package PhpMyAdmin
 */
class File
{
    /**
     * @var string the temporary file name
     * @access protected
     */
    protected $_name = null;

    /**
     * @var string the content
     * @access protected
     */
    protected $_content = null;

    /**
     * @var Message|null the error message
     * @access protected
     */
    protected $_error_message = null;

    /**
     * @var bool whether the file is temporary or not
     * @access protected
     */
    protected $_is_temp = false;

    /**
     * @var string type of compression
     * @access protected
     */
    protected $_compression = null;

    /**
     * @var integer
     */
    protected $_offset = 0;

    /**
     * @var integer size of chunk to read with every step
     */
    protected $_chunk_size = 32768;

    /**
     * @var resource|null file handle
     */
    protected $_handle = null;

    /**
     * @var boolean whether to decompress content before returning
     */
    protected $_decompress = false;

    /**
     * @var string charset of file
     */
    protected $_charset = null;

    /**
     * @var ZipExtension
     */
    private $zipExtension;

    /**
     * constructor
     *
     * @param boolean|string $name file name or false
     *
     * @access public
     */
    public function __construct($name = false)
    {
        if ($name && is_string($name)) {
            $this->setName($name);
        }

        if (extension_loaded('zip')) {
            $this->zipExtension = new ZipExtension(new ZipArchive());
        }
    }

    /**
     * destructor
     *
     * @see     File::cleanUp()
     * @access  public
     */
    public function __destruct()
    {
        $this->cleanUp();
    }

    /**
     * deletes file if it is temporary, usually from a moved upload file
     *
     * @access  public
     * @return boolean success
     */
    public function cleanUp(): bool
    {
        if ($this->isTemp()) {
            return $this->delete();
        }

        return true;
    }

    /**
     * deletes the file
     *
     * @access  public
     * @return boolean success
     */
    public function delete(): bool
    {
        return unlink($this->getName());
    }

    /**
     * checks or sets the temp flag for this file
     * file objects with temp flags are deleted with object destruction
     *
     * @param boolean $is_temp sets the temp flag
     *
     * @return boolean File::$_is_temp
     * @access  public
     */
    public function isTemp(?bool $is_temp = null): bool
    {
        if (null !== $is_temp) {
            $this->_is_temp = $is_temp;
        }

        return $this->_is_temp;
    }

    /**
     * accessor
     *
     * @param string|null $name file name
     *
     * @return void
     * @access  public
     */
    public function setName(?string $name): void
    {
        $this->_name = trim($name);
    }

    /**
     * Gets file content
     *
     * @return string|false the binary file content,
     *                      or false if no content
     *
     * @access  public
     */
    public function getRawContent()
    {
        if (null === $this->_content) {
            if ($this->isUploaded() && ! $this->checkUploadedFile()) {
                return false;
            }

            if (! $this->isReadable()) {
                return false;
            }

            if (function_exists('file_get_contents')) {
                $this->_content = file_get_contents($this->getName());
            } elseif ($size = filesize($this->getName())) {
                $handle = fopen($this->getName(), 'rb');
                $this->_content = fread($handle, $size);
                fclose($handle);
            }
        }

        return $this->_content;
    }

    /**
     * Gets file content
     *
     * @return string|false the binary file content as a string,
     *                      or false if no content
     *
     * @access  public
     */
    public function getContent()
    {
        $result = $this->getRawContent();
        if ($result === false) {
            return false;
        }
        return '0x' . bin2hex($result);
    }

    /**
     * Whether file is uploaded.
     *
     * @access  public
     *
     * @return bool
     */
    public function isUploaded(): bool
    {
        if (! is_string($this->getName())) {
            return false;
        } else {
            return is_uploaded_file($this->getName());
        }
    }

    /**
     * accessor
     *
     * @access public
     * @return string|null File::$_name
     */
    public function getName(): ?string
    {
        return $this->_name;
    }

    /**
     * Initializes object from uploaded file.
     *
     * @param string $name name of file uploaded
     *
     * @return boolean success
     * @access  public
     */
    public function setUploadedFile(string $name): bool
    {
        $this->setName($name);

        if (! $this->isUploaded()) {
            $this->setName(null);
            $this->_error_message = Message::error(__('File was not an uploaded file.'));
            return false;
        }

        return true;
    }

    /**
     * Loads uploaded file from table change request.
     *
     * @param string $key       the md5 hash of the column name
     * @param string $rownumber number of row to process
     *
     * @return boolean success
     * @access  public
     */
    public function setUploadedFromTblChangeRequest(
        string $key,
        string $rownumber
    ): bool {
        if (! isset($_FILES['fields_upload'])
            || empty($_FILES['fields_upload']['name']['multi_edit'][$rownumber][$key])
        ) {
            return false;
        }
        $file = File::fetchUploadedFromTblChangeRequestMultiple(
            $_FILES['fields_upload'],
            $rownumber,
            $key
        );

        // check for file upload errors
        switch ($file['error']) {
        // we do not use the PHP constants here cause not all constants
        // are defined in all versions of PHP - but the correct constants names
        // are given as comment
            case 0: //UPLOAD_ERR_OK:
                return $this->setUploadedFile($file['tmp_name']);
            case 4: //UPLOAD_ERR_NO_FILE:
                break;
            case 1: //UPLOAD_ERR_INI_SIZE:
                $this->_error_message = Message::error(__(
                    'The uploaded file exceeds the upload_max_filesize directive in '
                    . 'php.ini.'
                ));
                break;
            case 2: //UPLOAD_ERR_FORM_SIZE:
                $this->_error_message = Message::error(__(
                    'The uploaded file exceeds the MAX_FILE_SIZE directive that was '
                    . 'specified in the HTML form.'
                ));
                break;
            case 3: //UPLOAD_ERR_PARTIAL:
                $this->_error_message = Message::error(__(
                    'The uploaded file was only partially uploaded.'
                ));
                break;
            case 6: //UPLOAD_ERR_NO_TMP_DIR:
                $this->_error_message = Message::error(__('Missing a temporary folder.'));
                break;
            case 7: //UPLOAD_ERR_CANT_WRITE:
                $this->_error_message = Message::error(__('Failed to write file to disk.'));
                break;
            case 8: //UPLOAD_ERR_EXTENSION:
                $this->_error_message = Message::error(__('File upload stopped by extension.'));
                break;
            default:
                $this->_error_message = Message::error(__('Unknown error in file upload.'));
        } // end switch

        return false;
    }

    /**
     * strips some dimension from the multi-dimensional array from $_FILES
     *
     * <code>
     * $file['name']['multi_edit'][$rownumber][$key] = [value]
     * $file['type']['multi_edit'][$rownumber][$key] = [value]
     * $file['size']['multi_edit'][$rownumber][$key] = [value]
     * $file['tmp_name']['multi_edit'][$rownumber][$key] = [value]
     * $file['error']['multi_edit'][$rownumber][$key] = [value]
     *
     * // becomes:
     *
     * $file['name'] = [value]
     * $file['type'] = [value]
     * $file['size'] = [value]
     * $file['tmp_name'] = [value]
     * $file['error'] = [value]
     * </code>
     *
     * @param array  $file      the array
     * @param string $rownumber number of row to process
     * @param string $key       key to process
     *
     * @return array
     * @access  public
     * @static
     */
    public function fetchUploadedFromTblChangeRequestMultiple(
        array $file,
        string $rownumber,
        string $key
    ): array {
        $new_file = [
            'name' => $file['name']['multi_edit'][$rownumber][$key],
            'type' => $file['type']['multi_edit'][$rownumber][$key],
            'size' => $file['size']['multi_edit'][$rownumber][$key],
            'tmp_name' => $file['tmp_name']['multi_edit'][$rownumber][$key],
            'error' => $file['error']['multi_edit'][$rownumber][$key],
        ];

        return $new_file;
    }

    /**
     * sets the name if the file to the one selected in the tbl_change form
     *
     * @param string $key       the md5 hash of the column name
     * @param string $rownumber number of row to process
     *
     * @return boolean success
     * @access  public
     */
    public function setSelectedFromTblChangeRequest(
        string $key,
        ?string $rownumber = null
    ): bool {
        if (! empty($_REQUEST['fields_uploadlocal']['multi_edit'][$rownumber][$key])
            && is_string($_REQUEST['fields_uploadlocal']['multi_edit'][$rownumber][$key])
        ) {
            // ... whether with multiple rows ...
            return $this->setLocalSelectedFile(
                $_REQUEST['fields_uploadlocal']['multi_edit'][$rownumber][$key]
            );
        }

        return false;
    }

    /**
     * Returns possible error message.
     *
     * @access  public
     * @return Message|null error message
     */
    public function getError(): ?Message
    {
        return $this->_error_message;
    }

    /**
     * Checks whether there was any error.
     *
     * @access  public
     * @return boolean whether an error occurred or not
     */
    public function isError(): bool
    {
        return $this->_error_message !== null;
    }

    /**
     * checks the superglobals provided if the tbl_change form is submitted
     * and uses the submitted/selected file
     *
     * @param string $key       the md5 hash of the column name
     * @param string $rownumber number of row to process
     *
     * @return boolean success
     * @access  public
     */
    public function checkTblChangeForm(string $key, string $rownumber): bool
    {
        if ($this->setUploadedFromTblChangeRequest($key, $rownumber)) {
            // well done ...
            $this->_error_message = null;
            return true;
        } elseif ($this->setSelectedFromTblChangeRequest($key, $rownumber)) {
            // well done ...
            $this->_error_message = null;
            return true;
        }
        // all failed, whether just no file uploaded/selected or an error

        return false;
    }

    /**
     * Sets named file to be read from UploadDir.
     *
     * @param string $name file name
     *
     * @return boolean success
     * @access  public
     */
    public function setLocalSelectedFile(string $name): bool
    {
        if (empty($GLOBALS['cfg']['UploadDir'])) {
            return false;
        }

        $this->setName(
            Util::userDir($GLOBALS['cfg']['UploadDir']) . Core::securePath($name)
        );
        if (@is_link($this->getName())) {
            $this->_error_message = Message::error(__('File is a symbolic link'));
            $this->setName(null);
            return false;
        }
        if (! $this->isReadable()) {
            $this->_error_message = Message::error(__('File could not be read!'));
            $this->setName(null);
            return false;
        }

        return true;
    }

    /**
     * Checks whether file can be read.
     *
     * @access  public
     * @return boolean whether the file is readable or not
     */
    public function isReadable(): bool
    {
        // suppress warnings from being displayed, but not from being logged
        // any file access outside of open_basedir will issue a warning
        return @is_readable((string) $this->getName());
    }

    /**
     * If we are on a server with open_basedir, we must move the file
     * before opening it. The FAQ 1.11 explains how to create the "./tmp"
     * directory - if needed
     *
     * @todo move check of $cfg['TempDir'] into Config?
     * @access  public
     * @return boolean whether uploaded file is fine or not
     */
    public function checkUploadedFile(): bool
    {
        if ($this->isReadable()) {
            return true;
        }

        $tmp_subdir = $GLOBALS['PMA_Config']->getUploadTempDir();
        if ($tmp_subdir === null) {
            // cannot create directory or access, point user to FAQ 1.11
            $this->_error_message = Message::error(__(
                'Error moving the uploaded file, see [doc@faq1-11]FAQ 1.11[/doc].'
            ));
            return false;
        }

        $new_file_to_upload = tempnam(
            $tmp_subdir,
            basename($this->getName())
        );

        // suppress warnings from being displayed, but not from being logged
        // any file access outside of open_basedir will issue a warning
        ob_start();
        $move_uploaded_file_result = move_uploaded_file(
            $this->getName(),
            $new_file_to_upload
        );
        ob_end_clean();
        if (! $move_uploaded_file_result) {
            $this->_error_message = Message::error(__('Error while moving uploaded file.'));
            return false;
        }

        $this->setName($new_file_to_upload);
        $this->isTemp(true);

        if (! $this->isReadable()) {
            $this->_error_message = Message::error(__('Cannot read uploaded file.'));
            return false;
        }

        return true;
    }

    /**
     * Detects what compression the file uses
     *
     * @todo    move file read part into readChunk() or getChunk()
     * @todo    add support for compression plugins
     * @access  protected
     * @return  string|false false on error, otherwise string MIME type of
     *                       compression, none for none
     */
    protected function detectCompression()
    {
        // suppress warnings from being displayed, but not from being logged
        // f.e. any file access outside of open_basedir will issue a warning
        ob_start();
        $file = fopen($this->getName(), 'rb');
        ob_end_clean();

        if (! $file) {
            $this->_error_message = Message::error(__('File could not be read!'));
            return false;
        }

        $this->_compression = Util::getCompressionMimeType($file);
        return $this->_compression;
    }

    /**
     * Sets whether the content should be decompressed before returned
     *
     * @param boolean $decompress whether to decompress
     *
     * @return void
     */
    public function setDecompressContent(bool $decompress): void
    {
        $this->_decompress = $decompress;
    }

    /**
     * Returns the file handle
     *
     * @return resource file handle
     */
    public function getHandle()
    {
        if (null === $this->_handle) {
            $this->open();
        }
        return $this->_handle;
    }

    /**
     * Sets the file handle
     *
     * @param resource $handle file handle
     *
     * @return void
     */
    public function setHandle($handle): void
    {
        $this->_handle = $handle;
    }


    /**
     * Sets error message for unsupported compression.
     *
     * @return void
     */
    public function errorUnsupported(): void
    {
        $this->_error_message = Message::error(sprintf(
            __(
                'You attempted to load file with unsupported compression (%s). '
                . 'Either support for it is not implemented or disabled by your '
                . 'configuration.'
            ),
            $this->getCompression()
        ));
    }

    /**
     * Attempts to open the file.
     *
     * @return bool
     */
    public function open(): bool
    {
        if (! $this->_decompress) {
            $this->_handle = @fopen($this->getName(), 'r');
        }

        switch ($this->getCompression()) {
            case false:
                return false;
            case 'application/bzip2':
                if ($GLOBALS['cfg']['BZipDump'] && function_exists('bzopen')) {
                    $this->_handle = @bzopen($this->getName(), 'r');
                } else {
                    $this->errorUnsupported();
                    return false;
                }
                break;
            case 'application/gzip':
                if ($GLOBALS['cfg']['GZipDump'] && function_exists('gzopen')) {
                    $this->_handle = @gzopen($this->getName(), 'r');
                } else {
                    $this->errorUnsupported();
                    return false;
                }
                break;
            case 'application/zip':
                if ($GLOBALS['cfg']['ZipDump'] && function_exists('zip_open')) {
                    return $this->openZip();
                }

                $this->errorUnsupported();
                return false;
            case 'none':
                $this->_handle = @fopen($this->getName(), 'r');
                break;
            default:
                $this->errorUnsupported();
                return false;
        }

        return ($this->_handle !== false);
    }

    /**
     * Opens file from zip
     *
     * @param string|null $specific_entry Entry to open
     *
     * @return bool
     */
    public function openZip(?string $specific_entry = null): bool
    {
        $result = $this->zipExtension->getContents($this->getName(), $specific_entry);
        if (! empty($result['error'])) {
            $this->_error_message = Message::rawError($result['error']);
            return false;
        }
        $this->_content = $result['data'];
        $this->_offset = 0;
        return true;
    }

    /**
     * Checks whether we've reached end of file
     *
     * @return bool
     */
    public function eof(): bool
    {
        if ($this->_handle !== null) {
            return feof($this->_handle);
        }
        return $this->_offset == strlen($this->_content);
    }

    /**
     * Closes the file
     *
     * @return void
     */
    public function close(): void
    {
        if ($this->_handle !== null) {
            fclose($this->_handle);
            $this->_handle = null;
        } else {
            $this->_content = '';
            $this->_offset = 0;
        }
        $this->cleanUp();
    }

    /**
     * Reads data from file
     *
     * @param int $size Number of bytes to read
     *
     * @return string
     */
    public function read(int $size): string
    {
        switch ($this->_compression) {
            case 'application/bzip2':
                return bzread($this->_handle, $size);
            case 'application/gzip':
                return gzread($this->_handle, $size);
            case 'application/zip':
                $result = mb_strcut($this->_content, $this->_offset, $size);
                $this->_offset += strlen($result);
                return $result;
            case 'none':
            default:
                return fread($this->_handle, $size);
        }
    }

    /**
     * Returns the character set of the file
     *
     * @return string character set of the file
     */
    public function getCharset(): string
    {
        return $this->_charset;
    }

    /**
     * Sets the character set of the file
     *
     * @param string $charset character set of the file
     *
     * @return void
     */
    public function setCharset(string $charset): void
    {
        $this->_charset = $charset;
    }

    /**
     * Returns compression used by file.
     *
     * @return string MIME type of compression, none for none
     * @access  public
     */
    public function getCompression(): string
    {
        if (null === $this->_compression) {
            return $this->detectCompression();
        }

        return $this->_compression;
    }

    /**
     * Returns the offset
     *
     * @return integer the offset
     */
    public function getOffset(): int
    {
        return $this->_offset;
    }

    /**
     * Returns the chunk size
     *
     * @return integer the chunk size
     */
    public function getChunkSize(): int
    {
        return $this->_chunk_size;
    }

    /**
     * Sets the chunk size
     *
     * @param integer $chunk_size the chunk size
     *
     * @return void
     */
    public function setChunkSize(int $chunk_size): void
    {
        $this->_chunk_size = $chunk_size;
    }

    /**
     * Returns the length of the content in the file
     *
     * @return integer the length of the file content
     */
    public function getContentLength(): int
    {
        return strlen($this->_content);
    }
}
