<?php

namespace App\Http\Controllers\Api\Auth;

use Auth;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Controllers\Api\Controller;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class LoginController extends Controller
{
    public function login(LoginRequest $request){
        $account = $request->account;

        filter_var($account, FILTER_VALIDATE_EMAIL) ?
            $credentials['email'] = $account :
            $credentials['phone'] = $account ;

        $credentials['password']  = $request->password;

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['errors' => [ 'account' => '账号或密码错误'] ], 401);
        }

        return response()->json([
            'access_token' =>  'Bearer '. $token,
            'expires_in'   =>  Auth::guard('api')->factory()->getTTL() * 60
        ], 200);
    }


    public function logout() {
        try{
            Auth::guard('api')->logout();
        }catch (TokenExpiredException $exception) {
            return $this->response->noContent();
        }

        return $this->response->noContent();
    }

    public function tryLogin() {
        return $this->response->noContent();
    }
}
