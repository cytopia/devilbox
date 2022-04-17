<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection;

use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

class Alias
{
    private const DEFAULT_DEPRECATION_TEMPLATE = 'The "%alias_id%" service alias is deprecated. You should stop using it, as it will be removed in the future.';

    private $id;
    private $public;
    private $private;
    private $deprecated;
    private $deprecationTemplate;

    public function __construct(string $id, bool $public = true)
    {
        $this->id = $id;
        $this->public = $public;
        $this->private = 2 > \func_num_args();
        $this->deprecated = false;
    }

    /**
     * Checks if this DI Alias should be public or not.
     *
     * @return bool
     */
    public function isPublic()
    {
        return $this->public;
    }

    /**
     * Sets if this Alias is public.
     *
     * @param bool $boolean If this Alias should be public
     *
     * @return $this
     */
    public function setPublic($boolean)
    {
        $this->public = (bool) $boolean;
        $this->private = false;

        return $this;
    }

    /**
     * Sets if this Alias is private.
     *
     * When set, the "private" state has a higher precedence than "public".
     * In version 3.4, a "private" alias always remains publicly accessible,
     * but triggers a deprecation notice when accessed from the container,
     * so that the alias can be made really private in 4.0.
     *
     * @param bool $boolean
     *
     * @return $this
     */
    public function setPrivate($boolean)
    {
        $this->private = (bool) $boolean;

        return $this;
    }

    /**
     * Whether this alias is private.
     *
     * @return bool
     */
    public function isPrivate()
    {
        return $this->private;
    }

    /**
     * Whether this alias is deprecated, that means it should not be referenced
     * anymore.
     *
     * @param bool   $status   Whether this alias is deprecated, defaults to true
     * @param string $template Optional template message to use if the alias is deprecated
     *
     * @return $this
     *
     * @throws InvalidArgumentException when the message template is invalid
     */
    public function setDeprecated($status = true, $template = null)
    {
        if (null !== $template) {
            if (preg_match('#[\r\n]|\*/#', $template)) {
                throw new InvalidArgumentException('Invalid characters found in deprecation template.');
            }

            if (!str_contains($template, '%alias_id%')) {
                throw new InvalidArgumentException('The deprecation template must contain the "%alias_id%" placeholder.');
            }

            $this->deprecationTemplate = $template;
        }

        $this->deprecated = (bool) $status;

        return $this;
    }

    public function isDeprecated(): bool
    {
        return $this->deprecated;
    }

    public function getDeprecationMessage(string $id): string
    {
        return str_replace('%alias_id%', $id, $this->deprecationTemplate ?: self::DEFAULT_DEPRECATION_TEMPLATE);
    }

    /**
     * Returns the Id of this alias.
     *
     * @return string The alias id
     */
    public function __toString()
    {
        return $this->id;
    }
}
