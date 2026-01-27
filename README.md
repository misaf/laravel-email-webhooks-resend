# Laravel Email Webhooks Resend

**Extension package to resend email webhook events in Laravel applications.**

## Features

- Resend email webhook events easily.
- Built on top of `laravel-email-webhooks`.
- Works with [Spatie Laravel Webhook Client](https://github.com/spatie/laravel-webhook-client).
- Fully tested with Pest and Orchestra Testbench.

## Requirements

- PHP ^8.3
- Laravel 10+
- `misaf/laravel-email-webhooks` ^1.0.0
- `spatie/laravel-webhook-client` ^3.4.3

## Installation

```bash
composer require misaf/laravel-email-webhooks-resend
Usage
Publish the service provider (if you need customization):

bash
Copy code
php artisan vendor:publish --provider="Misaf\EmailWebhooksResend\Providers\EmailWebhooksResendServiceProvider"
Resend webhook events:

php
Copy code
use Misaf\EmailWebhooksResend\Facades\EmailWebhooksResend;

EmailWebhooksResend::resend('event-name', $payload);
Integrate with your webhook client:

php
Copy code
use Spatie\WebhookClient\Models\WebhookCall;

WebhookCall::create([
    'name' => 'email_event',
    'payload' => $payload,
]);
Testing
The package uses Pest and Orchestra Testbench:

bash
Copy code
composer test
Contributing
All contributions are welcome. Feel free to submit pull requests or open issues.

License
This package is open-sourced under the MIT license.