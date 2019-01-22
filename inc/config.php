<?php
$cloudflareDetails = [
	'email' => '', // Enter the e-mail associated with your Cloudflare account.
	'apikey' => '', // Enter the API key associated with your Cloudflare account.
	'domain' => '' // Enter the domain you'd like to swap out the zone for.
];

$ips = [
	'original' => '', // Enter the original IP. Currently not used, will be used at a later revision.
	'backup' => '' // Enter the IP of your backup server.
	];

$opts = [
	'ssl' => true, // SSL Setting, pretty self-explanatory
	'sslVerify' => true
	];
?>