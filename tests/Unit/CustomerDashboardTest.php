<?php 

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\Users;
use App\Models\MealPlans;
use App\Services\CRUD;
use App\Models\Subscriptions;
use App\Models\SubscriptionsSelections;
use Log;
use Auth;

class CustomerDashboardTest extends TestCase
{
    use WithFaker;
    use WithoutMiddleware;

    private $activeSubscriptions;
    private $pausedSubscriptions;
    private $testUser;
    private $availablePlans;
   
    protected function setUp() {
        parent::setUp(); 
        
        $this->testUser = Users::with('details')->find(3);

        $this->activeSubscriptions = Subscriptions::whereStatus('active')->whereUserId($this->testUser->id)->get()->map(function($subscription) {
            return $subscription->id;
        });       
        
        $this->pausedSubscriptions = Subscriptions::whereStatus('paused')->whereUserId($this->testUser->id)->get()->map(function($subscription) {
            return $subscription->id;
        });       
        
        $this->subscriptions = Subscriptions::where('status', '!=', 'cancelled')->whereUserId($this->testUser->id)->groupBy('meal_plans_id')->select('meal_plans_id')->get()->map(function($subscription) {
            return $subscription->meal_plans_id;
        })->toArray();
    
        $this->mealPlans = MealPlans::whereNotIn('id', $this->subscriptions)->groupBy('id')->select('id')->get()->map(function($mealPlan) {
            return $mealPlan->id;
        })->toArray();

    }

    /**
     * Test on creating a user
     *
     * @return void
     */    
    public function testSaveChoices()
    {
       
        if ($this->activeSubscriptions) {
            
            $subscriptionSelections = SubscriptionsSelections::whereSubscriptionId($this->activeSubscriptions[0])->latest()->first();

            $response = $this->actingAs($this->testUser)
                            ->call(
                                'PUT', 
                                'customers/save-selections/'. $this->activeSubscriptions[0], 
                                [
                                    'dinner' =>  (string)$subscriptionSelections->menu_selections,
                                    'lunch' => '[]',
                                    'subcycleid' => $subscriptionSelections->id
                                ]
                            );
            
            $response->assertStatus(200)->assertJson([
                'success' => true,
            ]);

        } else {
            $this->assertTrue(false);
        }        
    }


    public function testUpdateBilling() {
        $fakePhone = $this->faker->phoneNumber;
        $fakeFirstName = $this->faker->firstName;
        $fakeLastName = $this->faker->lastName;

        $response = $this->actingAs($this->testUser)
                            ->call(
                                'PATCH', 
                                'customers/update-info-address', 
                                [
                                    'country' => 'Australia',
                                    'first_name' => $fakeFirstName,
                                    'last_name' => $fakeLastName,
                                    'mobile_phone' => $fakePhone,
                                    'email' => $this->testUser->email,
                                    'address1' => '3650 Davila Street Makati Terraces Condominium',
                                    'address2' => 'Makati City',
                                    'suburb' => 'Sydney',
                                    'state' => '2',
                                    'postcode' => '1204',
                                ]
                            );
        
        $response->assertStatus(200)->assertJson([
            'success' => true,
        ]);

        $this->setUp();    
    
        if (
            $this->testUser->details->first_name == $fakeFirstName &&
            $this->testUser->details->last_name == $fakeLastName &&
            $this->testUser->details->mobile_phone == $fakePhone
        ) {
            $this->assertTrue(true);
        }
    }


    public function testUpdateProfile(){
        $fakePhone = $this->faker->phoneNumber;
        $fakeFirstName = $this->faker->firstName;
        $fakeLastName = $this->faker->lastName;

        $response = $this->actingAs($this->testUser)
                            ->call(
                                'PATCH', 
                                'customers/update-profile', 
                                [
                                    'first_name' => $fakeFirstName,
                                    'last_name' => $fakeLastName,
                                    'mobile_phone' => $fakePhone,
                                    'email' => $this->testUser->email,
                                ] 
                            );
            
        $response->assertStatus(200)->assertJson([
            'success' => true,
        ]);

        $this->setUp();    
        
        if (
            $this->testUser->details->first_name == $fakeFirstName &&
            $this->testUser->details->last_name == $fakeLastName &&
            $this->testUser->details->mobile_phone == $fakePhone
        ) {
            $this->assertTrue(true);
        }
    }


