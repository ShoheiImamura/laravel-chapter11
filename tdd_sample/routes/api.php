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

Route::get('customers', 'ApiController@getCustomers');
Route::post('customers', 'ApiController@postCustomer');

Route::get('customers/{customer_id}','ApiController@getCustomer');
Route::put('customers/{customer_id}', 'ApiController@putCustomer');
Route::delete('customers/{customer_id}', 'ApiController@deleteCustomer');
Route::get('reports', 'ApiController@getReports');
Route::post('reports', 'ApiController@postReport');
Route::get('reports/{reports_id}', 'ApiController@getReport');
Route::put('reports/{reports_id}', 'ApiController@putReport');
Route::delete('reports/{reports_id}', 'ApiController@deleteReport');
