<?php

namespace App\Http\Controllers\Api\Ticket;

use \DB;
use \Auth;
use App\Models\Ticket;
use App\Models\TicketsOffice;
use App\Models\UserTicket;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class TicketController extends Controller
{
    // 车票系统是否开放
    public function getTicketsOfficeState() {
        $ticketsOffice = TicketsOffice::first();
        return response()->json(['ticketsOfficeState' => $ticketsOffice->state]);
    }

    // 登录用户的车票
    public function getAuthTickets() {

        $userId = Auth::guard('api')->id();

        // 取出所有起始点的车票
        $tickets = Ticket::where('state', '=', 1)->get();

        // 取出此用户已购的车票的ticket_id
        $noBuyTicketsList = UserTicket::where([
            ['user_id', '=', $userId],
            ['state', '=', 1]
        ])->pluck('ticket_id');

        // 将对象转为数组
        $newNoBuyTicketsList = [];
        foreach ($noBuyTicketsList as $value) {
            $newNoBuyTicketsList[] = $value;
        }

        // 用户未购买的车票
        $startPlaceTicketList = [];
        foreach ($tickets as $ticket) {
            if (!in_array($ticket['id'], $newNoBuyTicketsList)) {
                $ticket['ticket_id'] = $ticket['id'];           // 为了让ticket_id与id保持一致（数据库无法做到，在这里添加）
                $startPlaceTicketList[] = $ticket;
            }
        }

        // 用户购买的前往大学城车票
        $universityTicketList = UserTicket::where([
            ['state', '=', 1],
            ['terminus', '=', '大学城'],
            ['user_id', '=', $userId],
        ])->get();

        // 用户购买的前往桂花岗车票
        $osmanthusTicketList = UserTicket::where([
            ['state', '=', 1],
            ['terminus', '=', '桂花岗'],
            ['user_id', '=', $userId],
        ])->get();

        return response()->json([
            'price' => $tickets[0]['price'],
            'osmanthusTicketList'  => $osmanthusTicketList,
            'startPlaceTicketList' => $startPlaceTicketList,
            'universityTicketList' => $universityTicketList,
        ]);
    }

    // 未登录用户的车票
    public function getNoAuthTickets() {
        // 取出所有起始点的车票
        $tickets = Ticket::all();
        return response()->json(['startPlaceTicketList' => $tickets]);
    }

    // 保存前往大学城的车票
    public function addToUniversity(Request $request) {

        $user = Auth::guard('api')->user();
        $userId = $user->id;
        $username = $user->username;

        // 将该用户大学城车票全部的state置为0
        UserTicket::where([
            ['user_id', '=', $userId],
            ['terminus', '=', '大学城'],
        ])->update(['state' => 0]);

        // 将传递过来的车票全部插入数据库
        $newUniversityTicketList = [];
        foreach ($request->universityTicketList as $universityTicket) {
            $newUniversityTicketList[] = [
                'user_id' => $userId,
                'ticket_id' => $universityTicket['ticket_id'],
                'start_place' => $universityTicket['start_place'],
                'terminus' => '大学城',
                'from_time' => $universityTicket['from_time'],
                'arrival_time' => $universityTicket['arrival_time'],
                'ticket_num' => (integer)$universityTicket['ticket_num'],
                'state' => 1,
                'username' => $username,
                'leader' => $universityTicket['leader'],
                'date' => $universityTicket['date'],
                'price' => $universityTicket['price'],
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())
            ];
        }

        DB::table('user_tickets')->insert($newUniversityTicketList);

        return $this->response->created();
    }

    // 保存前往桂花岗的车票
    public function addToOsmanthus(Request $request) {
        $user = Auth::guard('api')->user();
        $userId = $user->id;
        $username = $user->username;

        // 将该用户桂花岗车票全部的state置为0
        UserTicket::where([
            ['user_id', '=', $userId],
            ['terminus', '=', '桂花岗'],
        ])->update(['state' => 0]);

        // 将传递过来的车票全部插入数据库
        $newOsmanthusTicketList = [];
        foreach ($request->osmanthusTicketList as $osmanthusTicket) {
            $newOsmanthusTicketList[] = [
                'user_id' => $userId,
                'ticket_id' => $osmanthusTicket['ticket_id'],
                'start_place' => $osmanthusTicket['start_place'],
                'terminus' => '桂花岗',
                'from_time' => $osmanthusTicket['from_time'],
                'arrival_time' => $osmanthusTicket['arrival_time'],
                'ticket_num' => $osmanthusTicket['ticket_num'],
                'state' => 1,
                'username' => $username,
                'leader' => $osmanthusTicket['leader'],
                'date' => $osmanthusTicket['date'],
                'price' => $osmanthusTicket['price'],
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time())
            ];
        }

        DB::table('user_tickets')->insert($newOsmanthusTicketList);

        return $this->response->created();
    }

    // 获取用户购买的车票
    public function getBuyTickets() {
        $userId = Auth::guard('api')->id();

        $buyTicketList = UserTicket::where([
            ['state', '=', 1],
            ['user_id', '=', $userId],
        ])->latest('updated_at')->limit(4)->get();

        $num = count($buyTicketList);

        $noBuyTicketList = [];
        if ($num < 5) {
            $noBuyTicketList = UserTicket::where([
                ['state', '=', 0],
                ['user_id', '=', $userId],
            ])->latest()->limit(5-$num)->get();
        }

        $newTicketList = [];

        foreach ($buyTicketList as $buyTicket) {
            $newTicketList[] = $buyTicket;
        }

        foreach ($noBuyTicketList as $noBuyTicket) {
            $newTicketList[] = $noBuyTicket;
        }

        return response()->json(['ticketList' => $newTicketList]);
    }
}
