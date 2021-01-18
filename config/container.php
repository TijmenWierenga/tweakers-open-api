<?php

use App\UserRepository;
use Slim\App;

/** @var $app App */

$pdo = new PDO($_ENV['DATABASE_DSN']);
$userRepository = new UserRepository($pdo);
