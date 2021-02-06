<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerifyEmailTest extends TestCase
{
    use RefreshDatabase;

    public $username = "testing";
    public $password = "password";
    public $name = "Testing";
    public $email = "testing@gmail.com";
    public $code = 123456;
    public $user = [];

    public function execute(): \Illuminate\Testing\TestResponse
    {
        $this->user = User::factory()->create([
            'username' => "testing",
            'name' => "Testing",
            'email' => "testing@gmail.com",
            'verification_code' => 123456,
        ]);

        return $this->post('/api/verify/email', ["code" => $this->code, "email" => $this->email ]);
    }

    /** @test */
    public function verify_email_returns_token_and_user_with_valid_inputs()
    {
        $response = $this->execute();

        $response->assertOk();
        $response->assertCookie("token");
        $response->assertJson(collect($this->user)->only(["id", "username", "image_url", "name"])->all());
    }

    /** @test */
    public function verify_email_returns_400_with_invalid_inputs()
    {
        $this->code = 123;
        $this->email = "invalid";

        $response = $this->execute();

        $response->assertStatus(400);
    }

    /** @test */
    public function verify_email_returns_400_with_incorrect_verification_code()
    {
        $this->code = 111111;

        $response = $this->execute();

        $response->assertStatus(400);
    }
}
