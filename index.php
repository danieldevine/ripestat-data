<?php

require_once('bootstrap.php');

use \Coderjerk\RipestatData\RipestatData;

$ripestat = new RipestatData;

// $ripestat->getDomainWhoIs('mavericklabs.ie');

$ripestat->getDnsChain('flern');
