<?php
include '../../config.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '../../vendor/autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '../../Cybersource/ExternalConfiguration.php';

function CreateSearchRequest($orderNum)
{
    $requestObjArr = [
        "save" => false,
        "name" => "MRN",
        "timezone" => "America/Chicago",
        "query" => "clientReferenceInformation.code:$orderNum",
        "offset" => 0,
        "limit" => 100,
        "sort" => "id:asc,submitTimeUtc:asc",
    ];
    $requestObj = new CyberSource\Model\CreateSearchRequest($requestObjArr);

    $commonElement = new CyberSource\ExternalConfiguration();
    $config = $commonElement->ConnectionHost();
    $merchantConfig = $commonElement->merchantConfigObject();

    $api_client = new CyberSource\ApiClient($config, $merchantConfig);
    $api_instance = new CyberSource\Api\SearchTransactionsApi($api_client);

    try {
        $apiResponse = $api_instance->createSearch($requestObj);
        // print_r(PHP_EOL);
        // print_r($apiResponse);

        return $apiResponse;
    } catch (Cybersource\ApiException $e) {
        // print_r($e->getResponseBody());
        // print_r($e->getMessage());
    }
}
$orderNum = '3S010-22040146';
$result = CreateSearchRequest($orderNum);
print_r($result);

include $conf_php . 'common_end.php';
