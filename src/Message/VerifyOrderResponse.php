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
        return $this->getHttpStatus() === 200
            && (int)$this->getResultCode() >= 0
            && (int)$this->getAffectiveAmount() === (int)$this->getOriginalAmount();
    }

    /**
     * @inheritDoc
     */
    public function isCancelled()
    {
        return $this->getHttpStatus() === 200 && (int)$this->getResultCode() !== 0;
    }

    /**
     * A reference provided by the gateway to represent this transaction.
     */
    public function getTransactionReference(): ?string
    {
        return $this->data['RefNum'];
    }

    /**
     * The original amount
     */
    public function getOriginalAmount(): ?string
    {
        // NOTE: "OrginalAmount" has a typo! this is exactly the same parameter which saman ipg returns!!
        return $this->data['OrginalAmount'];
    }

    /**
     * The actual paid amount
     */
    public function getAffectiveAmount(): ?string
    {
        return $this->data['AffectiveAmount'];
    }
}
