<?php

namespace Paytic\Payments\Stripe\Message;

use ByTIC\Omnipay\Common\Message\Traits\HasViewTrait;
use Paytic\Payments\Stripe\Utility\Paths;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;
use Stripe\Checkout\Session;

/**
 * Class PurchaseResponse
 * @package Paytic\Payments\Stripe\Message
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    use HasViewTrait;

    /**
     * @var Session|null $session
     */
    private $session = null;

    /**
     * @inheritDoc
     */
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        if (isset($data['session']) && $data['session'] instanceof Session) {
            $this->setSession($data['session']);
        } else {
            throw new \InvalidArgumentException('A valid Session must be supplied');
        }
    }

    /**
     * @param Session $session
     */
    public function setSession(Session $session)
    {
        $this->session = $session;
    }

    public function getSessionID()
    {
        return $this->session ? $this->session->id : null;
    }


    protected function initViewVars()
    {
        $data = $this->getData();
        $data['session_id'] = $this->getSessionID();
        $data['session_id'] = $this->getSessionID();
        $this->getView()->with($data);
    }

    /**
     * @inheritDoc
     */
    protected function generateViewPath()
    {
        return Paths::viewsPath();
    }

    /**
     * @return string
     */
    protected function getViewFile()
    {
        return 'purchase';
    }

    public function isSuccessful()
    {
        return true;
    }
}
