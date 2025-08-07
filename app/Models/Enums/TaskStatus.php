<?php

namespace App\Models\Enums;

use App\Models\Enums\Traits\ValuesTrait;

/**
 * @implements ValuesTrait<string>
 */
enum TaskStatus: string
{
    use ValuesTrait;

    case COMPLETED = 'completed';
    case PENDING = 'pending';
}
