<?php

declare(strict_types=1);

namespace App\ErrorHandling;

use Throwable;

/**
 * @author Tijmen Wierenga <tijmen.wierenga@persgroep.net>
 */
interface Formatter
{
    public function format(Throwable $exception): array;
}
