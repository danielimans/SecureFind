<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LostFound extends Model
{
    use HasFactory;

        protected $fillable = [
        'item_name',
        'item_category',
        'item_status',
        'location',
        'event_datetime',
        'description',
        'image',
        'claim_status',
        'reported_by',
    ];
}
