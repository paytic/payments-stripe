<?php

namespace Paytic\Payments\Stripe\Tests;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Class GatewayTest
 * @package Paytic\Payments\Stripe\Tests
 */
class GatewayTest extends AbstractTest
{
    public function test_purchase_redirect()
    {
        $gateway = new \Paytic\Payments\Stripe\Gateway();
        $gateway->initialize(require TEST_FIXTURE_PATH . '/enviromentParams.php');

        $parameters = require TEST_FIXTURE_PATH . '/requests/Purchase/baseRequest.php';

        $parameters['returnUrl'] = 'http://test.com';
        $request = $gateway->purchase($parameters);
        $response = $request->send();

        $sessionId = $response->getSessionID();
        $content = $response->getViewContent();
        self::assertContains('stripe.redirectToCheckout({', $content);
        self::assertContains('sessionId:', $content);
        self::assertContains($sessionId, $content);
    }

    public function test_purchase_connected_account()
    {
        $gateway = new \Paytic\Payments\Stripe\Gateway();
        $gateway->initialize(require TEST_FIXTURE_PATH . '/enviromentParams.php');

        $parameters = require TEST_FIXTURE_PATH . '/requests/Purchase/baseRequest.php';
        $parameters['returnUrl'] = 'http://test.com';

        $parameters['application_fee_amount'] = '3.0';
        $accountId = getenv('STRIPE_CONNECTED_ID');
        $parameters['on_behalf_of'] = $accountId;

        $request = $gateway->purchase($parameters);
        $response = $request->send();

        $sessionId = $response->getSessionID();
        $content = $response->getViewContent();
        self::assertContains('stripe.redirectToCheckout({', $content);
        self::assertContains('sessionId:', $content);
        self::assertContains($sessionId, $content);
    }

    public function test_serverCompletePurchase()
    {
        $gateway = new \Paytic\Payments\Stripe\Gateway();
        $gateway->initialize(require TEST_FIXTURE_PATH . '/enviromentParams.php');

        $request = $gateway->serverCompletePurchase();
        self::assertInstanceOf(AbstractRequest::class, $request);
    }
}
