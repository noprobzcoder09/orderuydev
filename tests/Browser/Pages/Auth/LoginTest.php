<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends DuskTestCase
{
    /**
     * Check Login view
     *
     * @return void
     */
    public function testCheckView()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->assertSee('LOGIN NOW');
        });
    }


    public function testInvalidLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email', 'noprobz09@gmail.com')
                    ->type('password', 'secret123')
                    ->press('LOGIN NOW')
                    ->assertSee('These credentials do not match our records.');
        });
    }


    public function testLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email', 'noprobz09@gmail.com')
                    ->type('password', 'secret')
                    ->press('LOGIN NOW')
                    ->pause(9000)
                    ->assertSee('Dashboard');
        });
    }


    public function testCheckResetPassword()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->click('#btnforgotpassword')
                    ->pause(9000)
                    ->assertSee('RESET PASSWORD');
        });
    }
}
