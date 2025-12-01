<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;
    protected $table = 'galleries';
    protected $fillable = [
        'title',
        'main_image',
        'description',
        'gdate',
    ];

    protected $casts = [
        'gdate' => 'date',
    ];

    public function photos()
    {
        return $this->hasMany(Photo::class, 'gallery_id', 'id');
    }

    public function videos()
    {
        return $this->hasMany(Video::class, 'gallery_id', 'id');
    }

    public function video()
    {
        return $this->hasOne(Video::class, 'gallery_id', 'id');
    }
}
