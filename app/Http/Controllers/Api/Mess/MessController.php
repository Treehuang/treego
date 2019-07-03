<?php

namespace App\Http\Controllers\Api\Mess;

use App\Models\User;
use \DB;
use Auth;
use App\Models\Notice;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;


class MessController extends Controller
{
    public function getUnread() {
        $user_id = Auth::guard('api')->id();

        // 取出所有公告消息(排序)
        $notices = Notice::where('user_id', '=', 0)->orWhere('user_id', '=', $user_id)->latest()->get();

        // 取出此用户已读消息的message_id
        $readIdList = Message::where([
            ['user_id', '=', $user_id],
        ])->pluck('message_id');

        $newReadIdList = [];
        foreach ($readIdList as $readId) {
            $newReadIdList[] = $readId;
        }

        $newNotices = [];
        foreach ($notices as $key => $notice) {
            if (!in_array($notice['id'], $newReadIdList)) {
                $newNotices[] = $notice;
            }
        }

        return response()->json(['unReadList' => $newNotices]);
    }

    public function markRead(Request $request) {

        $readMessages = [
            'state' => 0,
            'mess' => $request->mess,
            'user_id' => Auth::guard('api')->id(),
            'message_id' => $request->message_id,
            'created_at' => $request->created_at,
        ];

        DB::table('messages')->insert($readMessages);

        return $this->response->created();
    }

    public function markAll(Request $request) {

        $user_id = Auth::guard('api')->id();

        $readMessList = [];
        foreach ($request->messList as $mess) {
            $readMessList[] = [
                'state' => 0,
                'mess'  => $mess['mess'],
                'user_id' => $user_id,
                'message_id' => $mess['id'],
                'created_at' => $mess['created_at'],
                'updated_at' => $mess['updated_at'],
            ];
        }

        // 批量插入
        DB::table('messages')->insert($readMessList);

        return $this->response->created();
    }

    public function getRead() {

        $readIdList = Message::where([
            ['state', '=', 0],
            ['user_id', '=', Auth::guard('api')->id()],
        ])->latest()->get();

        return response()->json(['readList' => $readIdList]);
    }

    public function toRecycle(Request $request) {

        Message::where([
            ['message_id', '=', $request->message_id],
            ['user_id', '=', Auth::guard('api')->id()]
        ])->update(['state' => 1]);

        return $this->response()->noContent();
    }

    public function getRecycle() {
        $recyleList = Message::where([
            ['state', '=', 1],
            ['user_id', '=', Auth::guard('api')->id()],
        ])->latest()->get();

        return response()->json(['recyleList' => $recyleList]);
    }

    public function restoreRecycle(Request $request) {
        Message::where([
            ['message_id', '=', $request->message_id],
            ['user_id', '=', Auth::guard('api')->id()]
        ])->update(['state' => 0]);

        return $this->response()->noContent();
    }

    public function emptyAll(Request $request) {
        Message::where([['user_id', '=', Auth::guard('api')->id()]])->whereIn('message_id', $request->messageIdList)->update(['state' => 2]);
        return $this->response()->noContent();
    }

    public function emptyOne(Request $request) {
        Message::where([
            ['message_id', '=', $request->message_id],
            ['user_id', '=', Auth::guard('api')->id()]
        ])->update(['state' => 2]);
    }

    public function removeAll(Request $request) {
        Message::where([['user_id', '=', Auth::guard('api')->id()]])->whereIn('message_id', $request->messageIdList)->update(['state' => 1]);
        return $this->response()->noContent();
    }
}
