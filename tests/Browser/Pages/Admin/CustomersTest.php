<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Users;
use App\Models\Subscriptions;
use App\Models\SubscriptionsSelections;
use App\Models\DeliveryZoneTimings;
use App\Models\MealPlans;
use App\Models\Cycles;
use Log;
use Illuminate\Foundation\Testing\WithFaker;

class CustomersTest extends DuskTestCase
{

    use WithFaker;
    /**
     * Check view
     *
     * @return void
     */

    private $admin;
    private $customer;
    private $userSubscription;
    private $deliveryZoneTimings;
    private $userPauseSubscription;

   
    public function setUp(){
        parent::setUp();
        
        $this->admin = Users::with('details')->whereRole('administrator')->whereActive('1')->first();
        $this->customer = Users::find(2);
        //$this->customer = Users::with('details')->whereRole('customer')->whereActive('1')->first();
        $this->userSubscription = Subscriptions::where('status', '!=', 'cancelled')->whereUserId($this->customer->id)->first();
        $this->userToPausedSubscription = Subscriptions::where('status', '!=', 'paused')->where('status', '!=', 'cancelled')->whereUserId($this->customer->id)->first();
        $this->userToResumeSubscription = Subscriptions::where('status', '=', 'paused')->where('status', '!=', 'cancelled')->whereUserId($this->customer->id)->first();
        $this->deliveryZoneTimings = DeliveryZoneTimings::first();
        
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

            Log::info('Browser Test for admin customer page.');
           
            $browser->loginAs($this->admin)
                    ->visit('/customers')
                    ->pause(10000)
                    ->assertPathIs('/customers')
                    ;
        });

    }


    public function testAddCustomer()
    {
        $fakeEmail = $this->faker->email;
        $this->browse(function (Browser $browser) use ($fakeEmail){

            Log::info('Browser Test for admin customer page - Adding new customer.');
           
            $browser->loginAs($this->admin)
                    ->visit('/customers')
                    ->pause(20000)                    
                    ->clickLink('Add new')
                    ->pause(10000)
                    ->assertPathIs('/customers/new/find-email')
                    ->type('email', $fakeEmail)
                    ->press('Go!')
                    ->pause(10000)
                    ->assertQueryStringHas('email', $fakeEmail)
                    ->pause(2000)
                    ->type('first_name', $this->faker->firstName)
                    ->type('last_name', $this->faker->lastName)
                    ->type('mobile_phone', '11111')
                    ->type('address1', 'Sydney')
                    ->type('address2', 'Sydney')
                    ->type('suburb', 'Sydney')
                    ->select('state', 1)
                    ->type('postcode', 1000)
                    ->select('delivery_zone_timings_id', 1)
                    ->click('#submit-form')
                    ->pause(500)
                    ->assertSee('Successfully')
                    ->pause(10000)
                    ;
        });


    }


    public function testEditProfile()
    {
        $this->browse(function (Browser $browser) {

            Log::info('Browser Test for admin customer page - Edit existing customer with customer id='.$this->customer->id.'.');
           
            $browser->loginAs($this->admin)
                    ->visit('/customers/edit/'.$this->customer->id)
                    ->pause(20000)    
                    ->click('a[onclick^=editCustomer]')
                    ->pause(10000)
                    ->assertVisible('#customer-form')  
                    ->whenAvailable('#customer-form', function($customerForm) {
                        $customerForm->type('first_name', $this->faker->firstName)
                                    ->type('last_name', $this->faker->lastName)
                                    ->type('mobile_phone', '1111')
                                    ->type('address1', '3650 Davila Street Makati Terraces Condominium')
                                    ->type('address2', 'Makati City')
                                    ->type('suburb', 'Sydney')
                                    ->select('state', 'Sydney')
                                    ->type('postcode', '1204')
                                    ->press('Save changes')
                                    ;
                    })
                    ->pause(2500)
                    ->assertSee('Success!')          
                    ;
        });

    }

    public function testActiveSubscriptionAdvanceBtnAddMarkAsPaid(){

        $mealPlans = MealPlans::groupBy('id')->select('id')->get()->map(function($mealPlan) {
            return $mealPlan->id;
        })->toArray();

        $cards = json_decode($this->customer->details->card_ids);

        if (is_null($this->userSubscription)) {
            $this->browse(function (Browser $browser) use ($mealPlans, $cards){      

                Log::info('Adding plan for customer='.$this->customer->id);
                //add new subscription plan
                $browser->loginAs($this->admin)
                    ->visit('/customers/edit/'.$this->customer->id)
                    ->pause(20000)    
                    ->click('@add-plan')
                    ->pause(20000)    
                    // ->whenAvailable('#active-subscription-modal', function($modal) use ($mealPlans, $cards){
                    //     $modal->assertVisible('#active-subscription-modal')
                    //             //->select('meal_plans_id', $mealPlans[0])
                    //             //->pause(50000)
                    //             //->select('delivery_zone_id', $this->deliveryZoneTimings->delivery_zone_id)
                    //             //->select('delivery_zone_timings_id', $this->deliveryZoneTimings->delivery_timings_id)  
                    //             //->select('card_id', $cards[0])      
                                
                    //             ;
                    // })
                    ->press('Save & Bill at cutover')
                    ->pause(30000)
                    ->assertSee('Are you sure you want to save this new plan?')
                    ->press('Yes')     
                    ->pause(10000)
                    ;
            });
            
            $this->setUp();
            
        }

        if (!is_null($this->userSubscription)) {       
        
            $this->browse(function (Browser $browser) use ($mealPlans, $cards){

                Log::info('Browser Test for admin customer page - Edit existing customer with customer id='.$this->customer->id.' - triggering Advance #groupbuttonadvance-'.$this->userSubscription->id.' button.');
                
                $browser->loginAs($this->admin)
                        ->visit('/customers/edit/'.$this->customer->id)
                        ->pause(10000)    
                        
                        //click advance button
                        ->click('#table-active-subs tbody tr #groupbuttonadvance-'.$this->userSubscription->id)       
                        ->pause(5000)                        
                        ->assertVisible('#table-active-subs tbody tr #groupbuttonadvance-'.$this->userSubscription->id.' + .dropdown-menu')
                        ->click('#table-active-subs tbody tr #groupbuttonadvance-'.$this->userSubscription->id.' + .dropdown-menu')                        
                        ->pause(1000)
                        ->assertVisible('#addmenuprevweek-modal')    
                        ->pause(10000)
                        
                        //modal interaction
                        ->whenAvailable('#addmenuprevweek-modal', function($prevWeekModal) use ($mealPlans, $cards){
                            $prevWeekModal->select('card_id', $cards[0])
                                        //->select('meal_plans_id', $mealPlans[0])
                                        ->pause(20000)
                                        ;
                        })
                        ->press('Add & Mark as Paid')
                        ->pause(30000)
                        ->press('Yes')
                        ->pause(30000)
                        ;
                        
            });
            

            
            //get the latest subscriptions cycles where subscription id = usersSubscription and userid = customer id
            $latestSubscriptionCycle = SubscriptionsSelections::whereUserId($this->customer->id)->whereSubscriptionId($this->userSubscription->id)->latest()->first();
            
            $cycle = Cycles::find($latestSubscriptionCycle->cycle_id);

            //check if the latest subscription is paid and its appearing on the selections section

            
            $this->browse(function (Browser $browser) use ($cycle, $latestSubscriptionCycle){
                
                Log::info('Browser Test for admin customer page - Edit existing customer with customer id='.$this->customer->id.' - checking the latest subscription cycle '.$latestSubscriptionCycle->id.' if its paid');
                
                $browser->loginAs($this->admin)
                    ->visit('/customers/edit/'.$this->customer->id)
                    ->pause(20000)
                    
                    //click selections button                    
                    ->click('#table-active-subs tbody tr:last-child a.selection-control')
                    ->pause(20000)
                    ->assertVisible('#table-active-subs tbody tr:last-child + tr')
                    ->pause(20000)
                    ->assertVisible('#table-active-subs tbody tr:last-child + tr td > table tbody tr:first-child')
                    ->whenAvailable('#table-active-subs tbody tr:last-child + tr td > table tbody tr:first-child', function($selectionRow) use ($cycle){
                                    $selectionRow->assertSee(date('F d, Y', strtotime($cycle->delivery_date)))
                                                ->assertSee('Paid') 
                                    ;
                    })
                    ;                 

            });
            
        }
        
    }


    public function testActiveSubscriptionAdvanceBtnAddBillNow(){

        $mealPlans = MealPlans::groupBy('id')->select('id')->get()->map(function($mealPlan) {
            return $mealPlan->id;
        })->toArray();

        $cards = json_decode($this->customer->details->card_ids);

       

        if (is_null($this->userSubscription)) {
            $this->browse(function (Browser $browser) use ($mealPlans, $cards){      

                Log::info('Adding plan for customer='.$this->customer->id);
                //add new subscription plan
                $browser->loginAs($this->admin)
                    ->visit('/customers/edit/'.$this->customer->id)
                    ->pause(20000)    
                    ->click('@add-plan')
                    ->whenAvailable('#active-subscription-modal', function($modal) use ($mealPlans, $cards){
                        $modal->assertVisible('#active-subscription-modal')
                                ->select('meal_plans_id', $mealPlans[0])
                                ->pause(50000)
                                ->select('delivery_zone_id', $this->deliveryZoneTimings->delivery_zone_id)
                                ->select('delivery_zone_timings_id', $this->deliveryZoneTimings->delivery_timings_id)  
                                //->select('card_id', $cards[0])      
                                
                                ;
                    })
                    ->press('Save & Bill at cutover')
                    ->pause(30000)
                    ->assertSee('Are you sure you want to save this new plan?')
                    ->press('Yes')     
                    ->pause(10000)
                    ;
            });
            
            $this->setUp();
        }

        if (!is_null($this->userSubscription)) {       
        
            $this->browse(function (Browser $browser) use ($mealPlans, $cards){

                Log::info('Browser Test for admin customer page - Edit existing customer with customer id='.$this->customer->id.' - triggering Advance #groupbuttonadvance-'.$this->userSubscription->id.' button.');
                
                $browser->loginAs($this->admin)
                        ->visit('/customers/edit/'.$this->customer->id)
                        ->pause(10000)    
                        
                        //click advance button
                        ->click('#table-active-subs tbody tr #groupbuttonadvance-'.$this->userSubscription->id)       
                        ->pause(5000)
                        
                        ->assertVisible('#table-active-subs tbody tr #groupbuttonadvance-'.$this->userSubscription->id.' + .dropdown-menu')
                        ->click('#table-active-subs tbody tr #groupbuttonadvance-'.$this->userSubscription->id.' + .dropdown-menu')
                        ->pause(1000)
                        ->assertVisible('#addmenuprevweek-modal')    
                        
                        
                        //modal interaction
                        ->whenAvailable('#addmenuprevweek-modal', function($prevWeekModal) use ($mealPlans, $cards){
                            $prevWeekModal->select('meal_plans_id', $mealPlans[0])
                                        ->pause(20000)
                                        ->select('card_id', $cards[0])
                                        ->pause(20000)
                                        ;
                        })
                        ->press('Add & Bill Now')
                        ->pause(30000)
                        ->press('Yes')
                        ->pause(30000)
                        ;
                        
            });
            

           
            //get the latest subscriptions cycles where subscription id = usersSubscription and userid = customer id
            $latestSubscriptionCycle = SubscriptionsSelections::whereUserId($this->customer->id)->whereSubscriptionId($this->userSubscription->id)->latest()->first();
            
            //check if the latest subscription is paid and its appearing on the selections section
            $this->browse(function (Browser $browser) use ($latestSubscriptionCycle){
                
                Log::info('Browser Test for admin customer page - Edit existing customer with customer id='.$this->customer->id.' - checking the latest subscription cycle '.$latestSubscriptionCycle->id.' if its paid and billed');
                
                $browser->loginAs($this->admin)
                    ->visit('/customers/edit/'.$this->customer->id)
                    ->pause(20000)
                    ->assertVisible('table#table-invoice')
                    ->assertVisible('table#table-invoice tr td:first-child')
                    ->whenAvailable('table#table-invoice tr td:first-child', function($invoice) use ($latestSubscriptionCycle){
                                    $invoice->assertSee(date('F d, Y', strtotime($latestSubscriptionCycle->created_at)));
                    })
                    ->assertVisible('table#table-invoice tr td:nth-child(4)')
                    ->whenAvailable('table#table-invoice tr td:nth-child(4)', function($invoice) use ($latestSubscriptionCycle){
                        $invoice->assertSee($latestSubscriptionCycle->ins_invoice_id);
                    })
                    ;

            });
            
            
        }

    }


    public function testAddNewCard()
    {
        $this->browse(function (Browser $browser) {

            Log::info('Browser Test for admin customer page - Adding a new card for customer id='.$this->customer->id);
            
            $browser->loginAs($this->admin)
                    ->visit('/customers/edit/'.$this->customer->id)
                    ->pause(20000)
                    ->assertVisible('a[onclick^=addNewCard]')
                    ->click('a[onclick^=addNewCard]')
                    ->pause(20000)
                    ->assertVisible('#creditcard-modal')
                    ->whenAvailable('#creditcard-modal', function($creditCard) {
                        $creditCard->type('card_name', $this->faker->firstName. ' '.$this->faker->lastName)
                                    ->type('card_number', $this->faker->creditCardNumber)
                                    ->type('card_expiration_date', $this->faker->creditCardExpirationDate)
                                    ->type('card_cvc', '187')
                                    ->press('Save')    
                                    ;
                    })
                    ->pause(5000)
                    ->assertSee('Success!')
                    ;

        });
    }


    public function testEditDZT() 
    {
        $this->browse(function (Browser $browser) {

            Log::info('Browser Test for admin customer page - Editing DZT for customer id='.$this->customer->id);
            
            $browser->loginAs($this->admin)
                    ->visit('/customers/edit/'.$this->customer->id)
                    ->pause(20000)
                    ->assertVisible('a[onclick^=editDeliveryZoneTiming]')
                    ->click('a[onclick^=editDeliveryZoneTiming]')
                    ->pause(20000)
                    ->assertVisible('#delivery-zt-modal')
                    ->whenAvailable('#delivery-zt-modal', function($dzt) {
                                $dzt->select('delivery_zone_timings_id', '1')
                                    ->press('Save changes')    
                                    ;
                    })
                    ->pause(5000)
                    ->assertSee('Success!')
                    ;

        });
    }

    public function testAddPlanBillNow()
    {
        $mealPlans = MealPlans::groupBy('id')->select('id')->get()->map(function($mealPlan) {
            return $mealPlan->id;
        })->toArray();

        $cards = json_decode($this->customer->details->card_ids);
        
        $this->browse(function (Browser $browser) use ($mealPlans, $cards){      

            Log::info('Adding plan for customer='.$this->customer->id);
            //add new subscription plan
            $browser->loginAs($this->admin)
                ->visit('/customers/edit/'.$this->customer->id)
                ->pause(20000)    
                ->click('@add-plan')
                ->whenAvailable('#active-subscription-modal', function($modal) use ($mealPlans, $cards){
                    $modal->assertVisible('#active-subscription-modal')
                            ->select('meal_plans_id', $mealPlans[0])
                            ->pause(50000)
                            ->select('delivery_zone_id', $this->deliveryZoneTimings->delivery_zone_id)
                            ->select('delivery_zone_timings_id', $this->deliveryZoneTimings->delivery_timings_id)  
                            ;
                })
                ->press('Save & Bill Now')
                ->pause(30000)
                ->assertSee('Are you sure you want to save this new plan and bill at cutover?')
                ->press('Yes')     
                //->pause(5000)
                ->pause(2000)
                ->assertSee('Success!')
                ;
        });
    }

    public function testAddPlanBillAtCutover()
    {
        $mealPlans = MealPlans::groupBy('id')->select('id')->get()->map(function($mealPlan) {
            return $mealPlan->id;
        })->toArray();

        $cards = json_decode($this->customer->details->card_ids);
        
        $this->browse(function (Browser $browser) use ($mealPlans, $cards){      

            Log::info('Adding plan for customer='.$this->customer->id);
            //add new subscription plan
            $browser->loginAs($this->admin)
                ->visit('/customers/edit/'.$this->customer->id)
                ->pause(20000)    
                ->click('@add-plan')
                ->whenAvailable('#active-subscription-modal', function($modal) use ($mealPlans, $cards){
                    $modal->assertVisible('#active-subscription-modal')
                            ->select('meal_plans_id', $mealPlans[0])
                            ->pause(50000)
                            ->select('delivery_zone_id', $this->deliveryZoneTimings->delivery_zone_id)
                            ->select('delivery_zone_timings_id', $this->deliveryZoneTimings->delivery_timings_id)  
                            ;
                })
                ->press('Save & Bill at cutover')
                ->pause(30000)
                ->assertSee('Are you sure you want to save and bill this new plan?')
                ->press('Yes')     
                ->pause(5000)
                ->assertSee('Success!')
                ;
        });
    }


    public function testCancelSubscription()
    {
       
        $this->browse(function (Browser $browser) {      
            
            Log::info('Canceling for user id'. $this->customer->id. ' of subscription id='.$this->userToPausedSubscription->id);
            
            $browser->loginAs($this->admin)
                ->visit('/customers/edit/'.$this->customer->id)
                ->pause(20000)    
                
                ->assertVisible('#table-active-subs tbody tr:first-child')
                ->whenAvailable('#table-active-subs tbody tr:first-child', function($row) {
                        $row->clickLink('Cancel')
                            ;
                })
                ->pause(10000)
                ->assertVisible('#question')
                ->whenAvailable('#question', function($question) {
                    $question->assertSee('Are you sure you want to cancel this subscription?')
                            ->press('Yes');
                })
                ->pause(5000)
                ->assertSee('Success!')
                ;
        });

    }

    public function testPausedTill()
    {
        $this->browse(function (Browser $browser) {      

            Log::info('PausedTill for user id'. $this->customer->id. ' of subscription id='.$this->userToPausedSubscription->id);

            $browser->loginAs($this->admin)
                ->visit('/customers/edit/'.$this->customer->id)
                ->pause(20000)    
                ->click('@pause-'.$this->userToPausedSubscription->id)
                ->pause(20000)
                //->assertVisible('#date-wrapper')
                
                ->whenAvailable('table#table-active-subs tbody tr:first-child td.selections-control #date-wrapper', function($dateWrapper){
                    $dateWrapper->select('date', '2019-02-24')
                                //->assertVisible('select[name="date"] + .cursor-pointer')
                                ->click('.cursor-pointer .input-group-text')  
                                ;
                })

                //->click('table#table-active-subs tbody tr:first-child td.selections-control #date-wrapper .cursor-pointer .input-group-text')

                // ->assertVisible('select[name="date"] + .cursor-pointer')      
                // ->click('#date-wrapper .cursor-pointer .fa.fa-check')       
                ->pause(30000)
                ->assertSee('Are you sure you want to pause this subscription?')
                ->press('Yes')
                ->pause(5000)
                ->assertSee('Success!')
                ;
        });
        
    }


    public function testResume()
    {
        $this->browse(function (Browser $browser) {      

            Log::info('Resume for user id'. $this->customer->id. ' of subscription id='.$this->userToResumeSubscription->id);

            $browser->loginAs($this->admin)
                ->visit('/customers/edit/'.$this->customer->id)
                ->pause(20000)    
                ->click('@resume-'.$this->userToResumeSubscription->id)
                ->pause(20000)
                ->assertSee('Are you sure you want to start this subscription?')
                ->press('Yes')
                ->pause(5000)
                ->assertSee('Success!')
                ;
        });
    }


    public function testChangeSubscriptionSelectionStatus() {
        $this->browse(function (Browser $browser) {    
            
            $status = 'cancelled';
           
            $browser->loginAs($this->admin)
                ->visit('/customers/edit/'.$this->customer->id)
                ->pause(20000)    
                ->click('@selections-'.$this->userSubscription->id)
                ->pause(20000)
                ->whenAvailable('table tbody tr#weeks-subscription-106 td:nth-child(3) .input-group', function($table) use ($status){
                    $table->select('newstatus', $status);
                })                
                ->pause(30000)
                ->assertSee('Are you sure you want to change this status?')
                ->press('Yes')
                ->pause(50000)
                ;

            //checking the status if match on the test above
            // $browser->loginAs($this->admin)
            //     ->visit('/customers/edit/'.$this->customer->id)
            //     ->pause(20000)    
            //     ->click('@selections-'.$this->userSubscription->id)
            //     ->pause(20000)
            //     ->whenAvailable('table tbody tr#weeks-subscription-147 td:nth-child(2)', function($table) use ($status){
            //         $table->assertSee(ucfirst($status));
            //     })                
            //     ;
            

            $browser->loginAs($this->admin)
                ->visit('/customers/edit/'.$this->customer->id)
                ->pause(20000)    
                ->click('@selections-'.$this->userSubscription->id)
                ->pause(20000)
                ->assertMissing('table tbody tr#weeks-subscription-106')
                ;

        });
    }
   
}
