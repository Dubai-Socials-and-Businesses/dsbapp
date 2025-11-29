<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;
    protected $table = 'photos';
    protected $fillable = [
        'title',
        'image',
        'gallery_id',
    ];

    public $hidden = ['created_at','updated_at'];
}
