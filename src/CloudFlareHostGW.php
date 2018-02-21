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

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Exception\TransferException;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Nexy\CloudFlareHostGW\Exception\ApiErrorException;

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
     * @var HttpMethodsClient
     */
    private $httpClient;

    /**
     * @param string  $hostKey
     * @param string  $userKey
     * @param HttpClient $httpClient
     */
    public function __construct($hostKey, $userKey = null, HttpClient $httpClient = null)
    {
        $this->hostKey = $hostKey;
        $this->userKey = $userKey;
        $this->httpClient = new HttpMethodsClient(
            $httpClient ?: HttpClientDiscovery::find(),
            MessageFactoryDiscovery::find()
        );
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
            'zone_name' => $zoneName,
        ], $userKey);
    }

    /**
     * @param string $zoneName
     * @param string $userKey
     *
     * @return mixed
     */
    public function zoneDelete($zoneName, $userKey = null)
    {
        return $this->request('zone_delete', [
            'zone_name' => $zoneName,
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
        try {
            $response = $this->httpClient->post(self::API_URL, [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ], http_build_query(array_merge([
                'host_key' => $this->hostKey,
                'act'      => $act,
                'user_key' => null !== $userKey ? $userKey : $this->userKey,
            ], $parameters)));
        } catch (TransferException $exception) {
            throw new ApiErrorException($exception->getMessage(), $exception->getCode(), $exception);
        }

        if ($response->getStatusCode() >= 400) {
            throw new ApiErrorException($response->getReasonPhrase(), $response->getStatusCode());
        }

        $data = json_decode($response->getBody()->getContents(), true);

        if ('error' === $data['result']) {
            throw new ApiErrorException($data['msg'], $data['err_code']);
        }

        return $data['response'];
    }
}
