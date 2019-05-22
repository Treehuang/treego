<?php

namespace App\Http\Controllers\Api\Test;

use \Cache;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function login() {

        $test = Cache::has('expires');

        $expires = (strtotime(date('Y-m-d', strtotime('+1 day')))-time())/60;
        if (!Cache::has('expires')) {
            Cache::put('expires', $expires, now()->addMinutes(1));
        }

        return response()->json(['存在expires' => $test, 'data' => Cache::get('expires')]);

//        $user = User::first();
//
//        $token = auth('api')->login($user);
//
//        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'data' => [
                'access_token' =>   'Bearer '. $token,
                'expires_in'   =>   \Auth::guard('api')->factory()->getTTL() * 60
            ]
        ], 200);
    }

    public function me(Request $request){
        //return response()->json(auth('api')->user());
        return response()->json(['data' => 1], 200);
    }

    public function refresh(Request $request){
        return $this->response()->array(['data' => 1]);
    }
}
