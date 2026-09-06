<?php

namespace App\Support;

final class StatusPage
{
    /** @var array<int, array{title: string, description: string, retryable: bool}> */
    private const DEFINITIONS = [
        400 => ['title' => 'Request not accepted', 'description' => 'The request could not be completed. Check the information and try again.', 'retryable' => false],
        401 => ['title' => 'Sign in required', 'description' => 'Your session is no longer active. Sign in before continuing.', 'retryable' => false],
        403 => ['title' => 'Access denied', 'description' => 'You do not have permission to view this content or perform this action.', 'retryable' => false],
        404 => ['title' => 'Page not found', 'description' => 'The page may have moved, been removed, or the address may be incorrect.', 'retryable' => false],
        405 => ['title' => 'Action unavailable', 'description' => 'This action is not supported for the requested content.', 'retryable' => false],
        408 => ['title' => 'Request timed out', 'description' => 'The server took too long to respond. Check your connection and try again.', 'retryable' => true],
        409 => ['title' => 'Changes conflict', 'description' => 'This content changed elsewhere. Refresh it before submitting your changes again.', 'retryable' => true],
        419 => ['title' => 'Session expired', 'description' => 'Your secure session expired. Refresh the page before trying again.', 'retryable' => true],
        422 => ['title' => 'Check your entries', 'description' => 'Some submitted information needs attention before it can be saved.', 'retryable' => false],
        429 => ['title' => 'Too many requests', 'description' => 'Too many actions were submitted in a short period. Wait a moment and try again.', 'retryable' => true],
        500 => ['title' => 'Something went wrong', 'description' => 'The system could not complete the request. The technical details were logged safely.', 'retryable' => true],
        502 => ['title' => 'Service connection failed', 'description' => 'A required service is not responding. Try again shortly.', 'retryable' => true],
        503 => ['title' => 'Service unavailable', 'description' => 'The system is temporarily unavailable while maintenance is completed.', 'retryable' => true],
        504 => ['title' => 'Service timed out', 'description' => 'A required service took too long to respond. Try again shortly.', 'retryable' => true],
    ];

    /** @return array{status: int, title: string, description: string, retryable: bool} */
    public static function forStatus(int $status): array
    {
        $definition = self::DEFINITIONS[$status] ?? self::DEFINITIONS[500];

        return ['status' => $status, ...$definition];
    }

    public static function supports(int $status): bool
    {
        return isset(self::DEFINITIONS[$status]);
    }
}
