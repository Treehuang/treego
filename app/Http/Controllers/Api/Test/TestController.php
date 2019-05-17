<?php

namespace App\Http\Controllers\Api\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function login() {
        $credentials = request(['phone', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
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
        return $this->response()->array(['data' => 1]);
    }

    public function refresh(Request $request){
        return $this->response()->array(['data' => 1]);
    }
}
