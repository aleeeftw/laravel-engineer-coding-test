<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

// This is a basic implementation of an api - query params filter, sort, offset and limit that can be reused in
// different controllers.
// Cursor pagination should be better so we could also implement that instead of offset and limit.
trait ApiQuery
{
    public function apiQueryApply(
        Request $request,
        Builder $query,
        array $allowedFilterBy = [],
        array $allowedSortBy = []
    ): Builder {
        // Get the filter by and bind it as an array.
        $filterBy = (array)$request->get(
            Config::get('api.controllers.query-keys.filterBy')
        );

        // Get the sort by and bind it as an array.
        $sortBy = (array)$request->get(
            Config::get('api.controllers.query-keys.sortBy')
        );

        // Get the offset, check the max and bind it as an int.
        $offset = max(
            (int)$request->get(Config::get('api.controllers.query-keys.offset')),
            Config::get('api.controllers.settings.offset-min')
        );

        // Get the limit, check the min and bind it as an int.
        $limit = min(
            (int)$request->get(Config::get('api.controllers.query-keys.limit')),
            Config::get('api.controllers.settings.limit-max')
        );

        $query->when(
            count($filterBy) > 0,
            // Apply filter by if we have any.
            function (Builder $query) use ($filterBy, $allowedFilterBy): Builder {
                foreach ($filterBy as $key => $value) {
                    // Check if the key is available in the provided allowed map.
                    if (!array_key_exists($key, $allowedFilterBy)) {
                        continue;
                    }

                    // If the value is an array apply whereIn.
                    if (is_array($value)) {
                        $query->whereIn($allowedFilterBy[$key], $value);
                        // If the value is a string apply a normal where.
                    } elseif (is_string($value)) {
                        // This is just for demonstration.
                        // We could extend this to accept different operators (!=, like...).
                        $query->where($allowedFilterBy[$key], '=', $value);
                    }
                }

                return $query;
            }
        )
            ->when(
                count($sortBy) > 0,
                // Apply sort by if we have any.
                function (Builder $query) use ($sortBy, $allowedSortBy): Builder {
                    foreach ($sortBy as $key => $value) {
                        // Check if the key is available in the provided allowed map.
                        if (!array_key_exists($key, $allowedSortBy)) {
                            continue;
                        }

                        // Make sure the direction is valid and apply it.
                        $direction = mb_strtolower($value);
                        if (is_string($value) && in_array($direction, ['asc', 'desc'], true)) {
                            $query->orderBy($allowedSortBy[$key], $direction);
                        }
                    }

                    return $query;
                }
            )
            // Apply the offset and limit.
            ->offset($offset)
            ->limit($limit);

        return $query;
    }
}
