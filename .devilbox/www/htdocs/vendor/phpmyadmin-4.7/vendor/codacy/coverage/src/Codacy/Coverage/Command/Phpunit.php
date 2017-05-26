<?php

namespace Codacy\Coverage\Command;

use Codacy\Coverage\Parser\PhpUnitXmlParser;

/**
 * Class Phpunit
 *
 */
class Phpunit extends Clover {

    protected function configure()
    {
        parent::configure();

        $this
            ->setName("phpunit")
            ->setDescription("Send coverage results in phpunit format");
    }

    protected function getParser($path = null)
    {
        $path = is_null($path) ?
            "build" . DIRECTORY_SEPARATOR . "coverage-xml" :
            $path;

        $parser = new PhpUnitXmlParser($path . DIRECTORY_SEPARATOR . "index.xml");
        $parser->setDirOfFileXmls($path);

        return $parser;
    }
}
