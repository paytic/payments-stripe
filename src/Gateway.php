<?php

namespace Paytic\Payments\Stripe;

use ByTIC\Payments\Gateways\Providers\AbstractGateway\Traits\GatewayTrait;
use ByTIC\Payments\Gateways\Providers\AbstractGateway\Traits\OverwriteServerCompletePurchaseTrait;
use Omnipay\Common\Message\NotificationInterface;
use Paytic\Payments\Stripe\Message\PurchaseRequest;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Stripe\PaymentIntentsGateway as AbstractGateway;

/**
 * Class Gateway
 * @package Paytic\Payments\Stripe
 * @method NotificationInterface acceptNotification(array $options = array())
 */
class Gateway extends AbstractGateway
{
    use GatewayTrait;
    use OverwriteServerCompletePurchaseTrait;

    /**
     * @param array $parameters
     * @return PurchaseRequest|RequestInterface
     */
    public function purchase(array $parameters = []): RequestInterface
    {
        return $this->createRequestWithInternalCheck('purchase', $parameters);
    }

    /**
     * @inheritDoc
     */
    public function setSandbox($value): self
    {
        return $this->setTestMode($value == 'yes');
    }

    /**
     * @inheritDoc
     */
    public function getSandbox(): string
    {
        return $this->getTestMode() === true ? 'yes' : 'no';
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        if (strlen($this->getApiKey()) >= 5) {
            return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getPublicKey()
    {
        return $this->getParameter('publicKey');
    }

    /**
     * @param $value
     * @return self
     */
    public function setPublicKey(string $value): self
    {
        return $this->setParameter('publicKey', $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultParameters(): array
    {
        return [
            'testMode' => true, // Must be the 1st in the list!
            'publicKey' => $this->getPublicKey(),
            'apiKey' => $this->getApiKey(),
        ];
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement @method \Omnipay\Common\Message\NotificationInterface acceptNotification(array $options = array())
    }
}
