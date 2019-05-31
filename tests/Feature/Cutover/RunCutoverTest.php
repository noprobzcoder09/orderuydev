<?php

namespace Tests\Unit;

use Tests\TestCase;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

use App\Models\Cycles;
use Log;

class SubscriptionTest extends TestCase
{

    use WithoutMiddleware;
    use WithFaker;
    
    private $cutoverDates;

    protected function setUp() {
        parent::setUp(); 

       $this->cutoverDates = [
            '2019-12-16',
            '2019-12-23',
            '2019-12-30',
        ];

    }

    
    public function testSinglePlan() {


        foreach ($this->cutoverDates as $cutover) {
            $cutoverCall = $this->call('GET', '/cutover?date='.$cutover);

            Log::info('cutover run - '. $cutover);

            if (
                $cutoverCall->assertStatus(200) &&
                $cutoverCall->assertSeeText('1')
            ) {
                $generateExcel = $this->call('GET', '/tests/generate/excel?name='.$cutover.'-singleplan-cutover');
                if (
                    $generateExcel->assertStatus(200) &&
                    $generateExcel->assertSeeText('1')
                ) {
                    $this->assertTrue(true);
                } else {
                    break;
                }
            }
        }


    }


    public function testMultiplePlan() {


        foreach ($this->cutoverDates as $cutover) {
            $cutoverCall = $this->call('GET', '/cutover?date='.$cutover);

            Log::info('cutover run - '. $cutover);

            if (
                $cutoverCall->assertStatus(200) &&
                $cutoverCall->assertSeeText('1')
            ) {
                $generateExcel = $this->call('GET', '/tests/generate/excel?name='.$cutover.'-multipleplan-cutover');
                if (
                    $generateExcel->assertStatus(200) &&
                    $generateExcel->assertSeeText('1')
                ) {
                    $this->assertTrue(true);
                } else {
                    break;
                }
            }
        }


    }


    public function testPausePlan() {


        foreach ($this->cutoverDates as $cutover) {
            $cutoverCall = $this->call('GET', '/cutover?date='.$cutover);

            Log::info('cutover run - '. $cutover);

            if (
                $cutoverCall->assertStatus(200) &&
                $cutoverCall->assertSeeText('1')
            ) {
                $generateExcel = $this->call('GET', '/tests/generate/excel?name='.$cutover.'-pause-plan-cutover');
                if (
                    $generateExcel->assertStatus(200) &&
                    $generateExcel->assertSeeText('1')
                ) {
                    $this->assertTrue(true);
                } else {
                    break;
                }
            }
        }
    }




}
