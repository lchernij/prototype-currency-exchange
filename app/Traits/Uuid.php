<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Uuid
{
    /**
     * Creatre a Uuid before object saved in database
     */
    public static function bootUuid()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }
}