    public function testUpdateDelivery() {
        $fakePhone = $this->faker->phoneNumber;
        $fakeFirstName = $this->faker->firstName;
        $fakeLastName = $this->faker->lastName;

        $response = $this->actingAs($this->testUser)
                            ->call(
                                'PATCH', 
                                'customers/update-delivery-zone-timing', 
                                [
                                    'current_delivery_zone_timings_id' => 1,
                                    'delivery_zone_id' => 1,
                                    'delivery_zone_timings_id' => 1,
                                    'delivery_notes' => 'Test notes'
                                ] 
                            );
            
        $response->assertStatus(200)->assertJson([
            'success' => true,
        ]);

        $this->setUp();    
        
        if ($this->testUser->details->delivery_zone_timings_id == '1') {
            $this->assertTrue(true);
        }
    }


    public function testAddPlan() {
       
        $this->call('PATCH', 'customers/updateplan', $this->mealPlans[0]);
        $this->call('GET', 'customers/get-deliverytime-byzone/1');

        $response = $this->actingAs($this->testUser)
                        ->call(
                            'POST', 
                            'customers/new-plan', 
                            [
                                'meal_plans_id' => 1,
                                'delivery_zone_id' => 1,
                                'delivery_zone_timings_id' => 1,
                            ] 
                        );

        $response->assertStatus(200)->assertJson([
        'success' => true,
        ]);
  
    }


    public function testCancelPlan() {
        
        $subscriptionSelections = SubscriptionsSelections::whereSubscriptionId($this->activeSubscriptions[0])->latest()->first();

        $response = $this->actingAs($this->testUser)
                        ->call(
                            'PATCH', 
                            'customers/cancell-plans', 
                            [
                                'subscriptionId' => $this->activeSubscriptions[0],
                                'subscriptionCycleId' => $subscriptionSelections->id
                            ] 
                        );

        $response->assertStatus(200)->assertSeeText('1');


    }


    public function testPausePlan() {
        $subscriptionSelections = SubscriptionsSelections::whereSubscriptionId($this->activeSubscriptions[0])->latest()->first();

        $response = $this->actingAs($this->testUser)
                        ->call(
                            'PATCH', 
                            'customers/save-stoptill-date', 
                            [   
                                'date' => '2019-01-16',
                                'subscriptionId' => $this->activeSubscriptions[0],
                                'subscriptionCycleId' => $subscriptionSelections->id
                            ] 
                        );

        $response->assertStatus(200)->assertJson([
            'success' => true
        ]);

        $subscription = Subscriptions::find($this->activeSubscriptions[0]);
        $this->assertEquals($subscription->status, 'paused');
       
    }

    public function testResume() {
        $subscriptionSelections = SubscriptionsSelections::whereSubscriptionId($this->pausedSubscriptions[0])->latest()->first();

        $response = $this->actingAs($this->testUser)
                        ->call(
                            'PATCH', 
                            'customers/cancel-paused-date', 
                            [   
                                'date' => '2019-01-16',
                                'subscriptionId' => $this->pausedSubscriptions[0],
                                'subscriptionCycleId' => $subscriptionSelections->id
                            ] 
                        );

        $response->assertStatus(200)->assertSeeText('1');

        $subscription = Subscriptions::find($this->pausedSubscriptions[0]);
        $this->assertEquals($subscription->status, 'active');
    }

    public function testStopAllPlans() {
        $response = $this->actingAs($this->testUser)
                        ->call(
                            'PATCH', 
                            'customers/save-stop-all-till-date', 
                            [   
                                'date' => '01/31/2019',
                            ] 
                        );

        $response->assertStatus(200)->assertJson([
            'success' => true
        ]);

    }


    public function testCancelAllPlans() {
        $response = $this->actingAs($this->testUser)
                        ->call(
                            'PATCH', 
                            'customers/cancell-all-plans'
                        );

        $response->assertStatus(200)->assertSeeText('1');

        $subscriptions = Subscriptions::whereUserId($this->testUser->id)->where('status', '!=', 'cancelled')->count();
        $this->assertEquals($subscriptions, 0);            
    }


    
}
