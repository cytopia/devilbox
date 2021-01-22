<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Superclass for the single Property Item classes.
 *
 * @package PhpMyAdmin
 */
declare(strict_types=1);

namespace PhpMyAdmin\Properties\Options;

/**
 * Parents only single property items (not groups).
 * Defines possible options and getters and setters for them.
 *
 * @package PhpMyAdmin
 */
abstract class OptionsPropertyOneItem extends OptionsPropertyItem
{
    /**
     * Whether to force or not
     *
     * @var bool
     */
    private $_force_one;
    /**
     * Values
     *
     * @var array
     */
    private $_values;
    /**
     * Doc
     *
     * @var string
     */
    private $_doc;
    /**
     * Length
     *
     * @var int
     */
    private $_len;
    /**
     * Size
     *
     * @var int
     */
    private $_size;
    /* ~~~~~~~~~~~~~~~~~~~~ Getters and Setters ~~~~~~~~~~~~~~~~~~~~ */

    /**
     * Gets the force parameter
     *
     * @return bool
     */
    public function getForce()
    {
        return $this->_force_one;
    }

    /**
     * Sets the force parameter
     *
     * @param bool $force force parameter
     *
     * @return void
     */
    public function setForce($force)
    {
        $this->_force_one = $force;
    }

    /**
     * Gets the values
     *
     * @return array
     */
    public function getValues()
    {
        return $this->_values;
    }

    /**
     * Sets the values
     *
     * @param array $values values
     *
     * @return void
     */
    public function setValues(array $values)
    {
        $this->_values = $values;
    }

    /**
     * Gets MySQL documentation pointer
     *
     * @return string
     */
    public function getDoc()
    {
        return $this->_doc;
    }

    /**
     * Sets the doc
     *
     * @param string $doc MySQL documentation pointer
     *
     * @return void
     */
    public function setDoc($doc)
    {
        $this->_doc = $doc;
    }

    /**
     * Gets the length
     *
     * @return int
     */
    public function getLen()
    {
        return $this->_len;
    }

    /**
     * Sets the length
     *
     * @param int $len length
     *
     * @return void
     */
    public function setLen($len)
    {
        $this->_len = $len;
    }

    /**
     * Gets the size
     *
     * @return int
     */
    public function getSize()
    {
        return $this->_size;
    }

    /**
     * Sets the size
     *
     * @param int $size size
     *
     * @return void
     */
    public function setSize($size)
    {
        $this->_size = $size;
    }
}
