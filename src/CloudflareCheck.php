<?php
namespace Failover;

require_once('vendor/autoload.php');

class CloudflareFailover{

	function __construct(string $domain, string $email, string $apiKey){
		$cloudflareKey     = new Cloudflare\API\Auth\APIKey($email, $apiKey);
		$cloudflareAdapter = new Cloudflare\API\Adapter\Guzzle($cloudflareKey);
		$cloudflareZones = new \Cloudflare\API\Endpoints\Zones($cloudflareAdapter);
		$zoneID = $cloudflareZones->getZoneID($domain);
		$dns = new \Cloudflare\API\Endpoints\DNS($cloudflareAdapter);
	}

	public function cloudflareErrorCheck(string $domain, bool $ssl=true, bool $verifySSL=true){
		
		if($ssl == false){
			$ch = curl_init('http://' . $domain);
		}else{
			$ch = curl_init('https://' . $domain);
		}

		if($verifySSL == false){
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		}

		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);

		$output = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if($httpcode == 520 || $httpcode == 521 || $httpcode == 522 || $httpcode == 523 || $httpcode == 524 || $httpcode == 525 || $httpcode == 526 || $httpcode == 527 || $httpcode == 530){

			return false;
		}else{
			return true;
		}
	}

	public function cloudflareDNSChangeARecord(string $email, string $apiKey, string $domain, string $newIP){

		$records = $dns->listRecords($zoneID, "A", $domain);
		$dns->deleteRecord($zoneID, $records->result[0]->id);

		if ($dns->addRecord($zoneID, "A", $domain, $newIP, 0, true) === true) {
	    	return true;
		}else{
			return false;
		}
	}
}
?>