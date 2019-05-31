<?php

/*
|--------------------------------------------------------------------------
| Private Routes
|--------------------------------------------------------------------------
|
| Here is where you can register private routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/auth-success', function(){
	return view('errors.200');
});

Route::get('/getcards','Controller@getcards');
Route::group(['middleware'=>'auth'], function () {
	Route::get('/','Customers@index')->middleware('can:manage')->name('dashboard');
	Route::get('/home', 'Customers@index')->middleware('can:manage')->name('dashboard.home');
	Route::get('/searchcustomer','Customers@searchCustomerViaNavigation');
});

/**
* Customer's Routes
*
*/
Route::group(['prefix' => 'customers','middleware'=>'auth'], function () {

	Route::get('/','Customers@index')->middleware('can:manage')->name('customers');
	Route::get('/settings','Customers@settings');
	Route::get('/list','Customers@masterlist');
	Route::get('/new/find-email','Customers@findEmail')->name('customers.find-email');
	Route::get('/new','Customers@new')->middleware('can:create')->name('customers.find-email.new');
	Route::get('/edit/{id}','Customers@view')->middleware('can:edit')->name('customers.edit');
	Route::get('/invoice/{id}','Customers@invoice')->name('customers.invoice.show');

	Route::post('/search','Customers@search');
	Route::put('/create','Customers@create');
	Route::post('/verify-email','Customers@verifyEmail');

	Route::get('/reset-password/{userId}','Customers@resetPassword');	
	
	Route::patch('/cancel-subscription/{userId}/{subscribeId}/{subscribeCycleId}','Customers@cancelSubscription');	
	Route::patch('/pause-subscription/{userId}/{subscribeId}','Customers@pauseSubscription');	
	Route::patch('/play-subscription/{userId}/{subscribeId}','Customers@playSubscription');	
	Route::patch('/updatestatus/{userId}/{subscriptionCycleId}','Customers@updatestatus');	
	
	Route::patch('/addmenuprevweekorderupdateplan','Customers\PreviousWeekSubscription@updatePlan');	
	Route::get('/addmenuprevweekcontent/{userId}/{subscriptionId}','Customers@addmenuprevweekcontent');
	Route::get('/addmenuprevweekordersummary','Customers\PreviousWeekSubscription@getOrderSummary');
	Route::put('/new-plan-previous-week/{userId}','Customers\PreviousWeekSubscription@createSubscription');
	Route::put('/new-plan-with-billing-previous-week/{userId}','Customers\PreviousWeekSubscription@createSubscriptionBilling');
	
	
	
	Route::patch('/update-profile/{id}','Customers@updateProfile');	
	Route::patch('/update-delivery/{id}','Customers@updateDelivery');
	Route::get('/future-delivery-timing-schedule/{userId}/{subscriptionCycleId}','Customers@futureDeliveryTimingSchedule');
	Route::put('/create-card','Customers\Card@create');


    Route::group(['prefix' => '{id}/audit'], function() {
    	Route::resource('logs', 'CustomerAuditLog', ['only' => ['index', 'show'], 'as' => 'customer.audit']);
    });

	/**
	* Subscriptions Routes
	*
	*/
	Route::get('/subscriptions/active','Customers@activeSubcriptions');
	Route::get('/subscriptions/past','Customers@pastSubcriptions');
	Route::get('/subscriptions/weeks','Customers@weeksSubcriptions');
	Route::get('/subscriptions/previousweeks','Dashboard@previousWeeksSubscription');
	Route::get('/subscriptions/menus','Customers@menusWeekSubcriptions');
	Route::get('/subscriptions/invoice-menu','Customers@invoiceWeeksSubscriptions');
	Route::get('/subscriptions/invoices','Customers@invoicesSubcriptions');

	Route::get('/subscriptions/past-weeks','Customers@pastWeeksSubcriptions');
	Route::get('/subscriptions/past-menus','Customers@pastMenusWeekSubcriptions');
	Route::get('/previous-menu-selections/{subscription_cycle_id}','Customers@loadPreviousMenuSelections');
	Route::post('/previous-menu-selections/update/{subscription_cycle_id}','Customers@updatePreviouseMenuSelections');
	


	Route::get('/logout','Dashboard@logout');
	Route::get('/menu-page','Dashboard@getMenuPage');
	Route::get('/subscriptionids','Dashboard@getSubscriptionIds');
	Route::get('/get-deliverytime-byzone/{zoneId}','Dashboard@getDeliveryZoneTimings');
	Route::put('/save-selections','Dashboard@saveSelections');
	Route::get('/getPlans','Dashboard@getPlans');
	Route::get('/getInvoices','Dashboard@getInvoices');
	Route::get('//next-delivery-date/{DZtimingId}','Dashboard@getNextDateDelivery');
	Route::patch('/cancell-all-plans','Dashboard@cancellAllPlans');
	Route::patch('/cancell-plans','Dashboard@cancellPlan');
	Route::patch('/save-stoptill-date','Dashboard@saveStopTillDate');
	Route::patch('/save-stop-all-till-date','Dashboard@saveStopAllTillDate');
	Route::patch('/cancel-paused-date','Dashboard@cancelPausedDate');
	Route::patch('/update-info-address','Dashboard@updateBillingInfo');
	
	Route::patch('/update-default-card','Controller@updateDefaultCard');
	
	// Customer dashboard add new card
	Route::put('/save-card','Customers\Dashboard\Card@create');
	// Update Profile
	Route::patch('/update-profile','Customers\Dashboard\Profile@update');
	// Update Password
	Route::patch('/update-password','Customers\Dashboard\Password@update');
	// update delivery
	Route::patch('/update-delivery-zone-timing','Customers\Dashboard\Delivery@update');

	// Customer dashboard Add new subscription
	Route::post('/new-plan','Customers\Dashboard\ManagePlans@createSubscription');
	Route::get('/store-coupon','Customers\Dashboard\ManagePlans@storeonCouponStorage');
	Route::get('/order-subscription-summary-client','Customers\Dashboard\ManagePlans@getOrderSummary');
	Route::delete('/remove-coupon','Dashboard\ManagePlans@removeCoupon');
	Route::patch('/client/updateplan','Customers\Dashboard\ManagePlans@updateplan');
	Route::get('/future-delivery-timing-schedule','Dashboard@futureDeliveryTimingSchedule');

	// Admin customer page
	Route::put('/new-plan/{userId}','Customers\Subscription@createSubscription');
	Route::put('/new-plan-with-billing/{userId}','Customers\Subscription@createSubscriptionBilling');
	Route::put('/store-coupon/{userId}','Customers\Subscription@storeonCouponStorage');
	Route::get('/order-subscription-summary','Customers\Subscription@getOrderSummary');
	Route::delete('/remove-coupon','Customers\Subscription@removeCoupon');
	Route::patch('/updateplan','Customers\Subscription@updateplan');

	// Billing issue
	Route::group(['prefix'=>'billing-issue'], function () {
		Route::get('/','Customers\BillingIssue@index')->middleware('can:manage')->name('customers.billing-issue');
		Route::get('/list','Customers\BillingIssue@list')->middleware('can:list');
		Route::get('/billnow','Customers\BillingIssue@billNow')->middleware('can:update');
		Route::get('/updatecard','Customers\BillingIssue@updateCard')->middleware('can:create');
		Route::get('/cancelweek','Customers\BillingIssue@cancelWeek')->middleware('can:update');
		Route::get('/cancelsubscription','Customers\BillingIssue@cancelsubscription')->middleware('can:update');
		Route::get('/card-content/{userId}','Customers\BillingIssue@cardContent')->middleware('can:update');
		Route::patch('/addnew-creditcard/{userId}','Customers\BillingIssue@createNewCreditCard')->middleware('can:create');
		Route::patch('/update-creditcard-default/{userId}','Customers\BillingIssue@updateCardDefault')->middleware('can:update');
		Route::patch('/billnow/{userId}','Customers\BillingIssue@subscriptionBillNow')->middleware('can:update');
		Route::patch('/cancelfortheweek/{userId}','Customers\BillingIssue@cancelForTheWeek')->middleware('can:update');
		Route::patch('/cancelsubscription/{userId}','Customers\BillingIssue@cancelsubscription')->middleware('can:update');
		
	});
});

