<?php

namespace App\Services;

use App\Repositories\City\CityRepository;
use Illuminate\Support\Arr;

class CityService
{
    protected CityRepository $cityRepository;

    /**
     * Create a new service instance.
     */
    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function getByStateAndSearch(array $data): array
    {
        if ($value = Arr::get($data, 'term')) {
            $this->cityRepository->where('name', 'like', "%$value%");
            $this->cityRepository->limit(10);
        }

        if ($value = Arr::get($data, 'state_id')) {
            $this->cityRepository->where('state_id', $value);
        }

        return $this->cityRepository->get();
    }
}
