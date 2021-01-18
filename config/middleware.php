<?php

use App\ErrorHandling\ErrorHandler;
use App\ErrorHandling\ExceptionHandlers\HttpExceptionHandler;
use App\ErrorHandling\ExceptionHandlers\InvalidServerRequestMessageHandler;
use App\ErrorHandling\ExceptionHandlers\UsernameTakenHandler;
use App\ErrorHandling\ExceptionHandlers\UserNotFoundHandler;
use League\OpenAPIValidation\PSR15\ValidationMiddlewareBuilder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\App;

/** @var $app App */

// This is where the OpenAPI validation middleware is instantiated.
// It loads the OpenAPI specification and will validate both request and response
$validationMiddleware = (new ValidationMiddlewareBuilder)
    ->fromYamlFile(__DIR__ . '/../reference/demo-api.v1.yaml')
    ->getValidationMiddleware();

$app
    ->add(function (Request $request, RequestHandler $handler): Response {
        $response = $handler->handle($request);

        return $response->withAddedHeader('Content-Type', 'application/json');
    })
    ->add($validationMiddleware) // The OpenAPI validation middleware is added to the middleware stack here
    ->addBodyParsingMiddleware();

$app->addRoutingMiddleware();

$errorHandler = new ErrorHandler(
    $app->getResponseFactory()->createResponse(),
    [
        new HttpExceptionHandler(),
        new InvalidServerRequestMessageHandler(), // This is a custom error handler that will return meaningful validation error messages
        new UserNotFoundHandler(),
        new UsernameTakenHandler(),
    ]
);

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($errorHandler);
