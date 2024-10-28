<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'username',
        'email',
        'passwd',
    ];

    protected $hidden = [
        'passwd',
    ];
}
