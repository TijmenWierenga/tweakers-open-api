<?php

declare(strict_types=1);

namespace App\ErrorHandling\Formatters;

use App\ErrorHandling\Formatter;
use Throwable;

/**
 * @author Tijmen Wierenga <tijmen.wierenga@persgroep.net>
 */
final class SimpleExceptionFormatter implements Formatter
{

    public function format(Throwable $exception): array
    {
        return [
            'error' => $exception->getMessage()
        ];
    }
}
