<?php

declare(strict_types=1);

namespace Zeal\Paymob;

use GuzzleHttp\Client;
use Zeal\Paymob\Response\CheckoutResponse;
use Zeal\Paymob\Response\RefundResponse;
use Zeal\Paymob\Response\AuthenticationResponse;

final class Paymob
{
    /**
     * Paymob API Credentails
     *
     * @var array
     */
    private $credentials;

    /**
     * Base API Endpont
     *
     * @var string
     */
    private $api = "https://accept.paymob.com/api/";

    /**
     * Message Send Response
     *
     * @var CheckoutResponse|TokenizationResponse
     */
    private $response;

    /**
     * Guzzle Client for Iframe APIs
     *
     * @var Client
     */
    private $http;

    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;

        $this->http = new Client([
            'base_uri' => $this->api,
            'http_errors' => false,
            'defaults' => [
                'headers' => ['Content-Type' => 'application/json'],
            ]
        ]);

        $this->authenticate($apiKey);

        return $this;
    }

    /**
     * Check out an order
     *
     * @param array  $data order details
     * @return Paymob
     */
    public function checkout(array $data): Paymob
    {
        $hash = $this->generateRequestHash(
            $this->getPaymentPath($data)
        );

        $response = $this->http->request('POST', "/checkout", [
            'form_params' => [
                'hash' => $hash,
                'merchantId' => $this->credentials['merchantId'],
                'shopper_reference' => (string) $data['shopperReference'],
                'cardToken' => $data['cardToken'],
                'ccvToken' => $data['cvvToken'] ?? '',
                'amount' => $data['amount'],
                'currency' => $data['currency'],
                'orderId' => $data['orderId'],
                'serviceName' => 'customizableForm',
            ],
        ]);

        $this->response = new CheckoutResponse((string) $response->getBody());

        return $this;
    }

    /**
     * Refund an order
     *
     * @param string $orderId
     * @param mixed $merchantOrderId
     * @param float $amounts
     * @return Paymob
     */
    public function refund(): void
    {
        // @TODO
    }

    /**
     * Response getter
     *
     * @return CheckoutResponse|TokenizationResponse
     */
    public function response()
    {
        return $this->response;
    }

    private function authenticate(string $apiKey)
    {
        $response = $this->http->request('POST', "auth/tokens", [
            'json' => [
                "api_key" => $apiKey,
            ],
        ]);

        $this->response = new AuthenticationResponse($response);
        $this->authToken = $this->response->getAuthToken();

        return $this;
    }

    public function dd($value)
    {
        highlight_string("<?php\n\$data =\n" . var_export($value, true) . ";\n?>");
        die();
    }
}
