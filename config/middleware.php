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

$validationMiddleware = (new ValidationMiddlewareBuilder)
    ->fromYamlFile(__DIR__ . '/../reference/demo-api.v1.yaml')
    ->getValidationMiddleware();

$app
    ->add(function (Request $request, RequestHandler $handler): Response {
        $response = $handler->handle($request);

        return $response->withAddedHeader('Content-Type', 'application/json');
    })
    ->add($validationMiddleware)
    ->addBodyParsingMiddleware();

$app->addRoutingMiddleware();

$errorHandler = new ErrorHandler(
    $app->getResponseFactory()->createResponse(),
    [
        new HttpExceptionHandler(),
        new InvalidServerRequestMessageHandler(),
        new UserNotFoundHandler(),
        new UsernameTakenHandler(),
    ]
);

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($errorHandler);
