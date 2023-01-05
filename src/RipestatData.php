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
    public function domainWhoIs($hostname)
    {
        $this->endpoint = 'whois';
        $this->getIpLoopFromHostnames($hostname);
    }

    /**
     * This data call returns the recursive chain of
     * DNS forward (A/AAAA/CNAME) and reverse (PTR) records
     * starting form either a hostname or an IP address.
     *
     * @param string $hostname
     * @return void
     */
    public function dnsChain($hostname)
    {
        $this->endpoint = 'dns-chain';
        $this->lookup($hostname);
    }

    /**
     * Returns details of reverse DNS delegations
     * for IP prefixes in the RIPE region.
     *
     * @param string $hostname
     * @return void
     */
    public function reverseDns($hostname)
    {

        $this->endpoint = 'reverse-dns';
        $this->getIpLoopFromHostnames($hostname);
    }

    /**
     * A simple lookup for the reverse DNS info
     * against a single IP address.
     *
     * @param string $hostname
     * @return void
     */
    public function reverseDnsIP($hostname)
    {

        $this->endpoint = 'reverse-dns-ip';
        $this->getIpLoopFromHostnames($hostname);
    }

    /**
     * Looks up an array of IPS related to a given hostname
     *
     * @param string $hostname
     * @return void
     */
    protected function getIpLoopFromHostnames($hostname)
    {

        $ips = gethostbynamel($hostname);

        if (!$ips) {
            return;
        }

        foreach ($ips as $ip) {
            $this->lookup($ip);
        }
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
