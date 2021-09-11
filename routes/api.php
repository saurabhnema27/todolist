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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login','LoginRegisterController@Login');
Route::post('/register','LoginRegisterController@Register');

// middlware routes which include the ony login person can user

Route::middleware('auth:api')->group(function () {

    Route::get('/logout','LoginRegisterController@Logout');
    Route::get('/gettodolist','UserTodoListController@getTodoListwithItems');
    Route::post('/addtodolist','UserTodoListController@createTodoList');
    Route::post('/addtodoitem','UserTodoListController@addTodoItemsToList');
    Route::post('/changestatustodoitem','UserTodoListController@changeStatusOfTodoItem');
    Route::post('/assigntodoitem','UserTodoListController@assignTodoItemsToUser');
});
