<?php
/**
 * `UNION` keyword builder.
 */

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Components;

use PhpMyAdmin\SqlParser\Component;
use function implode;

/**
 * `UNION` keyword builder.
 */
class UnionKeyword extends Component
{
    /**
     * @param array<UnionKeyword[]> $component the component to be built
     * @param array                 $options   parameters for building
     *
     * @return string
     */
    public static function build($component, array $options = [])
    {
        $tmp = [];
        foreach ($component as $componentPart) {
            $tmp[] = $componentPart[0] . ' ' . $componentPart[1];
        }

        return implode(' ', $tmp);
    }
}