Route::group(['middleware'=>'auth'], function () {
	Route::group(['prefix'=>'dashboard'], function () {
		Route::get('/','Dashboard@index');
		Route::get('/delivery/delivery-timings-settings','Dashboard@getDeliveryTimingsSettings');
		Route::group(['prefix'=>'billing-issue'], function () {
			Route::get('/view-subscriptions','Customers\Dashboard\BillingIssue@getBillingIssueSubscriptions');
			Route::patch('/cancel-subscription-cycle/{subscriptionId}/{subscriptionCycleId}','Customers\Dashboard\BillingIssue@cancelSubscriptionCycle');
			Route::patch('/cancel-subscription/{subscriptionId}/{subscriptionCycleId}','Customers\Dashboard\BillingIssue@cancelSubscription');
			Route::patch('/charge-card','Customers\Dashboard\BillingIssue@chargeCard');
			Route::patch('/update-card-and-bill','Customers\Dashboard\BillingIssue@updateCardAndBill');
		});

		Route::group(['prefix'=>'setup-delivery-zone-timing'], function () {
			Route::patch('/update-delivery-zone-timing','Customers\Dashboard\Delivery@updateDeliveryZoneTimingId');
		});
	});
});


/**
* Plans Routes
*
*/
Route::group(['prefix' => 'products','middleware'=>'auth'], function () {

	Route::get('/plan/new','Products\Plan@new')->middleware('can:create')								->name('products.plan.new');
	Route::get('/plan/edit/{id}','Products\Plan@edit')->middleware('can:edit')							->name('products.plan.edit');
	Route::get('/plan/all-plans','Products\Plan@masterlist')->middleware('can:list') 					->name('products.plan.all');
	Route::get('/plan/scheduler','Products\Plan@scheduler')->middleware('can:create')					->name('products.plan.scheduler');
	Route::get('/plan/schedule-list','Products\Plan@scheduleList')->middleware('can:list');
	Route::get('/plan/manage-meals-status/{id}','Products\Plan@manageMeals')->middleware('can:create');
	Route::get('/plan/meals/active','Products\Plan@activeMeals');
	Route::get('/plan/meals/inactive','Products\Plan@inactiveMeals');

	Route::post('/plan/verify-name','Products\Plan@verifyName')->middleware('can:manage');
	Route::post('/plan/verify-sku','Products\Plan@verifySku')->middleware('can:manage');
	Route::post('/plan/create','Products\Plan@create')->middleware('can:create');
	Route::post('/plan/update','Products\Plan@update')->middleware('can:update');
	Route::delete('/plan/delete/{id}','Products\Plan@delete')->middleware('can:delete');

	Route::patch('/plan/save-meals-status/{id}','Products\Plan@saveMealStatus')->middleware('can:edit');

	/**
	* Meals Routes
	*
	*/
	Route::get('/meals/new','Products\Meals@new')->middleware('can:create') 				->name('products.meal.new');
	Route::get('/meals/edit/{id}','Products\Meals@edit')->middleware('can:edit')			->name('products.meal.edit');
	Route::get('/meals/all-meals','Products\Meals@masterlist')->middleware('can:list')		->name('products.meal.all');
	Route::get('/meals/list','Products\Meals@list')->middleware('can:list');
	Route::get('/meals/active','Products\Meals@active');
	Route::get('/meals/inactive','Products\Meals@inactive');

	Route::post('/meals/verify-sku','Products\Meals@verifySku');
	Route::put('/meals/create','Products\Meals@create')->middleware('can:create');
	Route::patch('/meals/update','Products\Meals@update')->middleware('can:update');
	Route::delete('/meals/delete/{id}','Products\Meals@delete')->middleware('can:delete');


	/**
	* Meta Routes
	*
	*/


	Route::get('/meta/new','Products\Meta@new')->middleware('can:create');
	Route::get('/meta/edit/{id}','Products\Meta@edit')->middleware('can:edit');
	Route::get('/meta/all-meals','Products\Meta@masterlist')->middleware('can:list');
	Route::get('/meta/search-field','Products\Meta@searchField');

	Route::post('/meta/verify-sku','Products\Meta@verifySku');
	Route::put('/meta/create','Products\Meta@create')->middleware('can:create');
	Route::patch('/meta/update','Products\Meta@update')->middleware('can:update');
	Route::delete('/meta/delete/{mealId}/{metaDd}','Products\Meta@delete')->middleware('can:delete');
});


