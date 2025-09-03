<?php

namespace ShreejaDigital\Settings\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = ['key', 'value', 'encrypted'];

    protected $casts = [
        'encrypted' => 'bool',
    ];
}
