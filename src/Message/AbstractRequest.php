<?php



namespace Omnipay\Saman\Message;

use Exception;
use RuntimeException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\ResponseInterface;

/**
 * Class AbstractRequest
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * Live Endpoint URL
     *
     * @var string URL
     */
    protected string $liveEndpoint = 'https://sep.shaparak.ir';

    /**
     * @return string
     */
    abstract protected function getHttpMethod();

    /**
     * @param string $endpoint
     * @return string
     */
    abstract protected function createUri(string $endpoint);

    /**
     * @param array $data
     * @return AbstractResponse
     */
    abstract protected function createResponse(array $data);


    public function setOrderId(int $value){
        return $this->setParameter('orderId', $value);
    }
    public function getOrderId(){
        return $this->getParameter('orderId');
    }

    public function getTerminalId(){
        return $this->getParameter('TerminalId');
    }

    /**
     * @return bool
     */
    public function getAutoVerify(): bool
    {
        return (bool)$this->getParameter('autoVerify');
    }

    /**
     * @return string
     * @throws InvalidRequestException
     */
    public function getAmount(): string
    {
        $currency = $this->getCurrency();

        // a little hack to prevent error because of non-standard currency code!
        // only "IRR" is a standard iso code
        if ($currency !== 'IRR') {
            $this->setCurrency('IRR');
            $value = parent::getAmount();
            $this->setCurrency($currency);
        } else {
            $value = parent::getAmount();
        }

        $value = $value ?: $this->httpRequest->query->get('Amount');
        return (string)$value;
    }

    /**
     * @return string|null
     */
    public function getCustomerPhone(): ?string
    {
        return $this->getParameter('CellNumber');
    }

    /**
     * Get the request return URL.
     *
     * @return string
     */
    public function getReturnUrl()
    {
        return $this->getParameter('RedirectUrl');
    }

    /**
     * @return string|null
     */
    public function getPayerName(): ?string
    {
        return $this->getParameter('payerName');
    }

    /**
     * @return string|null
     */
    public function getPayerDesc(): ?string
    {
        return $this->getParameter('payerName');
    }

    /**
     * @return string|null
     */
    public function getAllowedCard(): ?string
    {
        return $this->getParameter('allowedCard');
    }

    /**
     * @param string $value
     * @return self
     */
    public function setTerminalId(string $value): self
    {
        return $this->setParameter('TerminalId', $value);
    }

    /**
     * @param string $value
     * @return self
     */
    public function setAmount($value): self
    {
        return $this->setParameter('Amount', $value);
    }


    /**
     * @param string $value
     * @return self
     */
    public function setReturnUrl($value): self
    {
        return $this->setParameter('RedirectUrl', $value);
    }




    /**
     * @param string $value
     * @return self
     */
    public function setApiKey(string $value): self
    {
        return $this->setParameter('apiKey', $value);
    }

    /**
     * @param bool $autoVerify
     * @return self
     */
    public function setAutoVerify(bool $autoVerify): self
    {
        return $this->setParameter('autoVerify', $autoVerify);
    }

    /**
     * @param string $customerPhone
     * @return self
     */
    public function setCustomerPhone(string $customerPhone): self
    {
        return $this->setParameter('customerPhone', $customerPhone);
    }

    /**
     * @param mixed $meta
     * @return self
     */
    public function setMeta(mixed $meta): self
    {
        return $this->setParameter('meta', json_encode($meta));
    }

    /**
     * @param string $payerName
     * @return self
     */
    public function setPayerName(string $payerName): self
    {
        return $this->setParameter('payerName', $payerName);
    }

    /**
     * @param string $payerDesc
     * @return self
     */
    public function setPayerDesc(string $payerDesc): self
    {
        return $this->setParameter('payerDesc', $payerDesc);
    }

    /**
     * @param string $allowedCard
     * @return self
     */
    public function setAllowedCard(string $allowedCard): self
    {
        return $this->setParameter('allowedCard', $allowedCard);
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        if ($this->getTestMode()) {
            throw new \InvalidArgumentException('Nextpay payment gateway does not support test mode.');
        }
        return $this->liveEndpoint;
    }


    public function setRefNum($value){
        return $this->setParameter('RefNum', $value);
    }

    public function getRefNum(){
        return $this->getParameter('RefNum');
    }

    public function setTerminalNumber($value){
        return $this->setParameter('TerminalNumber', $value);
    }
    public function getTerminalNumber(){
        return $this->getParameter('TerminalNumber');
    }


    /**
     * Send the request with specified data
     *
     * @param mixed $data The data to send.
     * @return ResponseInterface
     * @throws RuntimeException
     * @throws InvalidResponseException
     */
    public function sendData($data)
    {
        try {
            $body = json_encode($data);

            if ($body === false) {
                throw new RuntimeException('Err in access/refresh token.');
            }

            $httpResponse = $this->httpClient->request(
                $this->getHttpMethod(),
                $this->createUri($this->getEndpoint()),
                [
                    'Accept' => 'application/json',
                    'Content-type' => 'application/json',
                ],
                $body
            );
            $json = $httpResponse->getBody()->getContents();
            $result = !empty($json) ? json_decode($json, true) : [];
            $result['httpStatus'] = $httpResponse->getStatusCode();
            return $this->response = $this->createResponse($result);
        } catch (Exception $e) {
            throw new InvalidResponseException(
                'Error communicating with payment gateway: ' . $e->getMessage(),
                $e->getCode()
            );
        }
    }
}
