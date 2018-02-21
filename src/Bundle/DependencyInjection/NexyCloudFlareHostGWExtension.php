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

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Sullivan Senechal <soullivaneuh@gmail.com>
 */
class NexyCloudFlareHostGWExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $container->setAlias('nexy_cloudflare_host_gw.http.client', new Alias($config['http']['client'], false));
        $container->setParameter($this->getAlias().'.host_key', $config['host_key']);
        $container->setParameter($this->getAlias().'.user_key', $config['user_key']);

        $loader->load('cloudflare_host_gw.xml');
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'nexy_cloudflare_host_gw';
    }
}
