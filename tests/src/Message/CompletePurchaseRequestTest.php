<?php

namespace ByTIC\Payments\Stripe\Tests\Message;

use ByTIC\Payments\Stripe\Message\CompletePurchaseRequest;
use ByTIC\Payments\Stripe\Message\CompletePurchaseResponse;
use ByTIC\Payments\Stripe\Tests\AbstractTest;
use ByTIC\Payments\Tests\Fixtures\Records\Purchases\PurchasableRecord;
use ByTIC\Payments\Tests\Fixtures\Records\Purchases\PurchasableRecordManager;
use Mockery\Mock;

/**
 * Class CompletePurchaseRequestTest
 * @package ByTIC\Payments\Stripe\Tests\Message
 */
class CompletePurchaseRequestTest extends AbstractTest
{
    public function test_sendData()
    {
        $httpRequest = $this->generateRequestFromFixtures(
            TEST_FIXTURE_PATH . '/requests/completePurchase/basicParams.php'
        );

        $model = \Mockery::mock(PurchasableRecord::class)->makePartial();
        $model->shouldReceive('getPaymentGateway')->andReturn(null);

        $modelManager = \Mockery::mock(PurchasableRecordManager::class)->makePartial();
        $modelManager->shouldReceive('findOne')->andReturn($model);

        /** @var Mock|CompletePurchaseRequest $request */
        $request = \Mockery::mock(CompletePurchaseRequest::class)->shouldAllowMockingProtectedMethods()->makePartial();
        $request->__construct($this->client, $httpRequest);
        $request->shouldReceive('updateParametersFromPurchase')->once();
        $request->shouldReceive('parseNotification')->once();
        $request->shouldReceive('getResponseClass')->andReturn(CompletePurchaseResponse::class);
        
        $request->setModelManager($modelManager);

        $response = $request->send();
        self::assertInstanceOf(CompletePurchaseResponse::class, $response);
    }
}
