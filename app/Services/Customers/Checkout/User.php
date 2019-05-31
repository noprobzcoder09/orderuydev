<?php

namespace App\Services\Customers\Checkout;

use Auth;

use App\Repository\CustomerRepository;
use App\Repository\UsersRepository;
use App\Services\Validator;

Class User
{   
    private $new = false;

    const rules = [
        'store' => [
            'first_name'    => 'required',
            'last_name'     => 'required',
            'mobile_phone'  => 'required',
            'email'         => 'required|email',
            'address1'      => 'required',
            // 'address2'      => 'required',
            'postcode'      => 'required',
            'suburb'        => 'required',
            'delivery_zone_timings_id'  => 'required|numeric',
            'delivery_zone_id'  => 'required|numeric',
            'last_name'     => 'required',
            'email'  => 'required|email|unique:users',
            'password' => 'required'
        ],

        'edit' => [
            'first_name'    => 'required',
            'last_name'     => 'required',
            'mobile_phone'  => 'required',
            'email'         => 'required|email',
            'address1'      => 'required',
            // 'address2'      => 'required',
            'postcode'      => 'required',
            'suburb'        => 'required',
            'delivery_zone_timings_id'  => 'required|numeric',
            'delivery_zone_id'  => 'required|numeric',
            'last_name'     => 'required',
        ],

        'editInfoAddress' => [
            'id'            => 'required',
            'first_name'    => 'required',
            'last_name'     => 'required',
            'mobile_phone'  => 'required',
            'email'         => 'required|email',
            'address1'      => 'required',
            // 'address2'      => 'required',
            'postcode'      => 'required',
            'suburb'        => 'required'
        ],
    ];
    
    public function __construct()
    {
        $this->customer = new CustomerRepository;
        $this->user = new UsersRepository;
        $this->validator = new Validator;
    }

    public function store(array $data)
    {   
        $this->validate($data);

        $this->customer->store($this->getStoreUser($data));
        if (empty($this->customer->id)) {
            throw new \Exception(sprintf(__('crud.failedToCreate'),'User'), 1);
            
        }
        $userId = $this->customer->id;

        $details = $this->customer->storeDetails($userId, $this->getUserDetails($data));
        if (empty($this->customer->id)) {
            throw new \Exception(sprintf(__('crud.failedToCreate'),'User Details'), 1);
            
        }

        $address = $this->customer->storeAddress($userId, $this->getUserAddress($data));
        if (empty($this->customer->id)) {
            throw new \Exception(sprintf(__('crud.failedToCreate'),'User Address'), 1);
            
        }

        $this->setId($userId);

        $this->user->setRow($userId);
    }

    public function update(int $userId, array $data)
    {   
        $data['id'] = $userId;
        $user = $this->customer->update($this->getUpdateUser($data));

        if ($this->customer->hasDetails($userId)) {
            $this->customer->updateDetails($userId, $this->getUserDetails($data));
        } else {
            $this->customer->storeDetails($userId, $this->getUserDetails($data));
        }

        if ($this->customer->hasAddres($userId)) {
            $this->customer->updateAddress($userId, $this->getUserAddress($data));
        } else {
            $this->customer->storeAddress($userId, $this->getUserAddress($data));
        }

        $this->setId($userId);

        $this->user->setRow($userId);
    }

    public function createLoginAccountWithoutName($email, $password, $contactId)
    {
        $userId = $this->user->storeWithoutName(array(
            'email' => $email,
            'password' => bcrypt($password),
            'role' => 'customer'
        ));

        $details = array(
            'first_name' => '',
            'last_name' => '',
            'mobile_phone' => '',
            'delivery_notes' => '',
            'dietary_notes' => '',
            'delivery_zone_timings_id' => 0
        );

        $address = array(
            'address1' => '',
            'address2' => '',
            'suburb' => '',
            'state' => 0,
            'country' => 'Australia',
            'postcode' => ''  
        );

        $this->customer->storeDetails($userId, $this->getUserDetails($details));
        $this->customer->storeAddress($userId, $this->getUserAddress($address));

        $this->updateContactId($userId, $contactId);

        $this->setId($userId);
    }

    public function updateContactId(int $userId, int $contactId)
    {   
        $this->customer->updateINSContactID($userId, $contactId);
    }

    public function storeCardId($id, $last4)
    {
        return $this->customer->updateCards($this->getId(), $id, $last4);
    }

    public function getEmail()
    {
        return $this->user->getEmail($this->getId());
    }

    public function getContactId()
    {
        return $this->user->getContactId();
    }

    public function getContactIdByEmail(string $email)
    {
        return $this->customer->getINSContactIdByEmail($email);
    }

    public function setNew(bool $new)
    {   
        $this->new = $new;
    }

    public function new()
    {   
        return $this->new;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
        $this->user->setRow($id);
    }

    private function getUserDetails(array $data)
    {
        return [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'mobile_phone' => $data['mobile_phone'],
            'delivery_notes' => $data['delivery_notes'] ?? '',
            'dietary_notes' => $data['dietary_notes'] ?? '',
            'delivery_zone_timings_id' => $data['delivery_zone_timings_id'],
            'billing_first_name' => $data['first_name'],
            'billing_last_name' => $data['last_name'],
            'billing_mobile_phone' => $data['mobile_phone']
            // 'status' => 'active'
        ];
    }

    private function getUserAddress(array $data)
    {
        return [
            'address1' => $data['address1'],
            'address2' => $data['address2'],
            'suburb' => $data['suburb'],
            'state' => $data['state'],
            'country' => $data['country'],
            'postcode' => $data['postcode']        
        ];
    }

    private function getStoreUser(array $data)
    {   
        $user = [
            'name' => $data['name'],
            'email' => $data['email'],
            'verification' => $data['name'],
            'password' => $data['password'],
            'role' => 'customer',
            'active' => 1
        ];
        
        if (isset($data['id'])) {
            $user['id'] = $data['id'];
        }

        return $user;
    }

    private function getUpdateUser(array $data)
    {   
        $user = [
            'name' => $data['name']
        ];
        
        if (isset($data['id'])) {
            $user['id'] = $data['id'];
        }

        return $user;
    }

    public function validate($data)
    {
        $this->validator->validate($data, self::rules['store']);
        
        if (!$this->validator->isValid) {
            throw new \Exception($this->validator->filterError($this->validator->messages), __('codes.rulesInvalid'));
        }
    }

    public function getIdByEmail($email)
    {
        return $this->customer->getIdByEmail($email);
    }

}


