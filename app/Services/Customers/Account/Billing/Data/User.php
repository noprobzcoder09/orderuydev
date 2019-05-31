<?php

namespace App\Services\Customers\Account\Billing\Data;

use App\Services\Customers\Account\InfusionsoftCustomer;
use App\Models\UserDetails as Model;
use DB;

Class User
{   
    public function __construct()
    {
        $this->model = new Model;
    }

    public function updateBillingAttempt($userId, string $status = '')
    {   
        $userId = !is_array($userId) ? array($userId) : $userId;
        foreach($userId as $id) {
            $id = $this->model->getDetailsIdByUser($id);
            $model = $this->model->find($id);
            $model->billing_attempt = $model->billing_attempt+1;
            $model->billing_attempt_desc = $status;
            $model->save();
        }
    }

    public function resetBillingAttempt($userId)
    {   
        $userId = !is_array($userId) ? array($userId) : $userId;
        foreach($userId as $id) {
            $id = $this->model->getDetailsIdByUser($id);
            $model = $this->model->find($id);
            $model->billing_attempt = 0;
            $model->billing_attempt_desc = '';
            $model->save();
        }
    }

    public function get(int $userId)
    {
        return $this->model
        ->select([
            'user_details.user_id',
            'card_ids','default_card',
            'ins_contact_id','delivery_notes'
        ])
        ->where('user_details.user_id', $userId)
        ->first();
    }

}
