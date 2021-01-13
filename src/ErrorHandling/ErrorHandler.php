<?php

declare(strict_types=1);

namespace App\ErrorHandling;

use App\ErrorHandling\Formatters\SimpleExceptionFormatter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Throwable;

/**
 * @author Tijmen Wierenga <tijmen.wierenga@persgroep.net>
 */
final class ErrorHandler
{
    private Formatter $formatter;

    /**
     * @param ExceptionHandler[] $exceptionHandlers
     */
    public function __construct(
        private Response $response,
        private array $exceptionHandlers,
        Formatter $formatter = null
    ) {
        if (! $formatter) {
            $this->formatter = new SimpleExceptionFormatter();
        }
    }

    public function __invoke(Request $request, Throwable $exception): Response
    {
        $response = $this->response
            ->withStatus(500)
            ->withAddedHeader('Content-Type', 'application/json');

        foreach ($this->exceptionHandlers as $handler) {
            if ($handler->canHandle($exception)) {
                return $handler->handle($exception, $response);
            }
        }

        $response->getBody()->write(json_encode($this->formatter->format($exception), JSON_THROW_ON_ERROR));

        return $response;
    }
}
