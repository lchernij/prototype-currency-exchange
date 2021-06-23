<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCurrency extends Model
{
    use HasFactory, Uuid;

    const ACTION_LESS = 'less';
    const ACTION_MORE = 'more';

    protected $fillable = [
        'value_trigger',
        'value_action'
    ];

    public function scopeUser($query, $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function currency()
    {
        return $this->hasOne(Currency::class, 'id', 'currency_id');
    }
}
