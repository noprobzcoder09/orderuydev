<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\MealPlans;
use App\Models\Coupons;

class SetupCouponTest extends TestCase
{
    use WithoutMiddleware;

    private $testMealPlan;
    private $testCoupon;

    protected function setUp(){
        parent::setUp();

        $this->testMealPlan = MealPlans::first();

        $this->testCoupon = Coupons::firstOrCreate(['coupon_code' => 'Test Coupon'], [
                                'coupon_code' => 'Test Coupon',
                                'discount_type' => 'Percent',
                                'discount_value' => '5',
                                'min_order' => '100',
                                'max_uses' => '500',
                                'products' => '["'.$this->testMealPlan->id.'"]',
                                'expiry_date' => '01/31/2019',
                                'user' => '[]',
                                
                            ]);

    }


    /**
     * Test on creating new coupon
     *
     * @return void
     */
    public function testCreateCoupon()
    {
        $response = $this->call(
                        'PUT', 
                        'coupons/create', 
                        [
                            'coupon_code' => 'Test Entry Coupon',
                            'discount_type' => 'Fixed',
                            'discount_value' => '100',
                            'min_order' => '100',
                            'max_uses' => '500',
                            'products' => '["'.$this->testMealPlan->id.'"]',
                            'expiry_date' => '01/31/2019',
                            'users' => '[]',
                            'products_sel' => '["'.$this->testMealPlan->id.'"]'
                        ]
                    );

        $response->assertStatus(200)->assertJson([
            'success' => true
        ]);
        
       
    }

     /**
     * Test on updating a coupon
     *
     * @return void
     */
    public function testUpdateCoupon()
    {
        $response = $this->call(
                        'PATCH',
                        'coupons/update',
                        [
                            'id' => $this->testCoupon->id, 
                            'coupon_code' => 'Test Update Coupon',
                            'discount_type' => 'Fixed',
                            'discount_value' => '100',
                            'min_order' => '100',
                            'max_uses' => '500',
                            'products' => $this->testMealPlan->id,
                            'expiry_date' => '01/31/2019',
                            'users' => '[]',
                            'products_sel' => '['.$this->testMealPlan->id.']',
                            'recur' => 'on'
                        ]
                    );

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true
                ]);
    }


     /**
     * Test on deleting a coupon
     *
     * @return void
     */
    public function testDeleteCoupon(){
        $response = $this->call(
                    'DELETE',
                    'coupons/delete/'.$this->testCoupon->id                 
                );

        $response->assertStatus(200)
            ->assertJson([
                'success' => 1
            ]);
    }


     /**
     * Test if view all coupons page has 200 status response
     *
     * @return void
     */
    public function testViewAllCoupons(){
        $response = $this->call(
                    'GET',
                    'coupons/all-coupons'                 
                );

        $response->assertStatus(200);
    }


     /**
     * Test if edit a coupon page has 200 status response
     *
     * @return void
     */
    public function testViewEditCoupons(){
        $response = $this->call(
                    'GET',
                    'coupons/edit/'.$this->testCoupon->id              
                );

        $response->assertStatus(200);
    }
}
