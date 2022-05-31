<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '../../../vendor/autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '../../../Resources/ExternalConfiguration.php';

function CreditUsingBluefinPCIP2PEForCardPresentEnabledAcquirer()
{
	$clientReferenceInformationArr = [
			"code" => "demomerchant"
	];
	$clientReferenceInformation = new CyberSource\Model\Ptsv2paymentsClientReferenceInformation($clientReferenceInformationArr);

	$processingInformationArr = [
			"commerceIndicator" => "retail"
	];
	$processingInformation = new CyberSource\Model\Ptsv2creditsProcessingInformation($processingInformationArr);

	$paymentInformationCardArr = [
			"expirationMonth" => "12",
			"expirationYear" => "2050"
	];
	$paymentInformationCard = new CyberSource\Model\Ptsv2paymentsidrefundsPaymentInformationCard($paymentInformationCardArr);

	$paymentInformationFluidDataArr = [
			"descriptor" => "Ymx1ZWZpbg==",
			"value" => "02d700801f3c20008383252a363031312a2a2a2a2a2a2a2a303030395e46444d53202020202020202020202020202020202020202020205e323231322a2a2a2a2a2a2a2a3f2a3b363031312a2a2a2a2a2a2a2a303030393d323231322a2a2a2a2a2a2a2a3f2a7a75ad15d25217290c54b3d9d1c3868602136c68d339d52d98423391f3e631511d548fff08b414feac9ff6c6dede8fb09bae870e4e32f6f462d6a75fa0a178c3bd18d0d3ade21bc7a0ea687a2eef64551751e502d97cb98dc53ea55162cdfa395431323439323830303762994901000001a000731a8003"
	];
	$paymentInformationFluidData = new CyberSource\Model\Ptsv2paymentsPaymentInformationFluidData($paymentInformationFluidDataArr);

	$paymentInformationArr = [
			"card" => $paymentInformationCard,
			"fluidData" => $paymentInformationFluidData
	];
	$paymentInformation = new CyberSource\Model\Ptsv2paymentsidrefundsPaymentInformation($paymentInformationArr);

	$orderInformationAmountDetailsArr = [
			"totalAmount" => "100.00",
			"currency" => "USD"
	];
	$orderInformationAmountDetails = new CyberSource\Model\Ptsv2paymentsidcapturesOrderInformationAmountDetails($orderInformationAmountDetailsArr);

	$orderInformationBillToArr = [
			"firstName" => "John",
			"lastName" => "Deo",
			"address1" => "201 S. Division St.",
			"locality" => "Ann Arbor",
			"administrativeArea" => "MI",
			"postalCode" => "48104-2201",
			"country" => "US",
			"email" => "test@cybs.com",
			"phoneNumber" => "999999999"
	];
	$orderInformationBillTo = new CyberSource\Model\Ptsv2paymentsidcapturesOrderInformationBillTo($orderInformationBillToArr);

	$orderInformationArr = [
			"amountDetails" => $orderInformationAmountDetails,
			"billTo" => $orderInformationBillTo
	];
	$orderInformation = new CyberSource\Model\Ptsv2paymentsidrefundsOrderInformation($orderInformationArr);

	$pointOfSaleInformationArr = [
			"catLevel" => 1,
			"entryMode" => "keyed",
			"terminalCapability" => 2
	];
	$pointOfSaleInformation = new CyberSource\Model\Ptsv2paymentsPointOfSaleInformation($pointOfSaleInformationArr);

	$requestObjArr = [
			"clientReferenceInformation" => $clientReferenceInformation,
			"processingInformation" => $processingInformation,
			"paymentInformation" => $paymentInformation,
			"orderInformation" => $orderInformation,
			"pointOfSaleInformation" => $pointOfSaleInformation
	];
	$requestObj = new CyberSource\Model\CreateCreditRequest($requestObjArr);


	$commonElement = new CyberSource\ExternalConfiguration();
	$config = $commonElement->ConnectionHost();
	$merchantConfig = $commonElement->merchantConfigObject();

	$api_client = new CyberSource\ApiClient($config, $merchantConfig);
	$api_instance = new CyberSource\Api\CreditApi($api_client);

	try {
		$apiResponse = $api_instance->createCredit($requestObj);
		print_r(PHP_EOL);
		print_r($apiResponse);

		return $apiResponse;
	} catch (Cybersource\ApiException $e) {
		print_r($e->getResponseBody());
		print_r($e->getMessage());
	}
}

if(!defined('DO_NOT_RUN_SAMPLES')){
	echo "\nCreditUsingBluefinPCIP2PEForCardPresentEnabledAcquirer Sample Code is Running..." . PHP_EOL;
	CreditUsingBluefinPCIP2PEForCardPresentEnabledAcquirer();
}
?>
