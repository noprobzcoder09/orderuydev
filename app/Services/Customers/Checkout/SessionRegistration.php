<?php

namespace App\Services\Customers\Checkout;

use App\Services\Session\Session as SessionStorage;
use \App\Services\Validator;

Class SessionRegistration
{     

    public function __construct()
    {
        $this->session = new SessionStorage('customer-account-registration');
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function setConfirmPassword(string $password)
    {
        $this->confirm_password = $password;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getConfirmPassword() {
        return $this->confirm_password;
    }

    public function store($email, $password, $confirmPassword)
    {   
        $this->setEmail($email);
        $this->setPassword($password);
        $this->setConfirmPassword($confirmPassword);

        $this->validator();

        $this->session->store($this->getUser());
    }

    public function destroy()
    {
        $this->session->destroy();
    }

    public function get()
    {
        return $this->session->get();
    }

    public function getUser()
    {
        return array(
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
            'password_confirmation' => $this->getConfirmPassword()
        );
    }

    private function validator()
    {
        $this->validator = new Validator;

        $this->validator->validate($this->getUser(), [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
        
        if (!$this->validator->isValid()) {
            throw new \Exception($this->validator->filterError($this->validator->getMessage()), 1);
        }
    }

}
