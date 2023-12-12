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
    protected string $liveEndpoint = 'https://nextpay.org/nx/gateway';

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

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->getParameter('apiKey');
    }



    public function setOrderId(int $value){
        return $this->setParameter('orderId', $value);
    }
    public function getOrderId(){
        return $this->getParameter('orderId');
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
        return $this->getParameter('customerPhone');
    }

    /**
     * @return mixed
     */
    public function getMeta(): mixed
    {
        return json_decode($this->getParameter('meta'));
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
