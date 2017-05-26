<?php

namespace Codacy\Coverage;

use Symfony\Component\Console\Application as ConsoleApplication;

use Codacy\Coverage\Command\Clover as CloverCommand;
use Codacy\Coverage\Command\Phpunit as PhpunitCommand;

/**
 * Class Application
 *
 */
class Application extends ConsoleApplication
{
    public function __construct()
    {
        parent::__construct("Codacy Coverage API Client");

        $this->add(new CloverCommand());
        $this->add(new PhpunitCommand());
    }
}
