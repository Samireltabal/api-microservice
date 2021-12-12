<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

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
        $jsonString = json_decode($jsonString, true);
        
        foreach ($jsonString as $value) {
            $data = City::firstOrCreate($value);
        }

        return response()->json('ok', 201);
        // return response()->json($jsonString, 200);
    }

    public function fileSql($file) {
        $file = file_get_contents(base_path('sql/' . $file . '.sql'));        
        \DB::beginTransaction();
        try {
            \DB::unprepared($file);
        } catch (\Throwable $th) {
            \DB::rollback();
            return $th;    
        }
        
        \DB::commit();
        return response()->json(['message' => 'ok'], 201);
    }

    public function list_countries() {
        $countries = Country::get();
        return response()->json($countries, 200);
    }

    public function list_states($country) {
        $states = State::where('country_id', '=', $country)->get();
        return response()->json($states, 200);
    }

    public function list_cities($country, $state) {
        $cities = City::where('country_id', '=', $country)->where('state_id', '=', $state)->get();
        return response()->json($cities, 200);
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
