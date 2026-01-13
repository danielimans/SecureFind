<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsDateTime;

class Incident extends Model
{
    use HasFactory;

    protected $table = 'incidents';

    protected $fillable = [
        'incident_type',
        'custom_incident_type',
        'description',
        'location',
        'incident_date',
        'evidence',
        'status',
        'reported_by',
    ];

    /**
     * Cast incident_date as datetime
     * This ensures Laravel handles the timezone correctly
     */
    protected $casts = [
        'incident_date' => 'datetime',
    ];

    /**
     * Relationship: Incident belongs to a User
     */
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}