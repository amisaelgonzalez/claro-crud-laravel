<?php

namespace App\Repositories\Eloquent\Country;

use App\Models\Country;
use App\Repositories\EloquentBaseRepository;

class CountryRepository extends EloquentBaseRepository implements CountryRepositoryInterface
{
    /**
     * Create a new repository instance.
     */
    public function __construct(Country $country)
    {
        $this->model = $country;
    }
}
