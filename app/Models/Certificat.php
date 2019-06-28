<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificat extends Model
{
    protected $table = 'certificat';

    protected $fillable = ['user_id', 'realname', 'certificat', 'record', 'time'];
}
