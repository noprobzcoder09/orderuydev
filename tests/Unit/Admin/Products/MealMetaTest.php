<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\Meals;
use App\Models\MealsMeta;

class ProductMealMeta extends TestCase
{
    use WithoutMiddleware;
    use WithFaker;


    private $testMeal;
    private $testMeta;

    protected function setUp() {
        parent::setUp(); 

        $this->testMeal = Meals::firstOrCreate(['meal_sku' => 'TestMeal'], [
            'meal_sku' => 'TestMeal',
            'meal_name' => 'Meal Test',
          
        ]);

        
        $this->testMeta = MealsMeta::firstOrCreate(['meta_key' => 'Test Meta'], [
            'meal_id' => $this->testMeal->id,
            'meta_key' => 'Test Meta',
            'meta_value' => 10,          
        ]);
        
    }

    
    /**
     * Test on creating a product meal meta
     *
     * @return void
     */
    public function testCreateMeta()
    {
        $meta = [
            'meal_id' => $this->testMeal->id,
            'meta_key' => 'Meta '. $this->faker->word(5),
            'meta_value' => 10000,
            'create_new' => 'on',
        ];

        $response = $this->call('PUT', 'products/meta/create', $meta);
       
        //print_r($response);
        $response->assertStatus(200)->assertJson([
             'success' => true,
        ]);
    }


     /**
     * Test on Search meta if it has key [items]
     *
     * @return void
     */
    public function testSearchMeta(){
        $response = $this->call('GET', 'products/meta/search-field?search=test');
        $response->assertStatus(200)->assertJsonStructure(['items']);
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
     * Test on Deleting a meta
     *
     * @return void
     */
    public function testDeleteMeta()
    {

        $response = $this->call('DELETE', 'products/meta/delete/'.$this->testMeal->id.'/'.$this->testMeta->id);

        $response->assertStatus(200)->assertJson([
             'success' => 1,
        ]);
    }




    /**
     * Test on Update a Meal meta
     *
     * @return void
     */
    public function testUpdateMeta()
    {
       
        $updateMeta = [
            'meal_id' => $this->testMeal->id,
            'id' => $this->testMeta->id,
            'meta_key' => $this->testMeta->meta_key,
            'meta_value' => 1,
        ];
      
        $response = $this->call('PUT', 'products/meta/create', $updateMeta);

        $response->assertJson([
             'success' => true,
        ]);
    }


    /**
     * Test on View edit meal meta if it will load
     *
     * @return void
     */
    public function testViewEditMeta()
    {
        $response = $this->call('GET', 'products/meta/edit/'.$this->testMeta->id);
        $response->assertStatus(200)
                ->assertSeeText($this->testMeta->meta_key)
                ->assertSeeText($this->testMeta->meta_value);
    }
}
