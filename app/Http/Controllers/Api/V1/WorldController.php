<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\FilterCityRequest;
use App\Http\Requests\Api\V1\FilterCountryRequest;
use App\Http\Requests\Api\V1\FilterStateRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Support\Facades\Lang;

/**
 * @group  Worlds
 *
 * APIs for managing worlds
 */
class WorldController extends Controller
{
    /**
     * Get countries
     * @authenticated
     *
     * @responseFile 200 doc/api/v1/world/countries.success.json
     * @responseFile 401 doc/api/default/401.json
     * @responseFile 422 doc/api/default/422.json
     */
    public function countries(FilterCountryRequest $request)
    {
        $countries = Country::when($request->query('term'), function ($query, $value) {
                                $query->where('name', 'like', "%$value%");
                                $query->limit(10);
                            })
                            ->get();

        $resp = [
            'msg'   => Lang::get('response.get_countries'),
            'data'  => [
                'countries' => $countries
            ]
        ];
        return response()->json($resp, 200);
    }

    /**
     * Get states
     * @authenticated
     *
     * @responseFile 200 doc/api/v1/world/states.success.json
     * @responseFile 401 doc/api/default/401.json
     * @responseFile 422 doc/api/default/422.json
     */
    public function states(FilterStateRequest $request)
    {
        $states = State::when($request->query('term'), function ($query, $value) {
                            $query->where('name', 'like', "%$value%");
                            $query->limit(10);
                        })
                        ->when($request->query('country_id'), function ($query, $value) {
                            $query->where('country_id', $value);
                        })
                        ->get();

        $resp = [
            'msg'   => Lang::get('response.get_states'),
            'data'  => [
                'states' => $states
            ]
        ];
        return response()->json($resp, 200);
    }

    /**
     * Get cities
     * @authenticated
     *
     * @responseFile 200 doc/api/v1/world/cities.success.json
     * @responseFile 401 doc/api/default/401.json
     * @responseFile 422 doc/api/default/422.json
     */
    public function cities(FilterCityRequest $request)
    {
        $cities = City::when($request->query('term'), function ($query, $value) {
                            $query->where('name', 'like', "%$value%");
                            $query->limit(10);
                        })
                        ->when($request->query('state_id'), function ($query, $value) {
                            $query->where('state_id', $value);
                        })
                        ->get();

        $resp = [
            'msg'   => Lang::get('response.get_cities'),
            'data'  => [
                'cities' => $cities
            ]
        ];
        return response()->json($resp, 200);
    }
}
