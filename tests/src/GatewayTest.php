<?php

namespace ByTIC\Payments\Stripe\Tests;

/**
 * Class GatewayTest
 * @package ByTIC\Payments\Stripe\Tests
 */
class GatewayTest extends AbstractTest
{
    public function test_purchase_redirect()
    {
        $gateway = new \ByTIC\Payments\Stripe\Gateway();
        $gateway->initialize(require TEST_FIXTURE_PATH . '/enviromentParams.php');

        $parameters = require TEST_FIXTURE_PATH . '/requests/Purchase/baseRequest.php';

        $parameters['returnUrl'] = 'http://test.com';
        $request = $gateway->purchase($parameters);
        $response = $request->send();

        $sessionId = $response->getSessionID();
        $content = $response->getViewContent();
        self::assertContains('stripe.redirectToCheckout({ sessionId:', $content);
        self::assertContains($sessionId, $content);
    }
}
