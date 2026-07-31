<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    public function allowRestify(?User $user = null): bool
    {
        return true;
    }

    public function show(?User $user = null, Post $model): bool
    {
        return true;
    }

    public function store(User $user): bool
    {
        return true;
    }

    public function storeBulk(User $user): bool
    {
        return true;
    }

    public function update(User $user, Post $model): bool
    {
        return true;
    }

    public function updateBulk(User $user, Post $model): bool
    {
        return true;
    }

    public function deleteBulk(User $user, Post $model): bool
    {
        return true;
    }

    public function delete(User $user, Post $model): bool
    {
        return true;
    }
}
