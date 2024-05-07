<?php

use App\Http\Controllers\signupController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mainController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\dashboardController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/',function()
{
    return view('login');
});
Route::get('/admin',function()
{
    return view('index');
});
Route::get('/signup',function()
{
    return view('signup');
});
Route::get('/update',function()
{
    return view('update');
});
Route::get('/dashboard',function()
{
    return view('dashboard');
});

Route::post('/signup',[signupController::class,'signup']);

Route::post('/login', [LoginController::class, 'login'])->name('login');

// crud
Route::get('/getData',[mainController::class,'getData']);
Route::post('/insertData',[mainController::class,'insertData']);
Route::get('/getCountries',[mainController::class,'getCountries']);
Route::post('/getStates',[mainController::class,'getStates']);
Route::post('/getCountries',[mainController::class,'getCountries']);
Route::post('/updateData',[mainController::class,'updateData']);
Route::post('/deleteData/{id}',[mainController::class,'deleteData']);

Route::post('/update',[loginController::class,'updatePassword'])->name('update');
Route::get('/dashboard',[dashboardController::class,'dashboard'])->name('dashboard');

Route::get('/logout',function()
{
    return view('login');
});
Route::get('/error',function()
{
    return view('error_page');
});