<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RegistrationTest extends TestCase
{
    /**
     * @return void
     */
    public function test_it_generates_a_unique_pin_when_user_registers()
    {
        $user = User::factory()->make();

        $response = $this->post('register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertStatus(302);

        $firstUser = Auth::user();

        $this->assertNotNull($firstUser->fresh()->verification_pin);
    }
}
