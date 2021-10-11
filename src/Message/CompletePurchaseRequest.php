<?php

namespace Paytic\Payments\Stripe\Message;

use ByTIC\Omnipay\Common\Message\Traits\GatewayNotificationRequestTrait;
use ByTIC\Payments\Gateways\Providers\AbstractGateway\Message\Traits\HasGatewayRequestTrait;
use ByTIC\Payments\Gateways\Providers\AbstractGateway\Message\Traits\HasModelRequest;
use Paytic\Payments\Stripe\Gateway;

/**
 * Class PurchaseResponse
 * @package ByTIC\Payments\Gateways\Providers\Paylike\Message
 *
 * @method CompletePurchaseResponse send
 */
class CompletePurchaseRequest extends AbstractCheckoutRequest
{
    use Traits\HasKeysTrait;
    use \ByTIC\Omnipay\Common\Message\Traits\SendDataRequestTrait;
    use HasModelRequest;
    use GatewayNotificationRequestTrait {
        getData as getDataNotificationTrait;
    }
    use HasGatewayRequestTrait;

    /**
     * @inheritdoc
     */
    public function getData(): array
    {
        $return = $this->getDataNotificationTrait();
        // Add model only if has data
        if (count($return)) {
            $return['model'] = $this->getModel();
        }

        return $return;
    }
    /**
     * @return mixed
     */
    public function isValidNotification()
    {
        return $this->hasGet('stpsid');
    }

    /**
     * @return bool|mixed
     * @throws \Exception
     */
    protected function parseNotification()
    {
        if ($this->validateModel()) {
            $model = $this->getModel();
            $this->updateParametersFromPurchase($model);
        }

        // Retrieve the session that would have been started earlier.
        \Stripe\Stripe::setApiKey($this->getApiKey());

        $session = \Stripe\Checkout\Session::retrieve($this->httpRequest->query->get('stpsid'));
        $paymentIntent = \Stripe\PaymentIntent::retrieve($session->payment_intent);

        return [
            'session' => $session,
            'paymentIntent' => $paymentIntent
        ];
    }

    /**
     * @param Gateway $modelGateway
     */
    protected function updateParametersFromGateway(Gateway $modelGateway)
    {
        $this->setPublicKey($modelGateway->getPublicKey());
        $this->setApiKey($modelGateway->getApiKey());
    }
}
