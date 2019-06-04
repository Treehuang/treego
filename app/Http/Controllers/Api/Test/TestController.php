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
        $user = User::find(2);

        $bind_data = ['emailCode' => 123456];
        $template = new SendCloudTemplate('email_code', $bind_data);
        Mail::raw($template, function ($message) use ($user) {
            $message->from('treehuang@163.com', 'treeGo');
            $message->to($user->email)->cc('treehuang@163.com');
        });
//        try {
//
//        }catch (\Exception $exception) {
//            return $this->response->errorInternal('邮件发送异常');
//        }

        return response()->json(['data' => 123]);
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
