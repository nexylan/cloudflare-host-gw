<?php

/*
 * This file is part of the Nexylan CloudFlare package.
 *
 * (c) Nexylan SAS <contact@nexylan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nexy\CloudFlareHostGW\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Sullivan Senechal <soullivaneuh@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('nexy_cloudflare_host_gw');

        $rootNode
            ->children()
                ->scalarNode('host_key')
                    ->isRequired()->cannotBeEmpty()
                ->end()
                ->scalarNode('user_key')
                    ->defaultNull()
                    ->info('You can set user key if you use only one.')
                ->end()
                ->scalarNode('timeout')
                    ->defaultValue(5)
                    ->info('The default http client timeout.')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
