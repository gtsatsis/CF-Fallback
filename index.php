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
		$cf = new CloudflareCheck('abc', 'abc', $cloudflareDetails['domain']);

		if($opts['ssl'] == false){
			$checkCloudflareErrors = $cf->cloudflareErrorCheck(false);
		}elseif($opts['ssl'] == true && $opts['sslVerify'] == false){
			$checkCloudflareErrors = $cf->cloudflareErrorCheck(true, false);
		}else{
			$checkCloudflareErrors = $cf->cloudflareErrorCheck();
		}

		if($checkCloudflareErrors == false){
			return 'All good! No errors were present in the scan.';
		}else{
			$changeDNSRecord = $cf->cloudflareDNSChangeARecord($cloudflareDetails['email'], $cloudflareDetails['apikey'], $cloudflareDetails['domain'], $ips['backup']);

			if($changeDNSRecord == true){
				return 'Uh oh! Origin seems down. We\'ve gone ahead and set your backup IP to be the DNS record present on' . $cloudflareDetails['domain'] . '.';
			}
		}
	}
}
?>