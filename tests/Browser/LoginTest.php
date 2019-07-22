<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_view_the_login_page()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->assertSee('Login');
        });
    }

     /** @test */
    public function a_user_can_click_the_login_page()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->clickLink('Login')
                    ->assertSee('Login');
        });
    }

    /** @test */
    public function a_user_cannot_login_with_wrong_credentials()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email', 'nouser@nouser.com')
                    ->type('password', 'nopassword')
                    // ->click('button[type="submit"')
                    ->click('@login-button')
                    ->assertSee('credentials do not match');
        });
    }

    /** @test */
    public function a_user_can_login_with_correct_credentials()
    {
        $user = User::create([
            'name' => 'User',
            'email' => 'user@user.com',
            'password' => bcrypt('password'),
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email', 'user@user.com')
                    ->type('password', 'password')
                    // ->click('button[type="submit"')
                    ->click('@login-button')
                    ->assertSee('are logged in');
        });
    }

    /** @test */
    public function the_modal_shows_correctly()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->click('.modal-button')
                    // ->pause(1000)
                    ->waitFor('.v--modal')
                    ->assertSee('hello, world!');
        });
    }
}
