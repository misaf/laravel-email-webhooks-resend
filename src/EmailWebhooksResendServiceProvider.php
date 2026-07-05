<?php

declare(strict_types=1);

namespace Misaf\LaravelEmailWebhooksResend;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Misaf\LaravelEmailWebhooks\EmailWebhooksManager;

final class EmailWebhooksResendServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->callAfterResolving(EmailWebhooksManager::class, function (EmailWebhooksManager $manager): void {
            $manager->extend('resend', fn(Application $app): ResendEmailWebhooksDriver => $app->make(ResendEmailWebhooksDriver::class));
        });
    }
}
