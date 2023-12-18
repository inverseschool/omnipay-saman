## Instalation

    composer require soheylak/omnipay-saman

## Example

###### Purchase

#### The result will be a redirect to the gateway or bank.

```php
    $gateway->setTerminalId('xxxxxxxxxxxx');
    $gateway->setReturnUrl('https://www.example.com/return');
   
    $response = $gateway->purchase([
        'Amount' => $amount,
        'currency' => $currency,
        'ResNum'=>'10',
        'CellNumber'=>'9120000000'
    ])->send();

    // Process response
    if ($response->isSuccessful() && $response->isRedirect()) {
        // store the transaction reference to use in completePurchase()
        $transactionReference = $response->getTransactionReference();
        // Redirect to offsite payment gateway
        $response->redirect();
    } else {
        // Payment failed: display message to customer
        echo $response->getMessage();
    }

```

### Complete Purchase (Verify)

```php
// Send purchase complete request
    
    $response = $gateway->completePurchase([
        'RefNum' => $refNum,
        'TerminalNumber' => $terminalNumber, 
    ])->send();
    
    if (!$response->isSuccessful() || $response->isCancelled()) {
        // Payment failed: display message to customer
        echo $response->getMessage();
    } else {
        // Payment was successful
        print_r($response);
    }
```

### Refund Order

Refund an order by the $refNum:

```php
    $response = $gateway->refund([
        'RefNum' => $refNum,
        'TerminalNumber' => $terminalNumber,
    ])->send();
    
    if ($response->isSuccessful()) {
        // Refund was successful
        print_r($response);
    } else {
        // Refund failed
        echo $response->getMessage();
    }
```



