<?php

use App\User;
use App\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

require __DIR__ . '/../config/environment.php';
require __DIR__ . '/../config/container.php';
require __DIR__ . '/../config/middleware.php';

/** @var UserRepository $userRepository */

$app->get('/users', function (Request $request, Response $response) use ($userRepository): Response {
    $users = $userRepository->all();

    $response->getBody()->write(json_encode($users, JSON_THROW_ON_ERROR));

    return $response;
});

$app->get('/users/{username}', function (Request $request, Response $response, array $args) use ($userRepository): Response {
    $username = $args['username'];

    $user = $userRepository->find($username);

    $response->getBody()->write(json_encode($user, JSON_THROW_ON_ERROR));

    return $response;
});

$app->post('/users', function (Request $request, Response $response) use ($userRepository): Response {
    $user = User::fromArray($request->getParsedBody());

    $userRepository->save($user);

    $response->getBody()->write(json_encode($user, JSON_THROW_ON_ERROR));

    return $response->withStatus(201)
        ->withAddedHeader('Location', sprintf('/users/%s', $user->username()));
});

$app->run();
