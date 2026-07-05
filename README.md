# Laravel Email Webhooks Resend

Resend driver package for `misaf/laravel-email-webhooks` on Laravel 13.

## Requirements

- PHP 8.4+
- Laravel 13
- `misaf/laravel-email-webhooks` 1.x
- `spatie/laravel-webhook-client` 3.x

## Installation

```bash
composer require misaf/laravel-email-webhooks-resend
```

The service provider is auto-discovered by Laravel and registers the `resend` email webhook driver.

## Usage

Process a Resend webhook payload through the shared facade:

```php
use Misaf\LaravelEmailWebhooks\Facades\EmailWebhooks;

$eventData = EmailWebhooks::driver('resend')->processEvent($payload);
```

When using Spatie Webhook Client, set the process job to:

```php
Misaf\LaravelEmailWebhooksResend\Jobs\Webhooks::class
```

The driver maps Resend payloads to `Misaf\LaravelEmailWebhooksResend\DTOs\ResendEvent` and dispatches the core package email events.

## Testing

```bash
composer test
```
