<?php

namespace App\Services;

use App\Services\Validator;
use \App\Services\CRUDInterface;

use Request;

Class MetaServices
{	
	public function __construct(CRUDInterface  $repository)
	{
		$this->repository = new $repository;
        $this->validator = new Validator;
	}

    public function searchField(string $name): array
    {
        $data = [];
        foreach($this->repository->searchField($name) as $row) {
            $data[] = [
                'id'    => $row->id,
                'text'  => $row->meta_key
            ];
        }
        return [
            'items' => $data,
        ];
    }

    public function store(array $data): array
    {   
        $rules = $this->repository->storeRules();
        
        if ($data['create_new'] == 0) {
            $rules = $this->repository->storeRulesMetaKey();

            $keyData = $this->repository->getByKey($data['search_field']);
            $data['meta_key'] = $keyData->meta_key;
            $data['meta_value'] = $keyData->meta_value;
        }  

        $this->validator->validate($data, $rules);

        $response['status'] = 200;

        $response['success'] = $this->validator->isValid;

        if ($this->validator->isValid)  
        {
            $response['message'] = $this->repository->successSavedMessage;
            if ($data['create_new'] == 0) { 
                $this->repository->storeKey($data);
            } else {
                $this->repository->store($data);
            }
        }

        else $response['message'] = $this->validator->filterError($this->validator->messages);

        return $response;
    }

    public function update(array $data): array
    {   
        $this->validator->validate($data, $this->repository->updateRules());

        $response['status'] = 200;

        $response['success'] = $this->validator->isValid;

        if ($this->validator->isValid)  
        {
            $response['message'] = $this->repository->successUpdatedMessage;

            $this->repository->update($data);
        }

        else $response['message'] = $this->validator->filterError($this->validator->messages);

        return $response;
    }

    public function edit(int $id): array
    {
        return [
            'status'    => 200,
            'data'      => $this->repository->getByKey($id)
        ];
    }

}
