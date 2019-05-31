<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Users;
use App\Models\DeliveryZoneTimings;
use Illuminate\Foundation\Testing\WithFaker;
use Log;

class DeliveryZoneTimingTest extends DuskTestCase
{

    use WithFaker;
    /**
     * Check view
     *
     * @return void
     */

    private $admin;
    private $dzt;
    
   
    public function setUp(){
        parent::setUp();
        
        $this->admin = Users::with('details')->whereRole('administrator')->whereActive('1')->first();
        $this->dzt = DeliveryZoneTimings::latest()->first();
        
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

            Log::info('Browser Test for admin Delivery Zone Timing page.');
           
            $browser->loginAs($this->admin)
                    ->visit('delivery/zone/timing/all-zone-timings')
                    ->pause(10000)
                    ->assertPathIs('/delivery/zone/timing/all-zone-timings')
                    ;
        });

    }


    public function testAdd()
    {
        $this->browse(function (Browser $browser) {

            Log::info('Browser Test for admin Delivery Zone Timing page - Add.');
           
            $browser->loginAs($this->admin)
                    ->visit('delivery/zone/timing/new')
                    ->pause(10000)
                    ->assertVisible('#zone-form')
                    ->select('delivery_zone_id', 1)
                    ->select('delivery_timings_id', 1)
                    ->press('Submit')
                    ->pause(3000)
                    ->assertSee('Successfully created new Delivery Zone timing.')                    
                    ;
        });
    }


    public function testEdit()
    {
        $this->browse(function (Browser $browser) {

            Log::info('Browser Test for admin Delivery Zone Timing page Edit '.$this->dzt->id);
           
            $browser->loginAs($this->admin)
                    ->visit('delivery/zone/timing/edit/'.$this->dzt->id)
                    ->pause(10000)
                    ->assertVisible('#zone-form')
                    ->select('delivery_zone_id', $this->dzt->delivery_zone_id)
                    ->select('delivery_timings_id', 1)
                    ->press('Submit')
                   
                    ->assertSee('Successfully updated Delivery Zone timing.')                    
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
