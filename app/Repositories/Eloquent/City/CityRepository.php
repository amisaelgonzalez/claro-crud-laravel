<?php

namespace App\Repositories\Eloquent\City;

use App\Models\City;
use App\Repositories\EloquentBaseRepository;

class CityRepository extends EloquentBaseRepository implements CityRepositoryInterface
{
    /**
     * Create a new repository instance.
     */
    public function __construct(City $city)
    {
        $this->model = $city;
    }
}
