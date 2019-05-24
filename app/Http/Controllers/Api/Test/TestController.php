<?php

namespace App\Http\Controllers\Api\Test;

use \Cache;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function login(Request $request) {

        Auth::guard('api')->invalidate();;


        return response(['data' => 'success']);

//        $test = Cache::has('expires');
//
//        $expires = (strtotime(date('Y-m-d', strtotime('+1 day')))-time())/60;
//        if (!Cache::has('expires')) {
//            Cache::put('expires', $expires, now()->addMinutes(1));
//        }
        //$data = Cache::has('192.168.1.112');
//        for($i=0; $i<100; $i++){
//            Cache::forget('192.168.1.112');
//        }
        //Cache::put('d', 'test', 1);
        //Cache::forget('1921681112');
        $key = $request->account;
        $data = Cache::get('15626114758');
        //$data = number_format((strtotime(date('Y-m-d', strtotime('+1 day')))-time())/60, 15);
        //return response()->json(['key' => $data]);

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
