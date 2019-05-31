<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Users;
use App\Models\Subscriptions;
use App\Models\DeliveryZoneTimings;
use Log;
use Illuminate\Foundation\Testing\WithFaker;

class DashboardTest extends DuskTestCase
{

    use WithFaker;
    /**
     * Check view
     *
     * @return void
     */

    private $customer;
    private $subscriptions;
    private $deliveryZoneTimings;

    public function setUp(){
        parent::setUp();
        
        $this->customer = Users::with('details')->whereRole('customer')->whereActive('1')->first();
        $this->subscriptions = Subscriptions::whereUserId($this->customer->id)->count();
        $this->deliveryZoneTimings = DeliveryZoneTimings::first();
    }


    /**
     * Test dashboard view 
     *
     * @return void
     */

    public function testView()
    {
        //visit dashboard

        $this->browse(function (Browser $browser) {

            Log::info('Browser Test for customer id '.$this->customer->id.' test Dashboard view.');
           
            $browser->loginAs($this->customer)
                    ->visit('/dashboard')
                    ->pause(10000)
                    ->assertPathIs('/dashboard')
                    ->assertSee('Hi '.$this->customer->details->first_name.',');
        });

    }

    /**
     * Test dashboard link if its working and redirected to dashboard page 
     *
     * @return void
     */
    public function testDashboardLink()
    {
        $this->browse(function (Browser $browser) {
            
            Log::info('Browser Test for customer id '.$this->customer->id.' test Dashboard link.');

            $browser->loginAs($this->customer)
                    ->visit('/dashboard')
                    ->clickLink('Dashboard')
                    ->pause(10000)
                    ->assertPathIs('/dashboard');
        });
    }


    /**
     * Test logout link if its working  
     *
     * @return void
     */
    public function testLogoutLink()
    {
        $this->browse(function (Browser $browser) {
            
            Log::info('Browser Test for customer id '.$this->customer->id.' test Logout link.');

            $browser->loginAs($this->customer)
                    ->visit('/dashboard')
                    ->clickLink('Logout')
                    ->pause(10000)
                    ->assertPresent('#login-wrapper');
        });
    }


    /**
     * Test dashboard tabs
     *
     * @return void
     */
    public function testDashboardTabs(){
        $this->browse(function (Browser $browser) {
            
            Log::info('Browser Test for customer id '.$this->customer->id.' test Logout link.');

            $browser->loginAs($this->customer)
                    ->visit('/dashboard')
                    ->click('ul.nav-pills li:nth-child(1)')
                    ->pause(10000)
                    ->assertVisible('.tab-content #tab1')
                    ->pause(10000)
                    
                    ->click('ul.nav-pills li:nth-child(2)')
                    ->pause(10000)
                    ->assertVisible('.tab-content #tab2')

                    ->click('ul.nav-pills li:nth-child(3)')
                    ->pause(10000)
                    ->assertVisible('.tab-content #tab3')

                    ->click('ul.nav-pills li:nth-child(4)')
                    ->pause(10000)
                    ->assertVisible('.tab-content #tab4')

                    ->click('ul.nav-pills li:nth-child(5)')
                    ->pause(10000)
                    ->assertVisible('.tab-content #tab5');

        });
    }


     /**
     * Test Your Menu tab if the elements are working
     *
     * @return void
     */
    public function testYourMenuElements()
    {
       
        if ($this->subscriptions <= 0) {
           
            $this->browse(function (Browser $browser) {
                
                Log::info('Browser Test for customer id '.$this->customer->id.' has no plan and its now checking out for a test product.');

                //mimic customer checkout
                $browser->loginAs($this->customer)
                        ->visit('/plan/7d-dinners-vego')
                        ->assertSee('7 DAYS DINNERS')
                        ->waitForText('ADD TO CART')
                        ->click('.btn-addtocart')
                        ->assertSee('YOUR DETAILS')
                        ->click('.btn-checkout')
                        ->waitForLocation('/dashboard', 20);
            });

        }

        //tests actions and elements
        $this->browse(function (Browser $browser) {
        
            Log::info('Browser Test for customer id '.$this->customer->id.' test Your Menu Tab.');

            $browser->loginAs($this->customer)
                    ->visit('/dashboard')
                    ->click('ul.nav-pills li:nth-child(1)')
                    ->pause(10000)
                    ->assertVisible('.tab-content #tab1')
                    ->pause(10000)
                    
                    ->clickLink('SAVE MY CHOICES')
                    ->pause(10000)
                    ;

        });
        
        
    }


