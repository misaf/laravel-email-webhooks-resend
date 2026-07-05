<?php

declare(strict_types=1);

use Misaf\LaravelEmailWebhooksResend\Tests\Helpers\TestResendEmailEvent;

describe('ResendEvent', function (): void {
    it('handles bounce data correctly', function (): void {
        $eventData = TestResendEmailEvent::fromArray([
            'type'   => 'email.bounced',
            'bounce' => [
                'type'    => 'Permanent',
                'message' => 'Test bounce message',
                'subType' => 'General',
            ],
        ]);

        $bounce = $eventData->bounce;
        assert(null !== $bounce);

        expect($bounce->toArray())->toBe([
            'type'    => 'Permanent',
            'message' => 'Test bounce message',
            'subType' => 'General',
        ]);
    });

    it('handles events without bounce data', function (): void {
        $eventData = TestResendEmailEvent::fromArray([
            'type' => 'email.sent',
        ]);

        expect($eventData->bounce)->toBeNull();
    });

    it('converts to array correctly', function (): void {
        $eventData = TestResendEmailEvent::fromArray([
            'type'   => 'email.bounced',
            'bounce' => [
                'type'    => 'Permanent',
                'message' => 'Test bounce message',
                'subType' => 'General',
            ],
        ]);

        expect($eventData->toArray())->toMatchArray([
            'to'         => ['test@example.com'],
            'from'       => 'sender@example.com',
            'subject'    => 'Test Email',
            'email_id'   => 'test-email-123',
            'created_at' => '2024-01-01T12:00:00Z',
            'type'       => 'email.bounced',
            'provider'   => 'test-provider',
            'bounce'     => [
                'type'    => 'Permanent',
                'message' => 'Test bounce message',
                'subType' => 'General',
            ],
        ]);
    });

    it('handles multiple recipients correctly', function (): void {
        $recipients = ['primary@example.com', 'cc@example.com', 'bcc@example.com'];
        $eventData = TestResendEmailEvent::fromArray([
            'to'   => $recipients,
            'type' => 'email.sent',
        ]);

        expect($eventData->to)->toBe($recipients);
    });

    it('stores original payload correctly', function (): void {
        $eventData = TestResendEmailEvent::fromArray([
            'original_payload' => ['test' => 'data'],
            'type'             => 'email.sent',
        ]);

        expect($eventData->originalPayload)->toBe(['test' => 'data']);
    });
});
