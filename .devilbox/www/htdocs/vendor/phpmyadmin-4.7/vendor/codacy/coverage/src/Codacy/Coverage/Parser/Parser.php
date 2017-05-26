<?php

namespace Codacy\Coverage\Parser;

/**
 * Interface IParser
 * All parsers need to implement this interface. This allows the JsonProducer
 * to be composed of different parsers via JsonProducer::setParser($parser).
 * @author Jakob Pupke <jakob.pupke@gmail.com>
 */
interface IParser
{
    public function makeReport();
}

/**
 * Class XMLParser
 * The superclass of all parsers that parse XML files.
 * @author Jakob Pupke <jakob.pupke@gmail.com>
 */
abstract class XMLParser
{
    /**
     * @var \SimpleXMLElement
     */
    protected $element;

    /**
     * @var null|string The root directory. Can be other than current working directory
     * in order to make tests pass against the static reports in tests/res directory.
     */
    protected $rootDir;

    /**
     * Construct PhpUnitXmlParser and set the XML object as member field.
     * All XML parser classes inherit this constructor.
     * @param string $rootDir Is only for making tests pass.
     * @param string $path Path to XML file
     */
    public function __construct($path, $rootDir = null)
    {
        if (file_exists($path)) {
            if ($rootDir == null) {
                $this->rootDir = getcwd();
            } else {
                $this->rootDir = $rootDir;
            }
            $this->element = simplexml_load_file($path);
        } else {
            throw new \InvalidArgumentException(
                "Unable to load the xml file. Make sure path is properly set. " .
                "Using: \"$path\"", E_USER_ERROR
            );
        }
    }

}
