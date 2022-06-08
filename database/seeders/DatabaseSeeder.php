<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Referral;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $referrals = Referral::factory(100)
            ->create()
            ->each(function ($referral) use ($user) {
                $referral->referrer()->associate($user);
                $referral->save();
            });
    }
}
