<?php

namespace App\Http\Controllers\Api\Management;

use App\Models\Notice;
use App\Models\Ticket;
use App\Models\TicketsOffice;
use App\Transformers\FareTransformer;
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

    // 管理系统车票系统 -- 管理车票--获取车票
    public function getAllFares() {
        $fares = Ticket::paginate(7);
        return $this->response->paginator($fares, new FareTransformer());
    }

    // 管理系统车票系统 -- 管理车票--获取特定页车票
    public function getCurrentFares(Request $request) {
        $fares = Ticket::paginate(7, ['*'], 'page', $request->current_page);
        return $this->response->paginator($fares, new FareTransformer());
    }

    // 管理系统车票系统 -- 管理车票--删除车票
    public function deleteFare(Request $request) {
        Ticket::where('id', '=', $request->id)->delete();
        $fares = Ticket::paginate(7, ['*'], 'page', $request->current_page);
        return $this->response->paginator($fares, new FareTransformer());
    }

    // 管理系统车票系统 -- 管理车票--激活车票
    public function activeFare(Request $request) {
        Ticket::where('id', '=', $request->id)->update(['state' => 1]);
        return $this->response()->noContent();
    }

    // 管理系统车票系统 -- 管理车票--车票失效
    public function unActiveFare(Request $request) {
        Ticket::where('id', '=', $request->id)->update(['state' => 0]);
        return $this->response()->noContent();
    }

    // 管理系统车票系统 -- 管理车票--开启/关闭Ticket Office
    public function setTicketOffice(Request $request) {

        // 如果是开启(需要发送系统通知)
        if($request->state === 1 && $request->send === 1) {
            try{
                DB::beginTransaction();
            }catch (\Exception $exception) {
                return response()->json(['data' => $exception->getMessage()], 500);
            }

            $resultOne = TicketsOffice::where('id', '=', 1)->update(['state' => $request->state]);
            $resultTwo = Notice::create([
                'mess' => '【系统通知】Ticket Office 已经开启啦！赶紧去添加ticket吧！'
            ]);

            if($resultOne && $resultTwo) {
                DB::commit();
                return $this->response->noContent();
            }else {
                try {
                    DB::rollBack();
                }catch (\Exception $exception) {
                    return response()->json(['data' => $exception->getMessage()], 500);
                }
            }

        }else {
            TicketsOffice::where('id', '=', 1)->update(['state' => $request->state]);
            return $this->response->noContent();
        }
    }
}
