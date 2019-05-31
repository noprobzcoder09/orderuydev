<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Users;
use App\Models\Coupons;
use App\Models\MealPlans;
use Illuminate\Foundation\Testing\WithFaker;
use Log;

class CouponTest extends DuskTestCase
{

    use WithFaker;
    /**
     * Check view
     *
     * @return void
     */

    private $admin;
    private $coupon;
    private $mealPlans;
    
   
    public function setUp(){
        parent::setUp();
        
        $this->admin = Users::with('details')->whereRole('administrator')->whereActive('1')->first();
        $this->coupon = Coupons::latest()->first();
        $this->mealPlans = MealPlans::get()->map(function($mealPlan) {
                                return $mealPlan->id;
                            })->toArray();
                            
    }


    /**
     * Test dashboard view 
     *
     * @return void
     */

    public function testView()
    {
        $this->browse(function (Browser $browser) {

            Log::info('Browser Test for admin Coupons page.');
           
            $browser->loginAs($this->admin)
                    ->visit('coupons/all-coupons')
                    ->pause(10000)
                    ->assertPathIs('/coupons/all-coupons')
                    ;
        });

    }


    public function testAdd()
    {
        $this->browse(function (Browser $browser) {

            Log::info('Browser Test for admin Coupon page - Add.');
           
            $browser->loginAs($this->admin)
                    ->visit('coupons/new')
                    ->pause(10000)
                    ->assertVisible('#coupons-form')
                    ->whenAvailable('#coupons-form', function($couponForm){
                        $couponForm->type('coupon_code', 'PROMOTEST')
                                   ->select('discount_type', 'Percent')
                                   ->type('discount_value', 10)
                                   ->type('min_order', 1)
                                   ->type('max_uses', 20)
                                   ->select('products', $this->mealPlans[0])
                                   ->type('expiry_date', '01/31/2019')
                                   ->press('Submit')
                                   ;
                    })
                    ->pause(5000)
                    ->assertSee('Successfully created new Coupons.')
                    ;
        });
    }


    public function testEdit()
    {
        $this->browse(function (Browser $browser) {

            Log::info('Browser Test for admin Coupon page Edit '.$this->coupon->id);
           
            $browser->loginAs($this->admin)
                    ->visit('coupons/edit/'.$this->coupon->id)
                    ->pause(10000)
                    ->assertVisible('#coupons-form')
                    ->whenAvailable('#coupons-form', function($couponForm){
                        $couponForm->type('coupon_code', 'PROMOTEST')
                                   ->select('discount_type', 'Percent')
                                   ->type('discount_value', 12)
                                   ->type('min_order', 1)
                                   ->type('max_uses', 20)
                                   ->select('products', $this->mealPlans[0])
                                   ->type('expiry_date', '02/31/2019')
                                   ->press('Submit')
                                   ;
                    })
                    ->pause(5000)
                    ->assertPathIs('/coupons/all-coupons')
                    ;
        });
    }


    public function testDelete()
    {
        $this->browse(function (Browser $browser) {

            Log::info('Browser Test for deleting the last row of the table.');
           
            $browser->loginAs($this->admin)
                    ->visit('coupons/all-coupons')
                    ->pause(10000)
                    ->assertVisible('table.dataTable')
                    ->whenAvailable('table.dataTable tbody tr:last-child td:last-child', function($row){
                                    $row->click('.deleteData');
                    })
                    ->pause(3000)
                    ->assertSee('Are you sure you want to delete this?')
                    ->press('Yes')   
                    ->pause(3000)                
                    ->assertSee('Deleted')                    
                    ;
        });
    }



   
}
