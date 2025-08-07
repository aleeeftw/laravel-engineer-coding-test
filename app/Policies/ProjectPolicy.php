<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        return $user->id === $project->user_id
            || $project->tasks()
                ->where('tasks.assigned_user_id', '=', $user->id)
                ->exists();
    }
}
