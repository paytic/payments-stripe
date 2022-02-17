<?php

namespace Paytic\Payments\Stripe\Message;

use Paytic\Omnipay\Common\Message\Traits\HtmlResponses\ConfirmHtmlTrait;
use Omnipay\Common\Message\AbstractResponse;
use ByTIC\Payments\Gateways\Providers\AbstractGateway\Message\Traits\CompletePurchaseResponseTrait;
use Stripe\PaymentIntent;

/**
 * Class CompletePurchaseResponse
 * @package ByTIC\Payments\Gateways\Providers\Paylike\Message
 */
class CompletePurchaseResponse extends AbstractResponse
{
    use ConfirmHtmlTrait;
    use CompletePurchaseResponseTrait;

    protected bool $successful = false;

    protected ?string $code = null;

    protected ?string $message = null;

    /**
     * @inheritDoc
     */
    public function __construct(CompletePurchaseRequest $request, $data)
    {
        parent::__construct($request, $data);

        if (isset($data['notification']['paymentIntent'])) {
            /** @var PaymentIntent $paymentIntent */
            $paymentIntent = $data['notification']['paymentIntent'];

            $this->internalTransactionRef = $paymentIntent->id;

            // Amazingly there doesn't seem to be a simple code nor message when the payment succeeds (or fails).
            // For now, just use the status for the message, and leave the code blank.
            switch ($paymentIntent->status) {
                case PaymentIntent::STATUS_SUCCEEDED:
                    $this->successful = true;
                    $this->message = $paymentIntent->status;
                    break;
                case PaymentIntent::STATUS_CANCELED:
                    $this->successful = false;
                    $this->message = 'Canceled by customer';
                    break;
                default:
                    // We don't know what happened, so act accordingly. Would be nice to make this better over time.
                    $this->successful = false;
                    $this->message = 'Unknown error';
                    break;

            }
        } else {
            $this->successful = false; // Just make sure.
            $this->message = 'Could not retrieve payment';
        }
    }

    public function isSuccessful(): bool
    {
        return $this->successful;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getCode()
    {
        return $this->code;
    }


    /** @noinspection PhpMissingParentCallCommonInspection
     * @return bool
     */
    protected function canProcessModel()
    {
        return true;
    }

    /**
     * @return array
     */
    public function getSessionDebug(): array
    {
        $notification = $this->getDataProperty('notification');
        return [
            'session' => $notification['session']->toArray(),
            'paymentIntent' => $notification['paymentIntent']->toArray()
        ];
    }
}
