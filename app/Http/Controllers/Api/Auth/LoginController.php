<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Api\GeetRequest;
use App\Http\Controllers\Api\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function login(GeetRequest $request){
        sleep(5);
        return 0;
    }

}
