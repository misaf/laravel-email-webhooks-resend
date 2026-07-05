<?php

declare(strict_types=1);

namespace Misaf\LaravelEmailWebhooksResend\Tests\Helpers;

use Illuminate\Support\Facades\Event;
use Misaf\LaravelEmailWebhooks\Events\EmailBounced;
use Misaf\LaravelEmailWebhooks\Events\EmailComplained;
use Misaf\LaravelEmailWebhooks\Events\EmailFailed;
use Misaf\LaravelEmailWebhooks\Events\EmailSent;
use Misaf\LaravelEmailWebhooksResend\DTOs\ResendEvent;

final class EventAssertionHelper
{
    /**
     * Assert that a sent event was dispatched with correct data
     *
     * @param  array<string>|null  $expectedRecipients
     */
    public static function assertSentEventDispatched(?array $expectedRecipients = null): void
    {
        Event::assertDispatched(EmailSent::class, function (EmailSent $event) use ($expectedRecipients) {
            $eventData = $event->eventData;

            if ( ! $eventData instanceof ResendEvent) {
                return false;
            }

            if (null !== $expectedRecipients) {
                return $eventData->to === $expectedRecipients;
            }

            return true;
        });
    }

    /**
     * Assert that a bounced event was dispatched with correct data
     */
    public static function assertBouncedEventDispatched(
        ?string $expectedType = null,
        ?string $expectedMessage = null,
        ?string $expectedSubType = null,
    ): void {
        Event::assertDispatched(EmailBounced::class, function (EmailBounced $event) use ($expectedType, $expectedMessage, $expectedSubType) {
            $eventData = $event->eventData;

            if ( ! $eventData instanceof ResendEvent) {
                return false;
            }

            $bounce = $eventData->bounce;
            if (null === $bounce) {
                return false;
            }

            if (null !== $expectedType && $bounce->type !== $expectedType) {
                return false;
            }

            if (null !== $expectedMessage && $bounce->message !== $expectedMessage) {
                return false;
            }

            return ! (null !== $expectedSubType && $bounce->subType !== $expectedSubType);
        });
    }

    /**
     * Assert that a complained event was dispatched with correct data
     */
    public static function assertComplainedEventDispatched(
        ?string $expectedType = null,
        ?string $expectedMessage = null,
        ?string $expectedSubType = null,
    ): void {
        Event::assertDispatched(EmailComplained::class, function (EmailComplained $event) use ($expectedType, $expectedMessage, $expectedSubType) {
            $eventData = $event->eventData;

            if ( ! $eventData instanceof ResendEvent) {
                return false;
            }

            $bounce = $eventData->bounce;
            if (null === $bounce) {
                return false;
            }

            if (null !== $expectedType && $bounce->type !== $expectedType) {
                return false;
            }

            if (null !== $expectedMessage && $bounce->message !== $expectedMessage) {
                return false;
            }

            return ! (null !== $expectedSubType && $bounce->subType !== $expectedSubType);
        });
    }

    /**
     * Assert that a failed event was dispatched with correct data
     */
    public static function assertFailedEventDispatched(): void
    {
        Event::assertDispatched(EmailFailed::class, function (EmailFailed $event) {
            $eventData = $event->eventData;

            return $eventData instanceof ResendEvent;
        });
    }

    /**
     * Assert that no events were dispatched
     */
    public static function assertNoEventsDispatched(): void
    {
        Event::assertNotDispatched(EmailSent::class);
        Event::assertNotDispatched(EmailBounced::class);
        Event::assertNotDispatched(EmailComplained::class);
        Event::assertNotDispatched(EmailFailed::class);
    }

    /**
     * Assert that a specific event was not dispatched
     */
    public static function assertEventNotDispatched(string $eventClass): void
    {
        Event::assertNotDispatched($eventClass);
    }

    /**
     * Assert that an event was dispatched with ResendEvent
     */
    public static function assertEventWithResendDto(string $eventClass): void
    {
        Event::assertDispatched($eventClass, function ($event) {
            return $event->eventData instanceof ResendEvent;
        });
    }
}
