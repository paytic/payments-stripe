<?php

require dirname(__DIR__) . '/tests/bootstrap.php';

$gateway = new \ByTIC\Payments\Stripe\Gateway();
$gateway->initialize(require TEST_FIXTURE_PATH . '/enviromentParams.php');

$parameters = require TEST_FIXTURE_PATH . '/requests/Purchase/baseRequest.php';

$parameters['returnUrl'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$parameters['returnUrl'] = str_replace('purchase', 'completePurchase', $parameters['returnUrl']);
$parameters['applicationFee'] = '5';
$accountId = getenv('STRIPE_CONNECTED_ID');
$parameters['on_behalf_of'] = $accountId;

$request = $gateway->purchase($parameters);
$response = $request->send();

// Send the Symfony HttpRedirectResponse
$response->send();
