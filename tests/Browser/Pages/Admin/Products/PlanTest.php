<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Users;
use App\Models\MealPlans;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Log;

class PlanTest extends DuskTestCase
{

    use WithFaker;
    /**
     * Check view
     *
     * @return void
     */

    private $admin;
    private $customer;
    private $testPlan;
    
   
    public function setUp(){
        parent::setUp();
        
        $this->admin = Users::with('details')->whereRole('administrator')->whereActive('1')->first();
        $this->customer = Users::with('details')->whereRole('customer')->whereActive('1')->first();
        $this->testPlan = MealPlans::whereSku('TestPlan')->first();
       
        
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

            Log::info('Browser Test for admin Plan page.');
           
            $browser->loginAs($this->admin)
                    ->visit('products/plan/all-plans')
                    ->pause(10000)
                    ->assertPathIs('/products/plan/all-plans')
                    ;
        });

    }


    public function testAdd()
    {
        Browser::macro('clickToggle', function($inputSelector) {
            $this->ensurejQueryIsAvailable();
            $this->driver->executeScript("jQuery(\"{$inputSelector}\").parent().click();");
            return $this;
        });
        
        $this->browse(function (Browser $browser) {

            Log::info('Browser Test for adding a plan.');

            $testFilePath = base_path() . '/tests/testassets/sample.jpg';
            $testFile = UploadedFile::fake()->image($testFilePath); 
           
            $browser->loginAs($this->admin)
                    ->visit('products/plan/new')
                    ->pause(10000)
                    ->assertVisible('#meals-plan-form')
                    ->whenAvailable('#meals-plan-form', function($planForm) use ($testFile) {
                        $planForm->type('sku', $this->faker->text(6))
                                ->type('plan_name', $this->faker->word())
                                ->type('no_days', 7)
                                ->type('no_meals', 7)
                                ->type('plan_name', 7)                                
                                //->check vegetarian
                                ->clickToggle('label.switch span.switch-label')
                                ->select('ins_product_id', 1)
                                ->attach('meal_plan_image', $testFile)
                                ->type('price', 900)
                                ->press('Submit')
                                ;
                    })
                    ->assertSee('Successfully created new Meals Plan.')
                    ;
        });
    }


    public function testEdit()
    {
        $this->browse(function (Browser $browser) {

            Log::info('Browser Test for editing a test plan.');

            if ( is_null($this->testPlan )) {
                $this->testPlan = MealPlans::create([
                    'sku' => 'TestPlan',
                    'plan_name' => 'Test Plan',
                    'ins_product_id' => 1,
                    'no_meals' => 7,
                    'no_days' => 7,
                    'vegetarian' => 1,
                    'price' => 1500,
                ]);
            }
            

            $testFilePath = base_path() . '/tests/testassets/sample.jpg';
            $testFile = UploadedFile::fake()->image($testFilePath); 

            Browser::macro('clickToggle', function($inputSelector) {
                $this->ensurejQueryIsAvailable();
                $this->driver->executeScript("jQuery(\"{$inputSelector}\").parent().click();");
                return $this;
            });

            $browser->loginAs($this->admin)
                    ->visit('products/plan/edit/'.$this->testPlan->id)
                    ->pause(50000)
                    ->assertVisible('#meals-plan-form')
                    ->whenAvailable('#meals-plan-form', function($planForm) use ($testFile) {
                        $planForm->type('plan_name', 'Test Plan Edited')
                                ->type('no_days', 7)
                                ->type('no_meals', 7)                                                      
                                //->check vegetarian
                                ->clickToggle('label.switch span.switch-label')
                                ->select('ins_product_id', 1)
                                ->attach('meal_plan_image', $testFile)
                                ->type('price', 900)
                                ->press('Submit')
                                ;
                    })
                    ->pause(500)
                    ->assertSee('Successfully updated Meals Plan.')
                    ;
        });
    }


    public function testDelete()
    {
       $this->browse(function (Browser $browser) {

            Log::info('Browser Test for deleting the last row of the table.');

            $browser->loginAs($this->admin)
                    ->visit('products/plan/all-plans')
                    ->pause(10000)
                    ->assertVisible('#DataTables_Table_0_wrapper table#DataTables_Table_0')
                    ->whenAvailable('#DataTables_Table_0_wrapper table#DataTables_Table_0', function($table) {
                        $table->click('tr:last-child td a.deleteData')
                                ;
                    })
                    ->pause(1000)
                    ->assertSee('Are you sure you want to delete this?')
                    ->press('Yes')
                    ->pause(3000)
                    ->assertSee('Successfully deleted')
                    ;
        });
    }



   
}
