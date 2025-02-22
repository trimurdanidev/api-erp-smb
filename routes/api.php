<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterUserController;
use App\Http\Middleware\Authenticate;
use App\Http\Controllers\MasterDepartmentController;
use App\Http\Controllers\AbsensiController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
//Master User
Route::post('/add',[MasterUserController::class,'store']);
Route::get('/showAll',[MasterUserController::class,'index']);
Route::get('/showById/{id}',[MasterUserController::class,'showId']);
Route::get('/showByUser/{user}',[MasterUserController::class,'showUser']);
Route::put('/update/{id}',[MasterUserController::class,'update']);
Route::delete('/delete/{id}',[MasterUserController::class,'destroy']);

//Master Department
Route::post('/addDept',[MasterDepartmentController::class,'store']);
Route::get('/showAllDept',[MasterDepartmentController::class,'index']);
Route::get('/showDeptById/{id}',[MasterDepartmentController::class,'showId']);
Route::put('/updateDept/{id}',[MasterDepartmentController::class,'update']);
Route::delete('/deleteDept/{id}',[MasterDepartmentController::class,'destroy']);




Route::post('/login',[MasterUserController::class, 'login']);
// Route::post('/login',[Authenticate::class, 'login']);
Route::middleware('auth:api')->post('/logout',[MasterUserController::class, 'logout']);
//Absensi
//Master Department
Route::middleware('auth:api')->post('/addAbsen',[AbsensiController::class,'store']);
Route::middleware('auth:api')->put('/addAbsenOut',[AbsensiController::class,'update']);
Route::middleware('auth:api')->delete('/deleteDept',[AbsensiController::class,'destroy']);
Route::get('/getAbsenDay/{absensiId}/{dateAbsen}',[AbsensiController::class,'showAbsenDayUser']);
Route::get('/showAllAbsen',[AbsensiController::class,'index']);
Route::get('/showAbsenById/{id}',[AbsensiController::class,'showId']);
Route::get('/showAbsenByDept/{deptId}',[AbsensiController::class,'showByDept']);

