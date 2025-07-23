<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Subscription extends Model
{
    //
    protected $guarded = [];
    protected $appends = ['start_at_formatted', 'end_at_formatted'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getStartAtFormattedAttribute()
    {
        return $this->start_at ? Carbon::parse($this->start_at)->format('F j, Y') : null;
    }

    public function getEndAtFormattedAttribute()
    {
        return $this->end_at ? Carbon::parse($this->end_at)->format('F j, Y') : null;
    }
}