// Route::get('/products/meals/new','Meals@new');
// Route::get('/products/meals/all-meals','Meals@masterlist');
// Route::get('/products/meals/meta/new','Meals@meta');
// Route::get('/products/meals/edit/{id}','Meals@mealEdit');

// Route::post('/products/meals/add','Meals@store');
// Route::post('/products/meals/meta/add','Meals@metaStore');
// Route::post('/products/meals/meta/delete','Meals@metaDelete');


Route::group(['prefix' => 'delivery','middleware'=>'auth'], function () {
	
	/**
	* DZ Routes
	*/
	Route::get('/zone/new','Setup\Zone@new')->middleware('can:manage-setup')				->name('delivery.zone.new');
	Route::get('/zone/edit/{id}','Setup\Zone@edit')->middleware('can:manage-setup')			->name('delivery.zone.edit');
	Route::get('/zone/all-zones','Setup\Zone@masterlist')->middleware('can:manage-setup')	->name('delivery.zone.all');
	Route::get('/zone/list','Setup\Zone@listAll')->middleware('can:manage-setup')			->name('delivery.zone.listAll');

	Route::post('/zone/verify-name','Setup\Zone@verifyName');
	Route::put('/zone/create','Setup\Zone@create')->middleware('can:manage-setup');
	Route::patch('/zone/update','Setup\Zone@update')->middleware('can:manage-setup');
	Route::delete('/zone/delete/{id}','Setup\Zone@delete')->middleware('can:manage-setup');
	Route::patch('/zone/disabled/{id}','Setup\Zone@disabled')->middleware('can:manage-setup')	->name('delivery.zone.disabled');
});

