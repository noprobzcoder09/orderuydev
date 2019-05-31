<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\DeliveryZoneTimings;
use App\Models\DeliveryZone;
use App\Models\DeliveryTimings;


class DeliveryZoneTimingsTest extends TestCase
{
    use WithoutMiddleware;

    private $testDeliveryZoneTimings;
    private $deliveryZone;
    private $deliveryTimings;

    
    protected function setUp(){
        parent::setUp();

        $this->deliveryZone = DeliveryZone::first();
        $this->deliveryTimings = DeliveryTimings::first();
        $this->testDeliveryZoneTimings = DeliveryZoneTimings::first();
        
    }


    public function testCheckDB()
    {
        if (
            is_null($this->deliveryZone) ||
            is_null($this->deliveryTimings)
        ) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
        }        
       
    }


    /**
     * Test on creating new DZT
     *
     * @return void
     */
    public function testCreate()
    {
        //temporary delete for exist data   
        $dzt = DeliveryZoneTimings::whereDeliveryZoneId($this->deliveryZone->id)->whereDeliveryTimingsId($this->deliveryTimings->id)->delete();
        
        $response = $this->call(
            'PUT', 
            'delivery/zone/timing/create',
            [
                'delivery_zone_id' => $this->deliveryZone->id,
                'delivery_timings_id' => $this->deliveryTimings->id
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
            'delivery/zone/timing/create',
            [
                'delivery_zone_id' => $this->deliveryZone->id,
                'delivery_timings_id' => $this->deliveryTimings->id
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
            'delivery/zone/timing/update',
            [
                'id' => $this->testDeliveryZoneTimings->id,
                'delivery_zone_id' => $this->deliveryZone->id,
                'delivery_timings_id' => $this->deliveryTimings->id
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
        $response = $this->call('DELETE', 'delivery/zone/timing/delete/'.$this->testDeliveryZoneTimings->id);

        $response->assertStatus(200)->assertJson([
            'success' => true
        ]);
    }


     /**
     * Test if view all DZT page has 200 status response
     *
     * @return void
     */
    public function testViewAll(){
        $response = $this->call('GET', 'delivery/zone/timing/all-zone-timings');
        $response->assertStatus(200);
    }


     /**
     * Test if edit a DZT page has 200 status response
     *
     * @return void
     */
    public function testViewEdit(){
        
        $response = $this->call('GET', 'delivery/zone/timing/edit/'.$this->testDeliveryZoneTimings->id);
        $response->assertStatus(200);        
    }


    /**
     * Test if new page has 200 status response
     *
     * @return void
     */
    public function testViewNew(){
        
        $response = $this->call('GET', 'delivery/zone/timing/new');
        $response->assertStatus(200);        
    }
    
}
