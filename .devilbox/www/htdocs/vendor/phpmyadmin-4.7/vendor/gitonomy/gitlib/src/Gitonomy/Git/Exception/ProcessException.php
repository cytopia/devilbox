<?php

namespace Gitonomy\Git\Exception;

use Symfony\Component\Process\Process;

class ProcessException extends RuntimeException implements GitExceptionInterface
{
    protected $process;

    public function __construct(Process $process)
    {
        parent::__construct("Error while running git command:\n".
            $process->getCommandLine()."\n".
            "\n".
            $process->getErrorOutput()."\n".
            "\n".
            $process->getOutput()
        );

        $this->process = $process;
    }

    public function getErrorOutput()
    {
        return $this->process->getErrorOutput();
    }

    public function getOutput()
    {
        return $this->process->getOutput();
    }
}
