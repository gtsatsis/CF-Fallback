<?php

require 'vendor/autoload.php';
require 'inc/config.php';

use Failover\CloudflareCheck;
use Failover\Addons\Statuspage;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(array_key_exists('authorization', $_GET)){
	if(in_array($_GET['authorization'], $authorizationKeys)){
		$cf = new CloudflareCheck();

		if($opts['ssl'] == false){
			$checkCloudflareErrors = $cf->cloudflareErrorCheck($cloudflareDetails['domain'], false);
		}elseif($opts['ssl'] == true && $opts['sslVerify'] == false){
			$checkCloudflareErrors = $cf->cloudflareErrorCheck($cloudflareDetails['domain'], true, false);
		}else{
			$checkCloudflareErrors = $cf->cloudflareErrorCheck($cloudflareDetails['domain']);
		}

		if($checkCloudflareErrors === true){
			echo 'All good! No errors were present in the scan.';
		}else{
			if($cloudflareDetails['differentZoneName'] == true){
				$changeDNSRecord = $cf->cloudflareDNSChangeARecordDifferentZoneCase($cloudflareDetails['email'], $cloudflareDetails['apikey'], $cloudflareDetails['domain'], $ips['backup'], $cloudflareDetails['zoneName']);
				if($changeDNSRecord === true){
					echo 'Uh oh! Origin seems down. We\'ve gone ahead and set your backup IP to be the DNS record present on' . $cloudflareDetails['domain'] . '.';
				}
			}else{
				$changeDNSRecord = $cf->cloudflareDNSChangeARecord($cloudflareDetails['email'], $cloudflareDetails['apikey'], $cloudflareDetails['domain'], $ips['backup']);
				if($changeDNSRecord === true){
					echo 'Uh oh! Origin seems down. We\'ve gone ahead and set your backup IP to be the DNS record present on' . $cloudflareDetails['domain'] . '.';
				}
			}
		}
	}
}
?>