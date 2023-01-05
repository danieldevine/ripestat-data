<?php

namespace Coderjerk\RipestatData;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;

class RipestatData
{
    protected ?string $base_url = 'https://stat.ripe.net/data/';

    public ?string $endpoint;

    /**
     * This data call returns whois information from the
     * relevant Regional Internet Registry and Routing Registry.
     *
     * @param string $hostname
     * @return void
     */
    public function getDomainWhoIs($hostname)
    {
        $this->endpoint = 'whois';

        $ips = gethostbynamel($hostname);

        if (!$ips) {
            return;
        }

        foreach ($ips as $ip) {
            $this->lookup($ip);
        }
    }

    /**
     * This data call returns the recursive chain of
     * DNS forward (A/AAAA/CNAME) and reverse (PTR) records
     * starting form either a hostname or an IP address.
     *
     * @param string $hostname
     * @return void
     */
    public function getDnsChain($hostname)
    {
        $this->endpoint = 'dns-chain';
        $this->lookup($hostname);
    }

    protected function lookup($resource)
    {
        try {
            $client = new Client();

            $url = $this->base_url . "/" . $this->endpoint . "/data.json?resource={$resource}";

            $response  = $client->request('GET', $url);

            d(json_decode($response->getBody()->getContents()));
        } catch (ClientException $e) {
            d($e->getResponse()->getBody()->getContents());
        }
    }
}
