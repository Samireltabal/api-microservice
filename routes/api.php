<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProtectedController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function() {
    return response()->json([
        'message' => 'Countries api is responding',
        'status'  => 'GOOD'
    ], 200);
});

Route::post('/auth/signup', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/auth/user', [AuthController::class, 'user']);
Route::get('/auth/generate', [AuthController::class, 'generate_api_key']);
Route::get('/auth/revoke/{token_id}', [AuthController::class, 'revokeToken']);

Route::get('/protected/ping', [ProtectedController::class, 'ping']);
Route::get('/get/{file}.json', [ProtectedController::class, 'fileJson']);
Route::get('/get/{file}.xml', [ProtectedController::class, 'fileXml']);
Route::get('/countries/list', [ProtectedController::class, 'list_countries']);

