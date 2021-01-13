<?php

$databaseFile = '/database/db.sq3';

if (! file_exists($databaseFile)) {
    file_put_contents($databaseFile, '');
}

$pdo = new PDO(sprintf('sqlite:%s', $databaseFile));

$pdo->exec(
    <<<SQL
        CREATE TABLE IF NOT EXISTS users (
            username VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            PRIMARY KEY (username)
        )
    SQL
);
