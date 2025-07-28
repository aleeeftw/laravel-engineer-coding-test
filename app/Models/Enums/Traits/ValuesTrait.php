<?php

namespace App\Models\Enums\Traits;

/**
 * @template T of string|int
 */
trait ValuesTrait
{
    /**
     * @return T[]
     */
    public static function values(): array
    {
        return array_map(fn(self $case): mixed => $case->value, self::cases());
    }
}
