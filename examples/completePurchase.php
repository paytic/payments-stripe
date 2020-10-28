<?php

require dirname(__DIR__) . '/tests/bootstrap.php';

$gateway = new \ByTIC\Payments\Stripe\Gateway();
$gateway->initialize(require TEST_FIXTURE_PATH . '/enviromentParams.php');

$request = $gateway->completePurchase();
$response = $request->send();

$response->send();
