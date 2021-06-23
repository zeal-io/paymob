# Kashier Payment API

[![Zeal](https://s3-eu-west-1.amazonaws.com/wuzzuf/files/company_logo/Zeal-Rewards-Egypt-32389-1526069891.png)](https://zeal-app.com)

# [Kashier Documentations](https://docs.google.com/document/d/11uktL4UctHny5nYXPqoLpAcYBbEfGFPTV4-kyq_YA08/edit)

# Features!

- Tokenize Credit Cards
- Process payment with the tokized cards

### Installation

As this package is private you can't install it with packagist so you need to import it from github directly

[How to add private repo as composer package](https://stackoverflow.com/questions/40619393/how-to-add-private-github-repository-as-composer-dependency)

### Usage

- Init Kashier instance

```php
use Zeal\Kashier\Kashier;

$kashier = new Kashier([
   'apiKey' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
   'merchantId' => 'MID-xxx-xxx',
   'email' => 'email@test.com', // used in refund api
   'passsword' => 'password',   // used in refund api
   'env' => 'testing', // don't pass for production
]);
```

- ### Tokenize card

```php
$kashier->tokenize([
    'shopperReference' => "xyz-xyz....", // a ref for the shopper like his 'uuid'
    'cardHolderName' => 'John Doe',
    "cardNumber" =>  "5111111111111118",
    "ccv" =>  "100",
    "expiryMonth" =>  "05",
    "expiryYear" =>  "20",
    "tokenValidity" => "perm"
]);
```

- You can access the tokenization response using

```php
    $kashier->response();
```

- Reponse API For Tokenization

```php
$kashier->response()->failed(); // return weather the tokenization failed of not
$kashier->response()->getError(); // displayes the response error
$kashier->response()->getStatusCode(); // get response status code
$kashier->response()->getStatus(); // get respo se status
$kashier->response()->getCardToken(); // get the card token to use in processing payment
$kashier->response()->getMaskedCard(); // gets masked card to save to DB ex: ************1118
$kashier->response()->getCcvToken();  // Get ccv token in case of temporary tokens
```

- ### Payment Process

```php
$kashier->checkout([
    'shopperReference' => "xyz-xyz....", // a ref for the shopper like his 'uuid'
    'orderId' => 123, // order id "generated from our end"
    'currency' => 'EGP',
    'amount' => 20,
    'cardToken' => $kashier->response()->getCardToken(), // use the generated card token from the prevous request or pass it from the DB
]);
```

- You can access the payment response using

```php
    $kashier->response();
```

- Reponse API For Tokenization

```php
$kashier->response()->failed(); // return weather the tokenization failed of not
$kashier->response()->getError(); // displayes the response error
$kashier->response()->getOrderId(); // get order id on kashier side
$kashier->response()->getOrderReference(); // gets order reference ex: TEST-ORD-22938
$kashier->response()->getTransactionId(); // gets transaction id ex: TX-91965584
$kashier->response()->getMerchantOrderId();  // Get merchant order id that is generated from your side
```

- ### Refund Order

```php
$kashier->refund(
    '123-123-....', // kashier order id'
    '123456', // merchant order id'
    100.5, // refund amount);
```

- You can access the refund response using

```php
    $kashier->response();
```

- Reponse API For Tokenization

```php
$kashier->response()->failed(); // return whether the refund failed or not
$kashier->response()->getError(); // displays the response error
```

### Todos

- Hold amount API
