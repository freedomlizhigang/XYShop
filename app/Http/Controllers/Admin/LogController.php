<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Log;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LogController extends Controller
{
	// 查询
    public function getIndex(Request $res)
    {
    	$title = '日志列表';
    	$admins = Admin::select('id','realname','name')->get();
    	// 超级管理员可以查看所有用户日志，其它人只能看自己的
    	if (session('user')->id === 1) {
    		$admin_id = $res->input('admin_id',0);
    		if ($admin_id != 0) {
    			$list = Log::where('admin_id',$admin_id)->orderBy('id','desc')->paginate(15);
    		}
    		else
    		{
    			$list = Log::orderBy('id','desc')->paginate(15);
    		}
    	}
    	else
    	{
    		$list = Log::where('admin_id',Auth::guard('admin')->user()->id)->orderBy('id','desc')->paginate(15);
    	}
    	return view('admin.log.index',compact('title','list','admins'));
    }
    // 清除7天前日志
    public function getDel()
    {
    	$logs = Log::where('created_at','<',Carbon::now()->addWeek(-1))->delete();
    	return back()->with('message','清除成功！');
    }
}
