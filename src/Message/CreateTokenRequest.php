<?php

namespace Omnipay\Saman\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Class CreateOrderRequest
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
    public function getData()
    {
        // Validate required parameters before return data
        $this->validate('TerminalId', 'Amount', 'currency', 'returnUrl');


//        ‫‪"action":"token",‬‬
//        ‫‪"TerminalId":"0000",‬‬
//        ‫‪"Amount":12000,‬‬
//        ‫‪"ResNum":"1qaz@WSX",‬‬
//        ‫‪"RedirectUrl":"http://mysite.com/receipt",‬‬
//        ‫"‪"CellNumber":"9120000000‬‬


        return [
            'TerminalId' => $this->getTerminalId(),
            'Amount' => $this->getAmount(),
            'ResNum' => $this->getOrderId(),
            'RedirectUrl' => $this->getReturnUrl(),
            'CellNumber' => $this->getCustomerPhone(),

//            'custom_json_fields' => $this->getMeta(),
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
