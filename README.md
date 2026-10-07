# Paymob (Laravel package)

> `zeal-io/paymob`: Laravel SDK for Paymob payment processing - authentication, order creation, payment keys (acceptance and intention), saved-token payments, refunds and voids, with typed response objects.

Consumed by the Zeal Laravel backends as a VCS composer repository (see `repositories` in the host `composer.json`).

## API (`src/Paymob.php`)

| Method | Purpose |
|---|---|
| `getPaymentKeyToken()` | Authenticate and obtain the payment key token |
| `createOrder()` / `PaymentOrder` | Register an order with Paymob |
| `createPaymentKey()` | Standard payment key |
| `createAcceptancePaymentKey()` | Acceptance payment key |
| `createIntentionPaymentKey()` | Paymob intention-based key |
| `payWithSavedToken()` | Charge a previously tokenized card |
| `syncTransactionResponse()` | Fetch/reconcile a transaction |
| `refund()` / `voidRefund()` | Refund or void a transaction |
| `checkResponseStatusCode()` / `response()` | Response handling helpers |

Supporting types: `Models/PaymentKey`, `Models/PaymentOrder`, and typed responses in `src/Response/` (Authentication, CreateOrder, PaymentKey, PayWithSavedToken, FetchPaymentTransaction, ConnectException). Exceptions in `src/Exceptions/`. Laravel wiring via `PaymobServiceProvider` (service provider auto-discovered).

## Configuration

Publish/env-driven via `config/payment.php`:

- `PAYMOB_API_KEY`
- `PAYMOB_AUTH_INTEGRATION` (Zeal auth integration id)
- `PAYMOB_PAYMENT_INTEGRATION` (Zeal payment integration id)

## Development

```bash
composer install
vendor/bin/phpunit
vendor/bin/phpcs
```
