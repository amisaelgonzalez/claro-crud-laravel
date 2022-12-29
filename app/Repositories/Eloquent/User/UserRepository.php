<?php

namespace App\Repositories\Eloquent\User;

use App\Models\User;
use App\Repositories\EloquentBaseRepository;

class UserRepository extends EloquentBaseRepository implements UserRepositoryInterface
{
    /**
     * Create a new repository instance.
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }
}
