<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Misaf\LaravelEmailWebhooks\DTOs\EmailEvent;
use Misaf\LaravelEmailWebhooks\Facades\EmailWebhooks;
use Misaf\LaravelEmailWebhooksResend\ResendEmailWebhooksDriver;
use Misaf\LaravelEmailWebhooksResend\Tests\Helpers\EventAssertionHelper;
use Misaf\LaravelEmailWebhooksResend\Tests\Helpers\ResendEventPayloadHelper;

beforeEach(function (): void {
    Event::fake();
});

describe('ResendEmailWebhooksDriver', function (): void {
    it('registers as resend driver', function (): void {
        expect(EmailWebhooks::driver('resend'))->toBeInstanceOf(ResendEmailWebhooksDriver::class);
    });

    it('processes :dataset events', function (array $payload, Closure $assertEventDispatched): void {
        /** @var ResendEmailWebhooksDriver $driver */
        $driver = EmailWebhooks::driver('resend');
        $eventData = $driver->processEvent($payload);

        expect($eventData)->toBeInstanceOf(EmailEvent::class);

        $assertEventDispatched();
    })->with([
        'sent' => [
            fn(): array => ResendEventPayloadHelper::createSentPayload(),
            fn(): Closure => function (): void {
                EventAssertionHelper::assertSentEventDispatched();
            },
        ],
        'failed' => [
            fn(): array => ResendEventPayloadHelper::createFailedPayload(),
            fn(): Closure => function (): void {
                EventAssertionHelper::assertFailedEventDispatched();
            },
        ],
    ]);
});
