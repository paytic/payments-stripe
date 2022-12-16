<?php

declare(strict_types=1);

require dirname(__DIR__) . '/tests/bootstrap.php';

$gateway = new \Paytic\Payments\Stripe\Gateway();
$gateway->initialize(require TEST_FIXTURE_PATH . '/enviromentParams.php');

$request = $gateway->completePurchase();
$response = $request->send();

$response->send();
