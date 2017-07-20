<?php

namespace App\Http\Controllers\admin;

use App;
use App\Models\Attr;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Storage;
use App\Http\Controllers\UEditor;

class AttrController extends Controller
{
    public function __construct()
    {
    	$this->attr = new Attr;
    }
    public function getIndex(Request $res)
    {
        $title = '附件列表';
        // 搜索关键字
        $key = trim($res->input('q'));
        $starttime = $res->input('starttime');
        $endtime = $res->input('endtime');
        $list = Attr::orderBy('id','desc')->where(function($q) use($key){
                if ($key != '') {
                    $q->where('filename','like','%'.$key.'%');
                }
            })->where(function($q) use($starttime){
                if ($starttime != '') {
                    $q->where('created_at','>',$starttime);
                }
            })->where(function($q) use($endtime){
                if ($endtime != '') {
                    $q->where('created_at','<',$endtime);
                }
            })->paginate(15);
        return view('admin.attr.index',compact('title','list','key','starttime','endtime'));
    }
    // 删除文件
    public function getDelfile(Request $res,$id = '')
    {
        // 找localurl
        $url = Attr::where('id',$id)->value('url');
        if (!is_null($url)) {
            // 数据库删除
            Attr::destroy($id);
            // 文件删除
            // Storage::delete($url);
        }
        return back()->with('message', '删除附件成功！');
    }
    /**
     * 文件上传
     * @param  Request $res [取文件用，资源]
     */
    public function postUploadimg(Request $res)
    {
        return App::make('com')->upload($res,$ext = array('jpg','jpeg','gif','png','doc','docx','xls','xlsx','ppt','pptx','pdf','txt','rar','zip','swf','apk','mp4'),$allSize = 100);
    }
}
