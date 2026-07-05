<?php

declare(strict_types=1);

namespace Misaf\LaravelEmailWebhooksResend\Tests\Helpers;

use Misaf\LaravelEmailWebhooksResend\DTOs\ResendBounceEvent;

final class TestResendBounceEvent extends ResendBounceEvent
{
    /**
     * @param array{
     *   type?: string,
     *   message?: string,
     *   subType?: string
     * } $data
     */
    public static function fromArray(array $data): static
    {
        return new self(
            type: $data['type'] ?? 'Permanent',
            message: $data['message'] ?? 'Test bounce message',
            subType: $data['subType'] ?? 'General',
        );
    }
}
