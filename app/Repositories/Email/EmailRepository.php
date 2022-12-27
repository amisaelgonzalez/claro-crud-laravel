<?php

namespace App\Repositories\Email;

use App\Models\Email;
use App\Repositories\BaseRepository;

class EmailRepository extends BaseRepository implements EmailRepositoryInterface
{
    /**
     * Create a new repository instance.
     */
    public function __construct(Email $email)
    {
        $this->model = $email;
    }
}
