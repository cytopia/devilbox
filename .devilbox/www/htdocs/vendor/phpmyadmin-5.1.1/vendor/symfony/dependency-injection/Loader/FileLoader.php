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

use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;
use Symfony\Component\Config\Exception\LoaderLoadException;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Config\Loader\FileLoader as BaseFileLoader;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Config\Resource\GlobResource;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

/**
 * FileLoader is the abstract class used by all built-in loaders that are file based.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
abstract class FileLoader extends BaseFileLoader
{
    public const ANONYMOUS_ID_REGEXP = '/^\.\d+_[^~]*+~[._a-zA-Z\d]{7}$/';

    protected $container;
    protected $isLoadingInstanceof = false;
    protected $instanceof = [];
    protected $interfaces = [];
    protected $singlyImplemented = [];
    protected $autoRegisterAliasesForSinglyImplementedInterfaces = true;

    public function __construct(ContainerBuilder $container, FileLocatorInterface $locator)
    {
        $this->container = $container;

        parent::__construct($locator);
    }

    /**
     * {@inheritdoc}
     *
     * @param bool|string          $ignoreErrors Whether errors should be ignored; pass "not_found" to ignore only when the loaded resource is not found
     * @param string|string[]|null $exclude      Glob patterns to exclude from the import
     */
    public function import($resource, $type = null, $ignoreErrors = false, $sourceResource = null/*, $exclude = null*/)
    {
        $args = \func_get_args();

        if ($ignoreNotFound = 'not_found' === $ignoreErrors) {
            $args[2] = false;
        } elseif (!\is_bool($ignoreErrors)) {
            @trigger_error(sprintf('Invalid argument $ignoreErrors provided to %s::import(): boolean or "not_found" expected, %s given.', static::class, \gettype($ignoreErrors)), \E_USER_DEPRECATED);
            $args[2] = (bool) $ignoreErrors;
        }

        try {
            parent::import(...$args);
        } catch (LoaderLoadException $e) {
            if (!$ignoreNotFound || !($prev = $e->getPrevious()) instanceof FileLocatorFileNotFoundException) {
                throw $e;
            }

            foreach ($prev->getTrace() as $frame) {
                if ('import' === ($frame['function'] ?? null) && is_a($frame['class'] ?? '', Loader::class, true)) {
                    break;
                }
            }

            if (__FILE__ !== $frame['file']) {
                throw $e;
            }
        }
    }

    /**
     * Registers a set of classes as services using PSR-4 for discovery.
     *
     * @param Definition           $prototype A definition to use as template
     * @param string               $namespace The namespace prefix of classes in the scanned directory
     * @param string               $resource  The directory to look for classes, glob-patterns allowed
     * @param string|string[]|null $exclude   A globbed path of files to exclude or an array of globbed paths of files to exclude
     */
    public function registerClasses(Definition $prototype, $namespace, $resource, $exclude = null)
    {
        if ('\\' !== substr($namespace, -1)) {
            throw new InvalidArgumentException(sprintf('Namespace prefix must end with a "\\": "%s".', $namespace));
        }
        if (!preg_match('/^(?:[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*+\\\\)++$/', $namespace)) {
            throw new InvalidArgumentException(sprintf('Namespace is not a valid PSR-4 prefix: "%s".', $namespace));
        }

        $classes = $this->findClasses($namespace, $resource, (array) $exclude);
        // prepare for deep cloning
        $serializedPrototype = serialize($prototype);

        foreach ($classes as $class => $errorMessage) {
            if (interface_exists($class, false)) {
                $this->interfaces[] = $class;
            } else {
                $this->setDefinition($class, $definition = unserialize($serializedPrototype));
                if (null !== $errorMessage) {
                    $definition->addError($errorMessage);

                    continue;
                }
                foreach (class_implements($class, false) as $interface) {
                    $this->singlyImplemented[$interface] = ($this->singlyImplemented[$interface] ?? $class) !== $class ? false : $class;
                }
            }
        }

        if ($this->autoRegisterAliasesForSinglyImplementedInterfaces) {
            $this->registerAliasesForSinglyImplementedInterfaces();
        }
    }

    public function registerAliasesForSinglyImplementedInterfaces()
    {
        foreach ($this->interfaces as $interface) {
            if (!empty($this->singlyImplemented[$interface]) && !$this->container->has($interface)) {
                $this->container->setAlias($interface, $this->singlyImplemented[$interface])->setPublic(false);
            }
        }

        $this->interfaces = $this->singlyImplemented = [];
    }

    /**
     * Registers a definition in the container with its instanceof-conditionals.
     *
     * @param string $id
     */
    protected function setDefinition($id, Definition $definition)
    {
        $this->container->removeBindings($id);

        if ($this->isLoadingInstanceof) {
            if (!$definition instanceof ChildDefinition) {
                throw new InvalidArgumentException(sprintf('Invalid type definition "%s": ChildDefinition expected, "%s" given.', $id, \get_class($definition)));
            }
            $this->instanceof[$id] = $definition;
        } else {
            $this->container->setDefinition($id, $definition instanceof ChildDefinition ? $definition : $definition->setInstanceofConditionals($this->instanceof));
        }
    }

    private function findClasses(string $namespace, string $pattern, array $excludePatterns): array
    {
        $parameterBag = $this->container->getParameterBag();

        $excludePaths = [];
        $excludePrefix = null;
        $excludePatterns = $parameterBag->unescapeValue($parameterBag->resolveValue($excludePatterns));
        foreach ($excludePatterns as $excludePattern) {
            foreach ($this->glob($excludePattern, true, $resource, true, true) as $path => $info) {
                if (null === $excludePrefix) {
                    $excludePrefix = $resource->getPrefix();
                }

                // normalize Windows slashes and remove trailing slashes
                $excludePaths[rtrim(str_replace('\\', '/', $path), '/')] = true;
            }
        }

        $pattern = $parameterBag->unescapeValue($parameterBag->resolveValue($pattern));
        $classes = [];
        $extRegexp = '/\\.php$/';
        $prefixLen = null;
        foreach ($this->glob($pattern, true, $resource, false, false, $excludePaths) as $path => $info) {
            if (null === $prefixLen) {
                $prefixLen = \strlen($resource->getPrefix());

                if ($excludePrefix && 0 !== strpos($excludePrefix, $resource->getPrefix())) {
                    throw new InvalidArgumentException(sprintf('Invalid "exclude" pattern when importing classes for "%s": make sure your "exclude" pattern (%s) is a subset of the "resource" pattern (%s).', $namespace, $excludePattern, $pattern));
                }
            }

            if (isset($excludePaths[str_replace('\\', '/', $path)])) {
                continue;
            }

            if (!preg_match($extRegexp, $path, $m) || !$info->isReadable()) {
                continue;
            }
            $class = $namespace.ltrim(str_replace('/', '\\', substr($path, $prefixLen, -\strlen($m[0]))), '\\');

            if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*+(?:\\\\[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*+)*+$/', $class)) {
                continue;
            }

            try {
                $r = $this->container->getReflectionClass($class);
            } catch (\ReflectionException $e) {
                $classes[$class] = $e->getMessage();
                continue;
            }
            // check to make sure the expected class exists
            if (!$r) {
                throw new InvalidArgumentException(sprintf('Expected to find class "%s" in file "%s" while importing services from resource "%s", but it was not found! Check the namespace prefix used with the resource.', $class, $path, $pattern));
            }

            if ($r->isInstantiable() || $r->isInterface()) {
                $classes[$class] = null;
            }
        }

        // track only for new & removed files
        if ($resource instanceof GlobResource) {
            $this->container->addResource($resource);
        } else {
            foreach ($resource as $path) {
                $this->container->fileExists($path, false);
            }
        }

        return $classes;
    }
}
