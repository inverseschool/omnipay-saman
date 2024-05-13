<?php

namespace Omnipay\Saman\Message;

use Exception;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\ResponseInterface;
use RuntimeException;

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
    protected $liveEndpoint = 'https://sep.shaparak.ir';

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

    public function getTerminalId(){
        return $this->getParameter('terminalId');
    }

    public function getAmount(): string
    {
        return $this->getParameter('amount');
    }

    public function setAmount($value): self
    {
        return $this->setParameter('amount', $value);
    }

    /**
     * @param string $value
     * @return self
     */
    public function setTerminalId(string $value): self
    {
        return $this->setParameter('terminalId', $value);
    }

    /**
     * @param string $value
     */
    public function setCellNumber(string $value)
    {
        return $this->setParameter('CellNumber', $value);
    }

    /**
     * @return string
     */
    public function getCellNumber(): ?string
    {
        return $this->getParameter('CellNumber');
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
     * @param mixed $meta
     * @return self
     */
    public function setMeta(mixed $meta): self
    {
        return $this->setParameter('meta', json_encode($meta));
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
