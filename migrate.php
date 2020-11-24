<?php

$databaseFile = '/database/db.sq3';

if (! file_exists($databaseFile)) {
    file_put_contents($databaseFile, '');
}

$pdo = new PDO(sprintf('sqlite:%s', $databaseFile));

$pdo->exec(
    <<<SQL
        CREATE TABLE IF NOT EXISTS users (
            username varchar(255),
            age integer
        )
    SQL
);
