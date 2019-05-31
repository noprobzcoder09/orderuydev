<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Users;
use Log;


class CheckAdminPagesPermissionTest extends DuskTestCase
{

    private $customer;

    protected function setUp()
    {
        parent::setUp();

        //$this->customer = Users::whereRole('customer')->whereActive('1')->first();
        $this->customer = Users::find(55);
    }


    public function testCheck()
    {
        $this->browse(function (Browser $browser) {
           
            Log::info('Browser Test for customer id '.$this->customer->id.' checking admin pages permissions.');

            //mimic customer checkout
            $browser->loginAs($this->customer)
                    
                    //customers page
                    ->visit('/customers')
                    ->assertSee('403')
                    ->pause(10000)

                    //billing issue pages
                    ->visit('/customers/billing-issue')
                    ->assertSee('403')
                    ->pause(10000)

                    //billing issue pages
                    ->visit('/customers/billing-issue')
                    ->assertSee('403')
                    ->pause(10000)

                    //all plans page
                    ->visit('/products/plan/all-plans')
                    ->assertSee('403')
                    ->pause(10000)

                    //add new plans                    
                    ->visit('/products/meals/all-meals')
                    ->assertSee('403')
                    ->pause(10000)

                    //all meals page
                    ->visit('/products/meals/all-meals')
                    ->assertSee('403')
                    ->pause(10000)

                    //add new plans                    
                    ->visit('/products/meals/new')
                    ->assertSee('403')
                    ->pause(10000)


                    //plans scheduler            
                    ->visit('/products/plan/scheduler')
                    ->assertSee('403')
                    ->pause(10000)

                    //all dzt  
                    ->visit('/delivery/zone/timing/all-zone-timings')
                    ->assertSee('403')
                    ->pause(10000)

                    //add new dzt  
                    ->visit('/delivery/zone/timing/new')
                    ->assertSee('403')
                    ->pause(10000)

                    //all zones 
                    ->visit('/delivery/zone/all-zones')
                    ->assertSee('403')
                    ->pause(10000)

                    //add new zones 
                    ->visit('/delivery/zone/new')
                    ->assertSee('403')
                    ->pause(10000)

                    //all timings
                    ->visit('/delivery/timing/all-timings')
                    ->assertSee('403')
                    ->pause(10000)

                    //add new timing
                    ->visit('/delivery/timing/new')
                    ->assertSee('403')
                    ->pause(10000)

                    //add all coupons
                    ->visit('/coupons/all-coupons')
                    ->assertSee('403')
                    ->pause(10000)

                    //add new coupons
                    ->visit('/coupons/new')
                    ->assertSee('403')
                    ->pause(10000)

                    //add new coupons
                    ->visit('/coupons/new')
                    ->assertSee('403')
                    ->pause(10000)

                    //all users
                    ->visit('/users/all-users')
                    ->assertSee('403')
                    ->pause(10000)
                    
                    //add new user
                    ->visit('/users/new')
                    ->assertSee('403')
                    ->pause(10000)
                   ;
        });
    }
}