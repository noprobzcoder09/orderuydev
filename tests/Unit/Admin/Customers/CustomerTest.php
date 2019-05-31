<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\Users;
use App\Models\UserDetails;
use App\Models\UserAddress;
use App\Models\DeliveryZoneTimings;
use App\Models\MealPlans;
use App\Models\SubscriptionsSelections;
use Log;

class CustomerTest extends TestCase
{

    use WithoutMiddleware;
    use WithFaker;
    

    private $testExistCustomer;
    private $testExistCustomerDetails;
    private $testExistCustomerAddress;
    private $testZone;
    private $testZoneTimings;
    private $testMealPlans;
    private $testSubscriptionCycles;

   

    protected function setUp() {
        parent::setUp(); 

        $this->testExistCustomer = Users::with('details')->find(2);
        $this->testExistCustomerDetails = UserDetails::firstOrCreate(['user_id' => $this->testExistCustomer->id],
                [
                    'user_id' => $this->testExistCustomer->id,
                    'first_name' => 'Noel',
                    'last_name' => 'Balaba',
                    'mobile_phone' => '+1111111',
                    'delivery_zone_timings_id' => 8,
                    'billing_first_name' => 'Noel',
                    'billing_last_name' => 'Balaba',
                    'billing_mobile_phone' => '+1111111',
                    'status' => 'active',
                ]
            );

        
        $this->testExistCustomerAddress = UserAddress::firstOrCreate(['user_id' => $this->testExistCustomer->id],
            [
                'address1' => 'Sydney',
                'suburb' => 'Sydney',
                'state' => 2,
                'country' => 'Australia',
                'postcode' => '6000',
            ]
        );
        
        $this->testZoneTimings = DeliveryZoneTimings::first();
        $this->testMealPlans = MealPlans::first();
        $this->testSubscriptionCycles = SubscriptionsSelections::whereUserId($this->testExistCustomer->id)->first();
    }



  
    /**
     * Test on creating a customer
     *
     * @return void
     */
    public function testCreateCustomer()
    {
        $customer = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'testcustomer@email.com',
            'mobile_phone' => '+00123456789',
            'country' => 'Australia',
            'address1' => 'Sydney',
            'address2' => 'Sydney',
            'suburb' => 'Sydney',
            'state' => 1,
            'postcode' => '6000',
            'delivery_zone_timings_id' => 1,
        ];

