<?php

namespace App\Http\Controllers\Api\Management;

use App\Models\Ticket;
use App\Models\UserTicket;
use App\Transformers\FareTransformer;
use App\Transformers\UserFareTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class StatisController extends Controller
{
    // 管理系统统计中心 -- 获取激活的车票
    public function getActiveFares() {
        $fares = Ticket::where('state', '=', 1)->get();
        return response()->json(['data' => $fares]);
    }

    // 管理系统统计中心 -- 获取每个起始点车票的订购情况
    public function getCountFares() {

        $result = [];

        $fares = Ticket::where('state', '=', 1)->get();
        foreach ($fares as $fare) {
            $ticket_num_sum = UserTicket::where([
                ['state', '=', 1],
                ['ticket_id', '=', $fare['id']]
            ])->sum('ticket_num');

            $fare['ticket_num_sum'] = $ticket_num_sum;

            $result[] = $fare;
        }

        return response()->json(['data' => $result]);
    }

    // 管理系统统计中心 -- 获取所有用户订购的车票(7条)
    public function getAllBuyFares() {
        $fares = UserTicket::where('state', '=', 1)->paginate(7);
        return $this->response->paginator($fares, new UserFareTransformer());
    }

    // 管理系统统计中心 -- 获取所有用户订购的车票(特定页)
    public function getCurrentBuyFares(Request $request) {
        $fares = UserTicket::where('state', '=', 1)->paginate(7, ['*'], 'page', $request->current_page);
        return $this->response->paginator($fares, new UserFareTransformer());
    }

    // 管理系统统计中心 -- 搜索
    public function getSearchFares(Request $request) {
        $fares = UserTicket::where([
            ['state', '=', 1],
            ['ticket_id', '=', $request->ticket_id],
        ])->paginate(7);
        return $this->response->paginator($fares, new UserFareTransformer());
    }

    // 管理系统统计中心 -- 搜索(特定页)
    public function getCurrentSearchFares(Request $request) {
        $fares = UserTicket::where([
            ['state', '=', 1],
            ['ticket_id', '=', $request->ticket_id],
        ])->paginate(7, ['*'], 'page', $request->current_page);
        return $this->response->paginator($fares, new UserFareTransformer());
    }

    // 管理系统统计中心 -- execl数据（全部）
    public function getExeclFares() {
        $fares = UserTicket::where('state', '=', 1)->get();
        return response()->json(['data' => $fares]);
    }

    // 管理系统统计中心 -- execl数据（特定）
    public function getExeclFare(Request $request) {
        $fares = UserTicket::where([
            ['state', '=', 1],
            ['ticket_id', '=', $request->ticket_id],
        ])->get();
        return response()->json(['data' => $fares]);
    }

    // 管理系统统计中心 -- 抵达大学城或桂花岗的车票（7条）
    public function getUnOsFare(Request $request) {
        $fares = UserTicket::where([
            ['state', '=', 1],
            ['terminus', '=', $request->terminus]
        ])->paginate(7);

        return $this->response->paginator($fares, new UserFareTransformer());
    }

    // 管理系统统计中心 -- 抵达大学城或桂花岗的车票（特定）
    public function getCurrentUnOsFare(Request $request) {
        $fares = UserTicket::where([
            ['state', '=', 1],
            ['terminus', '=', $request->terminus]
        ])->paginate(7, ['*'], 'page', $request->current_page);

        return $this->response->paginator($fares, new UserFareTransformer());
    }

    // 管理系统统计中心 -- 抵达大学城或桂花岗的车票（全部）
    public function getUnOsFares(Request $request) {
        $fares = UserTicket::where([
            ['state', '=', 1],
            ['terminus', '=', $request->terminus],
        ])->get();
        return response()->json(['data' => $fares]);
    }
}
