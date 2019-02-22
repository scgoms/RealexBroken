<?php

use GlobalPayments\Api\ServicesConfig;
use GlobalPayments\Api\HostedPaymentConfig;
use GlobalPayments\Api\Services\HostedService;
use GlobalPayments\Api\Entities\Enums\HppVersion;
use GlobalPayments\Api\Entities\Exceptions\ApiException;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/realex', function(){
    $config = new ServicesConfig();
    $config->merchantId = "kodal";
    $config->accountId = "internet";
    $config->sharedSecret = "secret";
    $config->serviceUrl = "https://pay.sandbox.realexpayments.com/pay";
    $config->hostedPaymentConfig = new HostedPaymentConfig();
    $config->hostedPaymentConfig->version = HppVersion::VERSION_2;
    $service = new HostedService($config);

    try {
        $hppJson = $service->charge(1999)
            ->withCurrency("EUR")
            ->serialize();

        /*
         * TODO: pass the HPP JSON to the client-side
         */
        return $hppJson;
    } catch (ApiException $e) {
        // TODO: Add your error handling here
    }
});

Route::post('/realexReturn', function(){
    // configure client settings
    $config = new ServicesConfig();
    $config->merchantId = "kodal";
    $config->accountId = "internet";
    $config->sharedSecret = "secret";
    $config->serviceUrl = "https://pay.sandbox.realexpayments.com/pay";

    $service = new HostedService($config);
    $responseJson = Request::input('hppResponse');

    /*
     * TODO: grab the response JSON from the client-side.
     * sample response JSON (values will be Base64 encoded):
     * $responseJson ='{"MERCHANT_ID":"MerchantId","ACCOUNT":"internet","ORDER_ID":"GTI5Yxb0SumL_TkDMCAxQA","AMOUNT":"1999",' .
     * '"TIMESTAMP":"20170725154824","SHA1HASH":"843680654f377bfa845387fdbace35acc9d95778","RESULT":"00","AUTHCODE":"12345",' .
     * '"CARD_PAYMENT_BUTTON":"Place Order","AVSADDRESSRESULT":"M","AVSPOSTCODERESULT":"M","BATCHID":"445196",' .
     * '"MESSAGE":"[ test system ] Authorised","PASREF":"15011597872195765","CVNRESULT":"M","HPP_FRAUDFILTER_RESULT":"PASS"}";
     */

    try {
        // create the response object from the response JSON
        $parsedResponse = $service->parseResponse($responseJson);

        $orderId = $parsedResponse->orderId; // GTI5Yxb0SumL_TkDMCAxQA
        $responseCode = $parsedResponse->responseCode; // 00
        $responseMessage = $parsedResponse->responseMessage; // [ test system ] Authorised
        $responseValues = $parsedResponse->responseValues; // get values accessible by key
        dd('Success');
    } catch (ApiException $e) {
        dd($e);
        // For example if the SHA1HASH doesn't match what is expected
        // TODO: add your error handling here
    }
});
