<?php

namespace App\Repository;

use Session;
use App\Models\UserAddress;
use App\Models\UserDetails;
use App\Models\Users;
use App\Models\Subscriptions;
use App\Models\SubscriptionsSelections;
use App\Rules\Custom;

use App\Repository\ZTRepository;
use App\Repository\CycleRepository;

use DB;

Class CustomerRepository implements CustomerRepositoryInterface
{	
    public $searchEmailExist = 'The email address is already exist. Click <a href="%s">%s</a> to update';

    public $searchEmailNotExist = 'The email address does not exist. Click <a href="%s">%s</a> to create account';

    public $successSavedMessage = 'Successfully created new Customer.';

    public $successUpdatedMessage = 'Successfully updated Customer.';

    public $successDeletedMessage = "Successfully deleted Customer.";

    public $errorDeleteMessage = "Sorry could not delete Customer.";

    public $successUpdatedDZTMessage = 'Successfully updated Delivery Zone Timing.';

    public $successCreatedSubscriptionMessage = 'Successfully added new subscription.';

    const update_delivery_rules = [
        'delivery_zone_timings_id' => 'required'
    ];

    const store_subscription_rule = [
        'meal_plans_id' => 'required',
        'id'            => 'required'
    ];

    const rules_user = [
        'store' => [
            'name'   => 'required',
            'email'  => 'required|email|unique:users',
            'name'   => 'required',
            'role'   => 'required'
        ],

        'edit' => [
            'name'   => 'required',
            // 'role'   => 'required',
            'id'     => 'required'
        ],
    ];

    const rules_user_details = [
        'store' => [
            'user_id'               => 'required',
            'first_name'            => 'required',
            'last_name'             => 'required',
            'mobile_phone'          => 'required',
            'delivery_notes'        => 'required',
            'delivery_zone_timings_id' => 'required'
        ],

        'edit' => [
            'user_id'               => 'required',
            'first_name'            => 'required',
            'last_name'             => 'required',
            'mobile_phone'          => 'required',
            'delivery_notes'        => 'required',
            'delivery_zone_timings_id' => 'required'
        ],
    ];

    const rules_user_address = [
        'store' => [
            'user_id'       => 'required',
            'address1'      => 'required',
            // 'address2'      => 'required',
            'suburb'        => 'required',
            'state'         => 'required',
            // 'country'       => 'required',
            'postcode'      => 'required'

        ],

        'edit' => [
            'user_id'       => 'required',
            'address1'      => 'required',
            // 'address2'      => 'required',
            'suburb'        => 'required',
            'state'         => 'required',
            // 'country'       => 'required',
            'postcode'      => 'required'
        ],
    ];

    const primary_key = 'id';

    const user_id = 'user_id';

    const name = 'name';

    const password = 'password';

    const email = 'email';

    const verification = 'verification';

    const role = 'role';

    const active = 'active';

    const first_name = 'first_name';

    const last_name = 'last_name';

    const mobile_phone = 'mobile_phone';

    const delivery_notes = 'delivery_notes';

    const dietary_notes = 'dietary_notes';

    const delivery_zone_timings_id = 'delivery_zone_timings_id';

    const address1 = 'address1';

    const address2 = 'address2';

    const suburb = 'suburb';

    const state = 'state';

    const country = 'country';

    const postcode = 'postcode';

    const meal_plans_id = 'meal_plans_id';

    const stripe_subscription_id = 'stripe_subscription_id';

    const subscription_id = 'subscription_id';

    const status = 'status';

    const price = 'price';

    const ins_contact_id = 'ins_contact_id';
    const inactive = 'inactive';
    const billing_issue = 'billing issue';
    const paused = 'paused';
    const unpaid = 'unpaid';
    const total_discount = 'total_discount';

    private $defaultPassword = '123456';

    const billing_first_name = 'billing_first_name';
    const billing_last_name = 'billing_last_name';
    const billing_mobile_phone = 'billing_mobile_phone';
    const ins_order_id = 'ins_order_id';
    const ins_invoice_id = 'ins_invoice_id';

    public $id;
    protected $row;

    public function __construct(int $userId = null) 
    {
        $this->account = new Users;
        $this->details = new UserDetails;
        $this->address = new UserAddress;
        $this->mealsRepo = new \App\Repository\MealsRepository;
        $this->planRepo = new \App\Repository\ProductPlanRepository;
        $this->subscriptionRepo = new \App\Repository\SubscriptionRepository;
        $this->subscriptionSelectionRepo = new \App\Repository\SubscriptionSelectionsRepository;
        $this->subscriptionInvoiceRepo = new \App\Repository\SubscriptionInvoiceRepository;
        $this->subscription = new Subscriptions;
        $this->subscriptionSelections = new SubscriptionsSelections;

        $this->zoneTimingRepository = new ZTRepository;
        $this->cycleRepository = new CycleRepository;

        if (!empty($userId)) {
            $this->setRow($userId);
        }
    }

    public function getActiveSubcriptions(int $userId)
    {
        return $this->subscription
                ->select([
                    'subscriptions.id as id',
                    'subscriptions_cycles.id as subscription_cycle_id',
                    'plan_name',
                    'vegetarian',
                    'status',
                    'cycle_subscription_status',
                    'ins_invoice_id',
                    'paused_till'
                ])
                ->join('subscriptions_cycles','subscriptions_cycles.subscription_id','=','subscriptions.id')
                ->join('meal_plans','meal_plans.id','=','subscriptions.meal_plans_id')
                ->where('subscriptions_cycles.user_id', $userId)
                
                //->whereRaw('subscriptions_cycles.cycle_id in (select id from cycles where status=1)')

                //added 3-28-2019
                ->whereRaw('subscriptions_cycles.cycle_id in (select id from cycles where status=1) AND subscriptions_cycles.cycle_subscription_status != "cancelled"')
                
                ->whereNotIn('status', ['cancelled','failed'])
                ->groupBy('subscriptions.id')
                ->get();
    }

    public function getPastSubcriptions(int $userId)
    {
        return $this->subscription
                ->select([
                    'subscriptions.id as id',
                    'subscriptions_cycles.id as subscription_cycle_id',
                    'plan_name',
                    'vegetarian',
                    'status',
                    'subscriptions_cycles.cancelled_at as subscriptions_cycles_cancelled_at',
                    'subscriptions.cancelled_at as subscriptions_cancelled_at',
                    'subscriptions_cycles.cycle_subscription_status'
                ])
                ->join('subscriptions_cycles','subscriptions_cycles.subscription_id','=','subscriptions.id')
                ->join('meal_plans','meal_plans.id','=','subscriptions.meal_plans_id')
                ->where('subscriptions_cycles.user_id', $userId)
                ->where(function($query){
                    $query->orWhere('subscriptions.status', 'cancelled');
                    $query->orWhere(function($query) {
                        $query->where('subscriptions.status', 'active');
                        $query->where('subscriptions_cycles.cycle_subscription_status', 'cancelled');
                    });
                })
                ->groupBy('subscriptions.id')

                
                
                //->where('subscriptions.status', 'cancelled')
                //->where('subscriptions_cycles.cycle_subscription_status', 'cancelled')
                //->whereRaw('subscriptions_cycles.cycle_id not in (select id from cycles where status=1)')
                // ->whereIn('subscriptions_cycles.cycle_subscription_status', ['active'])
                ->get();    

           
    }

    public function all()
    {   
        return $this->details->getAllUsers();
    }

    public function AllCustomer()
    {   
        return $this->details->getAllCustomer();
    }

    public function getAll()
    {   
        return $this->details->gerSubscriptions()->get();
    }

    public function getAllByStatus($status = '')
    {   
        
        $model = DB::table('user_details')->select([
            'user_details.user_id',
            'user_details.status as user_status',
            DB::raw("concat(user_details.first_name,' ',user_details.last_name) as name"),
            'first_name',
            'last_name',
            DB::raw('
                CONCAT(
                (select zone_name from delivery_zones where delivery_zones.id = delivery_zone_timings.delivery_zone_id),
                " / Delivery: ",
                (select delivery_day from delivery_timings where delivery_timings.id = delivery_zone_timings.delivery_timings_id)) as location_timing'
            ),
            DB::raw("
                (select GROUP_CONCAT(DISTINCT plan_name) from subscriptions 
                INNER JOIN meal_plans 
                ON meal_plans.id = subscriptions.meal_plans_id
                where subscriptions.user_id=user_details.user_id
                group by subscriptions.user_id) as plan_name")
        ])
        ->join('subscriptions','subscriptions.user_id','=','user_details.user_id')
        ->join('delivery_zone_timings','delivery_zone_timings.id','=','user_details.delivery_zone_timings_id')
        ->join('users','users.id','=','user_details.user_id')
        ->orderBy('user_details.last_name','asc')
        ->groupBy('user_details.user_id');
         
        if (!empty($status) && strtolower($status) != 'all') {
            $model = $model->where('user_details.status',$status);
            return $model;
        }
        
        // $model = $model
        //     ->whereNotIn('user_details.status',
        //         ['cancelled']
        //     );

        $model = $model->orderBy('user_details.user_id','asc');
        return $model;
    }

    public function getAllByStatusForNav($status = '')
    {   
        $model = DB::table('user_details')->select([
            'user_details.user_id',
            'user_details.status as user_status',
            DB::raw("concat(user_details.first_name,' ',user_details.last_name) as name"),
            'first_name',
            'last_name',
            DB::raw('
                CONCAT(
                (select zone_name from delivery_zones where delivery_zones.id = delivery_zone_timings.delivery_zone_id),
                " / Delivery: ",
                (select delivery_day from delivery_timings where delivery_timings.id = delivery_zone_timings.delivery_timings_id)) as location_timing'
            ),
            DB::raw("
                (select GROUP_CONCAT(DISTINCT plan_name) from subscriptions 
                INNER JOIN meal_plans 
                ON meal_plans.id = subscriptions.meal_plans_id
                where subscriptions.user_id=user_details.user_id
                group by subscriptions.user_id) as plan_name")
        ])
        ->leftJoin('subscriptions','subscriptions.user_id','=','user_details.user_id')
        ->join('delivery_zone_timings','delivery_zone_timings.id','=','user_details.delivery_zone_timings_id')
        ->leftJoin('users','users.id','=','user_details.user_id')
        ->orderBy('user_details.last_name','asc')
        ->groupBy('user_details.user_id');
         
        if (!empty($status) && strtolower($status) !== 'all') {
            $model = $model->where('user_details.status',$status);
            return $model;
        }

        // $model = $model
        //     ->whereNotIn('user_details.status',
        //         ['cancelled']
        //     );

        $model = $model->orderBy('user_details.user_id','asc');
        return $model;
    }

    public function getWeeksDatesDeliveries(int $userId, int $subid, array $excludeStatus = [])
    {
        return  $this->subscriptionSelections
                    ->select([
                        'subscriptions_cycles.id as id',
                        'cycles.delivery_date',
                        'subscriptions_cycles.cycle_subscription_status',
                        'subscriptions_cycles.user_id',
                        'subscriptions_cycles.ins_invoice_id',
                        DB::raw('(select id from subscriptions_cycles as c2 where c2.user_id = subscriptions_cycles.user_id and c2.id < subscriptions_cycles.id order by c2.id desc limit 1) as prev_id')
                    ])
                    ->join('cycles','cycles.id','=','subscriptions_cycles.cycle_id')
                    ->where(self::user_id, $userId)
                    // ->whereIn('cycle_subscription_status',array_diff(['paid','old week','pending','unpaid','paused'], $excludeStatus))
                    ->where('subscriptions_cycles.subscription_id', $subid)
                    ->orderBy('subscriptions_cycles.id','desc')
                        ->get();
    }

    public function getPastWeeksDatesDeliveries(int $userId, int $subcscriptionId, array $excludeStatus = [])
    {
        return  $this->subscriptionSelections
                    ->select([
                        'subscriptions_cycles.id as id',
                        'cycles.delivery_date',
                        'subscriptions_cycles.cycle_subscription_status',
                        'subscriptions_cycles.user_id',
                        'subscriptions_cycles.ins_invoice_id',
                        DB::raw('(select id from subscriptions_cycles as c2 where c2.user_id = subscriptions_cycles.user_id and c2.id < subscriptions_cycles.id order by c2.id desc limit 1) as prev_id')
                    ])
                    ->join('cycles','cycles.id','=','subscriptions_cycles.cycle_id')
                    //->join('cycles','cycles.id','=','subscriptions_cycles.cycle_id')
                    ->where(self::user_id, $userId)
                    //->whereIn('cycle_subscription_status',['cancelled'])
                    //->whereIn('subscriptions.status',['cancelled'])
                    //->where('subscriptions_cycles.id', $subcscriptionCycleId)
                    // ->whereIn('cycle_subscription_status',array_diff(['paid','old week','pending','unpaid','paused', 'cancelled'], $excludeStatus))
                    ->where('subscriptions_cycles.subscription_id', $subcscriptionId)
                    ->orderBy('subscriptions_cycles.id','desc')
                        ->get();
    }

    public function getMenusWeekDeliveries(int $id, bool $getMealIdOnly = false): array
    {   
        foreach($this->mealsRepo->getAllWithTrash() as $row) {
            if ($getMealIdOnly) {
                $meals[$row->id] = $row->id;
            } else {
                $meals[$row->id] = $row->meal_name;
            }            
        }

        $subscriptionsSelections = $this->subscriptionSelections->where(self::primary_key, $id)->first();
        $subscription = $this->subscription->find($subscriptionsSelections->subscription_id);
        $plan = $this->planRepo->get($subscription->meal_plans_id);
        $selections = json_decode($subscriptionsSelections->menu_selections);

        $isDinnerOnly = ($plan->no_meals/$plan->no_days) == 1;

        $data['lunch'] = [];
        $data['dinner'] = [];

        if ($isDinnerOnly) {
             foreach($selections as $menu) {
                if (isset($meals[$menu])) {
                    $data['dinner'][] = $meals[$menu];
                }
            }
        }   
        else {
            $selections_ = $selections;
            $lunch = array_splice($selections, 0, $plan->no_days);
            $dinner = array_splice($selections_, $plan->no_days, $plan->no_meals);

            foreach($dinner as $menu) {
                if (isset($meals[$menu])) {
                    $data['dinner'][] = $meals[$menu];
                }
            }

            foreach($lunch as $menu) {
                if (isset($meals[$menu])) {
                    $data['lunch'][] = $meals[$menu];
                }
            }
        }
       
        return $data;
    }
    
    public function getInvoices(int $userId, int $subscriptionId)
    {   
        return 
        $this->subscription
        ->select([
            'price',
            // 'quantity', this has been removed not needed
            'ins_invoice_id',
            'created_at'
        ])
        ->where(self::user_id, $userId)
        ->where(self::primary_key, $subscriptionId)
            ->get();
    }

    public function updateDeliveryRules(): array
    {
        return self::update_delivery_rules;
    }

    public function storeSubscriptionRule(): array
    {
        return self::store_subscription_rule;
    }

    public function storeRules(): array
    {
        $rules = self::rules_user['store'];

        /*$rules['customer'] = ['required', new Custom( function($attribute, $value) {

            list($first_name, $last_name) = $value;

            if($this->details->where([
                    self::first_name => $first_name,
                    self::last_name => $last_name
                ])
                ->count() > 0) {
                return false;
            }
            return true;
        })];*/

        
        return $rules;
    }

    public function updateRules(): array
    {
        $rules = self::rules_user['edit'];

        /*$rules['customer'] = ['required', new Custom( function($attribute, $value) {

            list($first_name, $last_name, $id) = $value;

            if($this->details->where([
                    self::first_name => $first_name,
                    self::last_name => $last_name
                ])
                ->where(self::user_id, '<>', $id)
                ->count() > 0) {
                return false;
            }
            return true;
        })];*/

        
        return $rules;
    }

    public function getId()
    {
        return $this->id;
    }

    public function storeAddress(int $userId, array $data)
    {
        return
        $this->address->create([
            self::user_id       => $userId,
            self::address1      => $data['address1'],
            self::address2      => $data['address2'],
            self::suburb        => $data['suburb'],
            self::state         => $data['state'],
            self::country       => $data['country'],
            self::postcode      => $data['postcode']
        ]);
    }

    public function storeDetails(int $userId, array $data)
    {
        $user = [
            self::user_id           => $userId,
            self::first_name        => $data['first_name'],
            self::last_name         => $data['last_name'],
            self::mobile_phone      => $data['mobile_phone'],
            self::delivery_notes    => $data['delivery_notes'],
            self::dietary_notes     => $data['dietary_notes'],
            self::delivery_zone_timings_id  => $data['delivery_zone_timings_id'] ?? 1,
            self::billing_first_name        => $data['first_name'],
            self::billing_last_name         => $data['last_name'],
            self::billing_mobile_phone      => $data['mobile_phone']
        ];

        if (isset($data[self::status])) {
            $user[self::status] = $data['status'];
        }

        $model = $this->details->create($user);

        return (array) $this->MODEL = $model;
    }

     public function updateAddress(int $userId, array $data)
    {
        return
        $this->address->where(self::user_id, $userId)
        ->update([
            self::address1      => $data['address1'],
            self::address2      => $data['address2'],
            self::suburb        => $data['suburb'],
            self::state         => $data['state'],
            self::country       => $data['country'],
            self::postcode      => $data['postcode']
        ]);
    }

    public function updateDetails(int $userId, array $data)
    {
        $model = $this->details->find($this->details->getDetailsIdByUser($userId));

        $model->first_name = $data['first_name'];
        $model->last_name = $data['last_name'];
        $model->mobile_phone = $data['mobile_phone'];
        $model->billing_first_name = $data['first_name'];
        $model->billing_last_name = $data['last_name'];
        $model->billing_mobile_phone = $data['mobile_phone'];
        if (isset($data['delivery_notes'])) {
            $model->delivery_notes = $data['delivery_notes'];
        }
        if (isset($data['dietary_notes'])) {
            $model->dietary_notes = $data['dietary_notes'];
        }
        if (isset($data['delivery_zone_timings_id'])) {
            $model->delivery_zone_timings_id = $data['delivery_zone_timings_id'];
        }
        if (isset($data['status'])) {
            $model->status = $data['status'];
        }
        $model->save();

        return $this->DETAILS_MODEL = $model;

    }

    public function store(array $data): array
    {   
        $user =  [
            self::name      => $data['name'],
            self::email     => $data['email'],
            self::password  => bcrypt($this->defaultPassword),
            self::verification  => $data['name'],
            self::role      => 'customer',
            self::active    => $data['active'],
        ];

        if (isset($data[self::password])) {
            $user[self::password] = bcrypt($data[self::password]);
        }
        
        $model = $this->account->create($user);

        $this->setId($model->id);

        return (array) $this->ACCOUNT_MODEL = $model;
    }

    public function storeSubscription(array $data)
    {          
        $this->subscriptionRepo->store($data);
        return $this->subscriptionRepo->model->id;
    }

    public function storeSubscriptionSelections(array $data)
    {          
        $this->subscriptionSelectionRepo->store($data);
        return $this->subscriptionSelectionRepo->model->id;
    }

    public function storeInvoice(array $data): array
    {          
        $data = (object)$this->subscriptionInvoiceRepo->store($data);
        $this->setId($data->id);

        return (array)$data;
    }

    public function update(array $data): array
    {      
        $this->setId($data['id']);

        return
        (array)$this->account->where(self::primary_key, $data['id'])
        ->update([
            self::name      => $data['name']
        ]);
    }

    public function updateINSContactID(int $userId, int $insContactID): array
    {      
        return
        (array)$this->details->where(self::user_id, $userId)
        ->update([
            self::ins_contact_id      => $insContactID
        ]);
    }

    public function updateDelivery(array $data)
    {      
        $this->setId($data['id']);

        
        $out = (array)$this->details->where(self::user_id, $data['id'])
        ->update([
            self::delivery_zone_timings_id      => $data['delivery_zone_timings_id']
        ]);

        $deliveryTimingId = $this->zoneTimingRepository->getTimingsIdById(
            $data['delivery_zone_timings_id']
        );

        $deliveryZoneId = $this->zoneTimingRepository->getDeliveryZoneIdById(
            $data['delivery_zone_timings_id']
        );
        
        $cycleId = $this->cycleRepository->getActiveByTimingId($deliveryTimingId);
        $cycleId = $cycleId->id ?? 0;

        if (empty($cycleId)) {
            throw new \App\Exceptions\UpdateDeliveryNoSubscription(__("Could not update current subscription week. Unknown Cycle or No Subscriptions Found."), 1);
        }

        $this->subscriptionSelectionRepo->updateCurrentSubscriptionWeekCycleId(
            $data['id'], 
            $cycleId,
            $deliveryZoneId
        );
    }

    

    public function updateAddress1(int $userId, array $data)
    {
        return
        $this->address->where(self::user_id, $userId)
        ->update([
            self::address1      => $data['address1'],
            self::address2      => $data['address2'],
            self::suburb        => $data['suburb'],
            self::state         => $data['state'],
            self::country       => $data['country'],
            self::postcode      => $data['postcode']
        ]);
    }

    public function updateDetails1(int $userId, array $data)
    {
        return
        $this->details->where(self::user_id, $userId)
        ->update([
            self::first_name        => $data['first_name'],
            self::last_name         => $data['last_name'],
            self::mobile_phone      => $data['mobile_phone'],
            self::delivery_notes    => $data['delivery_notes'],
            self::dietary_notes     => $data['dietary_notes'],
            self::billing_first_name => $data['first_name'],
            self::billing_last_name => $data['last_name'],
            self::billing_mobile_phone => $data['mobile_phone'],
            self::delivery_zone_timings_id  => $data['delivery_zone_timings_id'],
        ]);
    }

    public function updateCards(int $userId, $cardId, $last4)
    {
        $details = $this->details->where(self::user_id, $userId)->first();
        $details = !empty($details->card_ids) ? json_decode($details->card_ids) : [];
        $default = !empty($details->default_card) ? $details->default_card : '';

        array_push($details, array('id' => $cardId, 'last4' => $last4));

        $this->details->where(self::user_id, $userId)
            ->update([
                'card_ids' => json_encode($details)
            ]);

        if (empty($default)) {
            $this->details->where(self::user_id, $userId)
                ->update([
                    'default_card' => $cardId
                ]);
        }
    }

    public function getIdByEmail(string $email): int
    {
        return $this->account
                ->where(['email' => $email])
                ->select('id')
                    ->first()->id ?? 0;
    }
    

    public function findEmail(string $email): bool
    {
        return $this->account->where(['email' => $email])->count() > 0;
    }

    public function defaultCard(int $id): string
    {
        $d = $this->details->where([self::user_id => $id])->first();
        return isset($d->default_card) ? $d->default_card : '';
    }

    public function verify(string $value): string
    {
        return $this->account->where('email',$value)->count() > 0;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setRow($userId)
    {
        $this->row = $this->profile($userId);
    }

    public function profile(int $id)
    {
        return $this->details->where(self::user_id, $id)->first();
    }

    public function getFirstName()
    {
        return $this->row->first_name;
    }

    public function address(int $id)
    {
        return $this->address->where(self::user_id, $id)->first();
    }

    public function account(int $id)
    {
        return $this->account->where(self::primary_key, $id)->first();
    }

    public function getINSContactId(int $id): int
    {
        $ins_contact_id = $this->details->where(self::user_id, $id)->first();
        return isset($ins_contact_id->ins_contact_id) ? $ins_contact_id->ins_contact_id : 0;
    }

    public function getINSContactIdByEmail(string $email): int
    {
        $account = $this->account->where(self::email, $email)->first();
        $id = $account->id ?? 0;
        return $this->getINSContactId($id);
    }

    public function getCardId(int $id): int
    {
        $card_ids = $this->details->where(self::user_id, $id)->first();
        return isset($card_ids->card_ids) ? json_decode($card_ids->card_ids)[0] : 0;
    }

    public function verifyCardId(int $userId, int $cardId): int
    {
        $card_ids = $this->details->where(self::user_id, $userId)->first();
        $card_ids = isset($card_ids->card_ids) ? json_decode($card_ids->card_ids) : [];
        return empty($card_ids) ? false : (in_array($cardId, $card_ids));
    }

    public function getEmailByUserId(int $userId): string
    {
        return $this->account->email($userId);
    }

    public function zoneTiming(int $id)
    {
        return $this->details->where(self::user_id, $id)
                    ->select([
                    'zone_name',
                    'delivery_day',
                    'cutoff_day',
                    'cutoff_time',
                ])
                ->leftJoin('delivery_zone_timings','delivery_zone_timings.id','=','user_details.delivery_zone_timings_id')
                ->leftJoin('delivery_timings','delivery_timings.id','=','delivery_zone_timings.delivery_timings_id')
                ->leftJoin('delivery_zones','delivery_zones.id','=','delivery_zone_timings.delivery_zone_id')
                ->first();
    }

    public function getAccount(int $id)
    {
        $details = $this->details->select([
            'billing_first_name',
           'billing_last_name',
           'billing_mobile_phone',
           'first_name',
           'last_name',
           'mobile_phone',
           'delivery_notes',
           'dietary_notes',
           'delivery_zone_timings_id',
            DB::raw('(select delivery_zone_id from delivery_zone_timings where id=delivery_zone_timings_id) as delivery_zone_id'),
            DB::raw('(select delivery_timings_id from delivery_zone_timings where id=delivery_zone_timings_id) as delivery_timings_id')
        ])->where(self::user_id, $id)->first();

        $address = $this->address->select([
            'address1','address2','suburb','state','country','postcode'
        ])->where(self::user_id, $id)->first();

        $account = $this->account->select(['name','email'])->where(self::primary_key, $id)->first();

        return [
            'address' => $address,
            'account' => $account,
            'details' => $details
        ];
    }

    public function getDeliveryTimingsId(int $id)
    {
        $data = $this->details->select([
            DB::raw('(select delivery_zone_id from delivery_zone_timings where id=delivery_zone_timings_id) as delivery_zone_id'),
            DB::raw('(select delivery_timings_id from delivery_zone_timings where id=delivery_zone_timings_id) as delivery_timings_id')
        ])->where(self::user_id, $id)->first();

    
        return [
            'delivery_zone_id' => $data->delivery_zone_id ?? 0,
            'delivery_timings_id' => $data->delivery_timings_id ?? 0
        ];
    }

    public function hasDetails(int $id) {
        return $this->details->where(self::user_id,$id)->limit(1)->count() > 0;
    }

    public function hasAddres($id) {
        return $this->address->where(self::user_id,$id)->limit(1)->count() > 0;
    }

    public function getActiveCycleByDeliveryZoneTimings(int $userId)
    {   
        return DB::table('user_details')
        ->join('delivery_zone_timings',
            'delivery_zone_timings.id','=','user_details.delivery_zone_timings_id'
        )
        ->join('delivery_zones',
            'delivery_zones.id','=','delivery_zone_timings.delivery_zone_id'
        )
        ->join('cycles',
            'cycles.delivery_timings_id','=','delivery_zone_timings.delivery_timings_id'
        )
        ->where('user_details.user_id',$userId)
        ->where('cycles.status',1)
        ->first();

    }

    public function updateDeliveryZoneTimingId(int $userId, int $deliveryZoneTimingId)
    {
        $model = $this->details->find($this->details->getDetailsIdByUser($userId));

        $model->delivery_zone_timings_id = $deliveryZoneTimingId;
        
        $model->save();
    }

    public function getUnpaidSubscriptions(int $userId)
    {
        return  $this->subscriptionSelections
        ->select([
            'meal_plans_id', 'plan_name',
            'delivery_date', 'subscriptions_cycles.id as subscriptions_cycle_id',
            'subscription_id','cycle_subscription_status'
        ])
        ->join('cycles','cycles.id','=','subscriptions_cycles.cycle_id')
        ->join('subscriptions','subscriptions.id','=','subscriptions_cycles.subscription_id')
        ->join('meal_plans','meal_plans.id','subscriptions.meal_plans_id')
        ->where('subscriptions.user_id', $userId)
        ->where('subscriptions.status', self::billing_issue)
        ->where('subscriptions_cycles.cycle_subscription_status', self::unpaid)
        ->groupBy('subscriptions_cycles.subscription_id')
            ->get();
    }

    public function getForDeliverySubscriptions(int $userId)
    {   
        $cycles = array();
        foreach(DB::table('delivery_timings')->get() as $row) {
            $cycle = DB::table('cycles')
            ->where('status','-1')
            ->where('delivery_timings_id',$row->id)
            ->orderBy('id','desc')
            ->first();

            array_push($cycles, $cycle->id ?? 0);
        }
        

        return  $this->subscriptionSelections
        ->select([
            'meal_plans_id', 'plan_name',
             'delivery_date', 'subscriptions_cycles.id as subscriptions_cycle_id',
             'subscription_id',
             'subscriptions_cycles.cycle_subscription_status'
        ])
        ->join('cycles','cycles.id','=','subscriptions_cycles.cycle_id')
        ->join('subscriptions','subscriptions.id','=','subscriptions_cycles.subscription_id')
        ->join('meal_plans','meal_plans.id','subscriptions.meal_plans_id')
        ->where('subscriptions.user_id', $userId)
        ->where('subscriptions_cycles.cycle_subscription_status', 'paid')
        ->whereIn('subscriptions_cycles.cycle_id', $cycles)
            ->get();
    }

    public function getUsersForDeliverySubscriptionsByTiming(int $previousCycleId)
    {   
        return  $this->subscriptionSelections
        ->select([
            'subscriptions_cycles.user_id',
            'ins_contact_id'
        ])
        ->join('cycles','cycles.id','=','subscriptions_cycles.cycle_id')
        ->join('user_details','user_details.user_id','=','subscriptions_cycles.user_id')
        ->whereIn('subscriptions_cycles.cycle_subscription_status', [
            'paid', 'unpaid'
        ])
        ->groupBy('subscriptions_cycles.user_id')
        ->where('cycles.id', $previousCycleId)
            ->get();
    }

    public function getForDeliverySubscriptionWithStatus(int $userId)
    {   
        $cycles = array();
        foreach(DB::table('delivery_timings')->get() as $row) {
            $cycle = DB::table('cycles')
            ->where('status','-1')
            ->where('delivery_timings_id',$row->id)
            ->orderBy('id','desc')
            ->first();

            array_push($cycles, $cycle->id ?? 0);
        }
        

        return  $this->subscriptionSelections
        ->select([
             'delivery_date', 'subscriptions_cycles.id as subscriptions_cycle_id',
             'subscription_id',
             'subscriptions_cycles.cycle_subscription_status'
        ])
        ->join('cycles','cycles.id','=','subscriptions_cycles.cycle_id')
        ->join('subscriptions','subscriptions.id','=','subscriptions_cycles.subscription_id')
        ->where('subscriptions.user_id', $userId)
        ->whereIn('subscriptions_cycles.cycle_id', $cycles)
            ->get();
    }
    
}
