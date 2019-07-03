<?php

namespace App\Transformers;

use App\Models\Certificat;
use League\Fractal\TransformerAbstract;

class CertificatTransformer extends TransformerAbstract
{
    public function transform(Certificat $certificat) {
        return [
            'id' => $certificat->id,
            'user_id' => $certificat->user_id,
            'realname' => $certificat->realname,
            'record' => $certificat->record,
            'certificat' => $certificat->certificat,
            'time' => $certificat->time,
            'check_user' => $certificat->check_user,
            'audit_state' => $certificat->audit_state,
            'audit_time' => (string)$certificat->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}