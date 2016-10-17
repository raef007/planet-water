<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'HomeController@showHomeDashboard');
Route::get('/dashboard', 'HomeController@showHomeDashboard');

Route::post('/add/litres-update', 'HomeController@submitLitresUpdate');
Route::get('/delete/litres/{update_id}', 'HomeController@deleteLitresUpdate');

Route::get('/login', 'AccountLoggingController@showLoginPage');
Route::post('/login', 'AccountLoggingController@submitLoginCredentials');
Route::get('/redirect-user', 'AccountLoggingController@userTypeRedirection');
Route::get('/logout', 'AccountLoggingController@userLogout');

Route::get('/admin/customer/list', 'AdminController@showCustomerList');
Route::get('/admin/customer/{tank_id}', 'AdminController@showCustomerDashboard');
Route::post('admin/update/customer-litres', 'AdminController@updateCustomerLitres');
Route::get('admin/edit/litres/{update_id}', 'AdminController@getReadingDate');

Route::get('admin/add/customer', 'AdminController@showAddCustomerForm');
Route::get('admin/edit/customer/{tank_id}', 'AdminController@showEditCustomerForm');
Route::post('admin/submit/customer-information', 'AdminController@submitCustomerForm');

Route::get('/admin/dashboard', 'AdminController@showAdminDashboard');
Route::get('/admin/delivery-planner/{vehicle_id}', 'AdminController@showDeliveryPlanner');


