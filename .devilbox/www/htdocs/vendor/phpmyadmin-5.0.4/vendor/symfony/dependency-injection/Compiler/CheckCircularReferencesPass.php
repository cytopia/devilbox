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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;

/**
 * Checks your services for circular references.
 *
 * References from method calls are ignored since we might be able to resolve
 * these references depending on the order in which services are called.
 *
 * Circular reference from method calls will only be detected at run-time.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class CheckCircularReferencesPass implements CompilerPassInterface
{
    private $currentPath;
    private $checkedNodes;

    /**
     * Checks the ContainerBuilder object for circular references.
     */
    public function process(ContainerBuilder $container)
    {
        $graph = $container->getCompiler()->getServiceReferenceGraph();

        $this->checkedNodes = [];
        foreach ($graph->getNodes() as $id => $node) {
            $this->currentPath = [$id];

            $this->checkOutEdges($node->getOutEdges());
        }
    }

    /**
     * Checks for circular references.
     *
     * @param ServiceReferenceGraphEdge[] $edges An array of Edges
     *
     * @throws ServiceCircularReferenceException when a circular reference is found
     */
    private function checkOutEdges(array $edges)
    {
        foreach ($edges as $edge) {
            $node = $edge->getDestNode();
            $id = $node->getId();

            if (empty($this->checkedNodes[$id])) {
                // Don't check circular references for lazy edges
                if (!$node->getValue() || (!$edge->isLazy() && !$edge->isWeak())) {
                    $searchKey = array_search($id, $this->currentPath);
                    $this->currentPath[] = $id;

                    if (false !== $searchKey) {
                        throw new ServiceCircularReferenceException($id, \array_slice($this->currentPath, $searchKey));
                    }

                    $this->checkOutEdges($node->getOutEdges());
                }

                $this->checkedNodes[$id] = true;
                array_pop($this->currentPath);
            }
        }
    }
}
