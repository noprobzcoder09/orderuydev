<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use App\Models\MealPlans;


class ProductPlanTest extends TestCase
{   
    use WithoutMiddleware;
    use WithFaker;

    private $testPlan;

    protected function setUp() {
        parent::setUp(); 

        $this->testPlan = MealPlans::firstOrCreate(['sku' => 'TestPlan'], [
            'sku' => 'TestPlan',
            'plan_name' => 'Test Plan 1',
            'ins_product_id' => 1,
            'no_meals' => 7,
            'no_days' => 7,
            'vegetarian' => 'true',
            'price' => 1500,
        ]);
        
    }

    
    /**
     * Test on creating a product plan
     *
     * @return void
     */
    public function testCreatePlan()
    {
        $plan = [
            'sku' => $this->faker->text(5),
            'plan_name' => $this->faker->text(10),
            'ins_product_id' => 1,
            'no_meals' => 7,
            'no_days' => 7,
            'vegetarian' => 'true',
            'price' => 1500,
        ];

        $testFilePath = base_path() . '/tests/testassets/sample.jpg';
        $testFile = UploadedFile::fake()->image($testFilePath); 

        $response = $this->call('POST', 'products/plan/create', $plan, [], ['meal_plan_image' => $testFile]);
       
        $response->assertStatus(200)->assertJson([
             'success' => true,
        ]);
        
    }


    /**
     * Test on View all plans if it will load
     *
     * @return void
     */
    public function testViewAllPlans()
    {

        $response = $this->call('GET', 'products/plan/all-plans');
        $response->assertSee('<tr');

    }
    

    /**
     * Test on Deleting a plan
     *
     * @return void
     */
    public function testDeletePlan()
    {

        $response = $this->call('DELETE', 'products/plan/delete/'.$this->testPlan->id);

        $response->assertStatus(200)->assertJson([
             'success' => true,
        ]);
    }


    /**
     * Test on Update a plan
     *
     * @return void
     */
    public function testUpdatePlan()
    {
       
        $updatePlan = [
            'id' => $this->testPlan->id,
            'sku' => 'TestPlan',
            'plan_name' => 'Test Plan',     
            'ins_product_id' => 1,
            'no_meals' => 7,
            'no_days' => 7,
            'vegetarian' => 'false',
            'price' => 1800,       
        ];
      
        $response = $this->call('POST', 'products/plan/update', $updatePlan);

        $response->assertJson([
             'success' => true,
        ]);
    }


    /**
     * Test on View edit plan if it will load
     *
     * @return void
     */
    public function testViewEditPlan()
    {
        $response = $this->call('GET', 'products/plan/edit/'.$this->testPlan->id);
        $response->assertStatus(200);
    }


    /**
     * Test on Verifying SKU if exists
     *
     * @return void
     */
    public function testVerifySku()
    {
        $sku = MealPlans::find(1)->sku;

        $response = $this->call('POST', 'products/plan/verify-sku', ['sku' => $sku]);
        $response->assertSeeText('false');
    }    

}
