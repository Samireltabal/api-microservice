<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return response()->json([
            'file_name' => $file . '.json'
        ], 200);
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
