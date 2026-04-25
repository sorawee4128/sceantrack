<?php

namespace App\Policies;

use App\Models\SceneCase;
use App\Models\User;

class SceneCasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage scene cases') || $user->can('view own scene cases');
    }

    public function view(User $user, SceneCase $sceneCase): bool
    {
        if ($user->can('manage scene cases')) {
            return true;
        }

        return $user->can('view own scene cases') && $sceneCase->isOwnedBy($user);
    }

    public function create(User $user): bool
    {
        return $user->can('manage scene cases')
            || $user->can('submit scene cases')
            || $user->can('edit own draft');
    }

    public function update(User $user, SceneCase $sceneCase): bool
    {
        if ($user->can('manage scene cases')) {
            return true;
        }

        return $user->can('edit own draft')
            && $sceneCase->isOwnedBy($user)
            && $sceneCase->isDraft()
            && $sceneCase->created_at->diffInHours(now()) <= 24;
    }

    public function delete(User $user, SceneCase $sceneCase): bool
    {
        return $this->update($user, $sceneCase);
    }

    public function submit(User $user, SceneCase $sceneCase): bool
    {
        if ($user->can('manage scene cases')) {
            return true;
        }

        return $user->can('submit scene cases')
            && $sceneCase->isOwnedBy($user)
            && $sceneCase->isDraft();
    }
}
