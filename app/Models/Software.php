<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Software extends Model
{
    //
    use HasFactory;
    protected $guarded = [];
    public function packages():BelongsToMany
    {
        return $this->belongsToMany(Package::class,'package_software');
    }
}
