<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\FilterCityRequest;
use App\Http\Requests\Api\V1\FilterCountryRequest;
use App\Http\Requests\Api\V1\FilterStateRequest;
use App\Services\ApiResponse;
use App\Services\CityService;
use App\Services\CountryService;
use App\Services\StateService;
use Illuminate\Http\JsonResponse;

/**
 * @group  Worlds
 *
 * APIs for managing worlds
 */
class WorldController extends Controller
{
    /**
     * Get countries
     *
     * @responseFile 200 doc/api/v1/world/countries.success.json
     * @responseFile 401 doc/api/default/401.json
     * @responseFile 422 doc/api/default/422.json
     */
    public function countries(FilterCountryRequest $request, CountryService $countryService): JsonResponse
    {
        $countries = $countryService->getByName($request->validated());

        return ApiResponse::fullList(compact('countries'));
    }

    /**
     * Get states
     *
     * @responseFile 200 doc/api/v1/world/states.success.json
     * @responseFile 401 doc/api/default/401.json
     * @responseFile 422 doc/api/default/422.json
     */
    public function states(FilterStateRequest $request, StateService $stateService): JsonResponse
    {
        $states = $stateService->getByCountryAndSearch($request->validated());

        return ApiResponse::fullList(compact('states'));
    }

    /**
     * Get cities
     *
     * @responseFile 200 doc/api/v1/world/cities.success.json
     * @responseFile 401 doc/api/default/401.json
     * @responseFile 422 doc/api/default/422.json
     */
    public function cities(FilterCityRequest $request, CityService $cityService): JsonResponse
    {
        $cities = $cityService->getByStateAndSearch($request->validated());

        return ApiResponse::fullList(compact('cities'));
    }
}
