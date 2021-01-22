<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Text Plain Substring Transformations plugin for phpMyAdmin
 *
 * @package    PhpMyAdmin-Transformations
 * @subpackage Substring
 */
namespace PhpMyAdmin\Plugins\Transformations;

use PhpMyAdmin\Plugins\Transformations\Abs\SubstringTransformationsPlugin;

/**
 * Handles the substring transformation for text plain
 *
 * @package    PhpMyAdmin-Transformations
 * @subpackage Substring
 */
// @codingStandardsIgnoreLine
class Text_Plain_Substring extends SubstringTransformationsPlugin
{
    /**
     * Gets the plugin`s MIME type
     *
     * @return string
     */
    public static function getMIMEType()
    {
        return "Text";
    }

    /**
     * Gets the plugin`s MIME subtype
     *
     * @return string
     */
    public static function getMIMESubtype()
    {
        return "Plain";
    }
}
