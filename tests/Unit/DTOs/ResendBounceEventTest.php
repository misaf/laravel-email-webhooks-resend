<?php

declare(strict_types=1);

use Misaf\LaravelEmailWebhooksResend\Tests\Helpers\TestResendBounceEvent;

describe('ResendBounceEvent', function (): void {
    it('creates bounce event from valid data', function (): void {
        $bounce = new TestResendBounceEvent(
            type: 'Permanent',
            message: 'Hard bounce occurred',
            subType: 'OnAccountSuppressionList',
        );

        expect($bounce->toArray())->toBe([
            'type'    => 'Permanent',
            'message' => 'Hard bounce occurred',
            'subType' => 'OnAccountSuppressionList',
        ]);
    });

    it('creates bounce event from array', function (): void {
        $data = [
            'type'    => 'Temporary',
            'message' => 'Temporary failure',
            'subType' => 'Suppressed',
        ];

        expect(TestResendBounceEvent::fromArray($data)->toArray())->toBe($data);
    });

    it('converts to array correctly', function (): void {
        $bounce = new TestResendBounceEvent(
            type: 'Permanent',
            message: 'Test bounce message',
            subType: 'General',
        );

        expect($bounce->toArray())->toBe([
            'type'    => 'Permanent',
            'message' => 'Test bounce message',
            'subType' => 'General',
        ]);
    });

    it('handles bounce type and sub-type combinations', function (string $type, string $subType): void {
        $bounce = new TestResendBounceEvent(
            type: $type,
            message: "Test {$type} {$subType}",
            subType: $subType,
        );

        expect($bounce->type)->toBe($type);
        expect($bounce->subType)->toBe($subType);
    })->with([
        'permanent general'           => ['Permanent', 'General'],
        'permanent suppressed'        => ['Permanent', 'Suppressed'],
        'temporary mailbox full'      => ['Temporary', 'MailboxFull'],
        'temporary message too large' => ['Temporary', 'MessageTooLarge'],
    ]);
});
