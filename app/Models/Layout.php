<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Layout extends Model
{
    use HasFactory;

    protected $guarded = [];


    /**
     * @return HasMany
     */
    public function tiles(): HasMany
    {
        return $this->hasMany(Tile::class,'layout_id','id');
    }

}
