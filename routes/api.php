<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/articles', 'ArticleController@getWithAuthors');
Route::get('/articles/{id}', 'ArticleController@show');
Route::post('/articles', 'ArticleController@store');
Route::put('/articles/{id}', 'ArticleController@update');
Route::delete('/articles/{id}', 'ArticleController@delete');
Route::get('/authors', 'AuthorController@index');
Route::get('/authors/{id}', 'AuthorController@show');
