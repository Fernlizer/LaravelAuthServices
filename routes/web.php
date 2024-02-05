<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\GoogleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::controller(GoogleController::class)->prefix('subcategory')->group(function () {
//     Route::get('/', 'page')->name('admin.service.subcategory');
//     Route::post('/AddSubCategory', 'addSubCategory')->name('admin.service.subcategory.add');
//     Route::post('/getSubCategory', 'getSubCategory')->name('admin.service.subcategory.get');
//     Route::post('/updateSubCategory', 'updateSubCategory')->name('admin.service.subcategory.update');
//     Route::post('/updateSubCategoryStatus', 'updateSubCategoryStatus')->name('admin.service.subcategory.update.status');
// });

Route::controller(GoogleController::class)->group(function () {
    Route::get('/google/redirect', 'redirectToGoogle')->name('google.redirect');
    Route::get('/callback/gg', 'handleGoogleCallback')->name('google.callback');
});
