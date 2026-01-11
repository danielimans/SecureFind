<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

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

    protected $casts = [
        'event_datetime' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that reported this item.
     */
    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'reported_by');
    }

    /**
     * Get the image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            $imageUrl = Storage::disk('public')->url($this->image);
            return $imageUrl;
        }
        return null;
    }
}