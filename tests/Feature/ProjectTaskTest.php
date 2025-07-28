<?php

use App\Http\Controllers\ProjectTaskController;
use App\Models\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('project task - filtering and sorting', function (): void {
    $project = Project::factory()
        ->has(Task::factory()->count(5), 'tasks')
        ->create();

    $this->actingAs($project->user);

    $tasks = $project->tasks
        ->where('status', '=', TaskStatus::ACTIVE->value)
        ->sortByDesc(fn (Task $task): string => $task->assigned_user->name);

    $response = $this->getJson(action(
        [ProjectTaskController::class, 'index'],
        [
            'project' => $project->id,
            'filterBy' => ['status' => 'active'],
            'sortBy' => ['assigned_user_name' => 'desc'],
            'offset' => 0,
            'limit' => 10,
        ]),
    );
    $response->assertOk();
    $response->assertJsonCount($tasks->count(), 'data');
    $response->assertExactJsonStructure([
        'data' => [
            '*' => [
                'id',
                'title',
                'status',
                'assigned_user_name',
            ],
        ],
    ]);
    $response->assertJson([
        'data' => array_map(
            fn (Task $task): array => [
                'id' => $task->id,
                'title' => $task->title,
                'status' => $task->status->value,
                'assigned_user_name' => $task->assigned_user->name,
            ],
            $tasks->values()->all()
        ),
    ]);
});
