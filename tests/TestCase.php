<?php

declare(strict_types=1);

namespace Misaf\LaravelEmailWebhooksResend\Tests;

use Illuminate\Foundation\Application;
use Misaf\LaravelEmailWebhooks\EmailWebhooksServiceProvider;
use Misaf\LaravelEmailWebhooksResend\EmailWebhooksResendServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * @param  Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            EmailWebhooksServiceProvider::class,
            EmailWebhooksResendServiceProvider::class,
        ];
    }
}
