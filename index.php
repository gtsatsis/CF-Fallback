<?php
require 'vendor/autoload.php';
require 'inc/config.php';

use Failover\CloudflareCheck;
use Failover\Addons\Statuspage;

if(array_key_exists('authorization', $_GET)){
	if(in_array($authorization, $authorizationKeys)){
		$cf = new CloudflareFailover;

		if($opts['ssl'] == false){
			$checkCloudflareErrors = $cf->cloudflareErrorCheck($cloudflareDetails['domain'], false);
		}elseif($opts['ssl'] == true && $opts['sslVerify'] == false){
			$checkCloudflareErrors = $cf->cloudflareErrorCheck($cloudflareDetails['domain'], true, false);
		}else{
			$checkCloudflareErrors = $cf->cloudflareErrorCheck($cloudflareDetails['domain']);
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