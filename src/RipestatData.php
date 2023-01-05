<?php

namespace Coderjerk\RipestatData;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;

class RipestatData
{
    /**
     * The Base URL for all queries
     *
     * @var string
     */
    protected $base_url = 'https://stat.ripe.net/data/';

    /**
     * The api endpoint
     *
     * @var string
     */
    public $endpoint;

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
     * returns geolocation information for the given IP space
     * based on MaxMind's GeoLite2 data source.
     *
     * Prefix information (IPv4/IPv6) is based on GeoLite2 data
     * created by MaxMind, which is Copyright 2021 MaxMind, Inc. All Rights Reserved.
     * Please consult MaxMind's license (opens new window)before using
     * this data for non-internal projects. For details on the accuracy
     * of this data, please visit MaxMind's product website. According to
     *  information given on Maxmind's webpage (November 2019),
     * the data is being updated once a week on Tuesday.
     *
     * @param string $hostname
     * @return void
     */
    public function maxmindGeoLite($hostname)
    {
        $this->endpoint = 'maxmind-geo-lite';
        $this->getIpLoopFromHostnames($hostname);
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
     * Returns address space objects (inetnum or inet6num) from
     * the RIPE Database related to the queried resource.
     * Less- and more-specific results are first-level only,
     * further levels would have to be retrieved iteratively.
     *
     * @param string $hostname
     * @return void
     */
    public function addressSpaceHierarchy($hostname)
    {
        $this->endpoint = 'address-space-hierarchy';
        $this->getIpLoopFromHostnames($hostname);
    }

    /**
     * Returns the IP address of the requester
     *
     * @return void
     */
    public function whatsMyIp()
    {
        $this->endpoint = 'whats-my-ip';
        $this->lookup();
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

    protected function lookup($resource = false)
    {
        try {
            $client = new Client();

            $url = $this->base_url . "/" . $this->endpoint . "/data.json";

            if ($resource) {
                $url = "{$url}?resource={$resource}";
            }

            $response  = $client->request('GET', $url);

            d(json_decode($response->getBody()->getContents()));
        } catch (ClientException $e) {
            d($e->getResponse()->getBody()->getContents());
        }
    }
}
