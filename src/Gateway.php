<?php

namespace Omnipay\Saman;

use Omnipay\Common\AbstractGateway;
use Omnipay\Saman\Message\CreateTokenRequest;
use Omnipay\Saman\Message\RefundOrderRequest;
use Omnipay\Saman\Message\VerifyOrderRequest;

/**
 * @method \Omnipay\Common\Message\NotificationInterface acceptNotification(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface authorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface capture(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface purchase(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface completePurchase(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface refund(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface fetchTransaction(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface void(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
 */
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
            'returnUrl' => '',
            'currency' => 'IRT', // either IRT or IRR
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

//    /**
//     * @return string
//     */
//    public function getApiKey(): ?string
//    {
//        return $this->getParameter('apiKey');
//    }

    /**
     * @return string
     */
    public function getReturnUrl(): ?string
    {
        return $this->getParameter('returnUrl');
    }


    /**
     * @param string $value
     * @return self
     */
    public function setReturnUrl(string $value): self
    {
        return $this->setParameter('returnUrl', $value);
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