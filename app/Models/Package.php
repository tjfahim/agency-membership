<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    //
    protected $guarded = [];

    public const DURATION_MAP = [
        'daily' => 1,
        'weekly' => 7,
        'monthly' => 30,
        'yearly' => 365,
    ];
    public function softwares():BelongsToMany
    {
        return $this->belongsToMany(Software::class,'package_software');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
