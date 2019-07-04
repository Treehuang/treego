<?php

namespace App\Http\Controllers\Api\Management;

use \DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class FareController extends Controller
{
    // 管理系统车票系统 -- 添加车票
    public function addFares(Request $request) {
        $faresList = [];
        foreach ($request->faresList as $fare) {
            $faresList[] = [
                'date'   => $fare['date'],
                'price'  => $fare['price'],
                'leader' => $fare['leader'],
                'from_time' => $fare['from_time'],
                'start_place'  => $fare['start_place'],
                'arrival_time' => $fare['arrival_time'],
            ];
        };

        DB::table('tickets')->insert($faresList);

        return $this->response->created();
    }
}
