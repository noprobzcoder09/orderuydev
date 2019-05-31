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


Route::group(['prefix' => 'auth'], function() {
	Route::get('logout','Auth\LoginController@logout');
	Route::get('login','Auth\LoginController@showLoginForm');
	Route::post('login','Auth\LoginController@login');
	Route::post('sendresetpassword','Auth\LoginController@sendResetLinkEmail');
	Route::post('showResetForm','Auth\LoginController@showResetForm');
	Route::get('account','Controller@account');
	Route::post('password/reset','Auth\ResetPasswordController@reset');

	Route::post('ajax/logout','Auth\LoginController@logout');
	Route::post('ajax/login','Auth\LoginController@login');
});

Route::get('/plan/{sku}','Checkout@plan');
Route::get('/email-verify','Controller@verifyEmail');
Route::get('/get-deliverytime-byzone/{id}','Checkout@getDeliveryTimingByZone');

// Customer checkout
Route::group(['prefix' => 'order'], function() {

	Route::put('/addtocart','Customers\Checkout@addtocart');
	Route::get('/summary','Customers\Checkout@getOrderSummary');
	Route::put('/checkout','Customers\Checkout@checkout');
    Route::delete('/remove/{planId}','Customers\Checkout@removePlan');

	Route::group(['prefix' => 'coupon'], function() {
		Route::post('enter','Customers\Checkout@storeCoupon');
		Route::delete('delete','Customers\Checkout@removeCoupon');
	});

	Route::get('thank-you/{orderId}','Checkout@thankyou');
	Route::get('promo-inputs','Checkout@promoInputs');
	Route::get('/shipping','Checkout@getShippingData');

});

Route::group(['prefix' => 'checkout'], function() {
	Route::get('/','Checkout@checkout');
	Route::get('/delivery-timings-cutoff/by-delivery-zone-timings/{delivery_zone_timings_id}', 'Checkout@getDeliveryTimingsCutoff');
	Route::get('/delivery-timings-cutoff/by-timings/{delivery_timings_id}', 'Checkout@getDeliveryTimingsCutoff');
});

Route::group(['prefix' => 'registration'], function() {
    Route::put('save-session-register/','Customers\Checkout@saveSessionRegistration');
});

Route::get('/redirectusers', function() {
	return redirect((new \App\Repository\UsersRepository)->redirect());
});

Route::group(['prefix' => 'tests'], function() {
   	Route::get('test_connection', 'TestINFS@test_connection');
	Route::get('add_contact', 'TestINFS@add_contact');
	Route::get('update_contact', 'TestINFS@update_contact');
	Route::get('query_table', 'TestINFS@query_table');
	Route::get('get_card', 'TestINFS@get_card');
	Route::get('add_card', 'TestINFS@add_card');
	Route::get('get_product', 'TestINFS@get_product');
	Route::get('add_product', 'TestINFS@add_product');
	Route::get('add_order', 'TestINFS@create_order');
	Route::get('add_tag', 'TestINFS@manage_group');
	Route::get('assign_tag', 'TestINFS@assign_group');
	Route::get('remove_tag', 'TestINFS@remove_group');
	Route::get('check_status', 'TestINFS@checkStatus');
	Route::get('update_custom_field', 'TestINFS@updateCustomField'); 
	Route::get('test_cutover/{user_id}', 'TestINFS@test_cutover'); 
});

Auth::routes();


