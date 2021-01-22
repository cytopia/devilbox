<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Cache\Traits;

use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Cache\CacheItem;

/**
 * @author Nicolas Grekas <p@tchwork.com>
 *
 * @internal
 */
trait ArrayTrait
{
    use LoggerAwareTrait;

    private $storeSerialized;
    private $values = [];
    private $expiries = [];

    /**
     * Returns all cached values, with cache miss as null.
     *
     * @return array
     */
    public function getValues()
    {
        if (!$this->storeSerialized) {
            return $this->values;
        }

        $values = $this->values;
        foreach ($values as $k => $v) {
            if (null === $v || 'N;' === $v) {
                continue;
            }
            if (!\is_string($v) || !isset($v[2]) || ':' !== $v[1]) {
                $values[$k] = serialize($v);
            }
        }

        return $values;
    }

    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function hasItem($key)
    {
        if (\is_string($key) && isset($this->expiries[$key]) && $this->expiries[$key] > microtime(true)) {
            return true;
        }
        CacheItem::validateKey($key);

        return isset($this->expiries[$key]) && !$this->deleteItem($key);
    }

    /**
     * {@inheritdoc}
     *
     * @param string $prefix
     *
     * @return bool
     */
    public function clear(/*string $prefix = ''*/)
    {
        $prefix = 0 < \func_num_args() ? (string) func_get_arg(0) : '';

        if ('' !== $prefix) {
            foreach ($this->values as $key => $value) {
                if (0 === strpos($key, $prefix)) {
                    unset($this->values[$key], $this->expiries[$key]);
                }
            }
        } else {
            $this->values = $this->expiries = [];
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function deleteItem($key)
    {
        if (!\is_string($key) || !isset($this->expiries[$key])) {
            CacheItem::validateKey($key);
        }
        unset($this->values[$key], $this->expiries[$key]);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        $this->clear();
    }

    private function generateItems(array $keys, float $now, callable $f): iterable
    {
        foreach ($keys as $i => $key) {
            if (!$isHit = isset($this->expiries[$key]) && ($this->expiries[$key] > $now || !$this->deleteItem($key))) {
                $this->values[$key] = $value = null;
            } else {
                $value = $this->storeSerialized ? $this->unfreeze($key, $isHit) : $this->values[$key];
            }
            unset($keys[$i]);

            yield $key => $f($key, $value, $isHit);
        }

        foreach ($keys as $key) {
            yield $key => $f($key, null, false);
        }
    }

    private function freeze($value, $key)
    {
        if (null === $value) {
            return 'N;';
        }
        if (\is_string($value)) {
            // Serialize strings if they could be confused with serialized objects or arrays
            if ('N;' === $value || (isset($value[2]) && ':' === $value[1])) {
                return serialize($value);
            }
        } elseif (!is_scalar($value)) {
            try {
                $serialized = serialize($value);
            } catch (\Exception $e) {
                $type = \is_object($value) ? \get_class($value) : \gettype($value);
                $message = sprintf('Failed to save key "{key}" of type %s: ', $type).$e->getMessage();
                CacheItem::log($this->logger, $message, ['key' => $key, 'exception' => $e]);

                return null;
            }
            // Keep value serialized if it contains any objects or any internal references
            if ('C' === $serialized[0] || 'O' === $serialized[0] || preg_match('/;[OCRr]:[1-9]/', $serialized)) {
                return $serialized;
            }
        }

        return $value;
    }

    private function unfreeze(string $key, bool &$isHit)
    {
        if ('N;' === $value = $this->values[$key]) {
            return null;
        }
        if (\is_string($value) && isset($value[2]) && ':' === $value[1]) {
            try {
                $value = unserialize($value);
            } catch (\Exception $e) {
                CacheItem::log($this->logger, 'Failed to unserialize key "{key}": '.$e->getMessage(), ['key' => $key, 'exception' => $e]);
                $value = false;
            }
            if (false === $value) {
                $this->values[$key] = $value = null;
                $isHit = false;
            }
        }

        return $value;
    }
}
