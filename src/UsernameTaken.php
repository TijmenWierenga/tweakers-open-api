<?php

declare(strict_types=1);

namespace App;

use RuntimeException;

/**
 * @author Tijmen Wierenga <tijmen.wierenga@persgroep.net>
 */
final class UsernameTaken extends RuntimeException
{
    public static function forUsername(string $username): self
    {
        return new self(sprintf('Username (%s) is already taken', $username));
    }
}
