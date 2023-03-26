<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Memo extends Model
{
    // ユーザーを指定
    public function scopeUser($query)
    {
        return $query->where('user_id', Auth::id());
    }

    // 有効なものを指定
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }
}
