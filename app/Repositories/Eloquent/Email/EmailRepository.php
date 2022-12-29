<?php

namespace App\Repositories\Eloquent\Email;

use App\Models\Email;
use App\Repositories\EloquentBaseRepository;

class EmailRepository extends EloquentBaseRepository implements EmailRepositoryInterface
{
    /**
     * Create a new repository instance.
     */
    public function __construct(Email $email)
    {
        $this->model = $email;
    }
}
