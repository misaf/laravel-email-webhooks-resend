<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Misaf\LaravelEmailWebhooks\Facades\EmailWebhooks;
use Misaf\LaravelEmailWebhooksResend\ResendEmailWebhooksDriver;
use Misaf\LaravelEmailWebhooksResend\Tests\Helpers\EventAssertionHelper;
use Misaf\LaravelEmailWebhooksResend\Tests\Helpers\ResendEventPayloadHelper;

beforeEach(function (): void {
    Event::fake();
});

function resendDriver(): ResendEmailWebhooksDriver
{
    /** @var ResendEmailWebhooksDriver $driver */
    $driver = EmailWebhooks::driver('resend');

    return $driver;
}

describe('Resend Webhook Integration', function (): void {
    it('processes provider events correctly', function (array $payload, Closure $assertEventDispatched): void {
        resendDriver()->processEvent($payload);

        $assertEventDispatched();
        expect(true)->toBeTrue();
    })->with([
        'sent' => [
            fn(): array => ResendEventPayloadHelper::createSentPayload(),
            fn(): Closure => function (): void {
                EventAssertionHelper::assertSentEventDispatched();
            },
        ],
        'bounced' => [
            fn(): array => ResendEventPayloadHelper::createBouncedPayload(),
            fn(): Closure => function (): void {
                EventAssertionHelper::assertBouncedEventDispatched();
            },
        ],
        'failed' => [
            fn(): array => ResendEventPayloadHelper::createFailedPayload(),
            fn(): Closure => function (): void {
                EventAssertionHelper::assertFailedEventDispatched();
            },
        ],
    ]);

    it('handles multiple recipients', function (): void {
        $recipients = ['primary@google.com', 'cc@google.com', 'bcc@google.com'];
        $payload = ResendEventPayloadHelper::createMultiRecipientPayload('email.sent', $recipients);

        resendDriver()->processEvent($payload);

        EventAssertionHelper::assertSentEventDispatched($recipients);
    });

    it('analyzes bounce type correctly', function (string $type, bool $isHardBounce, bool $isSoftBounce): void {
        $payload = ResendEventPayloadHelper::createBouncedPayload(
            type: $type,
            message: 'Bounce message',
            subType: 'General',
        );

        $eventData = resendDriver()->processEvent($payload);

        expect($eventData->isHardBounce())->toBe($isHardBounce);
        expect($eventData->isSoftBounce())->toBe($isSoftBounce);
    })->with([
        'hard bounce' => ['Permanent', true, false],
        'soft bounce' => ['Temporary', false, true],
    ]);
});
