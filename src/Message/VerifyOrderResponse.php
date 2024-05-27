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
        return $this->getHttpStatus() === 200 && (int)$this->getResultCode() === 2;
    }

    /**
     * @inheritDoc
     */
    public function isCancelled()
    {
        return $this->getHttpStatus() === 200 && (int)$this->getResultCode() === 1;
    }

    public function getResultCode()
    {
        return $this->data['Status'] ?? null;
    }

    /**
     * A reference provided by the gateway to represent this transaction.
     */
    public function getTransactionReference(): ?string
    {
        return $this->data['RefNum'];
    }
}
