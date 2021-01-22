<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Abstract class for the regex validation input transformations plugins
 *
 * @package    PhpMyAdmin-Transformations
 * @subpackage RegexValidation
 */
declare(strict_types=1);

namespace PhpMyAdmin\Plugins\Transformations\Abs;

use PhpMyAdmin\Plugins\IOTransformationsPlugin;
use stdClass;

/**
 * Provides common methods for all of the regex validation
 * input transformations plugins.
 *
 * @package    PhpMyAdmin-Transformations
 * @subpackage RegexValidation
 */
abstract class RegexValidationTransformationsPlugin extends IOTransformationsPlugin
{
    /**
     * Gets the transformation description of the specific plugin
     *
     * @return string
     */
    public static function getInfo()
    {
        return __(
            'Validates the string using regular expression '
            . 'and performs insert only if string matches it. '
            . 'The first option is the Regular Expression.'
        );
    }

    /**
     * Does the actual work of each specific transformations plugin.
     *
     * @param string        $buffer  text to be transformed
     * @param array         $options transformation options
     * @param stdClass|null $meta    meta information
     *
     * @return string
     */
    public function applyTransformation($buffer, array $options = [], ?stdClass $meta = null)
    {
        // reset properties of object
        $this->reset();
        if (! empty($options[0]) && ! preg_match($options[0], $buffer)) {
            $this->success = false;
            $this->error = sprintf(
                __('Validation failed for the input string %s.'),
                htmlspecialchars($buffer)
            );
        }

        return $buffer;
    }

    /* ~~~~~~~~~~~~~~~~~~~~ Getters and Setters ~~~~~~~~~~~~~~~~~~~~ */

    /**
     * Gets the transformation name of the specific plugin
     *
     * @return string
     */
    public static function getName()
    {
        return "Regex Validation";
    }
}
