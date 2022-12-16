<?php

declare(strict_types=1);

namespace Paytic\Payments\Stripe\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;
use Paytic\Omnipay\Common\Message\Traits\HasViewTrait;
use Paytic\Payments\Stripe\Utility\Paths;
use Stripe\Checkout\Session;

/**
 * Class PurchaseResponse.
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    use HasViewTrait;

    /**
     * @var Session|null
     */
    private $session = null;

    /**
     * {@inheritDoc}
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

    public function setSession(Session $session)
    {
        $this->session = $session;
    }

    public function getSessionID(): ?string
    {
        return $this->session ? $this->session->id : null;
    }

    protected function initViewVars()
    {
        $data = $this->getData();
        $data['session_id'] = $this->getSessionID();
        $this->getView()->with($data);
    }

    /**
     * {@inheritDoc}
     */
    protected function generateViewPath(): string
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
