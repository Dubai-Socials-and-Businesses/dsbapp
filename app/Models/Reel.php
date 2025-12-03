<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reel extends Model
{
    use HasFactory;
    protected $table = 'reels';
    protected $fillable = [
        'title',
        'reel_url',
        'gallery_id'
    ];

    public $hidden = ['created_at', 'updated_at'];
}
