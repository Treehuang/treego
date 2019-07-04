<?php

namespace App\Http\Controllers\Api\Management;

use \DB;
use \Auth;
use App\Models\Notice;
use App\Models\User;
use App\Models\Certificat;
use App\Transformers\CertificatTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class AuditController extends Controller
{
    // 管理系统学籍审核 -- 待审核（全部）
    public function getNoAudits() {
        $noAudits = Certificat::where([['audit_state', '=', 0]])->get();
        return response()->json(['data' => $noAudits]);
    }

    // 管理系统学籍审核 -- 待审核（7条）
    public function getNoAudit() {
        $noAudit = Certificat::where([['audit_state', '=', 0]])->paginate(7);
        return $this->response->paginator($noAudit, new CertificatTransformer());
    }

    // 管理系统学籍审核 -- 获取特定页待审核
    public function getPagesNoAudit(Request $request) {
        $noAudit = Certificat::where([['audit_state', '=', 0]])->paginate(7, ['*'], 'page', $request->current_page);
        return $this->response->paginator($noAudit, new CertificatTransformer());
    }

    // 管理系统学籍审核 -- 管理员通过审核
    public function throwAudit(Request $request) {
        $user = Auth::guard('api')->user();

        try {
            DB::beginTransaction(); //开启事务
        }catch (\Exception $exception) {
            return response()->json(['data' => $exception->getMessage()], 500);
        }

        $restulOne = Certificat::where([['id', '=', $request->id]])->update(['audit_state' => 1, 'check_user' => $user->username]);
        $restulTwo =  User::where([['id', '=', $request->user_id]])->update(['is_check' => 1]);
        $restulThree = Notice::create([
            'user_id' => $request->user_id,
            'mess' => '【审核通知】您的学籍认证审核通过！',
        ]);

        if ($restulOne && $restulTwo && $restulThree) {
            DB::commit();  //提交
            $noAudit = Certificat::where([['audit_state', '=', 0]])->paginate(7, ['*'], 'page', $request->current_page);
            return $this->response->paginator($noAudit, new CertificatTransformer());
        }else {
            try {
                DB::rollBack();
            }catch (\Exception $exception) {
                return response()->json(['data' => $exception->getMessage()], 500);
            }

            return response()->json(['data' => '出错啦，呼叫程序小哥哥！'], 500);
        }
    }

    // 管理系统学籍审核 -- 管理员不给通过审核
    public function noThrowAudit(Request $request) {
        $user = Auth::guard('api')->user();
        if (!empty($request->noPassReason)) {
            $noPassReason = '【审核通知】您的学籍认证审核不通过！ 【原因】' . $request->noPassReason;
        }else {
            $noPassReason = '【审核通知】您的学籍认证审核不通过！';
        }

        try {
            DB::beginTransaction(); //开启事务
        }catch (\Exception $exception) {
            return response()->json(['data' => $exception->getMessage()], 500);
        }

        $restulOne = Certificat::where([['id', '=', $request->id]])->update(['audit_state' => 2, 'check_user' => $user->username]);
        $restulTwo =  User::where([['id', '=', $request->user_id]])->update(['is_check' => 3]);
        $restulThree = Notice::create([
            'user_id' => $request->user_id,
            'mess' => $noPassReason,
        ]);

        if ($restulOne && $restulTwo && $restulThree) {
            DB::commit();  //提交
            $noAudit = Certificat::where([['audit_state', '=', 0]])->paginate(7, ['*'], 'page', $request->current_page);
            return $this->response->paginator($noAudit, new CertificatTransformer());
        }else {
            try {
                DB::rollBack();
            }catch (\Exception $exception) {
                return response()->json(['data' => $exception->getMessage()], 500);
            }

            return response()->json(['data' => '出错啦，赶快呼叫程序小哥哥！'], 500);
        }
    }

    // 管理系统学籍审核 -- 已审核（7条）
    public function getAudit() {
        $audit = Certificat::where('audit_state', '=', 1)->orWhere('audit_state', '=', 2)->latest('updated_at')->paginate(7);
        return $this->response->paginator($audit, new CertificatTransformer());
    }

    // 管理系统学籍审核 -- 获取特定页已审核
    public function getPagesAudit(Request $request) {
        $noAudit = Certificat::where('audit_state', '=', 1)->orWhere('audit_state', '=', 2)->latest('updated_at')->paginate(7, ['*'], 'page', $request->current_page);
        return $this->response->paginator($noAudit, new CertificatTransformer());
    }

    // 管理系统学籍审核 -- 获取不能通过审核的原因
    public function getNoPassReason(Request $request) {

        $auditer = Certificat::where('id', '=', $request->id)->get();

        $noPassReason = Notice::where([
            ['user_id', '=', $request->user_id],
            ['updated_at', '=', $request->updated_at],
        ])->get();

        $noPassReason = ltrim($noPassReason[0]['mess']);
        $noPassReasonArr = explode(' ', $noPassReason, 3);
        if (!empty($noPassReasonArr[1])) {
            return response()->json(['data' => $noPassReasonArr[1]]);
        }else {
            return response()->json(['data' => '审核者 ' . $auditer[0]['check_user'] . ' 太懒了！ 没有填写审核不通过的原因~']);
        }
    }

    // 管理系统学籍审核 -- 通过审核（7条）
    public function getThrowAudit() {
        $audit = Certificat::where('audit_state', '=', 1)->latest('updated_at')->paginate(7);
        return $this->response->paginator($audit, new CertificatTransformer());
    }

    // 管理系统学籍审核 -- 获取特定页通过审核
    public function getCurrentPagesThrowAudit(Request $request) {
        $noAudit = Certificat::where('audit_state', '=', 1)->latest('updated_at')->paginate(7, ['*'], 'page', $request->current_page);
        return $this->response->paginator($noAudit, new CertificatTransformer());
    }

    // 管理系统学籍审核 -- 未通过审核（7条）
    public function getNoThrowAudit() {
        $audit = Certificat::where('audit_state', '=', 2)->latest('updated_at')->paginate(7);
        return $this->response->paginator($audit, new CertificatTransformer());
    }

    // 管理系统学籍审核 -- 获取特定页未通过审核
    public function getCurrentPagesNoThrowAudit(Request $request) {
        $noAudit = Certificat::where('audit_state', '=', 2)->latest('updated_at')->paginate(7, ['*'], 'page', $request->current_page);
        return $this->response->paginator($noAudit, new CertificatTransformer());
    }
}
