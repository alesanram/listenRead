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
    return view('auth.login');
});
Auth::routes();
Route::namespace("App\\Http\\Controllers")->group(function () {
//Rutas con autencificacion
    Route::group(['middleware' => ['auth','admin']],function () {
        Route::get('/home', 'HomeController@index')->name('home');

        //Genre Route
        Route::get('/genre', 'GenreController@index')->name('genre.index');
        Route::get('/genre/create', 'GenreController@create')->name('genre.create');
        Route::post('/genre/store', 'GenreController@store')->name('genre.store');
        Route::post('/genre/edit', 'GenreController@edit')->name('genre.edit');
        Route::post('/genre/update', 'GenreController@update')->name('genre.update');
        Route::post('/genre/delete', 'GenreController@destroy')->name('genre.destroy');

        //Tag Route
        Route::get('/tag', 'tagController@index')->name('tag.index');
        Route::get('/tag/create', 'tagController@create')->name('tag.create');
        Route::post('/tag/store', 'tagController@store')->name('tag.store');
        Route::post('/tag/edit', 'tagController@edit')->name('tag.edit');
        Route::post('/tag/update', 'tagController@update')->name('tag.update');
        Route::post('/tag/delete', 'tagController@destroy')->name('tag.destroy');

        //User Route
        Route::get('/user', 'UserController@index')->name('user.index');
        Route::post('/userN', 'UserController@indexN')->name('user.indexN');
        Route::post('/user/delete', 'UserController@destroy')->name('user.destroy');

        //Novel Route
        Route::get('/novel', 'novelController@index')->name('novel.index');
        Route::post('/novelN', 'novelController@indexN')->name('novel.indexN');
        Route::post('/novel/delete', 'novelController@destroy')->name('novel.destroy');

        //Comment Route
        Route::get('/comment', 'CommentController@index')->name('comment.index');
        Route::post('/commentN', 'CommentController@indexN')->name('comment.indexN');
        Route::post('/comment/delete', 'CommentController@destroy')->name('comment.destroy');

        //Review Route
        Route::get('/review', 'ReviewController@index')->name('review.index');
        Route::post('/reviewN', 'ReviewController@indexN')->name('review.indexN');
        Route::post('/review/delete', 'ReviewController@destroy')->name('review.destroy');

        //Notifications Route
        Route::get('/message/create','NotificationController@message')->name('notification.create');
        Route::get('/novel/report','NotificationController@reportNovel')->name('notification.reportNovel');
        Route::get('/review/report','NotificationController@reportReview')->name('notification.reportReview');
        Route::get('/comment/report','NotificationController@reportComment')->name('notification.reportComment');
        Route::get('/user/close','NotificationController@closeCount')->name('notification.closeCount');
        Route::post('/message/send', 'NotificationController@send')->name('notificaton.send');
    });
});
