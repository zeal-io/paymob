<?php

declare(strict_types = 1);

namespace Zeal\Paymob\Response;

use GuzzleHttp\Psr7\Response;
use Zeal\Paymob\Exceptions\InvalidPaymentException;
use Zeal\Paymob\Exceptions\UnauthenticatedException;

final class PayWithSavedTokenResponse
{
	/**
	 * Hold encoded guzzle response
	 *
	 * @var object
	 */
	private $response;
	
	/**
	 * Holds guzzle response decoded body
	 *
	 * @var object
	 */
	private $body;
	
	/**
	 * flag is response failed or not
	 *
	 * @var bool
	 */
	private $failed = false;
	
	/**
	 * Parses guzzle response body
	 *
	 * @param Response $response json string response body
	 */
	public function __construct(Response $response)
	{
		$this->response = $response;
		
		$this->body = json_decode((string) $response->getBody());
		
		$this->handleResponseExceptions();
	}
	
	/**
	 * Getter for failed flag
	 */
	public function failed(): bool
	{
		return $this->failed;
	}
	
	/**
	 * Returns response status code
	 */
	public function getStatusCode(): int
	{
		return $this->response->getStatusCode();
	}
	
	/**
	 * Return card token
	 */
	public function getPaymentKeyToken(): string
	{
		return $this->body->token;
	}
	
	private function handleResponseExceptions(): void
	{
		switch ($this->response->getStatusCode()) {
			case '401':
				$this->failed = true;
				throw new UnauthenticatedException(
						json_encode($this->body),
						$this->response->getStatusCode()
				);
				break;
			case '400':
				$this->failed = true;
				throw new InvalidPaymentException(
						json_encode($this->body),
						$this->response->getStatusCode()
				);
			default:
				break;
		}
	}
	
	public function getTransactionId()
	{
		return ($this->body->obj) ?? $this->body->obj->id;
	}
	
	public function getOrderReference()
	{
		return ($this->body->obj) ?? $this->body->obj->order ?? $this->body->obj->order->id;
	}
}
