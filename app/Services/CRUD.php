<?php

namespace App\Services;

use App\Services\Validator;
use \App\Services\CRUDInterface;

use Request;
use DB;

Class CRUD implements CRUDInterface
{	
	public function __construct(CRUDInterface  $repository)
	{
		$this->repository = new $repository;
        $this->validator = new Validator;
	}

	public function store(array $data): array
    {	 
        DB::beginTransaction();
        try
        {
           $this->validator->validate($data, $this->repository->storeRules());

            $response['status'] = 200;

            $response['success'] = $this->validator->isValid;

            if ($this->validator->isValid)  
            {
                $response['message'] = $this->repository->successSavedMessage;

                $this->model = $this->repository->store($data);

                if (method_exists($this->repository, 'getId')) {
                    $response['id'] = $this->repository->getId();
                }

                DB::commit();

                if (method_exists($this->repository, 'custom')) {
                    $this->repository->custom();
                }
                
            } 
            else $response['message'] = $this->validator->filterError($this->validator->messages);

            return $response; 
        }

        catch (\Exception $e) {
            DB::rollback();
            return [
                'message' => $e->getMessage(),
                'success' => false
            ];
        }
    	
    }

    public function delete(int $id): array
    {   
        $response['status'] = 200;
        $response['success'] = false;

        if ($response['success'] = $this->repository->delete($id)[0]) {
            $response['message'] = $this->repository->successDeletedMessage;            
        } 

        else $response['message'] = $this->repository->errorDeleteMessage;

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

    
    public function storeFile(int $id, $file, string $destination): string
    {   
        if ($file->isValid()) {
            $file = $file->store($destination);
            if(!empty($file)) {
                return $this->repository->storeFile($id, $file);
            }
        }
        return '';
    }

    public function verify(string $value): string
    {   
        if ($this->repository->verify($value)) {
            return 'false';
        }
        return 'true';
    }

    public function disabled(int $id, bool $disabled)
    {
        return $this->repository->disabled($id, $disabled);
    }
    
    public function search(): array
    {

    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function get(int $id)
    {
        return $this->repository->get($id);
    }

    public function setId($id) {
        $this->repository->id = $id;
    }

}
