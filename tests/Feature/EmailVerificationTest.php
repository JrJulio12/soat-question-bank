<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_notice_is_shown_for_unverified_users(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/email/verify');

        $response->assertStatus(200);
        $response->assertViewIs('auth.verify');
    }

    public function test_email_can_be_verified(): void
    {
        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect(route('home'));
    }

    public function test_verification_email_can_be_resent(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->post('/email/resend');

        $response->assertSessionHas('resent', true);
    }
}