        $response = $this->call('PUT', 'customers/create', $customer);
        $response->assertJson([
            'success' => true
        ]);
        
    }

    /**
     * Test on editing a customer profile
     *
     * @return void
     */
    public function testEditCustomerProfile()
    {
        $customer = [
            'id' => $this->testExistCustomer->id,
            'country' => 'Australia',
            'first_name' => $this->testExistCustomer->first_name,
            'last_name' => $this->testExistCustomer->last_name,
            'email' => $this->testExistCustomer->email,
            'mobile_phone' => '+63123456789',
            'address1' => 'Sydney',
            'address2' => '',
            'suburb' => 'Sydney',
            'state' => 2,
            'postcode' => '6000',
        ];

        $response = $this->call('PATCH', 'customers/update-profile/'.$this->testExistCustomer->id, $customer);
        $response->assertJson([
            'success' => true
        ]);
    }


    /**
     * Test on edint a customer's DZ time 
     *
     * @return void
     */
    public function testEditDZTime(){

        $response = $this->call(
                        'PATCH', 
                        'customers/update-delivery/'.$this->testExistCustomer->id, 
                        [
                            'id' => $this->testExistCustomer->id, 
                            'delivery_zone_timings_id' => 2
                        ]
                    );
        
        $response->assertStatus(200)->assertJson([
            'success' => true
        ]);
    }


    /**
     * Test on adding a new credit card 
     *
     * @return void
     */
    public function testAddCreditCard()
    {
        $response = $this->call(
                    'PUT', 
                    'customers/create-card', 
                    [
                        'userId' => $this->testExistCustomer->id,
                        'card_name' => 'James R Jackson',
                        'card_number' => '4645790045598017',
                        'card_expiration_date' => '05/21',
                        'card_cvc' => 187
                    ]
                );

        $response->assertStatus(200)->assertJson([
            'success' => true
        ]);
    }

    /**
     * Test on adding a new credit card 
     *
     * @return void
     */
    public function testGetDeliveryByTimezone()
    {
        $response = $this->call('GET', 'customers/get-deliverytime-byzone/2');
        $response->assertStatus(200);
    }

    

    /**
     * Test on getting order subscription summary
     *
     * @return void
     */
    public function testOrderSubscriptionSummary(){
        $response = $this->call(
                    'GET', 
                    'customers/order-subscription-summary'
                );

        $response->assertStatus(200)->assertSee('ORDER SUMMARY');
        
    }


    /**
     * Test on getting cards if its 200 response and has cards
     *
     * @return void
     */
    public function testGetCards(){
        $response = $this->call(
                    'GET', 
                    'getcards?id='.$this->testExistCustomer->id
                );

        $response->assertStatus(200)->assertJsonStructure(['cards']);
    }    


    /**
     * Test on adding saved bill now btn request
     *
     * @return void
     */
    public function testSavedBillNow(){
        
        $cards = json_decode($this->testExistCustomer->details->card_ids);

        $response = $this->call(
                    'PUT', 
                    'customers/new-plan/'.$this->testExistCustomer->id,
                    [
                        'id' => $this->testExistCustomer->id,
                        'meal_plans_id' => $this->testExistCustomer->id,
                        'delivery_zone_id' => $this->testZoneTimings->delivery_zone_id,
                        'delivery_zone_timings_id' => $this->testZoneTimings->delivery_timings_id,
                        'card_id' => $cards[0],
                    ]
                );

        $response->assertStatus(200)->assertJson([
            'success' => true
        ]);
    }   


    /**
     * Test on saving bill at cutover request
     *
     * @return void
     */
    public function testSavedBillAtCutOver(){
        $cards = json_decode($this->testExistCustomer->details->card_ids);

        $this->call('PATCH', 'customers/updateplan/', ['meals_plan_id' => 4]);

        $response = $this->call(
                    'PUT', 
                    'customers/new-plan-with-billing/'.$this->testExistCustomer->id,
                    [
                        'id' => $this->testExistCustomer->id,
                        'meal_plans_id' => $this->testMealPlans->id,
                        'delivery_zone_id' => $this->testZoneTimings->delivery_zone_id,
                        'delivery_zone_timings_id' => $this->testZoneTimings->delivery_timings_id,
                        'card_id' => $cards[0],
                    ]
                );

        $response->assertStatus(200)->assertJson([
            'success' => true
        ]);
    } 


    /**
     * Test on other request for customer page on load
     *
     * @return void
     */
    public function testCustomerPageOtherRequestOnLoad()
    {
        $this->call('GET', 'customers/subscriptions/active?user_id='.$this->testExistCustomer->id)->assertStatus(200);
        $this->call('GET', 'customers/subscriptions/past?user_id='.$this->testExistCustomer->id)->assertStatus(200);
        $this->call('GET', 'customers/subscriptions/invoices?userId='.$this->testExistCustomer->id)->assertStatus(200);
        $this->call('GET', 'customers/subscriptions/invoices?userId='.$this->testExistCustomer->id)->assertStatus(200);
        $this->call('GET', 'customers/subscriptions/weeks?subcycleid='.$this->testSubscriptionCycles->cycle_id.'&subid='.$this->testSubscriptionCycles->subscription_id.'&user_id='.$this->testExistCustomer->id)->assertStatus(200);
        $this->call('GET', 'customers/subscriptions/menus?id='.$this->testSubscriptionCycles->id)->assertStatus(200);        

    }


    public function testChangeSchedule()
    {
        $response = $this->call(
                    'GET', 
                    'customers/future-delivery-timing-schedule/'.$this->testExistCustomer->id.'/'.$this->testSubscriptionCycles->id
                );

        $response->assertStatus(200)->assertSee('Select date');
    }


    public function testPause()
    {
        $response = $this->call(
            'PATCH', 
            'customers/pause-subscription/'.$this->testExistCustomer->id.'/'.$this->testSubscriptionCycles->id,
            ['date' => '2018-12-16']
        );

        $response->assertStatus(200)->assertSeeText('1');
    }

    public function testResume()
    {
        $response = $this->call(
            'PATCH', 
            'customers/play-subscription/'.$this->testExistCustomer->id.'/'.$this->testSubscriptionCycles->id,
            []
        );

        $response->assertStatus(200)->assertSeeText('1');
    }


    public function testCancelSubscription()
    {
        $response = $this->call(
            'PATCH', 
            'customers/cancel-subscription/'.$this->testExistCustomer->id.'/'.$this->testSubscriptionCycles->subscription_id,
            []
        );

        $response->assertStatus(200)->assertSeeText('1');
    }


    public function testActiveSubscriptionAdvanceBtnAddMarkAsPaid()
    {   
        //get existing subscription to test
        $subscriptionSelections = SubscriptionsSelections::where('cycle_subscription_status', '!=', 'unpaid')->first();

        Log::info($subscriptionSelections);

        $this->call('PATCH', 'customers/addmenuprevweekorderupdateplan', ['meal_plans_id' => 4])->assertStatus(200);

        $response = $this->call(
            'PUT', 
            'customers/new-plan-previous-week/'. $subscriptionSelections->user_id,
            [
                'subscriptions_id' => $subscriptionSelections->subscription_id,
                'delivery_zone_id' => 1,
                'cycle_id' => $subscriptionSelections->cycle_id,
                'meal_plans_id' => $this->testMealPlans->id,
                'card_id' => 241
            ]
        );

        $response->assertStatus(200)->assertJson([
            'success' => true
        ]);

        $latestSubscription = SubscriptionsSelections::whereSubscriptionId($subscriptionSelections->subscription_id)->whereUserId($subscriptionSelections->user_id)->latest()->first();

        if ($latestSubscription->cycle_subscription_status === 'paid') {
            $this->assertTrue(true);
        } else {
            $this->assertTrue(false);
        }
    }



    public function testActiveSubscriptionAdvanceBtnAddBillNow()
    {   
        //get existing subscription to test
        $subscriptionSelections = SubscriptionsSelections::where('cycle_subscription_status', '!=', 'unpaid')->first();

        Log::info($subscriptionSelections);

        $this->call('PATCH', 'customers/addmenuprevweekorderupdateplan', ['meal_plans_id' => 4])->assertStatus(200);

        $response = $this->call(
            'PUT', 
            'customers/new-plan-with-billing-previous-week/'. $subscriptionSelections->user_id,
            [
                'subscriptions_id' => $subscriptionSelections->subscription_id,
                'delivery_zone_id' => 1,
                'cycle_id' => $subscriptionSelections->cycle_id,
                'meal_plans_id' => $this->testMealPlans->id,
                'card_id' => 241
            ]
        );

        $response->assertStatus(200)->assertJson([
            'success' => true
        ]);

        $latestSubscription = SubscriptionsSelections::whereSubscriptionId($subscriptionSelections->subscription_id)->whereUserId($subscriptionSelections->user_id)->latest()->first();

        if (
            $latestSubscription->cycle_subscription_status === 'paid' &&
            !empty($latestSubscription->ins_invoice_id)
        ) {
            $this->assertTrue(true);
        } else {
            $this->assertTrue(false);
        }
    }

    
}
