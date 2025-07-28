<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ApiQuery;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectTaskController extends Controller
{
    use ApiQuery;

    public function index(Project $project, Request $request): AnonymousResourceCollection
    {
        // Get the tasks from the project and apply the api query helper.
        return TaskResource::collection(
            $project->tasks()
                ->select('tasks.*')
                ->join('users', 'users.id', '=', 'tasks.assigned_user_id')
                ->tap(fn (Builder $query): Builder => $this->apiQueryApply(
                    request: $request,
                    query: $query,
                    allowedFilterBy: ['status' => 'tasks.status'],
                    allowedSortBy: ['assigned_user_name' => 'users.name'],
                ))
                ->get(),
        );
    }
}
