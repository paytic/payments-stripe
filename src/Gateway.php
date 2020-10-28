<?php

namespace ByTIC\Payments\Stripe;

use ByTIC\Payments\Gateways\Providers\AbstractGateway\Traits\GatewayTrait;
use ByTIC\Payments\Gateways\Providers\AbstractGateway\Traits\OverwriteServerCompletePurchaseTrait;
use ByTIC\Payments\Stripe\Message\PurchaseRequest;
use Omnipay\Common\Message\AbstractRequest as CommonAbstractRequest;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Stripe\PaymentIntentsGateway as AbstractGateway;

/**
 * Class Gateway
 * @package ByTIC\Payments\Stripe
 */
class Gateway extends AbstractGateway
{
    use GatewayTrait;
    use OverwriteServerCompletePurchaseTrait;

    /**
     * @param array $parameters
     * @return PurchaseRequest
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
     * @return CommonAbstractRequest
     */
    public function setPublicKey($value)
    {
        return $this->setParameter('publicKey', $value);
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     *
     * {@inheritdoc}
     */
    public function getDefaultParameters()
    {
        return [
            'testMode' => true, // Must be the 1st in the list!
            'publicKey' => $this->getPublicKey(),
            'apiKey' => $this->getApiKey(),
        ];
    }
}
