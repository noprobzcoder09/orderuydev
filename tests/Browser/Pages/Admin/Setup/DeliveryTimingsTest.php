<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Users;
use App\Models\DeliveryTimings;
use Illuminate\Foundation\Testing\WithFaker;
use Log;

class DeliveryTimingsTest extends DuskTestCase
{

    use WithFaker;
    /**
     * Check view
     *
     * @return void
     */

    private $admin;
    private $timing;
    
   
    public function setUp(){
        parent::setUp();
        
        $this->admin = Users::with('details')->whereRole('administrator')->whereActive('1')->first();
        $this->timing = DeliveryTimings::latest()->first();
        
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

            Log::info('Browser Test for admin Delivery Timing page.');
           
            $browser->loginAs($this->admin)
                    ->visit('delivery/timing/all-timings')
                    ->pause(10000)
                    ->assertPathIs('/delivery/timing/all-timings')
                    ;
        });

    }


    public function testAdd()
    {
        $this->browse(function (Browser $browser) {

            Log::info('Browser Test for admin Delivery Zone Timing page - Add.');
           
            $browser->loginAs($this->admin)
                    ->visit('delivery/timing/new')
                    ->pause(10000)
                    ->assertVisible('#zone-form')
                    ->select('delivery_day', 'Monday')
                    ->select('cutoff_day', 'Monday')
                    ->select('cutofftime_hour', '01')
                    ->select('cutofftime_minute', '01')
                    ->select('cutofftime_a', 'am')
                    ->press('Submit')
                    ->pause(3000)
                    ->assertSee('Successfully created new Delivery Schedule.')                    
                    ;
        });
    }


    public function testEdit()
    {
        $this->browse(function (Browser $browser) {

            Log::info('Browser Test for admin Delivery Zone Schedule page Edit '.$this->timing->id);
           
            $browser->loginAs($this->admin)
                    ->visit('delivery/timing/edit/'.$this->timing->id)
                    ->pause(10000)
                    ->assertVisible('#zone-form')
                    ->select('delivery_day', 'Monday')
                    ->select('cutoff_day', 'Monday')
                    ->select('cutofftime_hour', '01')
                    ->select('cutofftime_minute', '02')
                    ->select('cutofftime_a', 'pm')
                    ->press('Submit')
                    ->assertSee('Successfully updated Delivery Schedule.')    
                    ->pause(5000)        
                    ->assertPathIs('/delivery/timing/all-timings')        
                    ;
        });
    }


    public function testDelete()
    {
        $this->browse(function (Browser $browser) {

            Log::info('Browser Test for deleting the last row of the table.');
           
            $browser->loginAs($this->admin)
                    ->visit('delivery/zone/timing/all-zone-timings')
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
