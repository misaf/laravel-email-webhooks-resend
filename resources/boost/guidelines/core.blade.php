## Laravel Email Webhooks Resend

This package provides the Resend driver for `misaf/laravel-email-webhooks`.

### Package Structure

- The main package classes live at the root namespace: `Misaf\LaravelEmailWebhooksResend\ResendEmailWebhooksDriver` and `Misaf\LaravelEmailWebhooksResend\EmailWebhooksResendServiceProvider`.
- Resend DTOs live in `Misaf\LaravelEmailWebhooksResend\DTOs`.
- The concrete Resend driver must extend `Misaf\LaravelEmailWebhooks\EmailWebhooksDriver`.
- The service provider registers the `resend` driver through the shared `EmailWebhooks` facade.
- Do not add generic `Services`, `Providers`, or `Drivers` folders unless the package grows enough to justify that structure.

### Payload Handling

- Validate Resend payloads before creating DTOs.
- Use Laravel validation rules and shared core DTO constants for event and bounce types.
- `processEvent()` returns the Resend event DTO directly after the core driver dispatches the Laravel event.
- Keep Spatie Webhook Client integration inside `Misaf\LaravelEmailWebhooksResend\Jobs\Webhooks`.

### Testing

- Package tests use Pest with Orchestra Testbench.
- Use `Misaf\LaravelEmailWebhooksResend\Tests\TestCase` for tests that need the Laravel application container and the Resend provider registered.
- Keep Resend payload fixtures and event assertion helpers in the Resend package tests.
- Keep Pint available through `require-dev` because CI runs `vendor/bin/pint --test`.
- Optional Pest plugins may be installed, but only add architecture/type/profanity rules when they are enforced by tests or CI.

### Compatibility

- This package targets PHP 8.3+.
- Keep the dependency on the core package explicit through Composer.
