<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Users;
use App\Models\Subscriptions;
use App\Models\MealPlans;
use App\Models\Cycles;
use App\Models\DeliveryZoneTimings;
use App\Models\Coupons;
use Log;
use Illuminate\Foundation\Testing\WithFaker;

class CheckoutTest extends DuskTestCase
{

    use WithFaker;

    private $customer; 
    private $deliveryZoneTimings;
    private $subscriptions;
    private $mealPlans;
    private $coupon;

    public function setUp() {
        parent::setUp();
        //$this->customer = Users::with('details')->whereRole('customer')->whereActive('1')->first();
        $this->customer = Users::with('details')->find(55);
        $this->deliveryZoneTimings = DeliveryZoneTimings::first();
        $this->subscriptions = Subscriptions::where('status', '!=', 'cancelled')->whereUserId($this->customer->id)->groupBy('meal_plans_id')->select('meal_plans_id')->get()->map(function($subscription) {
            return $subscription->meal_plans_id;
        })->toArray();
    
        $this->mealPlans = MealPlans::whereNotIn('id', $this->subscriptions)->groupBy('id')->select('id')->get()->map(function($mealPlan) {
            return $mealPlan->id;
        })->toArray();

        $this->coupon = Coupons::latest()->first();
        

    }

    /**
     * Check view
     *
     * @return void
     */
    public function testCheckout()
    {
        $this->browse(function (Browser $browser) {
           
            Log::info('Browser Test for customer id '.$this->customer->id.' test checkout.');

            //mimic customer checkout
            $browser->loginAs($this->customer)
                    ->visit('/plan/7d-dinners-vego')
                    ->assertSee('7 DAYS DINNERS')
                    ->waitForText('ADD TO CART')
                    ->clickLink('Add to cart')
                    ->pause(30000)
                    ->assertVisible('#shipping-form')
                    ->whenAvailable('#shipping-form', function($shippingForm) {
                        $shippingForm->type('first_name', $this->customer->details->first_name)
                                     ->type('last_name', $this->customer->details->last_name)
                                     ->type('address1', $this->faker->address)
                                     ->type('suburb', 'Sydney')
                                     ->select('state', 1)
                                     ->type('postcode', 1111)
                                    ;
                    })     
                    ->whenAvailable('#delivery-wrapper', function($delivery) {
                        $delivery->select('delivery_zone_id', 1)   
                                 ->select('delivery_zone_timings_id', 1)   
                                 ;  
                    })                    
                    ;

                    // if (!empty($this->customer->details->default_card)) {
                    //     $browser->whenAvailable('#form-cards-list', function($card) {
                    //         $card->radio('my_card', $this->customer->details->default_card);
                    //     });
                    // } else {
                        $browser->whenAvailable('#credit-card-form', function ($creditCardForm) {
                            $creditCardForm->type('card_name', 'James R Jackson')
                                            ->type('card_number', '4645 7900 4559 8017')
                                            ->type('card_expiration_date', '05/21')
                                            ->type('card_cvc', '187')
                                            ;
                        });
                    //}

                    $browser->press('PLACE ORDER')
                            ->waitForLocation('/dashboard', 240)
                             ;
        });
    }


    public function testGuestCheckout()
    {
        

        $this->browse(function (Browser $browser) {
           
            Log::info('Browser Test for customer guest checkout.');

            //mimic customer checkout
            $browser->visit('/plan/7d-dinners-vego')
                    ->clickLink('Add to cart')
                    ->pause(30000)
                    ->assertVisible('#email-login-form')
                    ->whenAvailable('#email-login-form', function($loginForm) {
                        $loginForm->type('email', $this->faker->email)
                                  ->click('.btn-ecommerce')
                                ;
                    })
                    ->pause(10000)
                    ->assertVisible('#register-form')
                    ->whenAvailable('#register-form', function($registerForm) {
                        $registerForm->type('password', 123456)
                                    ->type('confirm_password', 123456)
                                    ->click('.btn-ecommerce')
                                    ;
                    })
                    ->pause(10000)
                    ->assertVisible('#shipping-form')
                    ->whenAvailable('#shipping-form', function($shippingForm) {
                        $shippingForm->type('first_name', $this->faker->firstName)
                                     ->type('last_name', $this->faker->lastName)
                                     ->type('mobile_phone', 111)
                                     ->type('address1', $this->faker->address)
                                     ->type('suburb', 'Sydney')
                                     ->select('state', 1)
                                     ->type('postcode', 1111)
                                    ;
                    })     
                    ->pause(10000)
                    ->whenAvailable('#delivery-wrapper', function($delivery) {
                        $delivery->select('delivery_zone_id', 1)   
                                 ->select('delivery_zone_timings_id', 1)   
                                 ;  
                    })   
                    ->pause(10000)
                    ->whenAvailable('#credit-card-form', function ($creditCardForm) {
                        $creditCardForm->type('card_name', 'James R Jackson')
                                        ->type('card_number', '4645 7900 4559 8017')
                                        ->type('card_expiration_date', '05/21')
                                        ->type('card_cvc', '187')
                                        ;
                    })
                    ->pause(10000)
                    ->press('PLACE ORDER')
                    ->waitForLocation('/dashboard', 240)             
                    ;
        });
    }



