<?php

declare(strict_types=1);

namespace Paytic\Payments\Stripe\Message\Traits;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\AbstractRequest as CommonAbstractRequest;

/**
 * Trait HasKeysTrait.
 */
trait HasKeysTrait
{
    /**
     * @return mixed
     */
    public function getPublicKey()
    {
        return $this->getParameter('publicKey');
    }

    /**
     * @return CommonAbstractRequest
     */
    public function setPublicKey($value)
    {
        return $this->setParameter('publicKey', $value);
    }

    /**
     * Get the gateway API Key (the "secret key").
     */
    public function getApiKey(): string
    {
        return $this->getParameter('apiKey');
    }

    /**
     * Set the gateway API Key.
     *
     * @return AbstractRequest provides a fluent interface
     */
    public function setApiKey($value): AbstractRequest
    {
        return $this->setParameter('apiKey', $value);
    }
}
