<?php

declare(strict_types=1);

namespace Misaf\EmailWebhooksResend\Tests\Helpers;

use Misaf\EmailWebhooksResend\DataTransferObjects\ResendEventDto;

final class TestResendEmailEventDto extends ResendEventDto
{
    /**
     * @param array{
     *   to?: list<string>,
     *   from?: string,
     *   subject?: string,
     *   email_id?: string,
     *   created_at?: string,
     *   type?: string,
     *   bounce?: array{
     *     type?: string,
     *     message?: string,
     *     subType?: string
     *   }
     * } $data
     */
    public static function fromArray(array $data): static
    {
        $payload = [
            'data' => [
                'to' => $data['to'] ?? ['test@example.com'],
                'from' => $data['from'] ?? 'sender@example.com',
                'subject' => $data['subject'] ?? 'Test Email',
                'email_id' => $data['email_id'] ?? 'test-email-123',
                'created_at' => $data['created_at'] ?? '2024-01-01T12:00:00Z',
            ],
            'type' => $data['type'] ?? 'email.sent',
        ];

        if (isset($data['bounce'])) {
            $payload['data']['bounce'] = $data['bounce'];
        }

        return new self($payload);
    }
}
