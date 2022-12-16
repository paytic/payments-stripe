<?php

declare(strict_types=1);

namespace Paytic\Payments\Stripe\Tests;

use Omnipay\Common\Message\AbstractRequest;
use Paytic\Payments\Stripe\Gateway;
use Paytic\Payments\Stripe\Message\PurchaseResponse;

/**
 * Class GatewayTest.
 */
class GatewayTest extends AbstractTest
{
    public function testPurchaseRedirect()
    {
        $gateway = new Gateway();
        $gateway->initialize(require TEST_FIXTURE_PATH . '/enviromentParams.php');

        $parameters = require TEST_FIXTURE_PATH . '/requests/Purchase/baseRequest.php';

        $parameters['returnUrl'] = 'http://test.com';

        $request = $gateway->purchase($parameters);

        /** @var PurchaseResponse $response */
        $response = $request->send();

        $sessionId = $response->getSessionID();
        $content = $response->getViewContent();
        self::assertStringContainsString('stripe.redirectToCheckout({', $content);
        self::assertStringContainsString('sessionId:', $content);
        self::assertStringContainsString($sessionId, $content);
    }

    public function testPurchaseConnectedAccount()
    {
        $gateway = new Gateway();
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
        self::assertStringContainsString('stripe.redirectToCheckout({', $content);
        self::assertStringContainsString('sessionId:', $content);
        self::assertStringContainsString($sessionId, $content);
    }

    public function testServerCompletePurchase()
    {
        $gateway = new Gateway();
        $gateway->initialize(require TEST_FIXTURE_PATH . '/enviromentParams.php');

        $request = $gateway->serverCompletePurchase();
        self::assertInstanceOf(AbstractRequest::class, $request);
    }
}
