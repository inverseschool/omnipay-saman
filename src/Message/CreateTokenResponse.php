<?php


namespace Omnipay\Saman\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Class CreateOrderResponse
 */
class CreateTokenResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * @return bool
     */
    public function isSuccessful()
    {

        return (int)$this->getHttpStatus() === 200 && (int)$this->getCode() === 1;
    }

    /**
     * @return bool
     */
    public function isRedirect()
    {
        return (int)$this->getCode() === 1 &&
            isset($this->data['token']) &&
            !empty($this->data['token']);
    }

    /**
     * @inheritDoc
     */
    public function getRedirectUrl()
    {
        /** @var CreateTokenRequest $request */
        $request = $this->request;
        return sprintf('%s/OnlinePG/SendToken?token=%s', $request->getEndpoint(), $this->getTransactionReference());
    }


    public function getTransactionReference(){
        return $this->data['token'];
    }
}
