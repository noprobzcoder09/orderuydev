<?php

namespace App\Services;

use App\Services\Validator;
use App\Mail\UserAdminRegistrationEmail;
use App\Services\InfusionsoftV2\InfusionsoftFactory;
use App\Services\Customers\Account\InfusionsoftCustomer;
use App\Repository\ZTRepository;
use App\Services\Card\ContactManager;
use DataTables;
use Yajra\DataTables\QueryDataTable;

use Request;
use DB;
use Mail;
use Log;
use App\Traits\Auditable;
use App\Models\Users;

Class Customer
{   
    use Auditable;

    public function __construct(\App\Repository\CustomerRepositoryInterface $repository)
    {
        $this->repository = new $repository;
        $this->validator = new Validator;
        $this->ztRepository = new \App\Repository\ZTRepository;
        $this->planRepository = new \App\Repository\ProductPlanRepository;
        $this->usersRepository = new \App\Repository\UsersRepository;
        $this->cycleRepository = new \App\Repository\CycleRepository;
        $this->subscriptionRepo = new \App\Repository\SubscriptionRepository;
        $this->subscriptionSelectionRepo = new \App\Repository\SubscriptionSelectionsRepository;
        $this->invoiceRepository = new \App\Repository\SubscriptionInvoiceRepository;
        $this->mealRepository = new \App\Repository\MealsRepository;
    }

    public function getAllByStatus($status, $type = 'first name', $filter = '') {
        $model = $this->repository->getAllByStatus($status);
        /*
        $type = strtolower($type);
        switch($type) {
            case 'last name':
                $model->where('last_name','like','%'.$filter.'%');
                break;
            case 'first name':
                $model->where('first_name','like','%'.$filter.'%');
                break;
            case 'phone':
                $model->where('mobile_phone',$filter);
                break;
            case 'email':
                $model->where('email',$filter);
                break;
            case 'infusionsoft id':
                $model->where('ins_contact_id',$filter);
                break;
            case 'database id':
                $model->where('user_details.user_id',$filter);
                break;
            default:
                break;
        }*/
        // $model->where('last_name','like','%'.$filter.'%');

        if (!empty($filter))
        {
            $model->whereRaw("
                (
                    last_name like '%{$filter}%'
                    or first_name like '%{$filter}%'
                    or mobile_phone='{$filter}'
                    or email='{$filter}'
                    or ins_contact_id='{$filter}'
                    or user_details.user_id='{$filter}'
                )
            ");    
        }
        
        return DataTables::of($model->get())->make(true);

    }

    public function getSearchCustomerViaNavigation($filter = '') {
        $model = $this->repository->getAllByStatusForNav('all');
        $model->addSelect('email');
        if (!empty($filter))
        {
            $model->whereRaw("
                (
                    last_name like '%{$filter}%'
                    or first_name like '%{$filter}%'
                    or mobile_phone='{$filter}'
                    or email='{$filter}'
                    or ins_contact_id='{$filter}'
                    or user_details.user_id='{$filter}'
                )
            ");    
        }

        $data = array();
        foreach($model->get() as $row) {
            array_push($data, array(
                'name' => $row->name.' - '.$row->email,
                'email' => $row->email,
                'link' => url('customers/edit/'.$row->user_id)
            ));
        }

        return $data;

    }

    public function getActiveSubcriptions(int $userId): array {
        $data = [];

        //dd($this->repository->getActiveSubcriptions($userId));

        foreach($this->repository->getActiveSubcriptions($userId) as $row) {
            $status = strtolower($row->status);
            $subscriptionCycleStatus = strtolower($row->cycle_subscription_status);
            $subscriptionInsInvoiceId = $row->ins_invoice_id;
            $data[] = [
                'id' => $row->id,
                'SubID' => $row->id,
                'Status' => ucfirst($row->cycle_subscription_status). (strtolower($status) == 'paused' ? '<br><small><i>Until '.date('F d, Y', strtotime($row->paused_till)).'</i></small>' : ''),
                'Product' => $row->plan_name,
                'Manage' => '
                    <div class="row '.$subscriptionCycleStatus.'">
                        <div class="col-sm-12">
                            <div class="btn-group m-btn-group m-btn-group--pill m-btn-group--air" role="group" aria-label="...">
                                '. ($status != 'billing issue' &&  $subscriptionCycleStatus != 'paid'   ?
                                '<a class="m-btn btn btn-danger" href="javascript:;" dusk="cancel-'.$row->id.'" onclick="Customer.cancel(this,'.$userId.','.$row->id.','.$row->subscription_cycle_id.')">
                                  <i class="icon-power"></i> Cancel
                                </a>   
                                '.(   $status == 'paused' ?
                                '<a class="m-btn btn btn-warning" href="javascript:;" dusk="resume-'.$row->id.'" onclick="Customer.play(this,'.$userId.','.$row->subscription_cycle_id.')">
                                  <i class="icon-control-play"></i> Resume
                                </a>'
                                : '<a class="m-btn btn btn-pause-date btn-warning" dusk="pause-'.$row->id.'" href="javascript:;" onclick="Customer.inputPause(this,'.$userId.','.$row->subscription_cycle_id.')">
                                  <i class="icon-control-pause"></i> Pause
                                </a>')
                                : '').'
                                <a data-subcycleid="'.$row->subscription_cycle_id.'" data-subid="'.$row->id.'" dusk="selections-'.$row->id.'" class="m-btn btn btn-success selection-control" href="javascript:;">
                                    <i class="fa fa-plus"></i> Selections
                                </a> 
                                <div class="btn-group">
                                    <button class="btn btn-radius-none btn-secondary dropdown-toggle" type="button" dusk="groupbuttonadvance-'.$row->id.'" id="groupbuttonadvance-'.$row->id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Advance
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="groupbuttonadvance-'.$row->id.'">
                                        <a data-subcycleid="'.$row->subscription_cycle_id.'" data-subid="'.$row->id.'" dusk="prevmenuweek-'.$row->id.'" class="m-btn dropdown-item" onclick="PreviousWeekMenu.addMenuPrevWeekModal(this,'.$userId.','.$row->id.','.$row->subscription_cycle_id.')" href="javascript:;">
                                          <i class="fa fa-plus"></i> Add Menu for Previous Week
                                        </a> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                '
            ];
        }

        return $data;
    }

    public function getPastSubcriptions(int $userId): array {
        $data = [];

        foreach($this->repository->getPastSubcriptions($userId) as $row) {

            $cancelled_at = ($row->subscriptions_cycles_cancelled_at !== '0000-00-00 00:00:00') ?  $row->subscriptions_cycles_cancelled_at : $row->subscriptions_cancelled_at;

            $data[] = [
                'id' => $row->id,
                'Product' => $row->plan_name,
                'SubID' => $row->id,
                'Date' => date('Y-m-d',strtotime($cancelled_at)),
                'Status' => $row->cycle_subscription_status,
                'Manage' => '
                    <a data-subid="'.$row->id.'" data-subcycleid="'.$row->subscription_cycle_id.'" class="mr-5 btn pull-right btn-success btn-sm past-selection-control" href="javascript:;">
                      <i class="fa fa-plus"></i> Selections
                    </a>
                '
            ];
        }

        return $data;
    }
    

    public function weeksSubcriptions(string $view, int $userId, int $subid, array $excludeStatus = []): string {
        return view($view.'customer.weeks-table', ['data' => $this->getWeeksSubcriptions(
            $userId,
            $this->repository->getWeeksDatesDeliveries($userId, $subid, $excludeStatus)
        )]);
    }

    public function pastWeeksSubcriptions(string $view, int $userId, int $subscriptionId, array $excludeStatus = []): string {
        return view($view.'customer.past-weeks-table', ['data' => $this->getWeeksSubcriptions(
            $userId,
            $this->repository->getPastWeeksDatesDeliveries($userId, $subscriptionId, $excludeStatus)
        )]);
    }

    public function getWeeksSubcriptions(int $userId, $weeks): array {
        $data = [];
        foreach($weeks as $row)
        {
            $status = $row->cycle_subscription_status;
            if ($status == 'old week') {
                $status = 'paid';
            }
            elseif ($status == 'unpaid') {
                $status = 'billing Issue';
            }
            $data[] = (object)[
                'id' => $row->id,
                'prev_id' => $row->prev_id,
                'user_id' => $row->user_id,
                'ins_invoice_id' => $row->ins_invoice_id,
                'week' => date('F d, Y', strtotime($row->delivery_date)),
                'status' => ucfirst($status)
            ];
        }
        return $data;
    }
    
    private function getInvoices(int $userId)
    {   
        $data = [];
        $plans = [];
        $appLink  = \Helper::getAppLink().'?Job/manageJob.jsp?view=edit&ID=';
        
        //$invoices = $this->invoiceRepository->getInvoiceOnlyByUserId($userId);
        $invoices = $this->invoiceRepository->getInvoiceByUserId($userId);
        
       
        $i = 0;
        $ivoice_data = array();
        foreach($invoices as $row)
        {   
            $deliveryDate = new \DateTime($row->delivery_date);
            $nowDate = new \DateTime(date('Y-m-d'));
            $orderId = "'".$row->ins_order_id."'";
            $status = strtolower($row->status);
            $status = in_array($status,['pending','unpaid']) ? 'unpaid' : 'paid';

            if ($deliveryDate == $nowDate) {
                $delivery = 'For Delivery Today ';
            }
            elseif ($deliveryDate > $nowDate) {
                $delivery = 'For Delivery On ';
            }
            else {
                $delivery = 'Delivered On ';
            }

            $data[$row->ins_invoice_id] = [
                'subId' => $row->subscription_id,
                'status' => ucfirst($status),
                'cycle' => $delivery. date('F d, Y', strtotime($row->delivery_date)),
                'items' => $row->plan_name,
                'invoiceId' => $row->ins_invoice_id,
                'orderId' => $row->ins_order_id,
                'date' => date('F d, Y', strtotime($row->created_at)),
                'download' => 
                    ($status == 'unpaid' ? 
                        '<a class="btn pull-right btn-danger btn-sm margin-bottom-10 hide" href="javascript:;" data-table="past" onclick="Customer.chargeNow('.$row->subscriptions_cycle_id.')">
                      <i class="fa fa-eye"></i> Charge Now
                    </a>' : '').
                    '<a class="btn pull-right btn-teal btn-sm invoice-control" href="javascript:;" data-table="past" onclick="Customer.invoiceDownload('.$orderId.')">
                      <i class="fa fa-eye"></i> Invoice
                    </a>'
            ];
        }
        $data = array_values($data);
        return array_map(function($data) { return (object) $data; }, $data);
    }

    public function getMenuWeekSubcriptions(string $view, int $id): string {

        $hasDefaultCycleMeals = $this->cycleRepository->hasDefaultCycleSelections(
            (int) $this->subscriptionSelectionRepo->getCycleIdById($id)
        );

        return view($view.'customer.menu-table', ['data' => $this->repository->getMenusWeekDeliveries($id), 'subscriptions_cycle_id' => $id, 'has_default_cycle_meals' => $hasDefaultCycleMeals]);
    }

    public function getPastMenuWeekSubcriptions(string $view, int $id): string {

        $hasDefaultCycleMeals = $this->cycleRepository->hasDefaultCycleSelections(
                                    (int) $this->subscriptionSelectionRepo->getCycleIdById($id)
                                );

        return view($view.'customer.menu-table', ['data' => $this->repository->getMenusWeekDeliveries($id), 'subscriptions_cycle_id' => $id, 'has_default_cycle_meals' => $hasDefaultCycleMeals]);
    }

    public function loadPreviousMenuSelections(string $view, int $subscription_cycle_id) {

        $cycleId = $this->subscriptionSelectionRepo->getCycleIdById($subscription_cycle_id);
        $subscriptionId = $this->subscriptionSelectionRepo->getSubscriptionIdById($subscription_cycle_id);
        $noOfDays = $this->planRepository->get(
                        (int) $this->subscriptionRepo->getMealsPlanIdById($subscriptionId)
                    )->no_days;

        //cycle default vego and non-vego selections
        $defaultCycleNonVegoSelections = $this->cycleRepository->getDefaultSelections((int) $cycleId);
        $defaultCycleVegoSelections = $this->cycleRepository->getVegDefaultSelections((int) $cycleId);
        
        //merge default selections
        $defaultCycleSelections =  array_merge(json_decode($defaultCycleNonVegoSelections), json_decode($defaultCycleVegoSelections));
        $defaultCycleMeals = $this->mealRepository->getByArray($defaultCycleSelections);

        // if (!empty($defaultCycleSelections)) {
        //     //meals from default selections id's
        //     $defaultCycleMeals = $this->mealRepository->getByArray($defaultCycleSelections);
        // } else {
        //     //pull from active meals
        //     $defaultCycleMeals = $this->mealRepository->getActive();
        // }

        return view($view.'customer.previous-menu-table', [
                            'user_menu_selections' => $this->repository->getMenusWeekDeliveries($subscription_cycle_id, true), 
                            'default_meals' => $defaultCycleMeals, 
                            'no_of_days' => $noOfDays, 
                            'subscription_cycle_id' => $subscription_cycle_id
                        ]); 
    }

    public function updatePreviouseMenuSelections(int $subscriptionId, array $menus) {
        
        $subscriptionCyclesSelections = $this->subscriptionSelectionRepo->updateSelectionsInArrayById($subscriptionId, json_encode($menus));
        
        if (!empty($subscriptionCyclesSelections)) {
            $response = ['result' => true, 'message' => "You've successfully updated the menu selections."];
        } else {
            $response = ['result' => false, 'message' => 'Error on updating previous menu selections.'];
        }
        
        return response()->json($response);
        
    }

    public function getInvoiceMenuSubscriptions(string $view, int $id): string {

        return view($view.'customer.menu-table', ['data' => $this->repository->getMenusWeekDeliveries($id)]);
    }
    
    public function getInvoicesSubcriptions(int $userId) {
        return $this->getInvoices($userId);
    }

    public function findEmail(string $email): bool {
        return $this->repository->findEmail($email);
    }

    public function getIdByEmail(string $email): int {
        return $this->repository->getIdByEmail($email);
    }

    public function getEmailSearchResult(string $email): array {
        $email = str_replace(['+'], '%2B', $email);
        $url = url('customers/new?email='.$email);
        if ($this->repository->findEmail($email)) {
            $url = url('customers/edit/'. $this->repository->getIdByEmail($email));
        }
        return [
            'status' => 200,
            'exist' => true,
            'url' => $url
        ];
    }

    public function verify(string $value): string
    {   
        if ($this->repository->verify($value)) {
            return 'false';
        }
        return 'true';
    }

    public function store(array $data): array
    {     
        DB::beginTransaction();
        try
        {   
            $data['role'] = 'customer';
            $data['active'] = '1';
            $data['delivery_notes'] = '';
            $data['dietary_notes'] = '';
            $data['suburb'] = '1';

            $data['name'] = Request::get('first_name').' '.Request::get('last_name');
            $data['customer'] = [Request::get('first_name'), Request::get('last_name')];

            $this->validator->validate($data, $this->repository->storeRules());

            $response['status'] = 200;

            $response['success'] = $this->validator->isValid;

            if ($this->validator->isValid)  
            {
                // Checking infusionsoft records
                // Apply contact id if found exist
                $api = (new InfusionsoftFactory('oauth2'))->service();

                $infusionsoftData = $api->fetchContactByEmail($data['email'], array('Id'));
                $infusionsoftContactId = $infusionsoftData[0]['Id'] ?? 0;

                $this->repository->store($data);

                if (method_exists($this->repository, 'getId')) {
                    $response['id'] = $this->repository->getId();
                }

                if (!empty($response['id'])) 
                {
                    $this->repository->storeDetails($response['id'], $data);
                    $this->repository->storeAddress($response['id'], $data);
                    $response['message'] = $this->repository->successSavedMessage;

                    $infusionsoftContactData = array(
                        "Email" => Request::get('email'),
                        "FirstName" => Request::get('first_name'),
                        "LastName" => Request::get('last_name'),
                        "Phone1" => Request::get('mobile_phone'),
                        "State" => Request::get('state_desc'),
                        "Country" => Request::get('country'),
                        "City" => Request::get('suburb'),
                        "StreetAddress1" => Request::get('address1'),
                        "StreetAddress2" => Request::get('address2'),
                        "PostalCode" => Request::get('postcode'),
                        "DateCreated" => date('d-m-Y')
                    );

                    $contactManager = new ContactManager($infusionsoftContactData);

                    if ($infusionsoftContactId !== 0) {
                        $contactManager->update(
                            $infusionsoftContactId
                        );
                    }
                    else
                    {
                        $contactManager->store();
                        $infusionsoftContactId = $contactManager->getId();
                    }

                    $this->repository->updateINSContactID(
                        $response['id'],
                        $infusionsoftContactId
                    );

                    // Update custom field
                    $infusionsoftCustomer = new InfusionsoftCustomer($response['id'], 'inline');
                    $infusionsoftCustomer->updateCustomerInfs();

                    // Optin email for marketing purpose
                    $api->optIn($data['email']);
                }

                DB::commit();
                
                Mail::to($data['email'])
                ->queue(new UserAdminRegistrationEmail($this->repository->ACCOUNT_MODEL));
            }

            else $response['message'] = $this->validator->filterError($this->validator->messages);

            return $response;
        }
        catch(\Exception $e) {
            DB::rollback();
            return [
                'message' => $e->getMessage(),
                'success' => false
            ];
        }

        catch(\Infusionsoft\Http\HttpException $e) {
            DB::rollback();
            return [
                'message' => $e->getMessage(),
                'success' => false
            ];
        }

    }

    public function delete(int $id): array
    {   
        $response['status'] = 200;
        $response['success'] = false;

        if ($response['success'] = $this->repository->delete($id)[0]) {
            $response['message'] = $this->repository->successDeletedMessage;            
        } 

        else $response['message'] = $this->repository->errorDeleteMessage;

        return $response;
    }

    public function update(array $data): array
    {   
        $data['delivery_notes'] = '';
        $data['dietary_notes'] = '';
        $data['status'] = 'active';

        $data['name'] = Request::get('first_name').', '.Request::get('last_name');
        $data['customer'] = [Request::get('first_name'), Request::get('last_name'), $data['id']];

        $this->validator->validate($data, $this->repository->updateRules());

        $response['status'] = 200;

        $response['success'] = $this->validator->isValid;

        if ($this->validator->isValid)  
        {
            $response['message'] = $this->repository->successUpdatedMessage;

            $this->repository->update($data);

            if (method_exists($this->repository, 'getId')) {
                $response['id'] = $this->repository->getId();
            }

            if (empty($this->repository->address($response['id']))) {
                $this->repository->storeAddress($response['id'], $data);
            } else {
                $this->repository->updateAddress($response['id'], $data);
            }

            if (empty($this->repository->profile($response['id']))) {
                $this->repository->storeDetails($response['id'], $data);
            } else {
                $this->repository->updateDetails($response['id'], $data);
            }
            
            if (!empty($response['id']))
            {   
                $api = (new InfusionsoftFactory('oauth2'))->service();
                
                $infusionsoftContactId = $this->repository->getINSContactId($response['id']);
                if (empty($infusionsoftContactId))
                {
                    $infusionsoftData = $api->fetchContactByEmail($data['email'], array('Id'));
                    $infusionsoftContactId = $infusionsoftData[0]['Id'] ?? 0;
                }

                $infusionsoftContactData = array(
                    "Email" => Request::get('email'),
                    "FirstName" => Request::get('first_name'),
                    "LastName" => Request::get('last_name'),
                    "Phone1" => Request::get('mobile_phone'),
                    "State" => Request::get('state_desc'),
                    "Country" => Request::get('country'),
                    "City" => Request::get('suburb'),
                    "StreetAddress1" => Request::get('address1'),
                    "StreetAddress2" => Request::get('address2'),
                    "PostalCode" => Request::get('postcode'),
                    "DateCreated" => date('d-m-Y')
                );

                $contactManager = new ContactManager($infusionsoftContactData);

                if ($infusionsoftContactId !== 0) {
                    $contactManager->update(
                        $infusionsoftContactId
                    );
                }
                else
                {
                    $contactManager->store();
                    $infusionsoftContactId = $contactManager->getId();
                }

                $this->repository->updateINSContactID(
                    $response['id'],
                    $infusionsoftContactId
                );

                // Update custom field
                $infusionsoftCustomer = new InfusionsoftCustomer($response['id']);
                $infusionsoftCustomer->updateCustomerInfs();

                // Optin email for marketing purpose
                $api->optIn($data['email']);
            }
            
        }

        else $response['message'] = $this->validator->filterError($this->validator->messages);

        return $response;
    }

    public function updateDelivery(array $data): array
    {   
        $this->validator->validate($data, $this->repository->updateDeliveryRules());

        $response['status'] = 200;

        $response['success'] = $this->validator->isValid;

        if ($this->validator->isValid)  
        {
            $response['message'] = $this->repository->successUpdatedDZTMessage;

            $this->repository->updateDelivery($data);
            
            if (!empty($this->repository->getId()))
            {   
                $email = $this->repository->getEmailByUserId(
                    $this->repository->getId()
                );
                
                $infusionsoftContactId = $this->repository->getINSContactId(
                    $this->repository->getId()
                );
                if (empty($infusionsoftContactId))
                {
                    $api = (new InfusionsoftFactory('oauth2'))->service();

                    $infusionsoftData = $api->fetchContactByEmail($email, array('Id'));
                    $infusionsoftContactId = $infusionsoftData[0]['Id'] ?? 0;
                }

                if (!empty($infusionsoftContactId))
                {
                    // Update custom field
                    $infusionsoftCustomer = new InfusionsoftCustomer($this->repository->getId());
                    $infusionsoftCustomer->updateCustomerInfs();
                }

            }
        }

        else $response['message'] = $this->validator->filterError($this->validator->messages);

        return $response;
    }

    public function cancelSubscription(int $userId, int $subscriptionId, int $subscriptionsCycleId)
    {
        $this->subscriptionRepo->cancellPlan($userId, $subscriptionId);
        $this->subscriptionSelectionRepo->cancellPlan($userId, $subscriptionsCycleId);

        $cancelled_plans   = $this->subscriptionRepo->getMealPlanBySubscriptionId($subscriptionId);
        
        $custom_user = Users::find($userId);

        $additional_details = 'Cancelled Subscription: ' . $cancelled_plans->meal_plan['plan_name'];

        $this->audit('User Cancelled Subscription', 'This user (' .$custom_user->name. ') cancelled his/her subscription.', $additional_details);

        $infusionsoftCustomer = new InfusionsoftCustomer($userId);
        $infusionsoftCustomer->updateCustomerInfs();
        $infusionsoftCustomer->cancelledAPlan();

        return 1;
    }

    public function pauseSubscription(int $userId, int $subscriptionCycleId, \DateTime $date)
    {   
        $subscriptionId = $this->subscriptionSelectionRepo->getSubscriptionIdById($subscriptionCycleId);
        $this->subscriptionRepo->saveStopTillDate($userId, $subscriptionId, $date);
        $this->subscriptionSelectionRepo->saveStopTillDate($userId, $subscriptionCycleId);

        $custom_user    = Users::find($userId);

        $paused_plan    = $this->subscriptionRepo->getMealPlanBySubscriptionId($subscriptionId);

        $formatted_date = date('M d, Y', strtotime($paused_plan->paused_till));

        $this->audit('User Paused Subscription', 'This user (' .$custom_user->name. ') paused the '.$paused_plan->meal_plan['plan_name'].' subscription until ' . $formatted_date .'.', '');

        $infusionsoftCustomer = new InfusionsoftCustomer($userId);
        $infusionsoftCustomer->updateCustomerInfs();
        $infusionsoftCustomer->pausedAPlan();

        return 1;
    }

    public function playSubscription(int $userId, int $subscriptionCycleId)
    {   
        $subscriptionId = $this->subscriptionSelectionRepo->getSubscriptionIdById($subscriptionCycleId);
        $out = $this->subscriptionRepo->cancelPausedDate($userId, $subscriptionId);
        $this->subscriptionSelectionRepo->cancelPausedDate($userId, $subscriptionCycleId);

        $custom_user    = Users::find($userId);
        
        $cancelled_paused_plan    = $this->subscriptionRepo->getMealPlanBySubscriptionId($subscriptionId);
        $this->audit('User Resumed the Subscription', 'This user (' .$custom_user->name. ') resumed the subscription of this meal plan '.$cancelled_paused_plan->meal_plan['plan_name'].'.', '');

        $infusionsoftCustomer = new InfusionsoftCustomer($userId);
        $infusionsoftCustomer->updateCustomerInfs();

        return $out;
    }
    
    public function profile(int $id)
    {
        return $this->repository->profile($id);
    }

    public function address(int $id)
    {
        return $this->repository->address($id);
    }

    public function account(int $id)
    {
        return $this->repository->account($id);
    }

    public function zoneTiming(int $id)
    {
        return $this->repository->zoneTiming($id);
    }

    public function zoneTimingList()
    {
        return $this->ztRepository->getAll();
    }

    public function mealPlans()
    {
        return $this->planRepository->getAll();
    }

    public function masterlist()
    {
        return $this->repository->getAll();
    }

    public function invoice()
    {
        echo "<pre>";
        $d = $this->infs->queryTable("Invoice", array("Id"=>'46996'), array('Id', 'ContactId', 'JobId', 'DateCreated', 'InvoiceTotal', 'TotalPaid', 'TotalDue', 'PayStatus', 'CreditStatus', 'RefundStatus', 'PayPlanStatus', 'AffiliateId', 'LeadAffiliateId', 'PromoCode', 'InvoiceType', 'Description', 'ProductSold', 'Synced', 'LastUpdated'));
        print_r($d);

        $d = $this->infs->queryTable("OrderItem", array("OrderId"=>$d[0]['JobId']), array('Id', 'OrderId', 'ProductId', 'SubscriptionPlanId', 'ItemName', 'Qty', 'CPU', 'PPU', 'ItemDescription', 'ItemType', 'Notes'));

        print_r($d);
        die();
    }

    public function getActiveCoupons(\App\Repository\CouponsRepository $coupon)
    {        
        $data = [];
        foreach($coupon->getAll() as $row) {
            $couponData = new \App\Services\Coupons\Model\Data($row->coupon_code);
            $expired = new \App\Services\Coupons\Validator\Data($row->coupon_code);
            if (!$expired->isExpired() && !$couponData->isUsed()) {
                $data[] =  (object)[
                    'id' => $row->id,
                    'coupon_code' => $row->coupon_code
                ];
            }
        }
        return $data;
    }

    public function getFutureDeliveryTimingSchedule(int $userId, int $subscriptionCycleId)
    {
        $cycleId = $this->subscriptionSelectionRepo->getCycleIdById($subscriptionCycleId);
        $cycle = $this->cycleRepository->get($cycleId);

        $deliveryDate = $cycle->delivery_date;

        for($i = 0; $i < 20; $i++) {
            $deliveryDate = date('Y-m-d', strtotime($deliveryDate.' +1 week'));
            // This is unecessary and for testing purpose only
            $data[] = $deliveryDate;
        }

        return $data;
    }

    public function getDeliveryTimingsId(int $id)
    {
        return $this->repository->getDeliveryTimingsId($id);
    }

    public function getDeliveryZoneTimings(int $zoneId)
    {
        $data = [];
        $model = new \App\Models\DeliveryZone;
        
        foreach($model->timings()->where(['delivery_zone_id' => $zoneId])->get() as $row) {
            $deliverydate = date('l dS F Y', strtotime($row->delivery_date));
            $data[] = [
                'id'    => $row->delivery_zone_timings_id,
                'date'  => $deliverydate
            ];
        }

        return $data;
    }

    public function checkStatus($userId){
//        Query Active Subs
//        If it contains any with billing issues, its a billing issue contact
//        If it contains no billing issues, and has subs active before now, its active
//        if it contains subs, but they are only in the future, paused
//        if it doesnt contain any active subs BUT has had at least one before, then they are cancelled
//        else they are nothing
        if(empty($userId)) return -1;
        $all_subs = $this->subscriptionRepo->getByUserIdWhateverStatus($userId);
        $sub_sel_repo = new \App\Repository\SubscriptionSelectionsRepository;
        $active_subs = array(); $past_subs = array(); $paused_subs = array();
//        $today_date = \Carbon\Carbon::now()->format('Y-m-d');
        
        $current_active_cycles_ar = array();
        $current_active_cycles = $this->cycleRepository->getActive();
        foreach($current_active_cycles as $cycles){
            $current_active_cycles_ar[] = $cycles->id;
        }
        $hasSub = false;
        foreach($all_subs as $subs){
            $hasSub = true;
            $status = strtolower($subs->status);
            if($status == "cancelled"){
               continue;
            }
            if($status == "billing issue"){
               return "Billing Issue";
            }
            if($status == "paused"){
                $paused_subs[] = $subs;
            }
            else {
                // $subs_cycle = $sub_sel_repo->getCurrent($subs->id);
                foreach($sub_sel_repo->getSelectionCyclesBySubscription($subs->id) as $subs_cycle)
                {   
                    if (empty($subs_cycle)) continue;

                    else if(
                        in_array($subs_cycle->cycle_id, $current_active_cycles_ar)
                        && (!in_array(strtolower($subs_cycle->cycle_subscription_status), ["cancelled",'failed']) )
                    ) {
                        $active_subs[] = $subs;
                    }
                }
            }
        }

        if (!$hasSub) {
            return '';
        }
        
        if(count($paused_subs) == 0 && count($active_subs) == 0) return "Cancelled";
        else if(count($active_subs) > 0) return "Active";
        return "Paused";
    }

    public function updateCustomFields($contactId, $fields=array()){
        if(empty($contactId)) return false;
        if(count($fields) == 0){
            return 0;
//            #Default values to be savedd for all custom fields for the contact 
//            $fields = array(
//                "_ActiveWeekMenu"=>"", 
//                "_ActiveLocation"=>"", 
//                "_NextDeliveryLocation"=>"", 
//                "_DeliveryMenu"=>"", 
//                "_PausedCancelledPlans"=>"", 
//                "_ActiveWeekCutOffDate"=>"", 
//                "_ActiveWeekCutOff"=>"", 
//                "_NextDeliveryDate"=>"", 
//                "_PausedTillDate"=>""
//            );
        }

        #update not INFS, if it has data for updation 
        $infs = new \App\Services\InfusionSoftServices;
        return $infs->deDupeContact($contactId, $fields);
    }

    public function updatestatus(int $userId, int $subscriptionCycleId, string $status)
    {
        try {

            $str_status = $status;

            $status = $this->subscriptionSelectionRepo->updatestatus(
                $userId, $subscriptionCycleId, $status
            );

            $custom_user = \App\Models\Users::find($userId);

            $this->audit('User Subscription Status Change', 'This User\'s ('.$custom_user->name.') subscription status has been changed into '.$str_status.'.', '');

            $infusionsoftCustomer = new InfusionsoftCustomer($userId);
            $infusionsoftCustomer->updateCustomerDeliveryDetailsInfs();

            return $status == 1 ? ['success' => true, 'message' => 'Successfully updated the status.'] : ['success' => false, 'message' => 'Error'];
        }
        catch (\Exception $e)
        {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getPreviousCycle(int $userId, int $subscriptionId, int $subscriptionCycleId)
    {    
        $data = array();
        $sub = $this->subscriptionSelectionRepo
            ->getCycle($userId, $subscriptionId, $subscriptionCycleId)
            ->select(['delivery_timings_id','delivery_zone_id'])
            ->join('cycles', 'cycles.id','=','subscriptions_cycles.cycle_id')
            ->orderBy('subscriptions_cycles.id','desc')
            ->first();

        if (empty($sub->delivery_timings_id)) {
            // throw new \Exception(__('There is no previous cycle.'), 1);
            return array();
        }

        $model = $this->cycleRepository
        ->getPrevious($sub->delivery_timings_id)
        ->limit(1)
        ->get();

        foreach($model as $row) {
            $data[] = (object)[
                'id' => $row->id,
                'delivery_date' => date('l dS F Y', strtotime($row->delivery_date)),
                'delivery_zone_id' => $sub->delivery_zone_id
            ];
        }


        //checking if subscription is already added on prev week
        $subscribed = $this->subscriptionSelectionRepo
                    ->getSubscriptionCycle($userId, $subscriptionId, $data[0]->id)
                    ->get();
        
        if ( !$subscribed->isEmpty() ) {
            throw new \Exception(__('This contact already has a menu for last week for this subscription'), 1);
        }

        return $data;
    }

    public function getProductsPlan()
    {
        $data = array();
        $model = $this->planRepository->getAll();

        foreach($model as $row) {
            $data[] = (object)[
                'id' => $row->id,
                'name' => $row->plan_name
            ];
        }

        return $data;
    }

    public function getPreviousSubscriptionMealPlan(int $subscriptionId)
    {
        $subscriptionMealPlan = $this->subscriptionRepo->get($subscriptionId);

        if (empty($subscriptionMealPlan)) {
            throw new \Exception(__('No data found on selected plan id.'), 1);
        }

        return $subscriptionMealPlan;
    }

    public function getActiveCycleByDeliveryZoneTimings(int $userId)
    {
        return $this->repository->getActiveCycleByDeliveryZoneTimings($userId);
    }

    public function getINSContactId(int $userId)
    {
        return $this->repository->getINSContactId($userId);
    }

    public function isCancelledLastWeek(int $userId)
    {        
        $totalCancelled = 0;
        $totalSubs = 0;
        foreach($this->repository->getForDeliverySubscriptionWithStatus($userId) as $row) {
            $totalSubs++;
            if (in_array(strtolower($row->cycle_subscription_status), ['cancelled'])) {
                $totalCancelled++;
            }
        }

        return $totalSubs == $totalCancelled;
    }
}
