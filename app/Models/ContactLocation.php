<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactLocation extends Model
{
    protected $fillable = [
        'label',
        'name',
        'address',
        'map_query',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];
}