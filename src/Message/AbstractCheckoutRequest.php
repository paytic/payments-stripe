<?php

namespace ByTIC\Payments\Stripe\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\AbstractRequest as CommonAbstractRequest;

/**
 * Class AbstractCheckoutRequest
 * @package ByTIC\Payments\Stripe\Message
 */
abstract class AbstractCheckoutRequest extends AbstractRequest
{
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

    /**
     * Get the gateway API Key (the "secret key").
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->getParameter('apiKey');
    }

    /**
     * Set the gateway API Key.
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setApiKey($value): AbstractRequest
    {
        return $this->setParameter('apiKey', $value);
    }
}
