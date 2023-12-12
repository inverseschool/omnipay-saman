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
            isset($this->data['trans_id']) &&
            !empty($this->data['trans_id']);
    }

    /**
     * @inheritDoc
     */
    public function getRedirectUrl()
    {
        /** @var CreateTokenRequest $request */
        $request = $this->request;
        return sprintf('%s/payment/%s', $request->getEndpoint(), $this->getTransactionReference());
    }

    /**
     * @inheritDoc
     */
    public function getTransactionId()
    {
        /** @var CreateTokenRequest $request */
        $request = $this->request;
        return $request->getTransactionId();
    }

    /**
     * @inheritDoc
     */
    public function getTransactionReference()
    {
        return $this->data['trans_id'];
    }
}