Route::group(['prefix' => 'delivery','middleware'=>'auth'], function () {
	/**
	* Timings Routes
	*
	*/
	Route::get('/timing/new','Setup\Timing@new')->middleware('can:manage-setup')				->name('delivery.timing.new');
	Route::get('/timing/edit/{id}','Setup\Timing@edit')->middleware('can:manage-setup')			->name('delivery.timing.edit');
	Route::get('/timing/all-timings','Setup\Timing@masterlist')->middleware('can:manage-setup')	->name('delivery.timing.all');
		Route::get('/timing/list','Setup\Timing@listAll')->middleware('can:manage-setup')			->name('delivery.timing.listAll');
		
	Route::post('/timing/verify-name','Setup\Timing@verifyName');
	Route::put('/timing/create','Setup\Timing@create')->middleware('can:manage-setup');
	Route::patch('/timing/update','Setup\Timing@update')->middleware('can:manage-setup');
	Route::delete('/timing/delete/{id}','Setup\Timing@delete')->middleware('can:manage-setup');
	Route::patch('/timing/disabled/{id}','Setup\Timing@disabled')->middleware('can:manage-setup')	->name('delivery.timing.disabled');

});

Route::group(['prefix' => 'coupons','middleware'=>'auth'], function () {
	/**
	* Timings Routes
	*
	*/
	Route::get('/new','Setup\Coupons@new')->middleware('can:manage-setup')				->name('coupons.new');
	Route::get('/edit/{id}','Setup\Coupons@edit')->middleware('can:manage-setup')			->name('coupons.edit');
	Route::get('/all-coupons','Setup\Coupons@masterlist')->middleware('can:manage-setup')	->name('coupons.all');
	Route::put('/create','Setup\Coupons@create')->middleware('can:manage-setup');
	Route::patch('/update','Setup\Coupons@update')->middleware('can:manage-setup');
	Route::delete('/delete/{id}','Setup\Coupons@delete')->middleware('can:manage-setup');

});

Route::group(['prefix' => 'delivery/zone/timing','middleware'=>'auth'], function () {
	/**
	* Zone Timings Routes
	*
	*/
	Route::get('/new','Setup\ZT@new')->middleware('can:manage-setup')						->name('delivery.zone.timing.all');
	Route::get('/edit/{id}','Setup\ZT@edit')->middleware('can:manage-setup')				->name('delivery.zone.timing.edit');
	Route::get('/all-zone-timings','Setup\ZT@masterlist')->middleware('can:manage-setup')	->name('delivery.zone.timing.new');

	Route::post('/g/verify-name','Setup\ZT@verifyName');
	Route::put('/create','Setup\ZT@create')->middleware('can:manage-setup');
	Route::patch('/update','Setup\ZT@update')->middleware('can:manage-setup');
	Route::delete('/delete/{id}','Setup\ZT@delete')->middleware('can:manage-setup');

});

Route::group(['prefix' => 'users','middleware'=>['auth']], function () {
	/**
	* User Routes
	*/
	Route::get('/new','Users@new')->middleware('can:create') 			->name('users.all');
	Route::get('/edit/{id}','Users@edit')->middleware('can:edit') 		->name('users.edit');
	Route::get('/all-users','Users@masterlist')->middleware('can:list') ->name('users.new');
	Route::get('/change-password','Users@changePassword')->middleware('can:update')->name('users.changePassword');
	Route::put('/update-password','Users@updatePassword')->middleware('can:update')->name('users.updatePassword');

	Route::post('/verify-email','Users@verifyEmail');
	Route::put('/create','Users@create')->middleware('can:manage-users');
	Route::patch('/update','Users@update')->middleware('can:manage-users');
});

Route::group(['prefix' => 'reports','middleware'=>'auth'], function () {
	/**
	* Reports Routes
	*
	*/
	Route::get('/','Reports\Reports@index')->middleware('can:create')						->name('reports.all');
	Route::get('/generate','Reports\Reports@generate')->middleware('can:create');
	Route::get('/timings/{location}','Reports\Reports@timings')->middleware('can:create');
	Route::get('/get-historic-cycles/{timings_id}', 'Reports\Reports@getHistoricCycles')->middleware('can:create');
	
});

