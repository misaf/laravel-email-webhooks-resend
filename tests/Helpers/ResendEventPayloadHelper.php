<?php

declare(strict_types=1);

namespace Misaf\LaravelEmailWebhooksResend\Tests\Helpers;

use Misaf\LaravelEmailWebhooks\DTOs\BounceEvent;
use Misaf\LaravelEmailWebhooks\DTOs\EmailEvent;

final class ResendEventPayloadHelper
{
    /**
     * Create a base Resend event payload
     *
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    public static function createPayload(string $type, array $overrides = []): array
    {
        $basePayload = [
            'type'       => $type,
            'created_at' => '2024-01-01T12:00:00Z',
            'data'       => [
                'email_id'   => 'test-email-123',
                'to'         => ['user@google.com'],
                'from'       => 'sender@google.com',
                'subject'    => 'Test Email',
                'created_at' => '2024-01-01T12:00:00Z',
            ],
        ];

        $merged = array_merge_recursive($basePayload, $overrides);

        if (isset($overrides['data']) && is_array($overrides['data']) && isset($overrides['data']['to'])) {
            $merged['data']['to'] = $overrides['data']['to'];
        }

        return $merged;
    }

    /**
     * Create a sent event payload
     *
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    public static function createSentPayload(array $overrides = []): array
    {
        return self::createPayload(EmailEvent::TypeSent, $overrides);
    }

    /**
     * Create a bounced event payload
     *
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    public static function createBouncedPayload(
        string $type = BounceEvent::TypePermanent,
        string $message = 'Email bounced',
        string $subType = 'General',
        array $overrides = [],
    ): array {
        $bounceData = [
            'data' => [
                'bounce' => [
                    'type'    => $type,
                    'message' => $message,
                    'subType' => $subType,
                ],
            ],
        ];

        return self::createPayload(EmailEvent::TypeBounced, array_merge_recursive($bounceData, $overrides));
    }

    /**
     * Create a complained event payload
     *
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    public static function createComplainedPayload(
        string $type = BounceEvent::TypePermanent,
        string $message = 'Email complained',
        string $subType = 'General',
        array $overrides = [],
    ): array {
        $bounceData = [
            'data' => [
                'bounce' => [
                    'type'    => $type,
                    'message' => $message,
                    'subType' => $subType,
                ],
            ],
        ];

        return self::createPayload(EmailEvent::TypeComplained, array_merge_recursive($bounceData, $overrides));
    }

    /**
     * Create a failed event payload
     *
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    public static function createFailedPayload(array $overrides = []): array
    {
        return self::createPayload(EmailEvent::TypeFailed, $overrides);
    }

    /**
     * Create a payload with multiple recipients
     *
     * @param  array<string>  $recipients
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    public static function createMultiRecipientPayload(string $type, array $recipients, array $overrides = []): array
    {
        $recipientData = [
            'data' => [
                'to' => $recipients,
            ],
        ];

        return self::createPayload($type, array_merge_recursive($recipientData, $overrides));
    }

    /**
     * Create a malformed payload (missing data key)
     *
     * @return array<string, mixed>
     */
    public static function createMalformedPayload(string $type = EmailEvent::TypeSent): array
    {
        return [
            'type' => $type,
            // Missing 'data' key
        ];
    }

    /**
     * Create a payload missing bounce data for bounce/complaint events
     *
     * @return array<string, mixed>
     */
    public static function createPayloadWithoutBounceData(string $type): array
    {
        return [
            'type' => $type,
            'data' => [
                'email_id'   => 'test-email-123',
                'to'         => ['user@google.com'],
                'from'       => 'sender@google.com',
                'subject'    => 'Test Email',
                'created_at' => '2024-01-01T12:00:00Z',
                // Missing 'bounce' key
            ],
        ];
    }
}
