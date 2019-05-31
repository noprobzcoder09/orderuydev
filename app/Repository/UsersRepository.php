<?php

namespace App\Repository;

use Session;
use App\Services\CRUDInterface;
use App\Mail\UserAdminRegistrationEmail;

use App\Models\Users;
use App\Models\UserDetails;
use App\Models\UserAddress;
use App\Models\DeliveryZoneTimings;
use App\Models\DeliveryTimings;
use App\Models\Cycles;
use App\Models\State;

use Log;
use Mail;

Class UsersRepository implements CRUDInterface
{	
    public $existMessage = 'The Email Address is Already taken.';

    public $successSavedMessage = 'Successfully created new user.';

    public $successUpdatedMessage = 'Successfully updated user.';

    public $defaultPassword = '123456';

    const rules = [
        
        'store' => [
            'name'   => 'required',
            'email'  => 'required|email|unique:users',
            'name'   => 'required',
            'role'   => 'required'
        ],

        'edit' => [
            'name'   => 'required',
            'role'   => 'required',
            'id'     => 'required'
        ],
    ];

    const name = 'name';

    const email = 'email';

    const password = 'password';

    const role = 'role';

    const active = 'active';

    const verification = 'verification';

    protected $MODEL;


    public function __construct() 
    {
        $this->model = new Users;
        $this->details = new UserDetails;
        $this->address = new UserAddress;
        $this->DZTimings = new DeliveryZoneTimings;
        $this->state = new State;
    }

    public function store(array $data): array
    {   
        $this->MODEL = $this->model->create([
            self::name      => $data['name'],
            self::email     => $data['email'],
            self::password  => bcrypt($this->defaultPassword),
            self::verification  => '',
            self::role      => $data['role'],
            self::active    => self::active,
        ]);

        return (array)$this->MODEL;
    }

    public function storeWithoutName(array $data)
    {   
        $this->MODEL = $this->model->create([
            self::name      => '',
            self::email     => $data['email'],
            self::password  => $data['password'],
            self::verification  => '',
            self::role      => $data['role'],
            self::active    => '',
        ]);

        return $this->MODEL->id ?? 0;
    }

    public function update(array $data): array
    {   
        return
        (array)$this->model->where('id', $data['id'])
        ->update([
            self::name      => $data['name'],
            self::verification  => '',
            self::role      => $data['role']
        ]);
    }

    public function delete(int $id): array
    {
        return [];
    }

    public function search(): array
    {
        return [];
    }

    public function verify(string $value): string
    {
        return $this->model->where('email',$value)->count() > 0;
    }

    public function storeRules(): array
    {
        return self::rules['store'];
    }

    public function updateRules(): array
    {
        return self::rules['edit'];
    }

    public function getAll()
    {
        return $this->model->get();
    }

    public function getActive()
    {
        return $this->model->where(self::active,1)->get();
        // return $this->model->details()->where(self::active,1)->get();
    }
    

    public function get(int $id)
    {
        return $this->model->find($id);
    }
    
    public function custom()
    {   
        Log::info('Triggered custom.');
        Mail::to($this->MODEL->email)
            ->queue(new UserAdminRegistrationEmail($this->MODEL));
    }

    public function details(int $id)
    {
        return $this->model->details()->find($id);
    }

    public function getDeliveryZoneTimingId(int $id): int
    {
        $id = $this->details->where('user_id',$id)->first();
        return isset($id->delivery_zone_timings_id) ? $id->delivery_zone_timings_id : 0;
    }

    public function getDeliveryZoneTimingByUserId(int $userId)
    {
        $dztId = $this->details->where('user_id',$userId)->first();
        return isset($dztId) ? $this->DZTimings->find($dztId)->first()  :   0;
    }

    public function getDeliveryTimingId(int $deliveryZoneTimingId): int
    {
        $id = $this->DZTimings->where('id',$deliveryZoneTimingId)->first();
        return isset($id->delivery_timings_id) ? $id->delivery_timings_id : 0;
    }

    public function getDeliveryZoneId(int $deliveryZoneTimingId): int
    {
        $id = $this->DZTimings->where('id',$deliveryZoneTimingId)->first();
        return isset($id->delivery_zone_id) ? $id->delivery_zone_id : 0;
    }

    public function getActiveLocation()
    {
        $dzrepo = new \App\Repository\ZTRepository;
        return $dzrepo->getDeliveryZoneLocation(
            $this->getDeliveryZoneTimingId($this->row->user_id ?? 0)
        );
    }

    public function getLastActiveDeliveryLocation()
    {
        $subsRepo = new \App\Repository\SubscriptionRepository;
        $zoneRepo = new \App\Repository\ZoneRepository;
        $d = $zoneRepo->get(
            $subsRepo->getMyLastDeliveryLocation($this->row->user_id)
        );

        return $d->zone_name ?? '';
    }

    public function getDeliveryAddress()
    {
        $dzrepo = new \App\Repository\ZTRepository;
        return $dzrepo->getDeliveryZoneAddress(
            $this->getDeliveryZoneTimingId($this->row->user_id ?? 0)
        );
    }

    public function getLastActiveDeliveryAddress()
    {
        $subsRepo = new \App\Repository\SubscriptionRepository;
        $zoneRepo = new \App\Repository\ZoneRepository;
        $d = $zoneRepo->get(
            $subsRepo->getMyLastDeliveryLocation($this->row->user_id)
        );

        return $d->delivery_address ?? '';
    }

    public function getActiveWeekCutOffDate()
    {
        $deliveryTimingId = $this->getDeliveryTimingId(
            $this->getDeliveryZoneTimingId($this->row->user_id)
        );

        $cycle = new Cycles;
        $cycle = $cycle->where('delivery_timings_id',$deliveryTimingId)
            ->where('status',1)
            ->first();

        $cutoverdate = $cycle->cutover_date ?? '';

        $cutofftime = DeliveryTimings::find($deliveryTimingId);
        $cutofftime  = $cutofftime->infs_cutoff_time ?? '';

        return $cutoverdate .' '.$cutofftime;
    }

    public function getActiveWeekDate()
    {
        $deliveryTimingId = $this->getDeliveryTimingId(
            $this->getDeliveryZoneTimingId($this->row->user_id)
        );
        
        $cycle = new Cycles;
        $cycle = $cycle->where('delivery_timings_id',$deliveryTimingId)
            ->where('status',1)
            ->first();
            
        return $cycle->delivery_date ?? '';
    }

    public function getLastActiveWeekDeliveryDate()
    {
        $subsRepo = new \App\Repository\SubscriptionRepository;
        
        return $subsRepo->getMyLastActiveDeliveryWeekDate(
            $this->row->user_id
        );
    }

    public function getFirstName(): string
    {
       $name = $this->details->where('user_id', (new \Auth)::id())->first();
       return isset($name->first_name) ? $name->first_name : '';
    }

    public function getLastName(): string
    {
       return $this->row->last_name ?? '';
    }

    public function getFName(): string
    {
       return $this->row->first_name ?? '';
    }

    public function getMobilePhone(): string
    {
       return $this->row->mobile_phone ?? '';
    }

    public function getBillState(): string
    {
        $d = $this->state->where('id', $this->rowAddress->state ?? 0)->first();
        return $d->state ?? '';
    }

    public function getBillCountry(): string
    {
       return $this->rowAddress->country ?? '';
    }

    public function getBillCity(): string
    {
       return $this->rowAddress->suburb ?? '';
    }

    public function getBillAddress1(): string
    {
       return $this->rowAddress->address1 ?? '';
    }

    public function getBillAddress2(): string
    {
       return $this->rowAddress->address2 ?? '';
    }

    public function getBillZip(): string
    {
       return $this->rowAddress->postcode ?? '';
    }

    public function setRow(int $id)
    {
       $this->row = $this->details->where('user_id', $id)->first();
       $this->setRowAddress($id);
    }

    public function setRowAddress(int $id)
    {
       $this->rowAddress = $this->address->where('user_id', $id)->first();
    }

    public function getContactId(): string
    {
       return isset($this->row->ins_contact_id) ? $this->row->ins_contact_id : '';
    }

    public function getCardId()
    {
        $d = isset($this->row->card_ids) ? json_decode($this->row->card_ids) : [];
        return empty($d) ? [] : $d;
    }

    public function getCardDefault(): string
    {
       return isset($this->row->default_card) ? $this->row->default_card : '';
    }

    public function getDeliveryNotes(): string
    {
       return isset($this->row->delivery_notes) ? $this->row->delivery_notes : '';
    }

    public function getEmail(int $userId): string
    {
       return $this->details->email($userId);
    }

    public function setName(string $name)
    {
       $this->model->name = $name;
    }

    public function setMobile(string $mobile = null)
    {
        $this->details->mobile_phone = $mobile;   
    }

    public function setEmail(string $email)
    {
        $this->model->email = $email;   
    }

    public function getStatus()
    {
        if (isset($this->row->status)) {
            return strtolower($this->row->status);
        }
        return '';
    }

    public function redirect(): string
    {
       $role = $this->model->where('id', (new \Auth)::id())->first();
       $role = isset($role->role) ? strtolower($role->role) : '';

       if ($role == 'customer') {
            return url('/dashboard');
       }
       return url('/');
    }

    public function isAdmin(): string
    {
       $role = $this->model->where('id', (new \Auth)::id())->first();
       $role = isset($role->role) ? strtolower($role->role) : '';

       if ($role == 'administrator') {
            return true;
       }
       return false;
    }

    public function getRoles(): array
    {
       return [
            'Administrator',
            //'Editor',
	        'Restricted Admin',
            'Customer'
       ];
    }

    public function updatePassword(int $userId, string $password)
    {
        $model = $this->model->findOrFail($userId);
        $model->password = bcrypt($password);
        return $model->save();
    }

    public function updateFirstName(int $userId, string $firstName)
    {
        $model = $this->details->findOrFail($this->details->getDetailsIdByUser($userId));
        $model->first_name = $firstName;
        return $model->save();
    }

    public function updateLastName(int $userId, string $lastName)
    {
        $model = $this->details->findOrFail($this->details->getDetailsIdByUser($userId));
        $model->last_name = $lastName;
        return $model->save();
    }

    public function updateName(int $userId, string $name)
    {
        $model = $this->model->findOrFail($userId);
        $model->name = $name;
        return $model->save();
    }

    public function updateMobile(int $userId, string $mobile)
    {
        $model = $this->details->findOrFail($this->details->getDetailsIdByUser($userId));
        $model->mobile_phone = $mobile;
        return $model->save();
    }

    public function updateEmail(int $userId, string $email)
    {
        $model = $this->model->findOrFail($userId);
        $model->email = $email;
        return $model->save();
    }

    public function updateDeliveryZoneTimingId(int $userId, int $deliveryZoneTimingId)
    {
        $model = $this->details->findOrFail($this->details->getDetailsIdByUser($userId));
        $model->delivery_zone_timings_id = $deliveryZoneTimingId;
        return $model->save();
    }

    public function updateDeliveryNotes(int $userId, $notes = '')
    {
        $model = $this->details->findOrFail($this->details->getDetailsIdByUser($userId));
        $model->delivery_notes = $notes;
        return $model->save();
    }

    public function updateStatus(int $userId, string $status)
    {
        $model = $this->details->findOrFail($this->details->getDetailsIdByUser($userId));
        $model->status = $status;
        return $model->save();
    }

    public function updateCardDefault(int $userId, $cardId)
    {
        $model = $this->details->findOrFail($this->details->getDetailsIdByUser($userId));
        $model->default_card = $cardId;
        return $model->save();
    }

    public function getUserIdByContactId($contactId)
    {
        $model = $this->details->where('ins_contact_id',$contactId)->first();
        return $model->user_id ?? 0;
    }

    public function updateCustomersCancelledAttachedDeliveryZoneToDefault($deliveryZoneId, int $default = 0)
    {
        $users = $this->details
        ->select('user_details.user_id')
        ->join('delivery_zone_timings',
            'user_details.delivery_zone_timings_id','=','delivery_zone_timings.id'
        )
        ->where('delivery_zone_timings.delivery_zone_id',$deliveryZoneId)
        ->whereIn('user_details.status',['cancelled'])
            ->get();

        $ids = array();
        foreach($users as $id) {
            array_push($ids, $id->user->id);
        }

        $this->details->whereIn('user_id', $ids)
            ->update([
                'delivery_zone_timings_id' => $default
            ]);
    }


    public function getUserDeliveryTimingId(int $userId)
    {
        $deliveryZoneTiming = $this->getDeliveryZoneTimingByUserId($userId);

        return !empty($deliveryZoneTiming)   ?   $this->getDeliveryTimingId($deliveryZoneTiming->id) : 0;
    }
    
}
