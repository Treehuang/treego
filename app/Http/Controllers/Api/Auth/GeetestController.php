<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Routing\Controller;
use Germey\Geetest\GeetestCaptcha;

class GeetestController extends Controller
{
    use GeetestCaptcha;

    public function __construct()
    {
        $version = app()::VERSION;
        if (! preg_match('/5\.1.*/', $version, $matches)) {
            $this->middleware('api');
        }
    }
}
