<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';

    protected $fillable = [
        'leader', 'ticket_id', 'start_place', 'from_time', 'arrival_time', 'date', 'ticket_num', 'price'
    ];
}
