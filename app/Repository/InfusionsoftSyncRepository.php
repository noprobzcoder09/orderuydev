<?php

namespace App\Repository;

use App\Models\InfusionsoftSync;

Class InfusionsoftSyncRepository
{   
    public $id;

    const PENDING = 0;
    const COMPLETED = 1;
    const PROGRESS = 2;

    public function __construct() 
    {
        $this->model = new InfusionsoftSync;
    }

    public function store(array $data)
    {   
        $this->model->create($data);   
    }

    public function delete(int $id): array
    {
        return [$this->model->where(self::primary_key, $id)->delete()];
    }

    public function getPending()
    {
        return $this->model
        ->whereIn('status', [self::PENDING, self::PROGRESS])
        ->get();
    }

    public function getPendingByField($field)
    {
        if (is_array($field)) {
            $this->model->whereIn('field', $field);
        } else {
            $this->model->where('field', $field);
        }
        return $this->model
        ->orderBy('id','asc')
        ->whereIn('status', [self::PENDING, self::PROGRESS])
            ->get();
    }
    

    public function get(int $id)
    {
        return $this->model->find($id);
    }

    public function updatedContact(int $id, string $contact)
    {
        $model = $this->model->find($id);
        $model->contacts_updated = (int)$model->contacts_updated + 1;
        $model->save();
    }

    public function completed(int $id)
    {
        $model = $this->model->find($id);
        $model->status = self::COMPLETED;
        $model->finished_at = date('Y-m-d H:i:s');
        $model->save();
    }

    public function progress(int $id)
    {
        $model = $this->model->find($id);
        $model->status = self::PROGRESS;
        if (is_null($model->started_at)) {
            $model->started_at = date('Y-m-d H:i:s');
            $model->save();
        }
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }
}
