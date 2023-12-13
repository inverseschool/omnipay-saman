<?php

namespace Omnipay\Saman\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Class InquiryOrderRequest
 */
class RefundOrderRequest extends AbstractRequest
{
    /**
     * @inheritDoc
     */
    protected function getHttpMethod()
    {
        return 'POST';
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     * @throws InvalidRequestException
     */
    public function getData()
    {
        // Validate required parameters before return data
        $this->validate('RefNum','TerminalNumber');

        return [
            'RefNum' => $this->getRefNum(),
            'TerminalNumber' => $this->getTerminalNumber(),
        ];
    }

    /**
     * @param string $endpoint
     * @return string
     */
    protected function createUri(string $endpoint)
    {
        return $endpoint . '/verifyTxnRandomSessionkey/ipg/ReverseTransaction';
    }

    /**
     * @param array $data
     * @return RefundOrderResponse
     */
    protected function createResponse(array $data)
    {
        return new RefundOrderResponse($this, $data);
    }
}
