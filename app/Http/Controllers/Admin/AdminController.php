<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Referral;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function referrals()
    {
        $referrals = Referral::with(['referrer'])->paginate(10);
        return view('admin.referrals', compact('referrals'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function users()
    {
        $users = User::paginate(10);
        return view('admin.users', compact('users'));
    }
}
