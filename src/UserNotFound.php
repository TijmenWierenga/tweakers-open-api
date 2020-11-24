<?php

declare(strict_types=1);

namespace App;

use RuntimeException;

/**
 * @author Tijmen Wierenga <tijmen.wierenga@persgroep.net>
 */
final class UserNotFound extends RuntimeException
{
    public static function withUsername(string $username): self
    {
        return new self(sprintf('User {%s} does not exist', $username));
    }
}
