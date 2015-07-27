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
     * @var string
     */
    private $userKey = null;

    /**
     * @var Browser
     */
    private $browser;

    /**
     * @param string  $hostKey
     * @param string  $userKey
     * @param Browser $browser
     */
    public function __construct($hostKey, $userKey = null, Browser $browser = null)
    {
        $this->hostKey = $hostKey;
        $this->userKey = $userKey;
        $this->browser = $browser ? $browser : new Browser();
    }

    /**
     * Set Buzz client timeout.
     *
     * @param int $timeout
     */
    public function setTimeout($timeout)
    {
        $this->browser->getClient()->setTimeout($timeout);
    }

    /**
     * @param string   $zoneName
     * @param string   $resolveTo
     * @param string[] $subdomains
     * @param string   $userKey
     *
     * @return mixed
     */
    public function zoneSet($zoneName, $resolveTo, array $subdomains, $userKey = null)
    {
        return $this->request('zone_set', [
            'zone_name'  => $zoneName,
            'resolve_to' => $resolveTo,
            'subdomains' => implode(',', $subdomains),
        ], $userKey);
    }

    /**
     * @param string $zoneName
     * @param string $userKey
     *
     * @return mixed
     */
    public function zoneLookup($zoneName, $userKey = null)
    {
        return $this->request('zone_lookup', [
            'zone_name'  => $zoneName,
        ], $userKey);
    }

    /**
     * @param string $act
     * @param array  $parameters
     * @param string $userKey
     *
     * @return mixed
     */
    private function request($act, array $parameters, $userKey = null)
    {
        $response = $this->browser->post(self::API_URL, [], array_merge([
            'host_key' => $this->hostKey,
            'act'      => $act,
            'user_key' => null !== $userKey ? $userKey : $this->userKey,
        ], $parameters));

        return json_decode($response->getContent(), true)['response'];
    }
}
