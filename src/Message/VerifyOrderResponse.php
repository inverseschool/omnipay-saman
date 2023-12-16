<?php

namespace Omnipay\Saman\Message;

/**
 * Class VerifyOrderResponse
 */
class VerifyOrderResponse extends AbstractResponse
{

    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return $this->getHttpStatus() === 200 && (int)$this->getResultCode() == 0;
    }

    /**
     * @inheritDoc
     */
    public function isCancelled()
    {
        return $this->getHttpStatus() === 200 && (int)$this->getCode() !== 0;
    }

    /**
     * A reference provided by the gateway to represent this transaction.
     */
    public function getTransactionReference(): ?string
    {
        return $this->data['RefNum'];
    }
}
