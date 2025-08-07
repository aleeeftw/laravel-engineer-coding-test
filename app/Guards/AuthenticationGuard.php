<?php

namespace App\Guards;

use App\Models\Project;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class AuthenticationGuard implements Guard
{
    use GuardHelpers;

    public function __construct(
        private Request $request,
    ) {}

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function user(): ?Authenticatable
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        // Check if we have a secret provided through the X-SAMPL-SECRET header.
        $secret = $this->request->header(Config::get('api.header.key'));
        if (!(is_string($secret) && strlen($secret) > 0)) {
            return null;
        }

        // This is just for demonstration.
        // I am going to make the authenticated user as the user from the project - task route so the call
        // works nicely with the policies.
        $project = Project::find($this->request->route('project'));
        if (is_null($project?->user)) {
            return null;
        }

        $this->user = $project->user;

        return $this->user;
    }

    public function validate(array $credentials = []): bool
    {
        return !is_null(new static($credentials['request'])->user());
    }
}
