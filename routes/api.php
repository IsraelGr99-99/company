<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//Indicamos que haremos uso de los tres controladores
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Creamos nuestras rutas de recursos
Route::resource('departments', DepartmentController::class);
Route::resource('employees', EmployeeController::class);
//Creamos rutas para mostrar todas la rutas
Route::get('employeesall',[EmployeeController::class,'all']);
Route::get('employeesbydepartment',[EmployeeController::class,'employeesbydepartment']);