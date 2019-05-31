<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\Meals;

class ProductMeal extends TestCase
{
    use WithoutMiddleware;
    use WithFaker;

    private $testMeal;

    protected function setUp() {
        parent::setUp(); 

        $this->testMeal = Meals::firstOrCreate(['meal_sku' => 'TestMeal'], [
            'meal_sku' => 'TestMeal',
            'meal_name' => 'Meal Test',
          
        ]);
        
    }

    
    /**
     * Test on creating a product meal
     *
     * @return void
     */
    public function testCreateMeal()
    {
        $meal = [
            'meal_sku' => 'Meal '. $this->faker->word(5),
            'meal_name' => 'Meal '. $this->faker->word(10),
        ];

        $response = $this->call('PUT', 'products/meals/create', $meal);
       
        $response->assertStatus(200)->assertJson([
             'success' => true,
        ]);
    }


    /**
     * Test on View all meals if it will load
     *
     * @return void
     */
    public function testViewAllMeals()
    {

        $response = $this->call('GET', 'products/meals/all-meals');
        $response->assertStatus(200);

    }
    

    /**
     * Test on Deleting a meal
     *
     * @return void
     */
    public function testDeleteMeal()
    {

        $response = $this->call('DELETE', 'products/meals/delete/'.$this->testMeal->id);

        $response->assertStatus(200)->assertJson([
             'success' => true,
        ]);
   
    }


    /**
     * Test on Update a Meal
     *
     * @return void
     */
    public function testUpdateMeal()
    {
       
        $updateMeal = [
            'id' => $this->testMeal->id,
            'meal_sku' => $this->testMeal->meal_sku,
            'meal_name' => 'Test Meal Edit',    
            'vegetarian' => 'true',        
        ];
      
        $response = $this->call('PATCH', 'products/meals/update', $updateMeal);

        $response->assertJson([
             'success' => true,
        ]);
    }


    /**
     * Test on enable a meal
     *
     * @return void
     */
    public function testEnableMeal()
    {
       
        $updateMeal = [
            'id' => $this->testMeal->id,
            'meal_sku' => $this->testMeal->meal_sku,
            'meal_name' => 'Test Meal',    
            'vegetarian' => true,    
            'status'    => true,    
        ];
      
        $response = $this->call('PATCH', 'products/meals/update', $updateMeal);

        $response->assertJson([
             'success' => true,
        ]);
    }


    /**
     * Test on View edit meal if it will load
     *
     * @return void
     */
    public function testViewEditMeal()
    {
        $response = $this->call('GET', 'products/meals/edit/'.$this->testMeal->id);
        $response->assertStatus(200);
    }


    /**
     * Test on Verifying SKU if exists
     *
     * @return void
     */
    public function testExistSku()
    {
        $response = $this->call('POST', 'products/meals/verify-sku', ['meal_sku' => $this->testMeal->meal_sku]);
        $response->assertSeeText('false');
    } 


    /**
     * Test on Verifying SKU if not exists
     *
     * @return void
     */
    public function testNotExistSku()
    {
        $response = $this->call('POST', 'products/meals/verify-sku', ['meal_sku' => str_random(5)]);
        $response->assertSeeText('true');
    }

   
}
