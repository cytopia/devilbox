<?php

/*
 * This file is part of Twig.
 *
 * (c) 2010-2019 Fabien Potencier
 * (c) 2019 phpMyAdmin contributors
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PhpMyAdmin\Twig\Extensions;

use PhpMyAdmin\Twig\Extensions\TokenParser\TransTokenParser;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class I18nExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        return [new TransTokenParser()];
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new TwigFilter('trans', 'gettext'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'i18n';
    }
}
