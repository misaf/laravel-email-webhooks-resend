<?php

declare(strict_types=1);

namespace Misaf\LaravelEmailWebhooksResend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Misaf\LaravelEmailWebhooks\DTOs\BounceEvent;
use Misaf\LaravelEmailWebhooks\DTOs\EmailEvent;
use Misaf\LaravelEmailWebhooks\EmailWebhooksDriver;
use Misaf\LaravelEmailWebhooksResend\DTOs\ResendEvent;

final class ResendEmailWebhooksDriver extends EmailWebhooksDriver
{
    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    protected function validatePayload(array $payload): array
    {
        $validator = Validator::make($payload, [
            'data'                => 'bail|required|array',
            'data.to'             => 'bail|required|array|min:1|max:100',
            'data.to.*'           => 'bail|required|email:rfc,strict,spoof,filter,filter_unicode|max:255',
            'data.from'           => 'bail|required|email:rfc,strict,spoof,filter,filter_unicode|max:255',
            'data.bounce'         => sprintf('bail|required_if:type,%s,%s|nullable|array', EmailEvent::TypeBounced, EmailEvent::TypeComplained),
            'data.bounce.type'    => ['bail', 'required_with:data.bounce', 'string', Rule::in(BounceEvent::types())],
            'data.bounce.message' => 'bail|required_with:data.bounce|string|max:1000',
            'data.bounce.subType' => 'bail|required_with:data.bounce|string|max:255',
            'data.subject'        => 'bail|required|string|min:1|max:255',
            'data.email_id'       => 'bail|required|string|min:1',
            'data.created_at'     => 'bail|required|string|date',
            'type'                => ['bail', 'required', 'string', Rule::in(EmailEvent::types())],
        ]);

        try {
            return $validator->validate();
        } catch (ValidationException $e) {
            throw new InvalidArgumentException(
                'Invalid Resend webhook payload: ' . $e->getMessage(),
            );
        }
    }

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
    protected function createEventFromPayload(array $payload): EmailEvent
    {
        return ResendEvent::fromArray($payload);
    }
}
