<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\dbs\BigDataCorpController;
use App\Http\Controllers\dbs\PlacaController;
use App\Http\Controllers\UpdateProfile;
use GuzzleHttp\Middleware;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'BDC'], function () {
    Route::post('/cpf/{type}', [BigDataCorpController::class, 'cpf'])->name('bigdatacorp.cpf');
    Route::post('/nome/{type}', [BigDataCorpController::class, 'nome'])->name('bigdatacorp.nome');
    Route::post('/telefone/{type}', [BigDataCorpController::class, 'telefone'])->name('bigdatacorp.telefone');
    Route::post('/email/{type}', [BigDataCorpController::class, 'email'])->name('bigdatacorp.email');
    Route::post('/placa/{type}', [BigDataCorpController::class, 'placa'])->name('bigdatacorp.placa');
});

Route::prefix('auth')->group(function(){
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [RegisterController::class, 'register']);
});


Route::group(['prefix' => 'dashboard', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/userInformation', [UpdateProfile::class, 'index']);
    Route::post('/updateUserInformation', [UpdateProfile::class, 'update']);

    Route::get('/logout', [LoginController::class, 'logout']);
    Route::get('/check', function () {
        return response()->json(['message'=> 'authenticated'], 200);
    });
});