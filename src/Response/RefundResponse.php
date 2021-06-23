<?php

declare(strict_types=1);

namespace Zeal\Paymob\Response;

final class RefundResponse
{
    /**
     * Hold json decoded guzzle response
     *
     * @var object
     */
    private $response;

    /**
     * flag is response failed or not
     *
     * @var bool
     */
    private $failed = false;

    /**
     * Parses guzzle response body
     *
     * @param string $response json string response body
     */
    public function __construct(string $response)
    {
        $this->response = json_decode($response);

        if (property_exists($this->response, 'error')) {
            $this->failed = true;
        } else {
            $this->response = $this->response->response;
        }
    }

    /**
     * Returns response error
     *
     * @return array array holds the error
     */
    public function getError()
    {
        return $this->response->error;
    }

    /**
     * Getter for failed flag
     *
     * @return bool
     */
    public function failed()
    {
        return $this->failed;
    }

    /**
     * Get order id on kashier side
     *
     * @return string
     */
    public function getOrderId()
    {
        return $this->response->orderId;
    }

    /**
     * Get order reference from kasier end
     *
     * @return string
     */
    public function getOrderReference()
    {
        return $this->response->orderReference;
    }

    /**
     * Get Kashier transaction id
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->response->transactionId;
    }

    /**
     * Get merchent order id
     *
     * @return string
     */
    public function getMerchantOrderId()
    {
        return $this->response->merchantOrderId;
    }

    /**
     * Get checkout creating data
     *
     * @return string
     */
    public function getCreationDate()
    {
        return $this->response->creationDate;
    }

    /**
     * Get ref id
     *
     * @return string
     */
    public function getRefId()
    {
        return $this->response->refId;
    }

    /**
     * Get merchant id
     *
     * @return string
     */
    public function getMerchantId()
    {
        return $this->response->merchantId;
    }
}
