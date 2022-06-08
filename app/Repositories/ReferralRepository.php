<?php

namespace App\Repositories;

use App\Models\Referral;
use App\Abstracts\Repositories\EloquentModelRepository;

class ReferralRepository extends EloquentModelRepository
{
    /**
     * Return Eloquent Model covered by this EloquentModelRepository.
     *
     * @return string
     */
    public static function getModel(): string
    {
        return Referral::class;
    }
}
