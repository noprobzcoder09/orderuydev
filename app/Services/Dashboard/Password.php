<?php

namespace App\Services\Dashboard;

use App\Services\Dashboard\Extended\Request;
use App\Services\Dashboard\Extended\User;
use Auth;

class Password
{      
    public function __construct(int $userId)
    {
        $this->id = $userId;
        $this->user = new User($userId);
        $this->request = new Request;
    }

    public function update()
    {
        try 
        {   
            $this->validate();
            $this->user->updatePassword($this->request->getPassword());
            Auth::loginUsingId($this->id);
           
            //send notification email
            \Mail::to(Auth::user()->email)
            ->queue(
                new \App\Mail\PasswordResetNotification(Auth::user())
            );
            return ['success' => true, 'message' => sprintf(__('crud.updated'),'profile')];
        }
        catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function validate()
    {   
        if ($this->request->getConfirmPassword() != $this->request->getPassword()) {
            throw new \Exception("Confirm password did not matched!", 1);
        }
        if (!Auth::attempt(['email' => $this->user->getEmail(), 'password' => $this->request->getCurrentPassword()])) {
            throw new \Exception("Invalid current password.", 1);
        }

        if (Auth::attempt(['email' => $this->user->getEmail(), 'password' => $this->request->getPassword()])) {
            throw new \Exception("Your New password should not be equal to your current password.", 1);
        }
    }
}

