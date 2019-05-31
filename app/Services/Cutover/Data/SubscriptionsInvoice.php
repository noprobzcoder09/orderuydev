<?php

namespace App\Services\Cutover\Data;

use App\Models\SubscriptionsInvoice as Model;

Class SubscriptionsInvoice
{      
    public function __construct()
    {
        $this->model = new Model;
    }


    public function isTaken(int $userId, int $invoiceId, int $orderId)
    {
        return $this->model->where([
            'user_id' => $userId, 
            'ins_invoice_id' => $invoiceId,
            'ins_order_id' => $orderId
        ])->count() > 0;
    }

    public function store(array $data)
    {   
        if (!$this->isTaken($data['user_id'], $data['ins_invoice_id'], $data['ins_order_id'])) {
            $model = new Model;
            $model->user_id = $data['user_id'];
            $model->ins_invoice_id = $data['ins_invoice_id'];
            $model->ins_order_id = $data['ins_order_id'];
            $model->status = $data['status'] ?? 'unpaid';
            $model->price = $data['price'];
            return $model->save();
        }

        return $this->model
        ->where([
            'user_id' => $data['user_id'],
            'ins_invoice_id' => $data['ins_invoice_id'],
            'ins_order_id' => $data['ins_order_id'],
        ])
        ->update([
            'status' => $data['status'] ?? 'unpaid',
            'price' => $data['price']
        ]);
    }

    public function updateStatus($invoiceId, string $status)
    {   
        return $this->model
        ->where([
            'ins_invoice_id' => $invoiceId
        ])
        ->update([
            'status' => $status
        ]);
    }

    public function remove($invoiceId)
    {   
        return $this->model
        ->where([
            'ins_invoice_id' => $invoiceId
        ])
        ->delete();
    }

    public function getOrderId($invoiceId)
    {   
        $d = $this->model
        ->where([
            'ins_invoice_id' => $invoiceId
        ])
        ->first();

        return $d->ins_order_id ?? 0;
    }
}
