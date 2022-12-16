<?php

declare(strict_types=1);

namespace Paytic\Payments\Stripe\Message;

use Paytic\Payments\Gateways\Providers\AbstractGateway\Message\Traits\HasGatewayRequestTrait;
use Paytic\Payments\Gateways\Providers\AbstractGateway\Message\Traits\HasModelRequest;
use Paytic\Omnipay\Common\Message\Traits\GatewayNotificationRequestTrait;
use Paytic\Omnipay\Common\Message\Traits\SendDataRequestTrait;
use Paytic\Payments\Stripe\Gateway;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;
use Stripe\Stripe;

/**
 * Class PurchaseResponse.
 *
 * @method CompletePurchaseResponse send()
 */
class CompletePurchaseRequest extends AbstractCheckoutRequest
{
    use GatewayNotificationRequestTrait {
        getData as getDataNotificationTrait;
    }
    use HasGatewayRequestTrait;
    use HasModelRequest;
    use SendDataRequestTrait;
    use Traits\HasKeysTrait;

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        $return = $this->getDataNotificationTrait();
        // Add model only if has data
        if (\count($return)) {
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
     *
     * @throws \Exception
     */
    protected function parseNotification()
    {
        if ($this->validateModel()) {
            $model = $this->getModel();
            $this->updateParametersFromPurchase($model);
        }

        // Retrieve the session that would have been started earlier.
        Stripe::setApiKey($this->getApiKey());

        $session = Session::retrieve($this->httpRequest->query->get('stpsid'));
        $paymentIntent = PaymentIntent::retrieve($session->payment_intent);

        return [
            'session' => $session,
            'paymentIntent' => $paymentIntent,
        ];
    }

    /**
     * @param Gateway $gateway
     */
    protected function updateParametersFromGateway($gateway)
    {
        $this->setPublicKey($gateway->getPublicKey());
        $this->setApiKey($gateway->getApiKey());
    }
}
