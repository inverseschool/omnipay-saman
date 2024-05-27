<?php


namespace Omnipay\Saman\Message;

/**
 * Class AbstractResponse
 */
abstract class AbstractResponse extends \Omnipay\Common\Message\AbstractResponse
{
    /**
     * @var array
     */
    protected $errorCodes = [
        '1' => 'کاربر انصراف داده است',
        '2' => 'پرداخت باموفقیت انجام شد',
        '3' => 'پرداخت انجام نشد',
        '4' => 'کاربر در بازه زمانی تعیین شده پاسخی ارسال نکرده است',
        '5' => 'پارامترهای ارسالی نامعتبر است',
        '8' => 'آدرس سرور پذیرنده نامعتبر است',
        '10' => 'توکن ارسال شده یافت نشد',
        '11' => 'بااین شماره ترمینال فقط تراکنش های توکنی قابل پرداخت هستند',
        '12' => 'شماره ترمینال ارسال شده یافت نشد',
    ];

    /**
     * @var array
     */
    protected $errorVerifyCode = [
        '-2' => 'تراکنش یافت نشد',
        '-6' => 'بیش از نیم ساعت از زمان اجرای تراکنش گذشته است',
        '0' => 'موفق',
        '2' => 'درخواست تکراری می باشد',
        '-105' => 'ترمینال ارسالی در سیستم موجود نمی باشد',
        '-104' => 'ترمینال ارسالی غیرفعال می باشد',
        '-106' => 'آدرس آی پی درخواستی غیر مجاز می باشد'
    ];

    /**
     * Response Message
     *
     * @return null|string A response message from the payment gateway
     */
    public function getMessage()
    {
        return $this->errorCodes[(string)$this->getErrorCode()] ?? ($this->errorVerifyCode[(string)$this->getErrorCode()] ?? parent::getMessage());
    }

    public function getErrorCode()
    {
        return $this->data['errorCode'] ?? ($this->data['ResultCode'] ?? parent::getCode());
    }

    /**
     * Response code
     *
     * @return null|string A response code from the payment gateway
     */
    public function getCode()
    {
        return $this->data['status'] ?? parent::getCode();
    }

    public function getResultCode()
    {
        return $this->data['ResultCode'] ?? null;
    }

    /**
     * Http status code
     *
     * @return int A response code from the payment gateway
     */
    public function getHttpStatus(): int
    {
        return (int)($this->data['httpStatus'] ?? null);
    }
}
