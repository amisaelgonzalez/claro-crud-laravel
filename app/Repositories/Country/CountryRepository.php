<?php

namespace App\Repositories\Country;

use App\Models\Country;
use App\Repositories\BaseRepository;

class CountryRepository extends BaseRepository implements CountryRepositoryInterface
{
    /**
     * Create a new repository instance.
     */
    public function __construct(Country $country)
    {
        $this->model = $country;
    }
}
