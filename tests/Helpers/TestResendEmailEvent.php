<?php

declare(strict_types=1);

namespace Misaf\LaravelEmailWebhooksResend\Tests\Helpers;

use Misaf\LaravelEmailWebhooksResend\DTOs\ResendEvent;

final class TestResendEmailEvent extends ResendEvent
{
    /**
     * @param array{
     *   to?: list<string>,
     *   from?: string,
     *   subject?: string,
     *   email_id?: string,
     *   created_at?: string,
     *   original_payload?: array<string, mixed>,
     *   type?: string,
     *   provider?: string,
     *   bounce?: array{
     *     type?: string,
     *     message?: string,
     *     subType?: string
     *   }
     * } $data
     */
    public static function fromArray(array $data): static
    {
        $bounce = null;
        if (isset($data['bounce'])) {
            $bounce = TestResendBounceEvent::fromArray($data['bounce']);
        }

        return new self(
            to: $data['to'] ?? ['test@example.com'],
            from: $data['from'] ?? 'sender@example.com',
            subject: $data['subject'] ?? 'Test Email',
            emailId: $data['email_id'] ?? 'test-email-123',
            createdAt: $data['created_at'] ?? '2024-01-01T12:00:00Z',
            originalPayload: $data['original_payload'] ?? ['test' => 'data'],
            type: $data['type'] ?? 'email.sent',
            provider: $data['provider'] ?? 'test-provider',
            bounce: $bounce,
        );
    }
}
