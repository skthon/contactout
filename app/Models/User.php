<?php

namespace App\Models;

use App\Traits\HasUUID;
use App\Models\Referral;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
     * Get all the referral invitations for this user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function referrals(): HasMany
    {
      return $this->hasMany(Referral::class, 'referrer_uuid', 'uuid');
    }

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
