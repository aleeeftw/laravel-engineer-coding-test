<?php

namespace App\Models\Enums;

use App\Models\Enums\Traits\ValuesTrait;

/**
 * @implements ValuesTrait<string>
 */
enum TaskStatus: string
{
    use ValuesTrait;

    case ACTIVE = 'active';
    case PENDING = 'pending';
}
