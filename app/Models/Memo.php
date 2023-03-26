<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    public function scopeUser($query)
    {
        return $query->where('user_id', \Auth::id());
    }

    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }
}
