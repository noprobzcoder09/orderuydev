<?php

namespace App\Services\Cutover\Data;

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

    public function getUsersNotCancelled()
    {
        return $this->model->whereNotIn('status', array(
            'cancelled'
        ))->orderBy('user_id','asc');
    }

     public function getCancelledUsers()
    {
        return $this->model->whereIn('status', array(
            'cancelled'
        ))->orderBy('user_id','asc');
    }

    public function getUsers()
    {
        return $this->model->orderBy('user_id','asc');
    }

    public function getUser(int $userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }

}
