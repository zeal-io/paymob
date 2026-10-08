<h1 align="center">Paymob (Laravel package)</h1>

<p align="center"><i>`zeal-io/paymob`: Laravel SDK for Paymob payment processing - authentication, order creation, payment keys (acceptance and intention), saved-token payments, refunds and voids, with typed response objects.</i></p>

<p align="center">![Language](https://img.shields.io/badge/lang-PHP-777BB4) ![Stack](https://img.shields.io/badge/stack-Laravel%20package-339933) ![Status](https://img.shields.io/badge/status-active-2EA44F) ![Visibility](https://img.shields.io/badge/repo-public-24292F) ![License](https://img.shields.io/badge/license-Custom-blue)</p>

---
## Contents

- [API (`src/Paymob.php`)](#api-srcpaymobphp)
- [Configuration](#configuration)
- [Development](#development)

Consumed by the Zeal Laravel backends as a VCS composer repository (see `repositories` in the host `composer.json`).

<p align="right"><a href="#contents">Back to contents</a></p>

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

<p align="right"><a href="#contents">Back to contents</a></p>

## Configuration

Publish/env-driven via `config/payment.php`:

- `PAYMOB_API_KEY`
- `PAYMOB_AUTH_INTEGRATION` (Zeal auth integration id)
- `PAYMOB_PAYMENT_INTEGRATION` (Zeal payment integration id)

<p align="right"><a href="#contents">Back to contents</a></p>

## Development

```bash
composer install
vendor/bin/phpunit
vendor/bin/phpcs
```

## License and contribution

Internal Zeal repository. License: Custom (see the LICENSE file).

Contribution: open a pull request against the default branch; keep changes minimal and described. For releases/deployments follow the platform CD process - never force-push shared branches.
