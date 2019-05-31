<?php

namespace App\Services\Sync;

use App\Repository\InfusionsoftSyncRepository;
use App\Repository\UsersRepository;

Class Data
{       
    const PENDING = 0;
    const COMPLETED = 1;
    const PROGRESS = 2;

    public function __construct()
    {
        $this->model = new InfusionsoftSyncRepository;    
        $this->user = new UsersRepository;    
    }

    public function store($field, $oldValue, $newValue, $contacts, $status, $group = '', $adminId)
    {
        $this->model->store(array(
            'field' => $field,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'status' => $status,
            'group' => $group,
            'contacts_updated' => '',
            'admin_id' => $adminId
        ));
    }

    public function getPending()
    {
        return $this->model->getPending();
    }

    public function get(int $id)
    {
        return $this->model->get($id);
    }

    public function getPendingByField($field)
    {
        return $this->model->getPendingByField($field);
    }

    public function getUserId($contactId)
    {
        return $this->user->getUserIdByContactId($contactId);
    }

    public function completed(int $id)
    {
        $this->model->completed($id);
    }

    public function progress(int $id)
    {
        $this->model->progress($id);
    }

    public function updatedContact(int $id, $contact)
    {
        $this->model->updatedContact($id, $contact);
    }
    
}
