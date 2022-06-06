<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Referral;
use App\Abstracts\Repositories\EloquentModelRepository;

class UserRepository extends EloquentModelRepository
{
    /**
     * Return Eloquent Model covered by this EloquentModelRepository.
     *
     * @return string
     */
    public static function getModel(): string
    {
        return User::class;
    }

    /**
     * Implement the steps when user is trying to register an account with a referral code
     *
     * @param string $email
     * @param null|string $referralCode
     */
    public static function accountWithReferralCode(string $email, ?string $referralCode)
    {
        // Fetch referrer if referral code exists
        $referrer = (isset($referralCode) && $referralCode != '')
            ? User::withReferralCode($referralCode)->first()
            : null;

        // return if referrer isn't valid
        if (! $referrer) {
            return null;
        }

        // If referral code is valid, then increment credits to the referrer and update referral status
        $referral = Referral::where('referred_email', $email)->first();
        $referral->status = true;
        $referral->save();

        $referralCount = Referral::where('referrer_uuid', $referrer->uuid)
            ->where('status', true)
            ->count();
        $referrer->credits = min(10, $referralCount);
        $referrer->save();
    }
}
