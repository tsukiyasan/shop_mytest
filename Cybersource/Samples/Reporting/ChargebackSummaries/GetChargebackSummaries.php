<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '../../../vendor/autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '../../../Resources/ExternalConfiguration.php';

function GetChargebackSummaries()
{
    // QUERY PARAMETERS
    $organizationId = "testrest";
    $startTime = "2021-08-01T00:00:00Z";
    $endTime = "2021-09-01T23:59:59Z";

    $commonElement = new CyberSource\ExternalConfiguration();
    $config = $commonElement->ConnectionHost();
    $merchantConfig = $commonElement->merchantConfigObject();

    $apiClient = new CyberSource\ApiClient($config, $merchantConfig);
    $apiInstance = new CyberSource\Api\ChargebackSummariesApi($apiClient);

    try {
        $apiResponse = $apiInstance->getChargebackSummaries($startTime, $endTime, $organizationId);
        print_r(PHP_EOL);
        print_r($apiResponse);

        return $apiResponse;
    } catch (Cybersource\ApiException $e) {
        print_r($e->getResponseBody());
        print_r($e->getMessage());
    }
}

if (!defined('DO_NOT_RUN_SAMPLES')) {
    echo "\nGetChargebackSummaries Sample Code is Running..." . PHP_EOL;
    GetChargebackSummaries();
}