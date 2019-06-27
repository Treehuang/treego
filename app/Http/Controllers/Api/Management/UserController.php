<?php

namespace App\Http\Controllers\Api\Management;

use \Auth;
use App\Models\User;
use App\Transformers\UserTranformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class UserController extends Controller
{
    // 管理系统账号管理 -- 获取最高管理员账号id
    public function getTopManagemerId() {
        $id = Auth::guard('api')->id();
        return response()->json(['id' => $id]);
    }

    // 管理系统账号管理 -- 获取所有账号
    public function getUsers() {
        $users = User::paginate(7);
        return $this->response->paginator($users, new UserTranformer());
    }
    // 管理系统账号管理 -- 获取特定页码的账号
    public function getCurrentPagesUsers(Request $request) {
        $users = User::paginate(7, ['*'], 'page', $request->current_page);
        return $this->response->paginator($users, new UserTranformer());
    }
    // 管理系统账号管理 -- 冻结账号
    public function freezeUser(Request $request) {
        User::where([['id', '=', $request->id]])->update(['state' => 0]);
        return $this->response()->noContent();
    }
    // 管理系统账号管理 -- 解冻账号
    public function unfreezeUser(Request $request) {
        User::where([['id', '=', $request->id]])->update(['state' => 1]);
        return $this->response()->noContent();
    }
    // 管理系统账号管理 -- 删除账号
    public function unsetUser(Request $request) {
        User::where([['id', '=', $request->id]])->delete();
        $users = User::paginate(7, ['*'], 'page', $request->current_page);
        return $this->response->paginator($users, new UserTranformer());
    }
    // 管理系统账号管理 -- 批量删除账号
    public function batchUnsetUser(Request $request) {
        User::whereIn('id', $request->selectIdList)->delete();
        $users = User::paginate(7, ['*'], 'page', $request->current_page);
        return $this->response->paginator($users, new UserTranformer());
    }

    // 管理系统账号管理 -- 授权管理员
    public function authorization(Request $request) {
        User::where([['id', '=', $request->id]])->update(['is_manager' => 1]);
        return $this->response()->noContent();
    }

    // 管理系统账号管理 -- 回收管理员授权
    public function unAuthorization(Request $request) {
        User::where([['id', '=', $request->id]])->update(['is_manager' => 0]);
        return $this->response()->noContent();
    }
}
