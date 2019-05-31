<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Users;
use Log;
use Illuminate\Foundation\Testing\WithFaker;

class UserTest extends DuskTestCase
{

    use WithFaker;
    /**
     * Check view
     *
     * @return void
     */

    private $admin;
    private $user;
  
   
    public function setUp(){
        parent::setUp();
        
        $this->admin = Users::with('details')->whereRole('administrator')->whereActive('1')->first();
        $this->user = Users::latest()->first();
      
        
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

            Log::info('Browser Test for admin users page.');
           
            $browser->loginAs($this->admin)
                    ->visit('/users/all-users')
                    ->pause(10000)
                    ->assertPathIs('/users/all-users')
                    ;
        });

    }


    public function testAdd()
    {
        $this->browse(function (Browser $browser){

            Log::info('Browser Test for admin User page - Add.');
           
            $browser->loginAs($this->admin)
                    ->visit('/users/new')
                    ->pause(10000)
                    ->assertVisible('#user-form')
                    ->whenAvailable('#user-form', function($userForm) {
                        $userForm->type('email', $this->faker->email)
                                ->type('name', $this->faker->firstName.' '.$this->faker->lastName)
                                ->select('role', 'customer')
                                ->press('Submit')
                                ;
                    })
                    ->pause(3000)
                    ->assertSee('Successfully created new user.')
                    ;
        });


    }


    public function testEdit()
    {
        $this->browse(function (Browser $browser){
            
            Log::info('Browser Test for admin User page Edit '.$this->user->id);
           
            $browser->loginAs($this->admin)
                    ->visit('/users/edit/'.$this->user->id)
                    ->pause(10000)
                    ->assertVisible('#user-form')
                    ->whenAvailable('#user-form', function($userForm) {
                        $userForm->type('name', $this->faker->firstName.' '.$this->faker->lastName)
                                ->select('role', 'customer')
                                ->press('Submit')
                                ;
                    })
                    ->assertSee('Successfully updated user.')
                    ->pause(5000)
                    ->assertPathIs('/users/all-users')
                    ;
        });        
    }

    
}
