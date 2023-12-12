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
        return $this->getHttpStatus() === 200 && (int)$this->getCode() === -90;
    }
}
