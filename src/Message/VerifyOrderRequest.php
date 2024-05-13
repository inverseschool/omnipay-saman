<?php

namespace Omnipay\Saman\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Class VerifyOrderRequest
 */
class VerifyOrderRequest extends AbstractRequest
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
    public function getData():array
    {
        // Validate required parameters before return data
        $this->validate('transactionReference');

        return [
            'RefNum' => $this->getTransactionReference(),
            'TerminalNumber' => $this->getTerminalId(),
        ];
    }

    /**
     * @param string $endpoint
     * @return string
     */
    protected function createUri(string $endpoint)
    {
        return $endpoint . '/verifyTxnRandomSessionkey/ipg/VerifyTransaction';
    }

    /**
     * @param array $data
     * @return VerifyOrderResponse
     */
    protected function createResponse(array $data)
    {
        return new VerifyOrderResponse($this, $data);
    }
}
