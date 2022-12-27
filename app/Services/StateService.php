<?php

namespace App\Services;

use App\Repositories\State\StateRepository;
use Illuminate\Support\Arr;

class StateService
{
    protected StateRepository $stateRepository;

    /**
     * Create a new service instance.
     */
    public function __construct(StateRepository $stateRepository)
    {
        $this->stateRepository = $stateRepository;
    }

    public function getByCountryAndSearch(array $data): array
    {
        if ($value = Arr::get($data, 'term')) {
            $this->stateRepository->where('name', 'like', "%$value%");
            $this->stateRepository->limit(10);
        }

        if ($value = Arr::get($data, 'country_id')) {
            $this->stateRepository->where('country_id', $value);
        }

        return $this->stateRepository->get();
    }
}
