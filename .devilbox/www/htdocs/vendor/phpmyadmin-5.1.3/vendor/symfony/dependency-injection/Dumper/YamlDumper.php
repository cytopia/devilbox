<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Dumper;

use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Argument\ArgumentInterface;
use Symfony\Component\DependencyInjection\Argument\IteratorArgument;
use Symfony\Component\DependencyInjection\Argument\ServiceClosureArgument;
use Symfony\Component\DependencyInjection\Argument\ServiceLocatorArgument;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Yaml\Dumper as YmlDumper;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Tag\TaggedValue;
use Symfony\Component\Yaml\Yaml;

/**
 * YamlDumper dumps a service container as a YAML string.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class YamlDumper extends Dumper
{
    private $dumper;

    /**
     * Dumps the service container as an YAML string.
     *
     * @return string A YAML string representing of the service container
     */
    public function dump(array $options = [])
    {
        if (!class_exists(\Symfony\Component\Yaml\Dumper::class)) {
            throw new LogicException('Unable to dump the container as the Symfony Yaml Component is not installed.');
        }

        if (null === $this->dumper) {
            $this->dumper = new YmlDumper();
        }

        return $this->container->resolveEnvPlaceholders($this->addParameters()."\n".$this->addServices());
    }

    private function addService(string $id, Definition $definition): string
    {
        $code = "    $id:\n";
        if ($class = $definition->getClass()) {
            if ('\\' === substr($class, 0, 1)) {
                $class = substr($class, 1);
            }

            $code .= sprintf("        class: %s\n", $this->dumper->dump($class));
        }

        if (!$definition->isPrivate()) {
            $code .= sprintf("        public: %s\n", $definition->isPublic() ? 'true' : 'false');
        }

        $tagsCode = '';
        foreach ($definition->getTags() as $name => $tags) {
            foreach ($tags as $attributes) {
                $att = [];
                foreach ($attributes as $key => $value) {
                    $att[] = sprintf('%s: %s', $this->dumper->dump($key), $this->dumper->dump($value));
                }
                $att = $att ? ', '.implode(', ', $att) : '';

                $tagsCode .= sprintf("            - { name: %s%s }\n", $this->dumper->dump($name), $att);
            }
        }
        if ($tagsCode) {
            $code .= "        tags:\n".$tagsCode;
        }

        if ($definition->getFile()) {
            $code .= sprintf("        file: %s\n", $this->dumper->dump($definition->getFile()));
        }

        if ($definition->isSynthetic()) {
            $code .= "        synthetic: true\n";
        }

        if ($definition->isDeprecated()) {
            $code .= sprintf("        deprecated: %s\n", $this->dumper->dump($definition->getDeprecationMessage('%service_id%')));
        }

        if ($definition->isAutowired()) {
            $code .= "        autowire: true\n";
        }

        if ($definition->isAutoconfigured()) {
            $code .= "        autoconfigure: true\n";
        }

        if ($definition->isAbstract()) {
            $code .= "        abstract: true\n";
        }

        if ($definition->isLazy()) {
            $code .= "        lazy: true\n";
        }

        if ($definition->getArguments()) {
            $code .= sprintf("        arguments: %s\n", $this->dumper->dump($this->dumpValue($definition->getArguments()), 0));
        }

        if ($definition->getProperties()) {
            $code .= sprintf("        properties: %s\n", $this->dumper->dump($this->dumpValue($definition->getProperties()), 0));
        }

        if ($definition->getMethodCalls()) {
            $code .= sprintf("        calls:\n%s\n", $this->dumper->dump($this->dumpValue($definition->getMethodCalls()), 1, 12));
        }

        if (!$definition->isShared()) {
            $code .= "        shared: false\n";
        }

        if (null !== $decoratedService = $definition->getDecoratedService()) {
            [$decorated, $renamedId, $priority] = $decoratedService;
            $code .= sprintf("        decorates: %s\n", $decorated);
            if (null !== $renamedId) {
                $code .= sprintf("        decoration_inner_name: %s\n", $renamedId);
            }
            if (0 !== $priority) {
                $code .= sprintf("        decoration_priority: %s\n", $priority);
            }

            $decorationOnInvalid = $decoratedService[3] ?? ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE;
            if (\in_array($decorationOnInvalid, [ContainerInterface::IGNORE_ON_INVALID_REFERENCE, ContainerInterface::NULL_ON_INVALID_REFERENCE])) {
                $invalidBehavior = ContainerInterface::NULL_ON_INVALID_REFERENCE === $decorationOnInvalid ? 'null' : 'ignore';
                $code .= sprintf("        decoration_on_invalid: %s\n", $invalidBehavior);
            }
        }

        if ($callable = $definition->getFactory()) {
            $code .= sprintf("        factory: %s\n", $this->dumper->dump($this->dumpCallable($callable), 0));
        }

        if ($callable = $definition->getConfigurator()) {
            $code .= sprintf("        configurator: %s\n", $this->dumper->dump($this->dumpCallable($callable), 0));
        }

        return $code;
    }

    private function addServiceAlias(string $alias, Alias $id): string
    {
        $deprecated = $id->isDeprecated() ? sprintf("        deprecated: %s\n", $id->getDeprecationMessage('%alias_id%')) : '';

        if (!$id->isDeprecated() && $id->isPrivate()) {
            return sprintf("    %s: '@%s'\n", $alias, $id);
        }

        return sprintf("    %s:\n        alias: %s\n        public: %s\n%s", $alias, $id, $id->isPublic() ? 'true' : 'false', $deprecated);
    }

    private function addServices(): string
    {
        if (!$this->container->getDefinitions()) {
            return '';
        }

        $code = "services:\n";
        foreach ($this->container->getDefinitions() as $id => $definition) {
            $code .= $this->addService($id, $definition);
        }

        $aliases = $this->container->getAliases();
        foreach ($aliases as $alias => $id) {
            while (isset($aliases[(string) $id])) {
                $id = $aliases[(string) $id];
            }
            $code .= $this->addServiceAlias($alias, $id);
        }

        return $code;
    }

    private function addParameters(): string
    {
        if (!$this->container->getParameterBag()->all()) {
            return '';
        }

        $parameters = $this->prepareParameters($this->container->getParameterBag()->all(), $this->container->isCompiled());

        return $this->dumper->dump(['parameters' => $parameters], 2);
    }

    /**
     * Dumps callable to YAML format.
     *
     * @param mixed $callable
     *
     * @return mixed
     */
    private function dumpCallable($callable)
    {
        if (\is_array($callable)) {
            if ($callable[0] instanceof Reference) {
                $callable = [$this->getServiceCall((string) $callable[0], $callable[0]), $callable[1]];
            } else {
                $callable = [$callable[0], $callable[1]];
            }
        }

        return $callable;
    }

    /**
     * Dumps the value to YAML format.
     *
     * @return mixed
     *
     * @throws RuntimeException When trying to dump object or resource
     */
    private function dumpValue($value)
    {
        if ($value instanceof ServiceClosureArgument) {
            $value = $value->getValues()[0];

            return new TaggedValue('service_closure', $this->getServiceCall((string) $value, $value));
        }
        if ($value instanceof ArgumentInterface) {
            $tag = $value;

            if ($value instanceof TaggedIteratorArgument || ($value instanceof ServiceLocatorArgument && $tag = $value->getTaggedIteratorArgument())) {
                if (null === $tag->getIndexAttribute()) {
                    $content = $tag->getTag();
                } else {
                    $content = [
                        'tag' => $tag->getTag(),
                        'index_by' => $tag->getIndexAttribute(),
                    ];

                    if (null !== $tag->getDefaultIndexMethod()) {
                        $content['default_index_method'] = $tag->getDefaultIndexMethod();
                    }
                    if (null !== $tag->getDefaultPriorityMethod()) {
                        $content['default_priority_method'] = $tag->getDefaultPriorityMethod();
                    }
                }

                return new TaggedValue($value instanceof TaggedIteratorArgument ? 'tagged_iterator' : 'tagged_locator', $content);
            }

            if ($value instanceof IteratorArgument) {
                $tag = 'iterator';
            } elseif ($value instanceof ServiceLocatorArgument) {
                $tag = 'service_locator';
            } else {
                throw new RuntimeException(sprintf('Unspecified Yaml tag for type "%s".', \get_class($value)));
            }

            return new TaggedValue($tag, $this->dumpValue($value->getValues()));
        }

        if (\is_array($value)) {
            $code = [];
            foreach ($value as $k => $v) {
                $code[$k] = $this->dumpValue($v);
            }

            return $code;
        } elseif ($value instanceof Reference) {
            return $this->getServiceCall((string) $value, $value);
        } elseif ($value instanceof Parameter) {
            return $this->getParameterCall((string) $value);
        } elseif ($value instanceof Expression) {
            return $this->getExpressionCall((string) $value);
        } elseif ($value instanceof Definition) {
            return new TaggedValue('service', (new Parser())->parse("_:\n".$this->addService('_', $value), Yaml::PARSE_CUSTOM_TAGS)['_']['_']);
        } elseif ($value instanceof \UnitEnum) {
            return new TaggedValue('php/const', sprintf('%s::%s', \get_class($value), $value->name));
        } elseif (\is_object($value) || \is_resource($value)) {
            throw new RuntimeException('Unable to dump a service container if a parameter is an object or a resource.');
        }

        return $value;
    }

    private function getServiceCall(string $id, Reference $reference = null): string
    {
        if (null !== $reference) {
            switch ($reference->getInvalidBehavior()) {
                case ContainerInterface::RUNTIME_EXCEPTION_ON_INVALID_REFERENCE: break;
                case ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE: break;
                case ContainerInterface::IGNORE_ON_UNINITIALIZED_REFERENCE: return sprintf('@!%s', $id);
                default: return sprintf('@?%s', $id);
            }
        }

        return sprintf('@%s', $id);
    }

    private function getParameterCall(string $id): string
    {
        return sprintf('%%%s%%', $id);
    }

    private function getExpressionCall(string $expression): string
    {
        return sprintf('@=%s', $expression);
    }

    private function prepareParameters(array $parameters, bool $escape = true): array
    {
        $filtered = [];
        foreach ($parameters as $key => $value) {
            if (\is_array($value)) {
                $value = $this->prepareParameters($value, $escape);
            } elseif ($value instanceof Reference || \is_string($value) && str_starts_with($value, '@')) {
                $value = '@'.$value;
            }

            $filtered[$key] = $value;
        }

        return $escape ? $this->escape($filtered) : $filtered;
    }

    private function escape(array $arguments): array
    {
        $args = [];
        foreach ($arguments as $k => $v) {
            if (\is_array($v)) {
                $args[$k] = $this->escape($v);
            } elseif (\is_string($v)) {
                $args[$k] = str_replace('%', '%%', $v);
            } else {
                $args[$k] = $v;
            }
        }

        return $args;
    }
}