Route::group(['middleware'=>'auth'], function () {
	Route::group(['prefix' => 'cutover'], function() {

		Route::get('/resync-customer', function() {
			$date = Request::get('date');
			if (empty($date)) {
				return 'Date is required.';
			}
			$sync = new \App\Services\Cutover\ResyncCustomer($date);
			$sync->handle();

			return 1;
		});

		Route::get('/resync-customer-active-menu', function() {
			$date = Request::get('date');
			if (empty($date)) {
				return 'Date is required.';
			}
			$sync = new \App\Services\Cutover\ResyncCustomerActiveMenu($date);
			$sync->handle();

			return 1;
		});
		

		Route::get('/generatecycle', function() {
			$generator = new \App\Services\Cutover\Generate;
			$generator->handle();

			return 1;
		});

		Route::get('/billing', function() {
			$date = Request::get('date');
			if (empty($date)) {
				return 'Date is required.';
			}
			$cutover = new \App\Services\Cutover\Billing($date);
			$cutover->handle();

			return 1;
		});

		Route::get('/cycles', function() {
			$date = Request::get('date');
			if (empty($date)) {
				return 'Date is required.';
			}
			$cutover = new \App\Services\Cutover\Cycles($date);
			$cutover->handle();

			return 1;
		});

		Route::get('/failedbilling', function() {
			$date = Request::get('date');
			if (empty($date)) {
				return 'Date is required.';
			}
			$date = new \DateTime($date);
			$failed = new \App\Services\Customers\BillingIssue\FailedBilling($date);
			$failed->handle();

			return 1;
		});

		Route::get('/', function() {
			$date = Request::get('date');
			if (empty($date)) {
				return 'Date is required.';
			}

			$date = new \DateTime($date);

			$cycles = new \App\Services\Cutover\Cycles($date->format('Y-m-d H:i:s'));
			$billing = new \App\Services\Cutover\Billing($date->format('Y-m-d'));

			$cycles->handle();
			$billing->handle();

			return 1;
		});
	});
});

Route::get('/jobtest', function() {
		\App\Jobs\JobTest::dispatch()
		->delay(now()->addMinutes(1));
});

/* tests routes */
Route::group(['prefix' => 'tests'], function() {
    Route::get('/generate/excel','FeatureTests@export');
});


Route::group(['middleware'=>'auth'], function () {
	Route::group(['prefix' => 'infusionsoft'], function() {

		Route::group(['prefix' => 'oauth'], function() {
			Route::get('/authenticate', 'Infusionsoft\Auth@authenticate');
			Route::get('/callbacktoken', 'Infusionsoft\Auth@callbacktoken');
		});

		Route::group(['prefix' => 'sync'], function() {
			Route::get('/deliveryzone', function() {				
				$api = (new \App\Services\InfusionsoftV2\InfusionsoftFactory('oauth2'))->service();
				$deliveryZone = new \App\Services\Sync\Sync\DeliveryZone\DeliveryZoneSync($api);
				$sync = new \App\Services\Sync\Sync($deliveryZone);
				$sync->run($api);
			});
			Route::get('/customerinfs', function() {
				
			});
		});
	});
});


Route::group(['middleware' => 'auth'], function() {

    Route::group(['prefix' => 'audit'], function() {
        Route::resource('logs', 'AuditLog', ['only' => ['index', 'show'], 'as' => 'audit']);
    });

    Route::group(['prefix' => 'api/v1/audit'], function() {
        Route::resource('logs', 'Api\AuditLog', ['only' => ['index', 'show', 'edit', 'update', 'destroy'], 'as' => 'api.audit']);
        Route::post('credentials', 'Api\ActivityLogCredential@authenticate');
    });

    Route::group(['prefix' => 'settings'], function() {

		Route::group(['middleware' => 'can:manage-api'], function() {
			Route::resource('api', 'Api', ['only' => ['index'], 'as' => 'settings']);
		});

		Route::group(['prefix' => 'communication-settings'], function() {
			Route::get('/','CommunicationSettings@index')->name('settings.communication');
			Route::put('/update','CommunicationSettings@update')->name('settings.communication.update');
		});
	});
	
	Route::group(['prefix' => 'api/v1/settings'], function() {
	    Route::resource('api', 'Api\Api', ['only' => ['index', 'show'], 'as' => 'api.settings']);
	    Route::patch('api/{id}', 'Api\Api@update')->middleware('can:manage-api');
    });
});
