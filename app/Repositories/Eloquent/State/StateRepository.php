<?php

namespace App\Repositories\Eloquent\State;

use App\Models\State;
use App\Repositories\EloquentBaseRepository;

class StateRepository extends EloquentBaseRepository implements StateRepositoryInterface
{
    /**
     * Create a new repository instance.
     */
    public function __construct(State $state)
    {
        $this->model = $state;
    }
}
