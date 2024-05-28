<?php

use App\Http\Controllers\signupController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mainController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ExcelController;



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

Route::get('/dashboard',[dashboardController::class,'dashboard'])->name('dashboard');

// Route::get('/logout',function()
// {
//     return view('login');
// });
Route::get('/error_page', function () {
    return view('error_page');
})->name('error_page');

Route::get('/logout',[loginController::class,'logout'])->name('logout');

Route::view('/update', 'update');
Route::post('/update', [LoginController::class, 'updatePassword'])->name('update');

Route::post('/generate-certificate', [CertificateController::class, 'generate'])->name('generate-certificate');

Route::get('/certificate',function()
{
    return view('certificate');
});
Route::post('/preview', [CertificateController::class, 'generatePreview'])->name('generatePreview');

Route::get('/generate-excel',[ExcelController::class,'generateExcel'])->name('generateExcel');
