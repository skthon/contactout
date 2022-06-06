<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ReferralInvitation;
use Illuminate\Support\Facades\Validator;
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
    public function submitReferral(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'emails'   => 'required|array|min:1|max:10',
            'emails.*' => 'required|email|distinct|max:64',
        ]);

        if ($validator->fails()){
            return response()->json([
                "status" => "error",
                "errors" => $validator->errors()
            ]);
        }

        foreach ($request->get('emails') as $email) {
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
        }

        return response()->json([
            "status" => "success"
        ]);
    }
}
