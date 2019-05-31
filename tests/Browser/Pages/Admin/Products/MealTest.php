<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Users;
use App\Models\Meals;
use Illuminate\Foundation\Testing\WithFaker;
use Log;

class MealTest extends DuskTestCase
{

    use WithFaker;
    /**
     * Check view
     *
     * @return void
     */

    private $admin;
    private $customer;
    private $meal;
    
   
    public function setUp(){
        parent::setUp();
        
        $this->admin = Users::with('details')->whereRole('administrator')->whereActive('1')->first();
        $this->customer = Users::with('details')->whereRole('customer')->whereActive('1')->first();
        $this->meal = $this->getLatestMeal();
        
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

            Log::info('Browser Test for admin Meals page.');
           
            $browser->loginAs($this->admin)
                    ->visit('products/plan/all-meals')
                    ->pause(10000)
                    ->assertPathIs('/products/plan/all-meals')
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

            Log::info('Browser Test for adding a meal.');

            $browser->loginAs($this->admin)
                    ->visit('products/meals/new')
                    ->pause(10000)
                    ->assertVisible('#meals-form')
                    ->whenAvailable('#meals-form', function($mealForm) {
                        $mealForm->type('meal_sku', $this->faker->text(6))
                                ->type('meal_name', $this->faker->word())
                                //->check vegetarian
                                ->clickToggle('label.switch span.switch-label')
                                ->press('Submit')
                                ;  
                    })
                    ->pause(10000)
                    ->assertPathIs('/products/meals/edit/' . ($this->getLatestMeal()->id))
                    ;
        });
    }


    public function getLatestMeal() {
        return Meals::latest()->first();
    }


    public function testEdit()
    {
      
        $this->browse(function (Browser $browser) {

            Log::info('Browser Test for editing a meal id.'. $this->meal->id);

            $browser->loginAs($this->admin)
                    ->visit('products/meals/edit/'. $this->meal->id)
                    ->pause(10000)
                    ->assertVisible('#meals-form')
                    ->assertVisible('#meta-form')
                    ->assertVisible('#form-meal')
                    ->whenAvailable('#meals-form', function($mealForm) {
                        $mealForm->type('meal_name', $this->faker->word())
                                 ->press('Submit')
                               ;
                    })
                    ->pause(5000)
                    ->assertSee('Success!')
                    ->whenAvailable('#meta-form', function($metaForm) {
                        $metaForm->check('create_new')
                                ->type('meta_key', 'testmetakey')
                                ->type('meta_value', 'testmetavalue')
                                ->press('Submit')
                                ;
                    })
                    ->pause(5000)
                    ->assertSee('Success!')     
                    ->whenAvailable('#form-meal', function($formMeal) {
                        $formMeal->assertSee('testmetakey')
                               ;
                    })              
                    ;
        });
    }


    public function testDelete()
    {
        $this->browse(function (Browser $browser) {

            Log::info('Browser Test for deleting a meal.');

            $browser->loginAs($this->admin)
                    ->visit('products/meals/all-meals')
                    ->pause(10000)
                    ->select('DataTables_Table_0_length', '100')
                    ->pause(10000)
                    ->click('@delete-'.$this->meal->id)
                    ->pause(50000)
                    ->assertSee('Are you sure you want to delete this?')
                    ->press('Yes')
                    ->pause(3000)
                    ->assertSee('Deleted')
                    ;
        });
    }

    
}
