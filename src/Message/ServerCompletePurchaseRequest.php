<?php

declare(strict_types=1);

namespace Paytic\Payments\Stripe\Message;

use Omnipay\Common\Message\AbstractRequest as CommonAbstractRequest;

class ServerCompletePurchaseRequest extends CommonAbstractRequest
{
    public function getData()
    {
        // TODO: Implement getData() method.
    }

    public function sendData($data)
    {
        // TODO: Implement sendData() method.
    }

    public function isValidNotification()
    {
        return false;
    }
}
