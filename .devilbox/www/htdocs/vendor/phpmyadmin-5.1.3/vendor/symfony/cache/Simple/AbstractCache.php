<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Cache\Simple;

use Psr\Log\LoggerAwareInterface;
use Psr\SimpleCache\CacheInterface as Psr16CacheInterface;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\Cache\Exception\InvalidArgumentException;
use Symfony\Component\Cache\ResettableInterface;
use Symfony\Component\Cache\Traits\AbstractTrait;
use Symfony\Contracts\Cache\CacheInterface;

@trigger_error(sprintf('The "%s" class is deprecated since Symfony 4.3, use "%s" and type-hint for "%s" instead.', AbstractCache::class, AbstractAdapter::class, CacheInterface::class), \E_USER_DEPRECATED);

/**
 * @deprecated since Symfony 4.3, use AbstractAdapter and type-hint for CacheInterface instead.
 */
abstract class AbstractCache implements Psr16CacheInterface, LoggerAwareInterface, ResettableInterface
{
    use AbstractTrait {
        deleteItems as private;
        AbstractTrait::deleteItem as delete;
        AbstractTrait::hasItem as has;
    }

    /**
     * @internal
     */
    protected const NS_SEPARATOR = ':';

    private $defaultLifetime;

    protected function __construct(string $namespace = '', int $defaultLifetime = 0)
    {
        $this->defaultLifetime = max(0, $defaultLifetime);
        $this->namespace = '' === $namespace ? '' : CacheItem::validateKey($namespace).':';
        if (null !== $this->maxIdLength && \strlen($namespace) > $this->maxIdLength - 24) {
            throw new InvalidArgumentException(sprintf('Namespace must be %d chars max, %d given ("%s").', $this->maxIdLength - 24, \strlen($namespace), $namespace));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        $id = $this->getId($key);

        try {
            foreach ($this->doFetch([$id]) as $value) {
                return $value;
            }
        } catch (\Exception $e) {
            CacheItem::log($this->logger, 'Failed to fetch key "{key}": '.$e->getMessage(), ['key' => $key, 'exception' => $e]);
        }

        return $default;
    }

    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function set($key, $value, $ttl = null)
    {
        CacheItem::validateKey($key);

        return $this->setMultiple([$key => $value], $ttl);
    }

    /**
     * {@inheritdoc}
     *
     * @return iterable
     */
    public function getMultiple($keys, $default = null)
    {
        if ($keys instanceof \Traversable) {
            $keys = iterator_to_array($keys, false);
        } elseif (!\is_array($keys)) {
            throw new InvalidArgumentException(sprintf('Cache keys must be array or Traversable, "%s" given.', \is_object($keys) ? \get_class($keys) : \gettype($keys)));
        }
        $ids = [];

        foreach ($keys as $key) {
            $ids[] = $this->getId($key);
        }
        try {
            $values = $this->doFetch($ids);
        } catch (\Exception $e) {
            CacheItem::log($this->logger, 'Failed to fetch values: '.$e->getMessage(), ['keys' => $keys, 'exception' => $e]);
            $values = [];
        }
        $ids = array_combine($ids, $keys);

        return $this->generateValues($values, $ids, $default);
    }

    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function setMultiple($values, $ttl = null)
    {
        if (!\is_array($values) && !$values instanceof \Traversable) {
            throw new InvalidArgumentException(sprintf('Cache values must be array or Traversable, "%s" given.', \is_object($values) ? \get_class($values) : \gettype($values)));
        }
        $valuesById = [];

        foreach ($values as $key => $value) {
            if (\is_int($key)) {
                $key = (string) $key;
            }
            $valuesById[$this->getId($key)] = $value;
        }
        if (false === $ttl = $this->normalizeTtl($ttl)) {
            return $this->doDelete(array_keys($valuesById));
        }

        try {
            $e = $this->doSave($valuesById, $ttl);
        } catch (\Exception $e) {
        }
        if (true === $e || [] === $e) {
            return true;
        }
        $keys = [];
        foreach (\is_array($e) ? $e : array_keys($valuesById) as $id) {
            $keys[] = substr($id, \strlen($this->namespace));
        }
        $message = 'Failed to save values'.($e instanceof \Exception ? ': '.$e->getMessage() : '.');
        CacheItem::log($this->logger, $message, ['keys' => $keys, 'exception' => $e instanceof \Exception ? $e : null]);

        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function deleteMultiple($keys)
    {
        if ($keys instanceof \Traversable) {
            $keys = iterator_to_array($keys, false);
        } elseif (!\is_array($keys)) {
            throw new InvalidArgumentException(sprintf('Cache keys must be array or Traversable, "%s" given.', \is_object($keys) ? \get_class($keys) : \gettype($keys)));
        }

        return $this->deleteItems($keys);
    }

    private function normalizeTtl($ttl)
    {
        if (null === $ttl) {
            return $this->defaultLifetime;
        }
        if ($ttl instanceof \DateInterval) {
            $ttl = (int) \DateTime::createFromFormat('U', 0)->add($ttl)->format('U');
        }
        if (\is_int($ttl)) {
            return 0 < $ttl ? $ttl : false;
        }

        throw new InvalidArgumentException(sprintf('Expiration date must be an integer, a DateInterval or null, "%s" given.', \is_object($ttl) ? \get_class($ttl) : \gettype($ttl)));
    }

    private function generateValues(iterable $values, array &$keys, $default): iterable
    {
        try {
            foreach ($values as $id => $value) {
                if (!isset($keys[$id])) {
                    throw new InvalidArgumentException(sprintf('Could not match value id "%s" to keys "%s".', $id, implode('", "', $keys)));
                }
                $key = $keys[$id];
                unset($keys[$id]);
                yield $key => $value;
            }
        } catch (\Exception $e) {
            CacheItem::log($this->logger, 'Failed to fetch values: '.$e->getMessage(), ['keys' => array_values($keys), 'exception' => $e]);
        }

        foreach ($keys as $key) {
            yield $key => $default;
        }
    }
}
