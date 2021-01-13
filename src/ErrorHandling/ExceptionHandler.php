<?php

declare(strict_types=1);

namespace App\ErrorHandling;

use Psr\Http\Message\ResponseInterface as Response;
use Throwable;

/**
 * @author Tijmen Wierenga <tijmen.wierenga@persgroep.net>
 */
interface ExceptionHandler
{
    public function handle(Throwable $exception, Response $response): Response;
    public function canHandle(Throwable $exception): bool;
}
