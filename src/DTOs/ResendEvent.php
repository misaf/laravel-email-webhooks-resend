<?php

declare(strict_types=1);

namespace Misaf\LaravelEmailWebhooksResend\DTOs;

use Misaf\LaravelEmailWebhooks\DTOs\EmailEvent;

/**
 * @phpstan-consistent-constructor
 */
class ResendEvent extends EmailEvent
{
    /**
     * @param array{
     *   data: array{
     *     to: list<string>,
     *     from: string,
     *     subject: string,
     *     email_id: string,
     *     created_at: string,
     *     bounce?: array{
     *       type: string,
     *       message: string,
     *       subType: string
     *     }
     *   },
     *   type: string
     * } $payload
     */
    public static function fromArray(array $payload): static
    {
        return new static(
            to: $payload['data']['to'],
            from: $payload['data']['from'],
            subject: $payload['data']['subject'],
            emailId: $payload['data']['email_id'],
            createdAt: $payload['data']['created_at'],
            originalPayload: $payload,
            type: $payload['type'],
            provider: 'resend',
            bounce: isset($payload['data']['bounce']) ? ResendBounceEvent::fromArray($payload['data']['bounce']) : null,
        );
    }
}
