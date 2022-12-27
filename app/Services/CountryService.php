<?php

namespace App\Services;

use App\Repositories\Country\CountryRepository;
use Illuminate\Support\Arr;

class CountryService
{
    protected CountryRepository $countryRepository;

    /**
     * Create a new service instance.
     */
    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function getByName(array $data): array
    {
        if ($value = Arr::get($data, 'term')) {
            $this->countryRepository->where('name', 'like', "%$value%");
            $this->countryRepository->limit(10);
        }

        return $this->countryRepository->get();
    }
}
