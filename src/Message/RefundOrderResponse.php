<?php

namespace Omnipay\Saman\Message;

/**
 * Class InquiryOrderResponse
 */
class RefundOrderResponse extends AbstractResponse
{
    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return $this->getHttpStatus() === 200 && (int)$this->getResultCode() == 0;
    }
}
