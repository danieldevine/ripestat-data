<?php

require_once('bootstrap.php');

use \Coderjerk\RipestatData\RipestatData;

$ripestat = new RipestatData;

// $ripestat->domainWhoIs('coderjerk.com');

// $ripestat->dnsChain('coderjerk.com');

// $ripestat->reverseDns('coderjerk.com');
$ripestat->reverseDnsIP('www.coderjerk.com');
