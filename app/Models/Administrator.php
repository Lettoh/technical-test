<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrator extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'nickname',
        'email',
        'password',
    ];
}
