<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ReferralInvitation;
use App\Http\Requests\ReferralInputRequest;
use Illuminate\Support\Facades\Notification;

class ReferralController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function referrals(Request $request)
    {
        return view('referrals');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function submitReferral(ReferralInputRequest $request)
    {
        collect($request->get('emails'))->each(function ($email) {
            // Save referral to db
            $referral = Referral::create([
                'referred_email' => $email,
                'status' => false
            ]);
            $referral->referrer()->associate(Auth::user());
            $referral->save();

            // Send an invitation
            Notification::route('mail', $referral->referred_email)->notify(
                new ReferralInvitation($referral)
            );
        });

        return response()->json(["status" => "ok"]);
    }
}
