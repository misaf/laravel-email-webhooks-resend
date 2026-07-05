<?php

declare(strict_types=1);

namespace Misaf\LaravelEmailWebhooksResend\Jobs;

use InvalidArgumentException;
use Misaf\LaravelEmailWebhooks\EmailWebhooksDriver;
use Misaf\LaravelEmailWebhooks\Facades\EmailWebhooks;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob as SpatieProcessWebhookJob;

final class Webhooks extends SpatieProcessWebhookJob
{
    public function handle(): void
    {
        $payload = $this->webhookCall->payload;

        if ( ! is_array($payload)) {
            throw new InvalidArgumentException('Webhook payload must be an array');
        }

        /** @var EmailWebhooksDriver $service */
        $service = EmailWebhooks::driver('resend');

        $service->processEvent($payload);
    }
}
