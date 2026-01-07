<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $table = 'incidents';

    protected $fillable = [
        'incident_type',
        'description',
        'location',
        'incident_date',
        'evidence',
        'status',
        'reported_by',
    ];

    /**
     * Relationship: Incident belongs to a User
     */
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
