<?php

declare(strict_types=1);

namespace App;

/**
 * @author Tijmen Wierenga <tijmen.wierenga@persgroep.net>
 */
final class User implements \JsonSerializable
{
    public function __construct(
        private string $username,
        private int $age
    )
    {}

    public function username(): string
    {
        return $this->username;
    }

    public function toArray(): array
    {
        return [
            'username' => $this->username,
            'age' => $this->age
        ];
    }

    public static function fromArray(array $user): self
    {
        return new self(
            $user['username'],
            (int) $user['age']
        );
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
