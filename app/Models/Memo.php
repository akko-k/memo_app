<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder; //追加
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Memo extends Model
{
    // モデルの初期起動メソッド
    protected static function booted()
    {
        static::addGlobalScope('user_id', function (Builder $builder) {
            $builder->where('user_id', Auth::id());
        });
    }

    // 有効な（論理削除されていない）メモを指定
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }
}
