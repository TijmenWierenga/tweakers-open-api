<?php

declare(strict_types=1);

namespace App\ErrorHandling\ExceptionHandlers;

use App\ErrorHandling\ExceptionHandler;
use App\ErrorHandling\Formatter;
use App\ErrorHandling\Formatters\SimpleExceptionFormatter;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpException;
use Throwable;

/**
 * @author Tijmen Wierenga <tijmen.wierenga@persgroep.net>
 */
final class HttpExceptionHandler implements ExceptionHandler
{
    private Formatter $formatter;

    public function __construct(Formatter $formatter = null)
    {
        if (! $formatter) {
            $this->formatter = new SimpleExceptionFormatter();
        }
    }

    public function handle(Throwable $exception, Response $response): Response
    {
        $response->getBody()->write(json_encode($this->formatter->format($exception), JSON_THROW_ON_ERROR));

        return $response->withStatus($exception->getCode());
    }

    public function canHandle(Throwable $exception): bool
    {
        return $exception instanceof HttpException;
    }
}
