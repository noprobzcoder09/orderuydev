<?php

namespace App\Services;

use App\Repository\UsersRepository;
use App\Models\Users;
use Request;
use Hash;

class Password
{      
    public function __construct()
    {
        $this->repository = new UsersRepository;        
    }

    public function update()
    {
        try 
        {   
            $validate = $this->validate();
            if (!$validate['success']) {
                return $validate;
            }
            
            $update = $this->repository->updatePassword(auth()->user()->id, Request::get('password'));            
            if ($update) {

                //send notification email
                \Mail::to(auth()->user()->email)
                    ->queue(
                        new \App\Mail\PasswordResetNotification(auth()->user())
                    );

                return ['success' => true, 'message' => 'Reset password successfully.', 'status' => 200];
            }            
        }
        catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage(), 'status' => 500];
        }
    }

    private function validate()
    {   
        if (Request::get('password') != Request::get('confirm_password')) {
            return ['success' => false, 'message' => 'New Password and Confirm password did not matched!', 'status' => 200];
        }

        if (!Hash::check(Request::get('current_password'), auth()->user()->password)) {
            return ['success' => false, 'message' => 'Invalid current password!', 'status' => 200];
        }

        return ['success' => true];
    }
}

