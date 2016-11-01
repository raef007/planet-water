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
Route::get('/admin/show/delivery', 'AdminController@showDeliveryForm');
Route::post('/admin/get/scheduled-delivery', 'AdminController@getScheduledDelivery');
Route::post('/admin/create-delivery', 'AdminController@createScheduledDelivery');
Route::post('/admin/add-refill-day/{vehicle_id}', 'AdminController@createRefillDay');
Route::get('/admin/cancel-delivery/{vehicle_delivery_id}', 'AdminController@cancelDelivery');

Route::get('/admin/delivery-summary/{vehicle_id}', 'AdminController@showDeliverySummary');
Route::post('/admin/send-summary-email', 'AdminController@sendSummaryEmail');

Route::get('/admin/transaction-log', 'AdminController@showTransactionLogs');
Route::post('/admin/logs-table', 'AdminController@showLogsTable');
Route::get('/cron/record-transaction', 'CronController@recordTransactionFromPast');
Route::post('/admin/save-transaction-log', 'AdminController@saveTransactionLogs');

Route::get('/admin/edit/vehicle/{vehicle_id}', 'VehicleController@showVehicleForm');
Route::post('/admin/submit/vehicle-information', 'VehicleController@submitVehicleForm');
Route::post('/admin/submit/ccat-form', 'VehicleController@submitCcatForm');
Route::get('/admin/delete/ccat/{ccat_id}', 'VehicleController@deleteCcat');

Route::post('/admin/add-vehicle-cat/{vehicle_id}', 'VehicleController@addVcat');
Route::get('/admin/delete/vcat/{category_id}', 'VehicleController@deleteVcat');
Route::post('/admin/save/delivery-remarks/{delivery_id}', 'AdminController@saveDeliveryRemarks');


