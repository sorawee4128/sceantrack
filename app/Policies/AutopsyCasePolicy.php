<?php

namespace App\Policies;

use App\Models\AutopsyCase;
use App\Models\User;

class AutopsyCasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage autopsy cases') || $user->can('view own autopsy cases');
    }

    public function view(User $user, AutopsyCase $autopsyCase): bool
    {
        if ($user->can('manage autopsy cases')) {
            return true;
        }

        return $user->can('view own autopsy cases') && $autopsyCase->isOwnedBy($user);
    }

    public function create(User $user): bool
    {
        return $user->can('manage autopsy cases')
            || $user->can('submit autopsy cases')
            || $user->can('edit own draft');
    }

    public function update(User $user, AutopsyCase $autopsyCase): bool
    {
        if ($user->can('manage autopsy cases')) {
            return true;
        }

        return $user->can('edit own draft')
            && $autopsyCase->isOwnedBy($user)
            && $autopsyCase->isDraft();
    }

    public function delete(User $user, AutopsyCase $autopsyCase): bool
    {
        return $this->update($user, $autopsyCase);
    }

    public function submit(User $user, AutopsyCase $autopsyCase): bool
    {
        if ($user->can('manage autopsy cases')) {
            return true;
        }

        return $user->can('submit autopsy cases')
            && $autopsyCase->isOwnedBy($user)
            && $autopsyCase->isDraft();
    }
}
