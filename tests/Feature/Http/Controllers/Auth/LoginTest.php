<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public $username = "testing";
    public $password = "password";
    public $name = "Testing";
    public $email = "testing@gmail.com";
    public $user = [];

    public function execute(): \Illuminate\Testing\TestResponse
    {
        $this->user = User::factory()->create([
            'username' => "testing",
            'name' => "Testing",
            'email' => "testing@gmail.com",
        ]);

        return $this->post('/api/login', ["email" => $this->email, "password" => $this->password ]);
    }

    /** @test */
    public function login_returns_token_and_user_with_valid_inputs()
    {
        $response = $this->execute();

        $response->assertOk();
        $response->assertCookie("token");
        $response->assertJson(collect($this->user)->only(["id", "username", "image_url", "name"])->all());
    }

    /** @test */
    public function login_returns_400_with_invalid_inputs()
    {
        $this->email = "";
        $this->password = "1234";

        $response = $this->execute();

        $response->assertStatus(400);
    }

    /** @test */
    public function login_returns_400_with_non_existing_email()
    {
        $this->email = "notfound@gmail.com";

        $response = $this->execute();

        $response->assertStatus(400);
    }

    /** @test */
    public function login_returns_400_with_incorrect_password()
    {
        $this->password = "incorrect";

        $response = $this->execute();

        $response->assertStatus(400);
    }
}
