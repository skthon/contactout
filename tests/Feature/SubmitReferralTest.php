<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubmitReferralTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_submit_referral_returns_an_error_if_existing_email_is_invited()
    {
        $oldUser = \App\Models\User::factory()->create();
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->post('/submit-referral', [
            'emails' => [
                $oldUser->email,
                $this->faker->unique()->safeEmail,
                $this->faker->unique()->safeEmail,
            ]
        ]);
        $data = collect(json_decode($response->getContent(), true));
        $this->assertEquals("error", $data->get('status'));
        $this->assertEquals(
            "{$oldUser->email} is/are either already registered or already invited",
            $data->get('message')
        );
    }

    public function test_submit_referral_returns_an_error_if_existing_referred_email_is_invited()
    {
        $user = \App\Models\User::factory()->create();
        $referral = \App\Models\Referral::factory()->create();

        $response = $this->actingAs($user)->post('/submit-referral', [
            'emails' => [
                $referral->referred_email,
                $this->faker->unique()->safeEmail,
                $this->faker->unique()->safeEmail,
            ]
        ]);
        $data = collect(json_decode($response->getContent(), true));
        $this->assertEquals("error", $data->get('status'));
        $this->assertEquals(
            "{$referral->referred_email} is/are either already registered or already invited",
            $data->get('message')
        );
    }

    public function test_submit_referral_is_successful()
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->post('/submit-referral', [
            'emails' => [
                $this->faker->unique()->safeEmail,
                $this->faker->unique()->safeEmail,
            ]
        ]);
        $data = collect(json_decode($response->getContent(), true));
        $this->assertEquals("ok", $data->get('status'));
    }
}
