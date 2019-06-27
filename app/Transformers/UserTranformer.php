<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTranformer extends TransformerAbstract
{
    public function transform(User $user) {
        return [
            'id'  => $user->id,
            'sex' => $user->sex,
            'email' => $user->email,
            'phone' => empty($user->phone) ? $user->phone : substr_replace($user->phone, "******", 3, 6),
            'avatar' => $user->avatar,
            'campus' => $user->campus,
            'college' => $user->college,
            'username' => $user->username,
            'state' => $user->state,
            'is_check' => $user->is_check,
            'is_manager' => $user->is_manager,
            'created_at' => (string) $user->created_at->format('Y-m-d'),
            'updated_at' => (string) $user->updated_at->format('Y-m-d'),
            'introduction' => $user->introduction,
        ];
    }
}