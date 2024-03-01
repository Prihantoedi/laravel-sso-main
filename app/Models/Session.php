<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user', 'token_access', 'token_refresh', 'token_csrf', 'expires_at'
    ];

    protected $hidden = [
        'id_user'
    ];
}

