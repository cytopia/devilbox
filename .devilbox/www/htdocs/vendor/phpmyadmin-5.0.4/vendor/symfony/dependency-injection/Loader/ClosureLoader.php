<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * ClosureLoader loads service definitions from a PHP closure.
 *
 * The Closure has access to the container as its first argument.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class ClosureLoader extends Loader
{
    private $container;

    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        $resource($this->container);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return $resource instanceof \Closure;
    }
}