    public function testPauseTill()
    {
        $this->browse(function (Browser $browser) {
           
            $browser->loginAs($this->customer)
                    ->visit('/dashboard')
                    ->click('ul.nav-pills li:nth-child(2)')
                    ->assertSee('ACTIVE MEAL PLANS')
                    ->pause(10000)
                    ->click('#listing-wrapper .listing:last-child .stoptilldate')
                    ->pause(10000)
                    ->select('.date', '2019-04-23')                    
                    ->click('#listing-wrapper .listing:last-child .cursor-pointer .fa-check')
                    ->pause(10000)
                    ->waitForText('RESUME', 3);
        });

    }

    public function testPauseResume()
    {
        $this->browse(function (Browser $browser) {
           
            $browser->loginAs($this->customer)
                    ->visit('/dashboard')
                    ->click('ul.nav-pills li:nth-child(2)')
                    ->assertSee('ACTIVE MEAL PLANS')
                    ->pause(10000)
                    ->click('#listing-wrapper .listing:last-child .cancelpauseddate')
                    ->pause(10000)
                    ->assertSee('Are you sure you want to cancell the paused date?')
                    ->press('Yes')     
                    ->pause(5000)
                    ->assertSee('Success!');
        });

    }


    public function testUpdateMeal()
    {
        $this->browse(function (Browser $browser) {

            $browser->loginAs($this->customer)
                    ->visit('/dashboard')
                    ->click('ul.nav-pills li:nth-child(1)')
                    ->pause(10000)
                    ->select('option_dinner_0', 34)
                    ->click('a.btn-addtocart')
                    ->pause(5000)
                    ->assertSee('Success!');
        });

    }

    public function testViewPreviousSelections()
    {
        $this->browse(function (Browser $browser) {
           
            $browser->loginAs($this->customer)
                    ->visit('/dashboard')
                    ->click('ul.nav-pills li:nth-child(2)')
                    ->assertSee('ACTIVE MEAL PLANS')
                    ->pause(10000)
                    ->click('#listing-wrapper .listing .listing-body a:last-child')
                    ->pause(10000)
                    ->assertVisible('.previous-selections-content');
        });

    }

    public function testCancelAllPlans()
    {
        $this->browse(function (Browser $browser) {
           
            $browser->loginAs($this->customer)
                    ->visit('/dashboard')
                    ->click('ul.nav-pills li:nth-child(2)')
                    ->assertSee('ACTIVE MEAL PLANS')
                    ->pause(10000)
                    ->click('a.btn-cancell-all-plans')
                    ->pause(10000)
                    ->assertSee('Are you sure you want to cancell all plans?')
                    ->press('Yes')     
                    ->pause(5000)
                    ->assertSee('Success!');
        });

    }


    public function testPauseAllPlans() {
        $this->browse(function (Browser $browser) {
           
            $browser->loginAs($this->customer)
                    ->visit('/dashboard')
                    ->click('ul.nav-pills li:nth-child(2)')
                    ->assertSee('ACTIVE MEAL PLANS')
                    ->pause(10000)
                    ->click('a.stopallplans')
                    ->pause(10000)
                    ->select('.main-subscriptions-button #date-container .date', '2019-04-23')   
                    ->click('.main-subscriptions-button #date-container .fa-check')
                    ->pause(10000)
                    ->waitForText('RESUME', 3);
        });
    }


    public function testAddSinglePlan()
    {
        
        $this->browse(function (Browser $browser) {      
            //mimic customer checkout
            $browser->loginAs($this->customer)
                    ->visit('/dashboard')
                    ->click('#wizzard-wrapper ul.nav-pills li:nth-child(2)')
                    ->assertSee('ACTIVE MEAL PLANS')                    
                    ->clickLink('ADD NEW PLAN')
                    ->pause(10000)
                    ->assertSee('New Plan')
                    ->select('meal_plans_id', $this->mealPlans[0])
                    ->pause(10000)
                    ->select('delivery_zone_timings_id', '3')
                    ->pause(10000)
                    ->press('Save')
                    ->pause(10000)
                    ->assertSee('Are you sure you want to save')
                    ->pause(3000)
                    ->press('Yes')
                    ->pause(3000)
                    ;
        });

    }



