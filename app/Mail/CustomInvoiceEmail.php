<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Users;
use App\Models\UserDetails;
use App\Models\Subscriptions;
use App\Models\SubscriptionsSelections;

use Log;

class CustomInvoiceEmail extends Mailable
{
    use Queueable, SerializesModels;

     public $model;

      /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Subscriptions $model)
    {
        $this->model = $model;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        $plan = (new \App\Repository\ProductPlanRepository)->get($this->model->meal_plans_id);

        $selections = (new \App\Repository\SubscriptionSelectionsRepository)->get($this->model->id);

        $mealsSelected = [];
        foreach($selections as $key => $row)
        {   
            $mealsSelected = json_decode($row->menu_selections);
            $mealsSelectedLunch = $mealsSelected;
            $mealsSelectedDinner = $mealsSelected;
            $meals = (new \App\Repository\MealsRepository)->getMealsByIds($mealsSelected);
            
            $cut = ($plan->no_meals/$plan->no_days);

            if ($cut > 1) 
            {
                $lunch = array_splice($mealsSelectedLunch, 0, $plan->no_days);
                $dinner = array_splice($mealsSelectedDinner, $plan->no_days, $plan->no_meals);
            }
            else 
            {
                $lunch = [];
                $dinner = $mealsSelectedDinner;            
            }
        }
      
        $first_name = (new \App\Repository\CustomerRepository)->profile($this->model->user_id)->first_name;
        
        return $this->view('emails.customer-invoice')->with([
            'name'  => $first_name,
            'plan' => $plan->plan_name,
            'lunch' => $lunch,
            'dinner' => $dinner,
            'meals' => $meals
        ]);
    }
}