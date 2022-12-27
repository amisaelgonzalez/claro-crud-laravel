<?php

namespace App\Repositories\State;

use App\Models\State;
use App\Repositories\BaseRepository;

class StateRepository extends BaseRepository implements StateRepositoryInterface
{
    /**
     * Create a new repository instance.
     */
    public function __construct(State $state)
    {
        $this->model = $state;
    }
}
