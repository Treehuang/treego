<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTicket extends Model
{
    protected $table = 'user_tickets';

    protected $fillable = [
        'user_id', 'ticket_id', 'leader', 'start_place', 'terminus', 'from_time', 'arrival_time', 'data', 'ticket_num', 'state', 'price'
    ];
}
