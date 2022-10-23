<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class WorldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function countries(Request $request)
    {
        $term = $request->query('term');
        $countries = Country::when($request->query('term'), function ($query, $value) {
                                $query->where('name', 'like', "%$value%");
                                $query->limit(10);
                            })
                            ->get();

        return response()->json([
            'countries' => $countries,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function states(Request $request)
    {
        $states = State::when($request->query('term'), function ($query, $value) {
                            $query->where('name', 'like', "%$value%");
                            $query->limit(10);
                        })
                        ->when($request->query('country_id'), function ($query, $value) {
                            $query->where('country_id', $value);
                        })
                        ->get();

        return response()->json([
            'states' => $states,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cities(Request $request)
    {
        $cities = City::when($request->query('term'), function ($query, $value) {
                            $query->where('name', 'like', "%$value%");
                            $query->limit(10);
                        })
                        ->when($request->query('state_id'), function ($query, $value) {
                            $query->where('state_id', $value);
                        })
                        ->get();

        return response()->json([
            'cities' => $cities,
        ]);
    }
}
