<?php

namespace App\Http\Controllers\Api\Test;

use \Cache;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Naux\Mail\SendCloudTemplate;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function login(Request $request) {
        $ip  = $request->getClientIp();
        $key = str_replace('.', '', $ip);
        $expires = strtotime(date('Y-m-d', strtotime('+1 day')))-time()/60;
        $data = Cache::get($key);
        var_dump($expires);exit;
        return response()->json(['data' => $data]);
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
        return response()->json(['user' => Auth::guard('api')->user()]);
    }

    public function refresh(Request $request){
        return $this->response()->array(['data' => 1]);
    }
}
