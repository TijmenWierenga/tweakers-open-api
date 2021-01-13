<?php

declare(strict_types=1);

namespace App\ErrorHandling\ExceptionHandlers;

use App\ErrorHandling\ExceptionHandler;
use League\OpenAPIValidation\PSR15\Exception\InvalidServerRequestMessage;
use League\OpenAPIValidation\Schema\Exception\KeywordMismatch;
use Psr\Http\Message\ResponseInterface as Response;
use Throwable;

/**
 * @author Tijmen Wierenga <tijmen.wierenga@persgroep.net>
 */
final class InvalidServerRequestMessageHandler implements ExceptionHandler
{

    public function handle(Throwable $exception, Response $response): Response
    {
        $validationException = $exception->getPrevious();

        $response->getBody()->write(json_encode([
            'error' => $validationException->getMessage(),
            'details' => $this->renderDetails($validationException),
        ], JSON_THROW_ON_ERROR));

        return $response->withStatus(400);
    }

    public function canHandle(Throwable $exception): bool
    {
        return $exception instanceof InvalidServerRequestMessage;
    }

    private function renderDetails(Throwable $exception): ?array
    {
        if (! $exception->getPrevious()) {
            return null;
        }

        $detailedException = $exception->getPrevious();

        if ($detailedException instanceof KeywordMismatch) {
            $key = $detailedException->dataBreadCrumb()?->buildChain();

            return [
                'description' => $detailedException->getMessage(),
                'key' => $key ? implode('.', $key) : null,
            ];
        }

        return [
            'description' => $exception->getPrevious()->getMessage()
        ];
    }
}