    public function testAddMultiplePlan()
    {   
     
        $this->browse(function (Browser $browser) {        
            //mimic customer checkout
            //Log::info($this->mealPlans);
            $browser->loginAs($this->customer) 
                    ->pause(50000)
                    ->visit('/dashboard')
                    ->click('ul.nav-pills li:nth-child(2)')
                    ->pause(30000)
                    ->assertVisible('#tab2')
                    ->whenAvailable('#tab2', function($tab2){
                        $tab2->clickLink('ADD NEW PLAN');
                    })
                    ->pause(20000)
                    ->assertVisible('#site-container #subscription-modal')
                    ->whenAvailable('#subscription-modal', function($subscription) {  
                        $subscription->assertSee('New Plan')
                                    ->select('meal_plans_id', $this->mealPlans[0])
                                    ->pause(10000)
                                    ->select('delivery_zone_timings_id', '3')
                                    ->pause(10000)
                                    ->press('Save')
                                    ;
                    })      
                    ->pause(20000)
                    ->assertSee('Are you sure you want to save')
                    ->press('Yes')                  
                    ->pause(5000)
                    ->assertSee('Success!')
                    ->pause(50000)

                    //another new plan
                    ->clickLink('ADD NEW PLAN')
                    ->pause(20000)
                    ->assertVisible('#subscription-modal')
                    ->whenAvailable('#subscription-modal', function($subscription) {  
                        $subscription->assertSee('New Plan')
                                    ->select('meal_plans_id', $this->mealPlans[0])
                                    ->pause(10000)
                                    ->select('delivery_zone_timings_id', '3')
                                    ->pause(10000)
                                    ->press('Save')
                                    ;
                    })      
                    ->pause(20000)
                    ->assertSee('Are you sure you want to save')
                    ->press('Yes')                  
                    ->pause(5000)
                    ->assertSee('Success!')
                    ->pause(50000)
                    ;
        });
        
            

    }


    public function testAddMultiplePlanWithBillingIssue()
    {   
        $this->browse(function (Browser $browser) {           
            $browser->loginAs($this->customer)
                    ->visit('/dashboard')
                    ->click('ul.nav-pills li:nth-child(3)')
                    ->pause(10000)
                    ->assertSee('SAVE BILLING')
                    ->type('first_name', $this->faker->firstName)
                    ->type('first_name', $this->faker->lastName)
                    ->pause(5000) 
                    ->clickLink('Save Billing')
                    ->pause(10000)                   
                    ;
        });

    }


    public function testAddCoupon()
    {
        
        $this->browse(function (Browser $browser) {     

            $mealPlan = MealPlans::find($this->mealPlans[0]);
            $discount = ($this->coupon->discount_type == 'Percent') ? ($this->coupon->discount_value / 100) * $mealPlan->price :  $this->coupon->discount_value;
            $deliveryZoneTimings = DeliveryZoneTimings::find($this->customer->details->delivery_zone_timings_id);

            Log::info(number_format((float)$discount, 2)); 
            Log::info($deliveryZoneTimings);
            //mimic customer checkout
            $browser->loginAs($this->customer)
                    ->visit('/dashboard')
                    ->click('#wizzard-wrapper ul.nav-pills li:nth-child(2)')
                    ->assertSee('ACTIVE MEAL PLANS')                    
                    ->clickLink('ADD NEW PLAN')
                    ->pause(10000)
                    ->assertSee('New Plan')
                    ->select('meal_plans_id', $this->mealPlans[0])
                    ->pause(20000)
                    ->select('delivery_zone_timings_id', $deliveryZoneTimings->delivery_timings_id)
                    ->pause(10000)
                    ->assertVisible('#coupon-link-wrapper a')
                    ->click('#coupon-link-wrapper a')
                    ->pause(5000)
                    ->assertVisible('#coupon-input-wrapper')
                    ->whenAvailable('#coupon-input-wrapper', function($coupon){
                        $coupon->type('coupon_code', $this->coupon->coupon_code)
                               ->click('span[onclick*="ManagePlan.storeCoupon()"]') 
                               ;
                    })
                    ->pause(5000)
                    ->whenAvailable('#table-order-summary', function($summary) use ($discount) {
                        $summary->assertSee($this->coupon->coupon_code)
                                ->assertSee('$'.number_format((float)$discount, 2))
                                ;
                    })
                    ->pause(5000)
                    ->press('Save')
                    ->pause(10000)
                    ->assertSee('Are you sure you want to save')
                    ->pause(3000)
                    ->press('Yes')
                    ->pause(3000)
                    ;
        });
    }

   
}
