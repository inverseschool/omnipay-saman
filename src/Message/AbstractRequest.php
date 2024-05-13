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
        return $this->getParameter('TerminalId');
    }

    /**
     * @return bool
     */
    public function getAutoVerify(): bool
    {
        return (bool)$this->getParameter('autoVerify');
    }

    public function getAmount(): string
    {
        return $this->getParameter('Amount');
    }

    public function setAmount($value): self
    {
        return $this->setParameter('Amount', $value);
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
    public function setRedirectUrl(string $value): self
    {
        return $this->setParameter('RedirectUrl', $value);
    }

    /**
     * @return string
     */
    public function getRedirectUrl(): ?string
    {
        return $this->getParameter('RedirectUrl');
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
     * @param bool $autoVerify
     * @return self
     */
    public function setAutoVerify(bool $autoVerify): self
    {
        return $this->setParameter('autoVerify', $autoVerify);
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
     * @param string $value
     * @return self
     */
    public function setResNum(string $value): self
    {
        return $this->setParameter('ResNum', $value);
    }

    /**
     * @return string
     */
    public function getResNum(): ?string
    {
        return $this->getParameter('ResNum');
    }

    /**
     * @param string $value
     * @return self
     */
    public function setRefNum($value):self{
        return $this->setParameter('RefNum', $value);
    }

    /**
     * @return string
     */
    public function getRefNum(){
        return $this->getParameter('RefNum');
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
