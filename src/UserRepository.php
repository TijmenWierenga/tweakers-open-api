<?php

declare(strict_types=1);

namespace App;

use PDO;

/**
 * @author Tijmen Wierenga <tijmen.wierenga@persgroep.net>
 */
final class UserRepository
{
    public function __construct(
        private PDO $pdo
    )
    {}

    public function save(User $user): void
    {
        if ($this->exists($user)) {
            throw UsernameTaken::forUsername($user->username());
        }

        $statement = $this->pdo->prepare(<<<SQL
            INSERT INTO users (username, password)
            VALUES (:username, :password)
        SQL);

        $statement->execute($user->toArray());
    }

    public function find(string $username): User
    {
        $statement = $this->pdo->prepare(
            <<<SQL
                SELECT * FROM users WHERE username = :username
            SQL
        );

        $statement->execute(['username' => $username]);

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if (empty($user)) {
            throw UserNotFound::withUsername($username);
        }

        return User::fromArray($user);
    }

    /**
     * @return array<int, User>
     */
    public function all(): array
    {
        $statement = $this->pdo->query(
            <<<SQL
                SELECT * FROM users
            SQL
        );

        $users = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            static fn (array $userData): User => User::fromArray($userData),
            $users
        );
    }

    private function exists(User $user): bool
    {
        $statement = $this->pdo->prepare(
            <<<SQL
                SELECT COUNT(*) FROM users WHERE username = :username 
            SQL
        );

        $statement->execute([
            'username' => $user->username()
        ]);

        $count = (int) $statement->fetch(PDO::FETCH_COLUMN);

        return $count > 0;
    }
}
