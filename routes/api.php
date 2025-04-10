<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/checkuid', [UserController::class, 'checkUid']);
Route::post('/checkuni', [UserController::class, 'checkUni']);

Route::get('/allupmenu', [MenuController::class, 'getAllUpMenuData']);
Route::get('/allmenu', [MenuController::class, 'getAllMenuData']);
Route::get('/category', [MenuController::class, 'selectCategoryData']);
Route::post('/menudata', [MenuController::class, 'selectMenuFormData']);
Route::post('/createmenu', [MenuController::class, 'createMenuData']);
Route::post('/editmenu', [MenuController::class, 'editMenuData']);
Route::post('/checkmenuuni', [MenuController::class, 'checkMenuUni']);
Route::post('/deletemenu', [MenuController::class, 'deleteMenuData']); // 新增

Route::get('/storedata', [StoreController::class, 'getAllStoreData']);
Route::get('/selectcity', [StoreController::class, 'selectCityData']);
Route::post('/selectarea', [StoreController::class, 'selectAreaData']);
Route::post('/selectstore', [StoreController::class, 'selectStoreData']);
Route::post('/checkstoreuni', [StoreController::class, 'checkStoreUni']);
Route::post('/deletestore', [StoreController::class, 'deleteStoreData']); // 新增

Route::post('/createorder', [OrderController::class, 'createOrder']);
Route::post('/getorderdata', [OrderController::class, 'getOrderData']);
Route::post('/editorderstatusdata', [OrderController::class, 'editOrderStatusData']);
Route::post('/usergetorder', [OrderController::class, 'userGetOrderData']);
Route::post('/usergetdetailorder', [OrderController::class, 'userGetOrderDetailData']);
Route::post('/deleteorder', [OrderController::class, 'deleteOrderData']); // 新增

Route::get('/getalluser', [AdminController::class, 'getAllUserData']);
Route::post('/createadmindata', [AdminController::class, 'createAdminData']);
Route::post('/editadmindata', [AdminController::class, 'editAdminData']);
Route::post('/deleteadmindata', [AdminController::class, 'deleteAdminData']);

Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});