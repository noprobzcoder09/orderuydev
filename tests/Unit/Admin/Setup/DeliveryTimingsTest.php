<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\DeliveryTimings;


class DeliveryTimingsTest extends TestCase
{
    use WithoutMiddleware;

    private $testDeliveryTimings;
    private $testExistDeliveryTimings;
   

    protected function setUp(){
        parent::setUp();

        $this->testDeliveryTimings = DeliveryTimings::firstOrCreate(['delivery_day' => 'Monday', 'cutoff_day' => 'Tuesday'], [
            'delivery_day' => 'Monday',
            'cutoff_day' => 'Tuesday',
            'cutofftime_hour' => '01',
            'cutofftime_minute' => '01',
            'cutofftime_a' => 'am'
        ]);

        $this->testExistDeliveryTimings = DeliveryTimings::find(1);
    }


    /**
     * Test on creating exist Timing
     *
     * @return void
     */
    public function testCreateExist()
    {
        $response = $this->call(
            'PUT', 
            'delivery/timing/create',
            [
                'delivery_day' => $this->testExistDeliveryTimings->delivery_day,
                'cutoff_day' => $this->testExistDeliveryTimings->cutoff_day,
                'cutofftime_hour' => '01',
                'cutofftime_minute' => '01',
                'cutofftime_a' => 'am'
            ]
        );
        $response->assertStatus(200)->assertJson([
            'success' => false
        ]);
    }
    

    /**
     * Test on creating new Timing
     *
     * @return void
     */
    public function testCreate()
    {
        //temporary delete for exist data   
        $dz = $this->testDeliveryTimings->delete();
        
        $response = $this->call(
            'PUT', 
            'delivery/timing/create',
            [
                'delivery_day' => 'Monday',
                'cutoff_day' => 'Wednesday',
                'cutofftime_hour' => '01',
                'cutofftime_minute' => '01',
                'cutofftime_a' => 'am'
            ]
        );
        
        $response->assertStatus(200)->assertJson([
            'success' => true
        ]);
        

    }

     /**
     * Test on updating a Timing
     *
     * @return void
     */
    public function testUpdate()
    {
        $response = $this->call(
            'PATCH', 
            'delivery/timing/update',
            [
                'id' => $this->testDeliveryTimings->id,
                'delivery_day' => $this->testDeliveryTimings->delivery_day,
                'cutoff_day' => $this->testDeliveryTimings->cutoff_day,
                'cutofftime_hour' => '12',
                'cutofftime_minute' => '12',
                'cutofftime_a' => 'pm'
            ]
        );
        
        $response->assertStatus(200)->assertJson([
            'success' => true
        ]);
    
    }


     /**
     * Test on deleting a Timing
     *
     * @return void
     */
    public function testDelete(){
        $response = $this->call('DELETE', 'delivery/timing/delete/'.$this->testDeliveryTimings->id);

        $response->assertStatus(200)->assertJson([
            'success' => 1
        ]);
    }


     /**
     * Test if view all Timing page has 200 status response
     *
     * @return void
     */
    public function testViewAll(){
        $response = $this->call('GET', 'delivery/timing/all-timings');
        $response->assertStatus(200);
    }


     /**
     * Test if edit a DZT page has 200 status response
     *
     * @return void
     */
    public function testViewEdit(){
        
        $response = $this->call('GET', 'delivery/timing/edit/'.$this->testDeliveryTimings->id);
        $response->assertStatus(200);        
    }


    /**
     * Test if new page has 200 status response
     *
     * @return void
     */
    public function testViewNew(){
        
        $response = $this->call('GET', 'delivery/timing/new');
        $response->assertStatus(200);        
    }
    
}
