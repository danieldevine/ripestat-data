<?php

require_once('bootstrap.php');

use \Coderjerk\RipestatData\RipestatData;

$ripestat = new RipestatData;

// $ripestat->domainWhoIs('coderjerk.com');
// $ripestat->dnsChain('coderjerk.com');
// $ripestat->reverseDns('coderjerk.com');
$reverseDnsIps = $ripestat->reverseDnsIP('coderjerk.com');
$ips = $ripestat->whatsMyIp();

foreach ($reverseDnsIps as $ip) {
    $results = $ip->data->result;

    foreach ($results as $result) {
        echo "<div>{$result}</div>";
    }
}

echo "<div>{$ips->data->ip}</div>";
