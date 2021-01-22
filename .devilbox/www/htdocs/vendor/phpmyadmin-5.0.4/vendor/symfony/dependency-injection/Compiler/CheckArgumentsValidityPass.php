<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * Checks if arguments of methods are properly configured.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 * @author Nicolas Grekas <p@tchwork.com>
 */
class CheckArgumentsValidityPass extends AbstractRecursivePass
{
    private $throwExceptions;

    public function __construct(bool $throwExceptions = true)
    {
        $this->throwExceptions = $throwExceptions;
    }

    /**
     * {@inheritdoc}
     */
    protected function processValue($value, $isRoot = false)
    {
        if (!$value instanceof Definition) {
            return parent::processValue($value, $isRoot);
        }

        $i = 0;
        foreach ($value->getArguments() as $k => $v) {
            if ($k !== $i++) {
                if (!\is_int($k)) {
                    $msg = sprintf('Invalid constructor argument for service "%s": integer expected but found string "%s". Check your service definition.', $this->currentId, $k);
                    $value->addError($msg);
                    if ($this->throwExceptions) {
                        throw new RuntimeException($msg);
                    }

                    break;
                }

                $msg = sprintf('Invalid constructor argument %d for service "%s": argument %d must be defined before. Check your service definition.', 1 + $k, $this->currentId, $i);
                $value->addError($msg);
                if ($this->throwExceptions) {
                    throw new RuntimeException($msg);
                }
            }
        }

        foreach ($value->getMethodCalls() as $methodCall) {
            $i = 0;
            foreach ($methodCall[1] as $k => $v) {
                if ($k !== $i++) {
                    if (!\is_int($k)) {
                        $msg = sprintf('Invalid argument for method call "%s" of service "%s": integer expected but found string "%s". Check your service definition.', $methodCall[0], $this->currentId, $k);
                        $value->addError($msg);
                        if ($this->throwExceptions) {
                            throw new RuntimeException($msg);
                        }

                        break;
                    }

                    $msg = sprintf('Invalid argument %d for method call "%s" of service "%s": argument %d must be defined before. Check your service definition.', 1 + $k, $methodCall[0], $this->currentId, $i);
                    $value->addError($msg);
                    if ($this->throwExceptions) {
                        throw new RuntimeException($msg);
                    }
                }
            }
        }

        return null;
    }
}
