<?php

use App\Http\Controllers\ProjectTaskController;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('unauthenticated', function (): void {
    $project = Project::factory()
        ->has(Task::factory()->count(5), 'tasks')
        ->create();

    $response = $this->getJson(action(
        [ProjectTaskController::class, 'index'],
        ['project' => $project->id]
    ));
    $response->assertUnauthorized();
});

test('authenticated', function (): void {
    $project = Project::factory()
        ->has(Task::factory()->count(5), 'tasks')
        ->create();

    $response = $this->withHeader('X-SAMPL-SECRET', 'random')
        ->getJson(action(
            [ProjectTaskController::class, 'index'],
            ['project' => $project->id]
        ));
    $response->assertOk();
});
