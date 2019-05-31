<?php

namespace App\Services\Cutover;

use App\Services\Cutover\Cycle\generateCycle;
use App\Services\Cutover\Cycle\RecurBilling;
use App\Services\Cutover\Cycle\Meals;
use App\Services\Cutover\Cycle\Selections;
use App\Services\Cutover\Cycle\Timing;
use App\Services\Cutover\Cycle\Configuration;
use App\Services\Cutover\Cycle\Cycle as CycleAdapter;

use App\Services\Log;
use DB;

Class Cycles
{   
    public function __construct($date = '')
    {   
        $this->date = $date;
        $this->log = new Log('cutover','logs/'.date('Y').'/'.date('m').'/system-'.date('Y-m-d').'.log');
    }

    public function handle()
    {   
        DB::beginTransaction();
        try 
        {   
            $selections = new Selections($this->date);
            $selections->handle();

            DB::commit();
        }
        catch(\Exception $e)
        {   
            DB::rollback();
            throw $e;
            $this->log->error($e->getMessage());
        }
    }

}

/*
1. Make the Customr page as the dashboard page - done
2. Billing issue

Billing attempt should be schedule on  8am and 12pm.
Status should be billing-issue not failed.
Retry would 2 times only.

3. Create a report billing issue 
with columns
Name 
Email 
Phone 
Plans 
Total Amount 
Billing Attempts No. 
Weeks Active 
Actions 

- (names) $195.00 2 4 Update Card | Bill Now | Cancel For Week Only | Cancel Customer

4. Customer admin page
- Cancelled = past subscriptions

5. Bug create an account with + sign in the Email 

6. Recheck the cutover scheduling

*/