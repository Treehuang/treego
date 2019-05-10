<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Controller;

use App\Http\Requests\Api\GeetRequest;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    public function register(GeetRequest $request)
    {
        return $this->response->array(['code' => 200, 'message' => 'success', 'data' => $request->all()]);
    }
}
