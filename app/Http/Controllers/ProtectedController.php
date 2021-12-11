<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

class ProtectedController extends Controller
{
    public function __construct() {
        $this->middleware(['auth:api','scope:get-protected']);
    }

    public function ping() {
        return response()->json([
            'message' => 'accessing private area'
        ], 200);
    }

    public function fileJson($file) {
        $jsonString = file_get_contents(base_path('sql/' . $file . '.json'));
        $jsonString = json_decode($jsonString);
        $collection = collect($jsonString);
        $array = $collection->toArray();
        // $countries = Country::create($array);
        // return $countries;
        foreach ($array as $country) {
            $single = collect($country)->toArray();
            $single_country = array();
            foreach ($country as $key => $value) {
                $single_country[$key] = $country[$key];
            }
            $hydrated = Country::hydrate($single_country);
            return $hydrated;
            $data = Country::create($single_country);
        }
        // return response()->json($jsonString, 200);
    }

    public function list_countries() {
        $countries = Country::all();
        return response()->json($countries, 200);
    }

    public function fileXml($file) {
        return response()->json([
            'file_name' => $file . '.xml'
        ],
        200,
        [ 'content-type' => 'application/xml']
        );
    }
}
