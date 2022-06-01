<?php

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
Route::namespace("App\\Http\\Controllers\\Api\\V1")->group(function () {
    //Rutas sin autentificacion
    Route::group(['middleware' => ['api']], function () {
        Route::post('/v1/register','ApiController@register');
        Route::post('/v1/login','ApiController@login');

        //Rutas con autencificacion por token
        Route::middleware('auth:api')->group(function () {
            Route::get('/v1/logout','ApiController@logout');
            Route::get('/v1/userData','ApiController@getUserDetail');
            Route::get('/v1/myPorfile','ApiController@myPorfile');

            //Novels Routes
            Route::get('/v1/novels/{indice}','NovelController@Index');
            Route::get('/v1/novels/{name}/{indice}','NovelController@IndexName');
            Route::get('/v1/novels/{genre}/{indice}','NovelController@indexGenre');
            Route::get('/v1/novel/{id}','NovelController@show');
            Route::get('/v1/novel/{id}/chapters/{indice}','NovelController@getChapters');
            Route::post('/v1/novel/create','NovelController@store');
            Route::post('/v1/novel/update','NovelController@update');
            Route::get('/v1/novel/delete/{id}','NovelController@destroy');
            Route::get('/v1/novel/{id}/addGenre/{genre}','NovelController@addGenre');
            Route::get('/v1/novel/{id}/vote/{starts}','NovelController@vote');
            Route::post('/v1/novel/{id}/coop','NovelController@coop');
            Route::get('/v1/genres','NovelController@genres');
            Route::get('/v1/novel/report/{indice}','NovelController@report');

            //Review Routes
            Route::get('/v1/novel/{novel_id}/reviews/{indice}','ReviewController@index');
            Route::post('/v1/review/create','ReviewController@store');
            Route::get('/v1/review/delete/{id}','ReviewController@destroy');
            Route::get('/v1/review/report/{indice}','ReviewController@report');

            //Comments Routes
            Route::get('/v1/chapter/{id_chapter}/comments/{indice}','CommentController@Index');
            Route::post('/v1/comment/create','CommentController@store');
            Route::get('/v1/comment/delete/{id}','CommentController@destroy');
            Route::get('/v1/comment/report/{indice}','CommentController@report');

            //Users Routes
            Route::get('/v1/user/{indice}','UserController@Index');
            Route::get('/v1/user/{indice}/{username}','UserController@IndexName');
            Route::get('/v1/user/novels','UserController@getNovels');
            Route::get('/v1/user/message','UserController@Message');
            Route::post('/v1/user/update','UserController@Update');
            Route::get('/v1/user/closeCount','UserController@CloseCount');

            //Chapters Routes
            Route::post('/v1/chapter/create','ChapterController@store');
            Route::get('/v1/chapter/{indice}','ChapterController@show');
            Route::post('/v1/chapter/update','ChapterController@update');
            Route::get('/v1/chapter/{indice}/publish','ChapterController@publish');
            Route::get('/v1/chapter/delete/{indice}','ChapterController@destroy');
        });
    });
});
