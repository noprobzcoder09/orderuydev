<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\Users;
use App\Services\CRUD;

class UserTest extends TestCase
{
    use WithFaker;
    use WithoutMiddleware;
   
    private $testUser;

    protected function setUp() {
        parent::setUp(); 

        $this->testUser = Users::firstOrCreate(['email' => 'unittest@gmail.com'], [
            'name' => 'unittest',
            'email' => 'unittest@gmail.com',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm',
            'verification' => '',
            'role' => 'customer',
            'active' => 'Active'
        ]);
        
    }

    /**
     * Test on creating a user
     *
     * @return void
     */    
    public function testCreateUser()
    {
        
        $user = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'secret',
            'verification' => '',
            'role' => 'customer',
            'active' => 'Active'
        ];

        $response = $this->call('PUT', 'users/create', $user);
        $response->assertStatus(200)->assertJson([
            'success' => true,
        ]);
    }

    /**
     * Test on checking email if exists
     *
     * @return void
     */  
    public function testVerifyEmail(){
        $email = Users::find(1)->email;

        $response = $this->call('POST', 'users/verify-email', ['email' => $email]);
        $response->assertSeeText('false');
    }

    /**
     * Test on updating test user
     *
     * @return void
     */  
    public function testUpdateUser(){
        
        $updateUser = [
            'id' => $this->testUser->id,
            'name' => 'Unit Test Name',
            'role' => 'Customer',
        ];
      
        $response = $this->call('PATCH', 'users/update', $updateUser);

        $response->assertStatus(200)->assertJson([
             'success' => true,
        ]);
    }

    /**
     * Test on Edit User View if it will load
     *
     * @return void
     */  
    public function testViewEditUser(){
        $user = Users::find(1);
        $response = $this->call('GET', 'users/edit/'.$user->id);

        $response->assertSee('User');
    }

     /**
     * Test on All Users View if it will load
     *
     * @return void
     */  
    public function testViewAllUsers(){
        
        $response = $this->call('GET', 'users/all-users');
        $response->assertSee('Masterlist');
    }

   
}
