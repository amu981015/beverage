<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;

Route::get('/', [IndexController::class, 'index'])->name('home');

Route::get('/menu', [MenuController::class, 'getAllUpMenus'])->name('menus.up');

Route::get('/faq', [IndexController::class, 'faq']);
Route::get('/about', [IndexController::class, 'about']);

Route::get('/map', [StoreController::class, 'showMap'])->name('map');
Route::get('/stores', [StoreController::class, 'getAllStores'])->name('stores.all');
Route::post('/stores/areas', [StoreController::class, 'getAreas'])->name('stores.areas');

Route::get('/register', [IndexController::class, 'register'])->name('register.form');
Route::get('/login', [IndexController::class, 'login']);
Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/checkuni', [AuthController::class, 'checkUsernameUnique'])->name('checkuni');
Route::post('/checkuid', [AuthController::class, 'checkUid'])->name('checkuid');;

Route::get('/admin', function () {
    return view('admin.admin');
})->name('admin');

Route::get('/categories', [MenuController::class, 'getCategories'])->name('categories');
Route::get('/upcategories', [MenuController::class, 'getupCategories'])->name('upcategories');
Route::post('/menudata', [MenuController::class, 'getMenusByCategory'])->name('menudata');
Route::post('/createorder', [OrderController::class, 'create'])->name('createorder');
Route::post('/usergetorder', [OrderController::class, 'userGetOrder'])->name('usergetorder');
Route::post('/usergetdetailorder', [OrderController::class, 'userGetOrderDetail'])->name('usergetdetailorder');
Route::get('/selectcity', [StoreController::class, 'getCities'])->name('selectcity');
Route::post('/selectarea', [StoreController::class, 'getAreas'])->name('selectarea');
Route::post('/selectstore', [StoreController::class, 'getStoresByCityAndArea'])->name('selectstore');

Route::get('/storebackground', function () {
    return view('admin.storebackground');
})->name('storebackground');
Route::post('/editorderstatus', [OrderController::class, 'editOrderStatus'])->name('editorderstatus');
Route::post('/getstoreorders', [OrderController::class, 'getStoreOrders'])->name('getstoreorders');

Route::post('/getorderdata', [OrderController::class, 'getOrderData']);


Route::post('/createmenu', [MenuController::class, 'create'])->name('createmenu');
Route::post('/editmenu', [MenuController::class, 'update'])->name('editmenu');
Route::post('/checkmenuuni', [MenuController::class, 'checkMenuUnique'])->name('checkmenuuni');
Route::get('/allupmenu', [MenuController::class, 'getAllUpMenus']);
Route::get('/allmenu', [MenuController::class, 'getAllMenus'])->name('allmenu');
Route::get('/category', [MenuController::class, 'getCategories']);

Route::get('/storedata', [StoreController::class, 'getAllStores']);

Route::get('/admin/background', function () {
    return view('admin.background');
})->name('admin.background');
Route::get('/getalluser', [UserController::class, 'getAllUsers'])->name('getalluser');
Route::post('/createadmindata', [UserController::class, 'createAdmin'])->name('createadmindata');
Route::post('/editadmindata', [UserController::class, 'editAdmin'])->name('editadmindata');
Route::post('/deleteadmindata', [UserController::class, 'deleteAdmin'])->name('deleteadmindata');
Route::post('/checkadminuni', [UserController::class, 'checkAdminUnique'])->name('checkadminuni');
Route::post('/store/check', [StoreController::class, 'checkStoreUnique'])->name('store.check');
