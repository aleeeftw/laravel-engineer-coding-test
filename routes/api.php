<?php

use App\Http\Controllers\ProjectTaskController;
use App\Models\Task;

Route::middleware([
    'auth',
])->group(function (): void {
    Route::prefix('/projects')->group(function (): void {
        Route::prefix('/{project}')->group(function (): void {
            Route::get('/tasks', [ProjectTaskController::class, 'index'])
                ->can('view', 'project')
                ->can('viewAny', Task::class);
        });
    });
});
