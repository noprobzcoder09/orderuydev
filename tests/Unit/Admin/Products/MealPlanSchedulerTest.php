<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\Cycles;
use App\Models\Meals;
use Carbon\Carbon;

class ProductMealPlanScheduler extends TestCase
{

    use WithoutMiddleware;
    use withFaker;


    private $testCycle;
    private $testMeals;

    protected function setUp() {
        parent::setup();

        $this->testCycle = Cycles::whereStatus(1)->first();
        $this->testMeals = Meals::whereStatus(1)->get();
    }

    /**
     * Test on checking if Meals Plan Scheduler will load
     *
     * @return void
     */
    public function testView()
    {
        $this->call('GET', 'products/plan/scheduler')->assertStatus(200);
    }

    
    /**
     * Test on all meals plan scheduler active status
     *
     * @return void
     */
    public function testActiveRequest()
    {        
        $this->call('GET', 'products/plan/schedule-list?status=1')->assertStatus(200);
      
    }

     
    /**
     * Test on all meals plan scheduler inactive status
     *
     * @return void
     */
    public function testInactiveRequest()
    {        
        $this->call('GET', 'products/plan/schedule-list?status=0')->assertStatus(200);
        
    }

     
    /**
     * Test on all meals plan scheduler all status
     *
     * @return void
     */
    public function testAllRequest()
    {        
        $this->call('GET', 'products/plan/schedule-list?status=all')->assertStatus(200);
    }


    public function testViewCycle(){
       
        $response = $this->call('GET', 'products/plan/manage-meals-status/'.$this->testCycle->id);
        $response->assertStatus(200)
                ->assertSee(Carbon::parse($this->testCycle->delivery_date)->format('l dS F Y'))
                ->assertSee(Carbon::parse($this->testCycle->cutover_date)->format('l dS F Y'));
        
    }

    public function testSave(){

       
        $arrayMealIds = [];
        if (!$this->testMeals->isEmpty()) {
            foreach ($this->testMeals as $meal) {
                array_push($arrayMealIds, $meal->id);
            }            
        }

        $patch = array_merge(['cycle_id' => $this->testCycle->id], ['meal_ids_add' => $arrayMealIds]);
        
        $response = $this->call(
                        'PATCH',
                        'products/plan/save-meals-status/'.$this->testCycle->id,
                        $patch
                    );

        $response->assertStatus(200)->assertJson([
            'success' => true
        ]);
    }
   
}
