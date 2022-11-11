<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;

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

Route::get('/', function () {
    return redirect('login');//view('welcome');
});

Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('/checkLogin', [UserController::class, 'checkLogin'])->name('checkLogin');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');
Route::get('/home', function () {
    if(session('email')){
        return view('home');
    } else {
        return redirect('login');
    }
});
Route::post('/saveProfile', [UserController::class, 'save'])->name('saveProfile');
Route::get('/profile', [UserController::class, 'profile'])->name('profile');
Route::get('/ranking', [UserController::class, 'ranking'])->name('ranking');
//Primera pantalla para crear test.
Route::get('/createTest', [TestController::class, 'createTest'])->name('create_test');
//Segunda pantalla para crear test.
Route::get('/initTest/{difficulty}', [TestController::class, 'initTest'])->name('init_test');
//Tercera pantalla para crear test.
Route::get('/newTest/{difficulty}/{category}/{complexity}', [TestController::class, 'newTest'])->name('new_test');
//Tercera pantalla para ejecutar test.
Route::get('/nextQuestion/{id}', [TestController::class, 'nextQuestion'])->name('nextQuestion');
Route::post('/test/disapprove', [TestController::class, 'disapprove'])->name('disapproveTest');
Route::post('/test/expireQuestion/{question_id}', [TestController::class, 'expireQuestion'])->name('expireQuestion');
Route::post('/test/processQuestion/{question_id}', [TestController::class, 'processQuestion'])->name('processQuestion');
Route::post('/addUser', [UserController::class, 'addUser'])->name('addUser');
?>