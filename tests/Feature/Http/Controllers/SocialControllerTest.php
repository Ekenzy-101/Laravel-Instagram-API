<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocialControllerTest extends TestCase
{
    use RefreshDatabase;

    public $name = "Testing";
    public $email = "testing@gmail.com";
    public $image_url = "https://testing.com";

    public function execute(): \Illuminate\Testing\TestResponse
    {
        return $this->post('/api/auth/facebook',
        ["email" => $this->email, "name" => $this->name, "image_url" => $this->image_url ]);
    }

    /** @test */
    public function facebook_auth_returns_token_and_user_with_valid_inputs()
    {
        $response = $this->execute();

        $response->assertStatus(200);
        $response->assertCookie("token");
        $response->assertJson([ "image_url" => $this->image_url, "name" => $this->name]);
    }

    /** @test */
    public function facebook_returns_400_with_invalid_inputs()
    {
        $this->email = "invalid email";
        $this->name = "";
        $this->image_url = "invalid url";

        $response = $this->execute();

        $response->assertStatus(400);
    }
}
