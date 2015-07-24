<?php

/*
 * This file is part of the Nexylan CloudFlare package.
 *
 * (c) Nexylan SAS <contact@nexylan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nexy\CloudFlareHostGW\Bundle;

use Nexy\CloudFlareHostGW\Bundle\DependencyInjection\NexyCloudFlareHostGWExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Sullivan Senechal <soullivaneuh@gmail.com>
 */
class NexyCloudFlareHostGWBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new NexyCloudFlareHostGWExtension();
        }

        return $this->extension;
    }
}
