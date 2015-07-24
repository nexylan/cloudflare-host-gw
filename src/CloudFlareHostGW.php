<?php

/*
 * This file is part of the Nexylan CloudFlare package.
 *
 * (c) Nexylan SAS <contact@nexylan.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nexy\CloudFlareHostGW;

use Buzz\Browser;

/**
 * @author Sullivan Senechal <soullivaneuh@gmail.com>
 */
final class CloudFlareHostGW
{
    const API_URL = 'https://api.cloudflare.com/host-gw.html';

    /**
     * @var string
     */
    private $hostKey;

    /**
     * @var Browser
     */
    private $browser;

    /**
     * @param string  $hostKey
     * @param Browser $browser
     */
    public function __construct($hostKey, Browser $browser = null)
    {
        $this->hostKey = $hostKey;
        $this->browser = $browser ? $browser : new Browser();
    }

    /**
     * @param string   $userKey
     * @param string   $zoneName
     * @param string   $resolveTo
     * @param string[] $subdomains
     *
     * @return mixed
     */
    public function zoneSet($userKey, $zoneName, $resolveTo, array $subdomains)
    {
        return $this->request('zone_set', [
            'user_key'   => $userKey,
            'zone_name'  => $zoneName,
            'resolve_to' => $resolveTo,
            'subdomains' => implode(',', $subdomains),
        ]);
    }

    /**
     * @param string $act
     * @param array  $parameters
     *
     * @return mixed
     */
    private function request($act, array $parameters)
    {
        $response = $this->browser->post(self::API_URL, array_merge([
            'host_key' => $this->hostKey,
            'act'      => $act,
        ], $parameters));

        return json_decode($response->getContent());
    }
}
