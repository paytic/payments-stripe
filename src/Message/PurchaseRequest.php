<?php

namespace ByTIC\Payments\Stripe\Message;

use Omnipay\Common\ItemBag;

/**
 * Class PurchaseResponse
 * @package ByTIC\Payments\Stripe\Message
 *
 * @method PurchaseResponse send()
 */
class PurchaseRequest extends \Omnipay\Stripe\Message\PaymentIntents\PurchaseRequest
{
    use Traits\HasKeysTrait;

    private function nullIfEmpty(string $value = null): ?string
    {
        return empty($value) ? null : $value;
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        // Just validate the parameters.
        $this->validate('apiKey', 'transactionId', 'returnUrl');

        if (empty($this->getCancelUrl())) {
            $this->setParameter('cancelUrl', $this->getReturnUrl());
        }

        $data = [
            'client_reference_id' => $this->getTransactionId(),
            'payment_method_types' => ['card'],
            'payment_intent_data' => [
                'description' => $this->getDescription(),
            ],
            'success_url' => $this->getReturnUrl(),
            'cancel_url' => $this->getCancelUrl(),
        ];

        if ($this->getApplicationFee()) {
            $data['payment_intent_data']['application_fee_amount'] = $this->getApplicationFeeInteger();
        }

        foreach (['success_url', 'cancel_url'] as $type) {
            $data[$type] .= (parse_url($data[$type], PHP_URL_QUERY) ? '&' : '?') . 'stpsid={CHECKOUT_SESSION_ID}';
        }

        $items = $this->getItems();
        if (!($items instanceof ItemBag)) {
            $items = new ItemBag(
                [
                    [
                        'prodid' => $this->getTransactionId(),
                        'name' => $this->getDescription(),
                        'description' => $this->getDescription(),
                        'quantity' => 1,
                        'currency' => $this->getCurrency(),
                        'price' => $this->getAmount()
                    ]
                ]
            );
        }

        $data['line_items'] = array_map(
            function (\Omnipay\Common\Item $item) {
                return [
                    'name' => $item->getName(),
                    'description' => $this->nullIfEmpty($item->getDescription()),
                    'amount' => (int)(100 * $item->getPrice()),
                    'currency' => $this->getCurrency(),
                    'quantity' => $item->getQuantity(),
                ];
            },
            array_values(
                array_filter(
                    $items->all(),
                    function (\Omnipay\Common\Item $item) {
                        return $item->getPrice() > 0;
                    }
                )
            )
        );

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function sendData($data): PurchaseResponse
    {
        // We use Stripe's SDK to initialise a (Stripe) session. The session gets passed through the process and is
        // used to identify this transaction.
        \Stripe\Stripe::setApiKey($this->getApiKey());

        $options = [];
        $sentData = [
            'publicKey' => $this->getPublicKey()
        ];
        if ($this->getOnBehalfOf()) {
            $options['stripe_account'] = $this->getOnBehalfOf();
            $sentData['stripe_account'] = $this->getOnBehalfOf();
        }
        // Initiate the session.
        $session = \Stripe\Checkout\Session::create($data, $options);
        $sentData['session'] = $session;
        return $this->response = new PurchaseResponse($this, $sentData);
    }
}
