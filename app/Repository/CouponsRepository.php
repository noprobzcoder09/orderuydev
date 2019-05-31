<?php

namespace App\Repository;

use Session;
use App\Services\CRUDInterface;
use App\Models\Coupons;
use App\Rules\Custom;

Class CouponsRepository implements CRUDInterface
{	
    public $successSavedMessage = 'Successfully created new Coupons.';
    public $successUpdatedMessage = 'Successfully updated Coupons.';
    public $successDeletedMessage = "Successfully deleted Coupons.";
    public $errorDeleteMessage = "Sorry could not delete Coupons.";

    const rules = [
        'store' => [
            'coupon_code'  => 'required|unique:coupons',
            'discount_type'  => 'required',
            'discount_value'  => 'required',
            'max_uses'  => 'required',
            'expiry_date'  => 'required',
        ],

        'edit' => [
            'id'            => 'required',
            'coupon_code'    => 'required',
            'discount_type'  => 'required',
            'discount_value'  => 'required',
            'max_uses'  => 'required',
            'expiry_date'  => 'required',
        ],
    ];

    const primary_key = 'id';

    const coupon_code = 'coupon_code';

    const discount_type = 'discount_type';

    const discount_value = 'discount_value';

    const expiry_date = 'expiry_date';

    const products = 'products';

    const number_used = 'number_used';

    const min_order = 'min_order';

    const max_uses = 'max_uses';

    const used = 'used';

    const user = 'user';

    const solo = 'solo';

    const onetime = 'onetime';

    const recur = 'recur';

    public $id;


    public function __construct() 
    {
        $this->model = new Coupons;
    }

    public function store(array $data): array
    {   
        return
        (array)$this->model->create([
            self::coupon_code    => $data['coupon_code'],
            self::discount_type  => $data['discount_type'],
            self::discount_value => $data['discount_value'],
            self::expiry_date => date('Y-m-d',strtotime($data['expiry_date'])),
            self::products    => isset($data['products_sel']) ? $data['products_sel'] : '',
            self::min_order   => isset($data['min_order']) ? $data['min_order'] : 0,
            self::max_uses    => $data['max_uses'],
            self::user    => isset($data['users']) ? $data['users'] : '',
            self::solo    => $data['solo'],
            self::onetime    => $data['onetime'],
            self::recur    => $data['recur']
        ]);
    }

    public function update(array $data): array
    {   
        return
        (array)$this->model->where(self::primary_key, $data['id'])
        ->update([
            self::coupon_code    => $data['coupon_code'],
            self::discount_type  => $data['discount_type'],
            self::discount_value => $data['discount_value'],
            self::expiry_date => date('Y-m-d',strtotime($data['expiry_date'])),
            self::products    => isset($data['products_sel']) ? $data['products_sel'] : '',
            self::min_order   => isset($data['min_order']) ? $data['min_order'] : 0,
            self::max_uses    => $data['max_uses'],
            self::user    => isset($data['users']) ? $data['users'] : '',
            self::solo    => $data['solo'],
            self::onetime    => $data['onetime'],
            self::recur    => $data['recur']
        ]);
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

        $rules['coupon_code'] = ['required', new Custom( function($attribute, $value) {
            if($this->model
                    ->where($attribute, $value)
                        ->count() > 0
                ) {
                return false;
            }
            return true;
        })];

        return $rules;
    }

    public function updateRules(): array
    {
        $data = self::rules['edit'];

        $data['coupon_code'] = ['required',new Custom( function($attribute, $value) {
             if($this->model
                    ->where($attribute, $value)
                    ->where(self::primary_key,'<>',$this->id)
                        ->count() > 0
                ) {
                return false;
            }
            return true;
        })];

        return $data;
    }

    public function getAll()
    {
        return $this->model->get();
    }

    public function get(int $id)
    {
        return $this->model->find($id);
    }

    public function getByCode(string $code)
    {
        return $this->model->where(['coupon_code' => $code])->first();
    }

    public function getCodeById(int $id)
    {
        $d = $this->model->find($id);
        return $d->coupon_code ?? '';
    }
    

    public function setId(int $id)
    {
        $this->id = $id;
    }
}
