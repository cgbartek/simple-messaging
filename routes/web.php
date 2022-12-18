<?php

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



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::middleware(['auth'])->group(function () {
  Route::get('/', 'Web\SpaController@render');
  Route::get('/messages', 'Web\SpaController@render');

  Route::get('/api/messages/{id}/{channel?}', 'MessagesController@index')->name('messages');
  Route::post('/api/messages', 'MessagesController@store')->name('messages');
  Route::delete('/api/messages/{id}', 'MessagesController@destroy')->name('messagesdestroy');
  Route::get('/api/messagedusers', 'MessagesController@getMessagedUsers')->name('messagedusers');
  Route::get('/api/channels', 'ChannelsController@index')->name('channels');
  Route::post('/api/userinfo', 'UsersController@getUserInfo')->name('getuserinfo');
  Route::get('/api/usersinfo', 'UsersController@getUsersInfo')->name('getusersinfo');
  Route::get('/api/unack/{recipient}/{channel?}', 'MessagesController@getUnack')->name('getunack');
  Route::delete('/api/removelocinfo', 'UsersController@removeUserLocInfo')->name('removelocinfo');
});
