<?php
namespace Failover\Addons;

public $components;
public $response;

/**
 * Statuspage.io
 */

class Statuspage{

	function __construct(string $apiKey=null){

		$usualHeaders = [
			'Content-Type: application/json',
			'Authorization:' . $apiKey
		];
	
	}

	public function getAllComponents(string $pageId, string $apiKey){

		$ch = curl_init('https://api.statuspage.io/v1/pages/' . $pageId . '/components');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $usualHeaders);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$this->components = curl_exec($ch);

	}

	public function updateComponentStatus(string $pageId, string $componentId, string $apiKey, string $componentStatus){

		$patchData = '{"component":{"status":"' . $componentStatus . '"}}';

		$ch = curl_init('https://api.statuspage.io/v1/pages/' . $pageId . '/components/'. $componentId);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $usualHeaders);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $patchData);

		$this->response = curl_exec($ch);
	}
}
?>