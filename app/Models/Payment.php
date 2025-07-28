<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Payment extends Model
{
    //
    protected $guarded = [];
    protected $appends = ['created_at_format'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class,'subscription_id', 'id');
    }
    
    public function getCreatedAtFormatAttribute(){
          return $this->created_at?->format('Y F j');
    }
}
