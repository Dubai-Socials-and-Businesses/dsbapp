<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $table = 'events';
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'description',
        'image',
        'video',
        'tags',
        'status',
        'organizer',
        'start_at',
        'start_date',
        'start_time',
        'end_at',
        'end_date',
        'end_time',
        'location',
        'price',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function attendees()
    {
        return $this->hasMany(EventUser::class, 'event_id','id');
    }
}
