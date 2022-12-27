<?php

namespace App\Repositories\City;

use App\Models\City;
use App\Repositories\BaseRepository;

class CityRepository extends BaseRepository implements CityRepositoryInterface
{
    /**
     * Create a new repository instance.
     */
    public function __construct(City $city)
    {
        $this->model = $city;
    }
}
