<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\TransactionsController;
use App\Http\Controllers\Api\WithdrawalsController;
use App\Http\Controllers\Api\WastesController;

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
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    //auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/update/{id}', [AuthController::class, 'update']);
    //admin
    Route::get('/admin', [AuthController::class, 'index']);
    Route::get('/admin/{id}', [AuthController::class, 'show']);
    Route::delete('/admin/{id}', [AuthController::class, 'destroy']);
    Route::put('/admin/role/{id}', [AuthController::class, 'updateRole']);
    //another
    Route::apiResource('category',CategoriesController::class);
    Route::apiResource('wastes',WastesController::class);
    Route::post('/transaction', [TransactionsController::class, 'store']);
    Route::get('/transaction/history', [TransactionsController::class, 'transaction_history']);
    Route::post('/withdrawals', [WithdrawalsController::class, 'store']);
    Route::get('/withdrawals/history', [WithdrawalsController::class, 'withdrawals_history']);
});
