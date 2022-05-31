<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '../../vendor/autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '../../Resources/ExternalConfiguration.php';

function CreateSearchRequest()
{
	$requestObjArr = [
			"save" => false,
			"name" => "MRN",
			"timezone" => "America/Chicago",
			"query" => "clientReferenceInformation.code:3S010-22040146",
			"offset" => 0,
			"limit" => 100,
			"sort" => "id:asc,submitTimeUtc:asc"
	];
	$requestObj = new CyberSource\Model\CreateSearchRequest($requestObjArr);


	$commonElement = new CyberSource\ExternalConfiguration();
	$config = $commonElement->ConnectionHost();
	$merchantConfig = $commonElement->merchantConfigObject();

	$api_client = new CyberSource\ApiClient($config, $merchantConfig);
	$api_instance = new CyberSource\Api\SearchTransactionsApi($api_client);

	try {
		$apiResponse = $api_instance->createSearch($requestObj);
		print_r(PHP_EOL);
		print_r($apiResponse);

		return $apiResponse;
	} catch (Cybersource\ApiException $e) {
		print_r($e->getResponseBody());
		print_r($e->getMessage());
	}
}

if(!defined('DO_NOT_RUN_SAMPLES')){
	echo "\nCreateSearchRequest Sample Code is Running..." . PHP_EOL;
	CreateSearchRequest();
}
?>
