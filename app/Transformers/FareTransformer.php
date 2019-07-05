<?php

namespace App\Transformers;

use App\Models\Ticket;
use League\Fractal\TransformerAbstract;

class FareTransformer extends TransformerAbstract {
    public function transform(Ticket $ticket) {
        return [
            'id' => $ticket->id,
            'state' => $ticket->state,
            'date' => $ticket->date,
            'price' => $ticket->price,
            'leader' => $ticket->leader,
            'from_time' => $ticket->from_time,
            'arrival_time' => $ticket->arrival_time,
            'start_place' => $ticket->start_place,
        ];
    }
}