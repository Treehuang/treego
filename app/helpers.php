<?php
use Carbon\Carbon;
use Illuminate\Http\Request;

// 登录次数限制的过期时间
function loginExpiresTime(Request $request){
    $ip  = $request->getClientIp();
    $key = str_replace('.', '', $ip);
    $expires = number_format((strtotime(date('Y-m-d', strtotime('+1 day')))-time())/60, 15);

    if (!Cache::has($key)) {
        Cache::put($key, $expires, Carbon::tomorrow());
    }

    return Cache::get($key);
}
