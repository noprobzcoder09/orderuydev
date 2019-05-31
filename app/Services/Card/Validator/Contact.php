<?php

namespace App\Services\Card\Validator;

use App\Services\Validator as Validate;

use App\Services\Card\Contracts\Validator as ValidatorInterface;

class Contact implements ValidatorInterface
{   
    private $isValid = true;
    private $message = '';
    public $update = false;
    private static $exemptedFieldsForUpdate = ['DateCreated'];

    public function __construct($contact, array $data, int $contactId = null)
    {
        $this->data = $data;
        $this->contact = $contact;
        $this->id = $contactId;
    }

    public function validator()
    {   
        $this->validateImportantFields($this->data);
    }

    private function validateImportantFields($contact)
    {
        $important = [];
        $data = [];
        $contactFields = $this->contact->fields;
        if ($this->update) {
            if (empty($this->id)) {
                throw new \Exception("Contact Id is required to update contact.", 1);
                
            }
        }

        foreach($contactFields as $key) {
            if (in_array($key, array_keys(self::$exemptedFieldsForUpdate))) {
                if (empty($contact[$key])) {
                    continue;
                }
            }
            if (!in_array($key, array_keys($contact))) {
                $important[] = $key;
                continue;
            }
            if (empty($contact[$key])) {
                $important[] = $key;
                continue;
            }
            $data[$key] = $contact[$key];
        }
        
        if (!empty($important)) {
            throw new \Exception(__('Contact Required fields are empty '.implode(', ', $important).'.'), 1);
        }

        return $data;
    }

    public function success()
    {
        return $this->isValid;
    }

    public function message()
    {
        return $this->message;
    }
}
