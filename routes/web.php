<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'PhotosController@index')->name('home');
Route::post('/fetch', 'PhotosController@fetch');
Route::post('/upload', 'PhotosController@upload');
Route::post('/delete', 'PhotosController@delete');
// Route::post('/download', 'PhotosController@download');
Route::get('/download2/{id}', 'PhotosController@download2');

// Reference
// https://www.youtube.com/watch?v=CjJh2Qen_Xg