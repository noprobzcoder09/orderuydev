<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Users;

class LoginTest extends TestCase
{

    private $testUser;

    protected function setUp() {
        parent::setUp(); 
        
        $this->testUser = Users::firstOrCreate(['email' => 'noprobz09@gmail.com'], [
            'name' => 'Noel Balaba',
            'email' => 'noprobz09@gmail.com',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm',//secret
            'verification' => '',
            'role' => 'customer',
            'active' => 'Active'
        ]);
    }


    /**
     * Test on user login
     *
     * @return void
     */    
    public function testLogin()
    {
        $credentials = [
            'email' => $this->testUser->email,
            'password' => 'secret'
        ];

        $response = $this->call('POST', 'auth/login', $credentials);
        $response->assertStatus(200)->assertJson([
            'success' => true
        ]);
    }


    /**
     * Test on resetting password
     *
     * @return void
     */    
    public function testResetPassword(){
        $email = [
            'email' => $this->testUser->email
        ];

        $response = $this->call('POST', 'auth/sendresetpassword', $email);
        $response->assertStatus(200)->assertJson([
            'success' => true
        ]);
    }
}
