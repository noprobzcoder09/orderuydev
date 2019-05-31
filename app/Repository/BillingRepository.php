<?php

namespace App\Repository;

use Session;

/*use App\Rules\CardExpirationDate;
use App\Rules\CardNumber;
use App\Rules\CardCVC;*/
use App\Rules\CardID;
use App\Rules\Cart;

use App\Models\UserAddress;
use App\Models\UserDetails;
use App\Models\Users;
use App\Rules\Custom;
use DB;
use Auth;

Class BillingRepository
{	
    public $successSavedMessage = 'Successfully created Order.';

    public $successUpdatedMessage = 'Successfully updated Order.';

    public $successDeletedMessage = "Successfully deleted Order";

    public $errorBillMessage = "Sorry we could not process your order. Please try again!";

    public $errorNoItemsMessage = "Oops! It seems you forgot to add to cart meals.";

    public $successSavedCardMessage = 'Successfully added new card.';

    public $errorCardExistCardMessage = 'Card is already exist in your list.';

    public $successSavedInfoAddressMessage = 'Successfully Saved Billing Information.';

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
            'email'  => 'required|email|unique:users'
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
    const delivery_zone_id = 'delivery_zone_id';
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
    const card_name = 'card_name';
    const card_expiration_date = 'card_expiration_date';
    const card_cvc = 'card_cvc';
    const card_number = 'card_number';
    const coupon_code = 'coupon_code';
    const billing_first_name = 'billing_first_name';
    const billing_last_name = 'billing_last_name';
    const billing_mobile_phone = 'billing_mobile_phone';

    private $defaultPassword = '123456';

    public $id;

    public function __construct($request) 
    {
        $this->account = new Users;
        $this->details = new UserDetails;
        $this->address = new UserAddress;

        $this->request = $request;
    }

     public function store(array $data)
    {          
        $model = $this->account->create([
            self::name      => $data['name'],
            self::email     => $data['email'],
            self::password  => isset($data['password']) ? $data['password'] : bcrypt($this->defaultPassword),
            self::verification  => $data['verification'],
            self::role      => 'customer',
            self::active    => $data['active'],
        ]);

        $this->setId($model->id);

        return $this->ACCOUNT_MODEL = $model;
    }

    public function update(array $data): array
    {   
        $this->setId($data['id']);

        $model = $this->account->find($data['id']);

        $model->name = $data['name'];
        
        $model->save();

        return (array)$this->ACCOUNT_MODEL = $model;
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
        $model = $this->details->create([
            self::user_id           => $userId,
            self::first_name        => $data['first_name'],
            self::last_name         => $data['last_name'],
            self::mobile_phone      => $data['mobile_phone'],
            self::delivery_notes    => $data['delivery_notes'] ?? '',
            self::dietary_notes     => $data['dietary_notes'] ?? '',
            self::delivery_zone_timings_id  => $data['delivery_zone_timings_id'] ?? 0,
            self::billing_first_name => $data['first_name'],
            self::billing_last_name => $data['last_name'],
            self::billing_mobile_phone => $data['mobile_phone'],
        ]);

        return (array)$this->DETAILS_MODEL = $model;
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
            $model->dietary_notes = $data['delivery_notes'];
        }
        if (isset($data['dietary_notes'])) {
            $model->dietary_notes = $data['dietary_notes'];
        }
        if (isset($data['delivery_zone_timings_id'])) {
            $model->delivery_zone_timings_id = $data['delivery_zone_timings_id'];
        }

        $model->save();

        return $this->DETAILS_MODEL = $model;

    }

    public function delete(int $id): array
    {
        return [$this->model->where(self::primary_key, $id)->delete()];
    }

    public function search(): array
    {
        return [];
    }

    public function verify(string $value): string
    {
        return $this->model->where(self::name,$value)->count() > 0;
    }

    public function storeRules(): array
    {
        $rules = self::rules['store'];
        
        if ($this->isCardNew()) 
        {
            $rules['card_cvc'] = ['required', new \LVR\CreditCard\CardCvc($this->getPostCardNumber())];
            $rules['card_number'] = ['required', new \LVR\CreditCard\CardNumber];
            $rules['expiration_year'] = [new \LVR\CreditCard\CardExpirationYear($this->getPostExpMonth())];
            $rules['expiration_month'] = [new \LVR\CreditCard\CardExpirationMonth($this->getPostExpYear())];
        }
        else
        {
            $rules['card_id'] = ['required', new CardID];
        }

        $rules['coupon_code'] = [new \App\Rules\AllCoupons(Auth::check() ? Auth::id() : 0)];        

        $rules['cart']  = [new Cart];

        return $rules;
    }

    public function editRules(): array
    {
        $rules = self::rules['edit'];
        
        // validate card
        if ($this->isCardNew()) 
        {
            $rules['card_cvc'] = ['required', new \LVR\CreditCard\CardCvc($this->getPostCardNumber())];
            $rules['card_number'] = ['required', new \LVR\CreditCard\CardNumber];
            $rules['expiration_year'] = [new \LVR\CreditCard\CardExpirationYear($this->getPostExpMonth())];
            $rules['expiration_month'] = [new \LVR\CreditCard\CardExpirationMonth($this->getPostExpYear())];
        }
        else
        {
            $rules['card_id'] = ['required', new CardID];
        }

        // Validate Promo Codes
        $rules['coupon_code'] = [new \App\Rules\AllCoupons(Auth::check() ? Auth::id() : 0)];         

        // Validate cart
        $rules['cart']  = [new Cart];

        // Validate Email
        $rules['email'] = ['required', new Custom( function($attribute, $value) {
            if($this->account
                    ->where($attribute, $value)
                    ->where(self::primary_key,'<>',$this->id)
                        ->count() > 0
                ) {
                return false;
            }
            return true;
        })];

        return $rules;
    }

    public function storeCardRules(): array
    {   
        $rules['card_name'] =  ['required'];
        $rules['card_cvc'] = ['required', new \LVR\CreditCard\CardCvc($this->getPostCardNumber())];
        $rules['card_number'] = ['required', new \LVR\CreditCard\CardNumber];
        $rules['expiration_year'] = [new \LVR\CreditCard\CardExpirationYear($this->getPostExpMonth())];
        $rules['expiration_month'] = [new \LVR\CreditCard\CardExpirationMonth($this->getPostExpYear())];

        return $rules;
    }

    public function updateRules(): array
    {
        return self::rules['edit'];
    }

    public function updateInfoAddressRules(): array
    {
        return self::rules['editInfoAddress'];
    }
    
    public function get(int $id)
    {
        return $this->model->find($id);    
    }

    public function getZones() {
        return $this->modelDZ->get();
    }

    public function getTimings() {
        return $this->modelTiming->get();
    }

    public function setId($id) {
        $this->id = $id;
    }

     public function getUserId($id) {
        return $this->id;
    }

    public function hasDetails(int $id) {
        return $this->details->where(self::user_id,$id)->limit(1)->count() > 0;
    }

    public function hasAddres($id) {
        return $this->address->where(self::user_id,$id)->limit(1)->count() > 0;
    }

    public function getPostName() {
        return $this->request[self::first_name].' '.$this->request[self::last_name];
    }

    public function getPostBillName() {
        return $this->request[self::first_name].' '.$this->request[self::last_name];
    }

    public function getPostAddress1() {
        return $this->request[self::address1];
    }

    public function getPostAddress2() {
        return $this->request[self::address2];
    }

    public function getPostBillCity() {
        return $this->request[self::state];
    }

    public function getPostBillState() {
        return $this->request[self::state];
    }

    public function getPostBillZip() {
        return $this->request[self::postcode];
    }

    public function getPostCountry() {
        return $this->request[self::country];
    }

    public function getPostPhone() {
        return $this->request[self::mobile_phone];
    }

    public function getPostEmail() {
        return $this->request[self::email];
    }

    public function getPostCardName() {
        return $this->request[self::card_name];
    }

    public function getPostCardNumber() {
        return str_replace(' ','',$this->request[self::card_number]);
    }

     public function getPostPromoCode() {
        return isset($this->request[self::coupon_code]) ? $this->request[self::coupon_code] : '';
    }

    public function getPostExpMonth() {
        $month = explode('/',$this->request[self::card_expiration_date])[0];
        return $month;
    }

    public function getPostExpYear() {
        $year = explode('/',$this->request[self::card_expiration_date])[1];
        return $year;
    }

    public function getPostCardCVC() {
        return $this->request[self::card_cvc];
    }

    public function isCardNew() {
        $new = $this->request['card'] == 'undefined' ? 'new' : $this->request['card'];
        return strtolower($new) == 'new';
    }

    public function getCardId() {
        return $this->request['card'];
    }

    public function getPostDeliveryNotes() {
        return isset($this->request[self::delivery_notes]) ? $this->request[self::delivery_notes] : '';
    }

    public function getDeliveryZoneTimingsId() {
        return $this->request[self::delivery_zone_timings_id];
    }

    public function getDeliveryZoneId() {
        return $this->request[self::delivery_zone_id];
    }
    
}
