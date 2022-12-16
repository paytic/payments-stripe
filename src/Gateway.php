<?php

declare(strict_types=1);

namespace Paytic\Payments\Stripe;

use Paytic\Payments\Gateways\Providers\AbstractGateway\Traits\GatewayTrait;
use Paytic\Payments\Gateways\Providers\AbstractGateway\Traits\OverwriteServerCompletePurchaseTrait;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Stripe\PaymentIntentsGateway as AbstractGateway;
use Paytic\Payments\Stripe\Message\PurchaseRequest;

/**
 * Class Gateway.
 *
 * @method NotificationInterface acceptNotification(array $options = array())
 */
class Gateway extends AbstractGateway
{
    use GatewayTrait;
    use OverwriteServerCompletePurchaseTrait;

    /**
     * @return PurchaseRequest|RequestInterface
     */
    public function purchase(array $parameters = []): RequestInterface
    {
        return $this->createRequestWithInternalCheck('purchase', $parameters);
    }

    /**
     * {@inheritDoc}
     */
    public function setSandbox($value): self
    {
        return $this->setTestMode('yes' == $value);
    }

    /**
     * {@inheritDoc}
     */
    public function getSandbox(): string
    {
        return true === $this->getTestMode() ? 'yes' : 'no';
    }

    public function isActive(): bool
    {
        if (\strlen($this->getApiKey()) >= 5) {
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
