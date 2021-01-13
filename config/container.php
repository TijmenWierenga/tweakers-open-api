<?php

use App\UserRepository;
use Slim\App;

/** @var $app App */

$pdo = new PDO(sprintf('sqlite:%s', '/database/db.sq3'));
$userRepository = new UserRepository($pdo);
