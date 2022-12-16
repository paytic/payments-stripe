<?php

declare(strict_types=1);

namespace Paytic\Payments\Stripe\Tests\Message;

use Mockery\Mock;
use Paytic\Payments\Stripe\Message\CompletePurchaseRequest;
use Paytic\Payments\Stripe\Message\CompletePurchaseResponse;
use Paytic\Payments\Stripe\Tests\AbstractTest;
use Paytic\Payments\Tests\Fixtures\Records\Purchases\PurchasableRecord;
use Paytic\Payments\Tests\Fixtures\Records\Purchases\PurchasableRecordManager;

/**
 * Class CompletePurchaseRequestTest.
 */
class CompletePurchaseRequestTest extends AbstractTest
{
    public function testSendData()
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
