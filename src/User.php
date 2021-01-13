<?php

declare(strict_types=1);

namespace App;

use JsonSerializable;

/**
 * @author Tijmen Wierenga <tijmen.wierenga@persgroep.net>
 */
final class User implements JsonSerializable
{
    public function __construct(
        private string $username,
        private string $password
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
            'password' => $this->password,
        ];
    }

    public static function fromArray(array $user): self
    {
        return new self(
            $user['username'],
            $user['password']
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'username' => $this->username,
        ];
    }
}
