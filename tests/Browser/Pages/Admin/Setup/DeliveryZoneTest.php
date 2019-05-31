<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Users;
use App\Models\DeliveryZone;
use Illuminate\Foundation\Testing\WithFaker;
use Log;

class DeliveryZoneTest extends DuskTestCase
{

    use WithFaker;
    /**
     * Check view
     *
     * @return void
     */

    private $admin;
    private $deliveryZone;
    
   
    public function setUp(){
        parent::setUp();
        
        $this->admin = Users::with('details')->whereRole('administrator')->whereActive('1')->first();
        $this->deliveryZone = DeliveryZone::latest()->first();
        
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

            Log::info('Browser Test for admin Delivery Zone page.');
           
            $browser->loginAs($this->admin)
                    ->visit('delivery/zone/all-zones')
                    ->pause(10000)
                    ->assertPathIs('/delivery/zone/all-zones')
                    ;
        });

    }


    public function testAdd()
    {
        $this->browse(function (Browser $browser) {

            Log::info('Browser Test for admin Delivery Zone page - Add.');
           
            $browser->loginAs($this->admin)
                    ->visit('delivery/zone/new')
                    ->pause(10000)
                    ->assertVisible('#zone-form')
                    ->type('zone_name', 'Test Zone')
                    ->press('Submit')
                    ->pause(5000)
                    ->assertSee('Successfully created new Delivery Zone.')
                    ;
        });
    }


    public function testEdit()
    {
        $this->browse(function (Browser $browser) {

            Log::info('Browser Test for admin Delivery Zone page Edit '.$this->deliveryZone->id);
           
            $browser->loginAs($this->admin)
                    ->visit('delivery/zone/edit/'.$this->deliveryZone->id)
                    ->pause(10000)
                    ->assertVisible('#zone-form')
                    ->type('zone_name', $this->deliveryZone->zone_name.' Edited')
                    ->press('Submit')
                    ->pause(5000)
                    ->assertPathIs('/delivery/zone/all-zones')
                    ;
        });
    }


    public function testDelete()
    {
        $this->browse(function (Browser $browser) {

            Log::info('Browser Test for deleting the last row of the table.');
           
            $browser->loginAs($this->admin)
                    ->visit('delivery/zone/all-zones')
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
