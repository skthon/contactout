<?php

namespace App\Models;

use App\Models\User;
use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Referral extends Model
{
    use HasFactory;
    use HasUUID;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'referred_email',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * The attributes that should be appended.
     *
     * @var array<string, string>
     */
    protected $appends = [
        'referral_status',
    ];

    /**
     * Get the user who has sent the invitation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_uuid', 'uuid');
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getReferralStatusAttribute()
    {
        return $this->status ? "Invitation Accepted" : "Invitation Sent";
    }
}
