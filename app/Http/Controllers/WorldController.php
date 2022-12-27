<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\V1\FilterCityRequest;
use App\Http\Requests\Api\V1\FilterCountryRequest;
use App\Http\Requests\Api\V1\FilterStateRequest;
use App\Services\CityService;
use App\Services\CountryService;
use App\Services\StateService;
use Illuminate\Http\JsonResponse;

class WorldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function countries(FilterCountryRequest $request, CountryService $countryService): JsonResponse
    {
        $countries = $countryService->getByName($request->validated());

        return response()->json(compact('countries'));
    }

    /**
     * Display a listing of the resource.
     */
    public function states(FilterStateRequest $request, StateService $stateService): JsonResponse
    {
        $states = $stateService->getByCountryAndSearch($request->validated());

        return response()->json(compact('states'));
    }

    /**
     * Display a listing of the resource.
     */
    public function cities(FilterCityRequest $request, CityService $cityService): JsonResponse
    {
        $cities = $cityService->getByStateAndSearch($request->validated());

        return response()->json(compact('cities'));
    }
}
