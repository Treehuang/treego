<?php

namespace App\Transformers;

use App\Models\UserTicket;
use League\Fractal\TransformerAbstract;

class UserFareTransformer extends TransformerAbstract {
    public function transform(UserTicket $userTicket) {
        return [
            'id' => $userTicket->id,
            'ticket_id' => $userTicket->ticket_id,
            'start_place' => $userTicket->start_place,
            'terminus' => $userTicket->terminus,
            'username' => $userTicket->username,
            'ticket_num' => $userTicket->ticket_num,
            'buytime' => (string)$userTicket->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}