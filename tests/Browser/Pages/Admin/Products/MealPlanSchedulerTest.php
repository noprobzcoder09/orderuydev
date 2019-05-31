<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Users;
use Log;

class MealPlanSchedulerTest extends DuskTestCase
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
    }


    /**
     * Test dashboard view 
     *
     * @return void
     */

    public function testView()
    {
        $this->browse(function (Browser $browser) {

            Log::info('Browser Test for admin Meal Plan Scheduler page.');
           
            $browser->loginAs($this->admin)
                    ->visit('products/plan/scheduler')
                    ->pause(10000)
                    ->assertPathIs('/products/plan/scheduler')
                    ->assertSee('Meals Plan Scheduler')
                    ;
        });
    }

    public function testAdd()
    {
        $this->browse(function (Browser $browser) {

            Log::info('Browser Test for admin Meal Plan Scheduler - add');
           
            $browser->loginAs($this->admin)
                    ->visit('products/plan/scheduler')
                    ->pause(10000)
                    ->assertPathIs('/products/plan/scheduler')
                    ->assertVisible('#DataTables_Table_0 tbody tr:first-child td:last-child a')
                    ->click('#DataTables_Table_0 tbody tr:first-child td:last-child a')
                    ->pause(10000)
                    ->assertVisible('#scheduler-form')
                    ->whenAvailable('#scheduler-form', function($schedulerForm){
                        $schedulerForm->check('add_all')
                                      ->pause(5000)  
                                      ->press('Save')
                                      ;
                    })
                    ->pause(3000)
                    ->assertSee('Saved!')
                    ;
        });
    }


    public function testEdit()
    {
        $this->browse(function (Browser $browser) {

            Log::info('Browser Test for admin customer page.');

            $browser->loginAs($this->admin)
                    ->visit('products/plan/scheduler')
                    ->pause(10000)
                    ->assertPathIs('/products/plan/scheduler')
                    ->assertSee('Meals Plan Scheduler')
                    ->assertVisible('#DataTables_Table_0 tbody tr:first-child td:last-child a')
                    ->click('#DataTables_Table_0 tbody tr:first-child td:last-child a')
                    ->pause(10000)
                    ->assertVisible('#scheduler-form')
                    ->whenAvailable('#scheduler-form', function($schedulerForm){
                        $schedulerForm->check('add_all')
                                      ->pause(5000)
                                      ->click('.select2-container--bootstrap .select2-selection--multiple ul li:first-child > span')
                                      ->click('.select2-container--bootstrap .select2-selection--multiple ul li:first-child > span')
                                      ->press('Save') 
                                      ;
                    })   
                    ->pause(3000)
                    ->assertSee('Saved!')              
                    ;
        });
    }


    

    
}
