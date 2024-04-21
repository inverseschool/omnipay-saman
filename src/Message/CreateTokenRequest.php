<?php

namespace Omnipay\Saman\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Class CreateTokenRequest
 */
class CreateTokenRequest extends AbstractRequest
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
        $this->validate('TerminalId', 'Amount', 'currency', 'RedirectUrl');

        return [
            'action' =>"token",
            'TerminalId' => $this->getTerminalId(),
            'Amount' => $this->getAmount(),
            'ResNum' => $this->getResNum(),
            'RedirectUrl' => $this->getRedirectUrl(),
            'CellNumber' => $this->getCellNumber(),
            'currency' => $this->getCurrency(),
            'payer_name' => $this->getPayerName(),
            'payer_desc' => $this->getPayerDesc(),
            'auto_verify' => $this->getAutoVerify(),
            'allowed_card' => $this->getAllowedCard(),
        ];
    }

    /**
     * @param string $endpoint
     * @return string
     */
    protected function createUri(string $endpoint)
    {
        return $endpoint . '/onlinepg/onlinepg';
    }

    /**
     * @param array $data
     * @return CreateTokenResponse
     */
    protected function createResponse(array $data)
    {
        return new CreateTokenResponse($this, $data);
    }
}
