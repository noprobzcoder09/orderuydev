<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\DeliveryZone;


class DeliveryZoneTest extends TestCase
{
    use WithoutMiddleware;

    protected $testDeliveryZone;
   

    protected function setUp(){
        parent::setUp();

        $this->testDeliveryZone = DeliveryZone::firstOrCreate(['zone_name' => 'Test Zone'], ['zone_name' => 'Test Zone']);
    }


    /**
     * Test on creating new DZ
     *
     * @return void
     */
    public function testCreate()
    {
        //temporary delete for exist data   
        $dz = $this->testDeliveryZone->delete();
        
        $response = $this->call(
            'PUT', 
            'delivery/zone/create',
            [
                'zone_name' => 'Test Zone Create',
            ]
        );
        
        $response->assertStatus(200)->assertJson([
            'success' => true
        ]);
        

    }


    /**
     * Test on creating exist DZT
     *
     * @return void
     */
    public function testCreateExist()
    {
        $response = $this->call(
            'PUT', 
            'delivery/zone/create',
            [
                'zone_name' => 'Test Zone Create',
            ]
        );
        
        $response->assertStatus(200)->assertJson([
            'success' => false
        ]);
    }


     /**
     * Test on updating a DZT
     *
     * @return void
     */
    public function testUpdate()
    {
        $response = $this->call(
            'PATCH', 
            'delivery/zone/update',
            [
                'id' => $this->testDeliveryZone->id,
                'zone_name' => 'Test Updated Zone',
            ]
        );
        
        $response->assertStatus(200)->assertJson([
            'success' => true
        ]);
    
    }


     /**
     * Test on deleting a DZT
     *
     * @return void
     */
    public function testDelete(){
        $response = $this->call('DELETE', 'delivery/zone/delete/'.$this->testDeliveryZone->id);

        $response->assertStatus(200)->assertJson([
            'success' => 1
        ]);
    }


     /**
     * Test if view all DZT page has 200 status response
     *
     * @return void
     */
    public function testViewAll(){
        $response = $this->call('GET', 'delivery/zone/all-zones');
        $response->assertStatus(200);
    }


     /**
     * Test if edit a DZT page has 200 status response
     *
     * @return void
     */
    public function testViewEdit(){
        
        $response = $this->call('GET', 'delivery/zone/edit/'.$this->testDeliveryZone->id);
        $response->assertStatus(200);        
    }


    /**
     * Test if new page has 200 status response
     *
     * @return void
     */
    public function testViewNew(){
        
        $response = $this->call('GET', 'delivery/zone/new');
        $response->assertStatus(200);        
    }
    
}
