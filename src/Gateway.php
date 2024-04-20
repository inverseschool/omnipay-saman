<?php

namespace Omnipay\Saman;

use Omnipay\Common\AbstractGateway;
use Omnipay\Saman\Message\CreateTokenRequest;
use Omnipay\Saman\Message\RefundOrderRequest;
use Omnipay\Saman\Message\VerifyOrderRequest;

class Gateway extends  AbstractGateway
{

    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     * @return string
     */
    public function getName(): string
    {
        return 'Saman';
    }

    /**
     * @return array
     */
    public function getDefaultParameters(): array
    {
        return [
            'testMode' => false,
            'TerminalId' => '',
            'RedirectUrl' => '',
        ];
    }

    /**
     * @inheritDoc
     */
    public function initialize(array $parameters = [])
    {
        parent::initialize($parameters);
        return $this;
    }

    /**
     * @return string
     */
    public function getRedirectUrl(): ?string
    {
        return $this->getParameter('RedirectUrl');
    }

    /**
     * @param string $value
     * @return self
     */
    public function setRedirectUrl(string $value): self
    {
        return $this->setParameter('RedirectUrl', $value);
    }

    public function setTerminalId(string $value){
        return $this->setParameter('TerminalId', $value);
    }

    public function getTerminalId(){
        return $this->getParameter('TerminalId');
    }


    /**
     * @inheritDoc
     */
    public function purchase(array $options = [])
    {
        return $this->createRequest(CreateTokenRequest::class, $options);
    }

    /**
     * @inheritDoc
     */
    public function completePurchase(array $options = [])
    {
        return $this->createRequest(VerifyOrderRequest::class, $options);
    }

    /**
     * @inheritDoc
     */
    public function refund(array $options = [])
    {
        return $this->createRequest(RefundOrderRequest::class, $options);
    }
}