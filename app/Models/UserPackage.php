<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPackage extends Model
{
    use HasFactory;
    protected $table = 'user_packages';
    protected $primaryKey = 'upid';
    protected $fillable = [
        'user_id',
        'package_id',
        'is_active',
    ];
}