    public function testManagePlansElements()
    {
        //tests actions and elements
        $this->browse(function (Browser $browser) {
        
            Log::info('Browser Test for customer id '.$this->customer->id.' test Manage Plans Tab.');

            $browser->loginAs($this->customer)
                    ->visit('/dashboard')
                    ->click('ul.nav-pills li:nth-child(2)')
                    ->pause(10000)
                    ->assertVisible('.tab-content #tab2')
                    
                    //check CANCEL ALL PLANS button if its working
                    ->clickLink('CANCEL ALL PLANS')
                    ->pause(10000)
                    ->assertSee('Are you sure you want to cancell all plans?')
                    ->press('No')
                    
                    //check STOP ALL PLANS TILL
                    ->clickLink('STOP ALL PLANS TILL')
                    ->pause(10000)
                    ->assertVisible('#date-container')
                   

                    //check ADD NEW PLAN
                    ->clickLink('ADD NEW PLAN')
                    ->pause(10000)
                    ->assertVisible('#subscription-modal')
                    ;

        });
    }


    public function testProfileUpdate()
    {
        //tests actions and elements
        $this->browse(function (Browser $browser) {
        
            Log::info('Browser Test for customer id '.$this->customer->id.' test Profile Tab.');

            $browser->loginAs($this->customer)
                    ->visit('/dashboard')
                    ->click('ul.nav-pills li:nth-child(5)')
                    ->pause(10000)
                    ->assertVisible('.tab-content #tab5')
                    
                    ->whenAvailable('#profile-form', function ($profileForm) {
                        $profileForm->type('first_name', $this->faker->firstName)
                                    ->type('last_name', $this->faker->lastName)
                                    ->type('mobile_phone', '111111')                                    
                                    ;
                    })
                    ->whenAvailable('#profile-password', function ($passwordForm) {
                        $passwordForm->type('current_password', '123456')
                                    ->type('password', '123456')
                                    ->type('confirm_password', '123456');
                    })

                    //check save billing
                    ->clickLink('Save')
                    ->pause(5000)
                    ->assertSee('Success!')
                    ->pause(10000)
                    ;

        });
    }


    public function testBillingUpdate(){
        
        //tests actions and elements
        $this->browse(function (Browser $browser) {
        
            $browser->loginAs($this->customer)
                    ->visit('/dashboard')
                    ->click('ul.nav-pills li:nth-child(3)')
                    ->pause(10000)
                    ->assertVisible('.tab-content #tab3')
                    
                    //check save billing
                    ->clickLink('Save Billing')
                    ->pause(5000)
                    ->assertSee('Success!')
                    ->pause(10000)

                    //credit card
                    ->type('card_name', 'James R Jackson')
                    ->type('card_number', '4645 7900 4559 8017')
                    ->type('card_expiration_date', '05/21')
                    ->type('card_cvc', '187')
                    ->clickLink('Save New Card')
                    ->pause(5000)
                    ->assertSee('Success!')
                    ;

        });
        
    }

    public function testDeliveryUpdate() 
    {
        //tests actions and elements
        $this->browse(function (Browser $browser) {
        
            Log::info('Browser Test for customer id '.$this->customer->id.' test Delivery Tab.');
            Log::info($this->deliveryZoneTimings->delivery_zone_timings_id);

            $browser->loginAs($this->customer)
                    ->visit('/dashboard')
                    ->click('ul.nav-pills li:nth-child(4)')
                    ->pause(10000)
                    ->assertVisible('.tab-content #tab4')
                    ->select('delivery_zone_id', $this->deliveryZoneTimings->delivery_zone_id)
                    ->select('delivery_zone_timings_id', $this->deliveryZoneTimings->delivery_zone_timings_id)
                    ->type('delivery_notes', 'Handle with care.')

                    //check save delivery
                    ->clickLink('Save')
                    ->pause(5000)
                    ->assertSee('Success!')
                    ->pause(10000)
                    ;

        });
    }


    public function testAddNewCard() 
    {
        //tests actions and elements
        $this->browse(function (Browser $browser) {
        
            $browser->loginAs($this->customer)
                    ->visit('/dashboard')
                    ->click('ul.nav-pills li:nth-child(3)')
                    ->pause(10000)
                    ->assertVisible('.tab-content #tab3')
                    
                    //credit card
                    ->type('card_name', 'Johny Doe')
                    ->type('card_number', '4532303164437912')
                    ->type('card_expiration_date', '05/21')
                    ->type('card_cvc', '187')
                    ->clickLink('Save New Card')
                    ->pause(800)
                    ->assertSee('Success!')
                    ;

        });
    }
   
}
