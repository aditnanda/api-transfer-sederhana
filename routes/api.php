<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\RekeningAdminController;
use App\Http\Controllers\TransaksiTransferController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class,'login']);
    Route::post('register', [AuthController::class,'register']);
    Route::post('update-token', [AuthController::class,'update_token']);
});

Route::group([
    'middleware' => ['auth:api', 'checkToken'],
    'prefix' => 'auth'
], function () {
    Route::post('me', [AuthController::class,'me']);
});

Route::group([
    'middleware' => ['auth:api', 'checkToken'],
    'prefix' => 'bank'
], function () {
    Route::get('/', [BankController::class, 'index'])->name('bank.index'); //all data
    Route::post('/', [BankController::class, 'store'])->name('bank.store'); //create data
    Route::delete('/', [BankController::class, 'destroy'])->name('bank.destroy'); //delete data
    Route::put('/', [BankController::class, 'update'])->name('bank.edit'); //edit data by id
});

Route::group([
    'middleware' => ['auth:api', 'checkToken'],
    'prefix' => 'rekening-admin'
], function () {
    Route::get('/', [RekeningAdminController::class, 'index'])->name('rekening_admin.index'); //all data
    Route::post('/', [RekeningAdminController::class, 'store'])->name('rekening_admin.store'); //create data
    Route::delete('/', [RekeningAdminController::class, 'destroy'])->name('rekening_admin.destroy'); //delete data
    Route::put('/', [RekeningAdminController::class, 'update'])->name('rekening_admin.edit'); //edit data by id
});

Route::group([
    'middleware' => ['auth:api', 'checkToken'],
    'prefix' => 'transfer'
], function () {
    Route::get('/', [TransaksiTransferController::class, 'index'])->name('transfer.index'); //all data
    Route::post('/', [TransaksiTransferController::class, 'store'])->name('transfer.store'); //create data
});