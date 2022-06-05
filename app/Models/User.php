<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasUUID;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin'          => 'boolean',
    ];

    /**
     * The attributes that should be appended.
     *
     * @var array<string, string>
     */
    protected $appends = [
        'referral_code',
    ];

    /**
     * Get the referral code of the user
     *
     * @return string
     */
    public function getReferralCodeAttribute()
    {
        return md5("contactout:referral:" . $this->email);
    }

    /**
     * Get the user with the given referral code
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $referralCode
     */
    public function scopeWithReferralCode(Builder $query, string $referralCode)
    {
        return $query->where(DB::raw("MD5(CONCAT('contactout:referral:', `email`))"), $referralCode);
    }
}
