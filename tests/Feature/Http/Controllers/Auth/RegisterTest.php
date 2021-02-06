<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public $username = "testing";
    public $password = "password";
    public $name = "Testing";
    public $email = "testing@gmail.com";
    public $user = [];

    public function execute(): \Illuminate\Testing\TestResponse
    {
        return $this->post('/api/register',
        ["email" => $this->email, "password" => $this->password,
        "name" => $this->name, "username" => $this->username ]);
    }

    /** @test */
    public function register_registers_user_with_valid_inputs()
    {
        $response = $this->execute();

        $response->assertCreated();
    }

    /** @test */
    public function register_returns_400_with_invalid_inputs()
    {
        $this->username = "-test=";

        $response = $this->execute();

        $response->assertCreated(400);
    }
}
