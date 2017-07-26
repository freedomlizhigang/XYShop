<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Common\Ueditor;
use App\Http\Controllers\Controller;
use App\Models\Common\Attr;
use Illuminate\Http\Request;

class UploaderController extends Controller
{
    // webuploader
    public function postUploadimg(Request $res)
    {
        return app('com')->upload($res,$ext = array('jpg','jpeg','gif','png','doc','docx','xls','xlsx','ppt','pptx','pdf','txt','rar','zip','swf','apk','mp4'),$allSize = 100);
    }
    /**
     * 编辑器文件上传
     * @param  Request $res [取文件用，资源]
     */
    // 百度Ueditor文件上传功能
    public function getUeditorupload(Request $res)
    {
        header("Content-Type: text/html; charset=utf-8");
        error_reporting(E_ERROR);
        // 取配置内容
        $CONFIG = config('ueditor');
        // 看是哪个对应的功能
        $action = $res->action;
        switch ($action) {
            case 'config':
                $result =  json_encode($CONFIG);
                break;
            
            /* 列出图片 */
            case 'listimage':
                $result = $this->action_list($res,$CONFIG);
                break;
            /* 列出文件 */
            case 'listfile':
                $result = $this->action_list($res,$CONFIG);
                break;

            /* 抓取远程文件 */
            case 'catchimage':
                $result = $this->action_crawler($res,$CONFIG);
                break;

            default:
                $result = json_encode(array(
                    'state'=> '请求地址出错'
                ));
                break;
        }

        /* 输出结果 */
        if (isset($res->callback)) {
            if (preg_match("/^[\w_]+$/", $res->callback)) {
                echo htmlspecialchars($res->callback) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                    'state'=> 'callback参数不合法'
                ));
            }
        } else {
            echo $result;
        }
    }
    public function postUeditorupload(Request $res)
    {
        header("Content-Type: text/html; charset=utf-8");
        error_reporting(E_ERROR);
        // 取配置内容
        $CONFIG = config('ueditor');
        // 看是哪个对应的功能
        $action = $res->action;
        switch ($action) {
            /* 上传图片 */
            case 'uploadimage':
            /* 上传涂鸦 */
            case 'uploadscrawl':
            /* 上传视频 */
            case 'uploadvideo':
            /* 上传文件 */
            case 'uploadfile':
                $result = $this->action_upload($res,$CONFIG);
                break;

            /* 抓取远程文件 */
            case 'catchimage':
                $result = $this->action_crawler($res,$CONFIG);
                break;

            default:
                $result = json_encode(array(
                    'state'=> '请求地址出错'
                ));
                break;
        }

        /* 输出结果 */
        if (isset($res->callback)) {
            if (preg_match("/^[\w_]+$/", $res->callback)) {
                echo htmlspecialchars($res->callback) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                    'state'=> 'callback参数不合法'
                ));
            }
        } else {
            echo $result;
        }
    }
    // 上传文件
    private function action_upload($res,$CONFIG)
    {
        /* 上传配置 */
        $base64 = "upload";
        switch ($res->action) {
            case 'uploadimage':
                $config = array(
                    "pathFormat" => $CONFIG['imagePathFormat'],
                    "maxSize" => $CONFIG['imageMaxSize'],
                    "allowFiles" => $CONFIG['imageAllowFiles']
                );
                $fieldName = $CONFIG['imageFieldName'];
                break;
            case 'uploadscrawl':
                $config = array(
                    "pathFormat" => $CONFIG['scrawlPathFormat'],
                    "maxSize" => $CONFIG['scrawlMaxSize'],
                    "allowFiles" => $CONFIG['scrawlAllowFiles'],
                    "oriName" => "scrawl.png"
                );
                $fieldName = $CONFIG['scrawlFieldName'];
                $base64 = "base64";
                break;
            case 'uploadvideo':
                $config = array(
                    "pathFormat" => $CONFIG['videoPathFormat'],
                    "maxSize" => $CONFIG['videoMaxSize'],
                    "allowFiles" => $CONFIG['videoAllowFiles']
                );
                $fieldName = $CONFIG['videoFieldName'];
                break;
            case 'uploadfile':
            default:
                $config = array(
                    "pathFormat" => $CONFIG['filePathFormat'],
                    "maxSize" => $CONFIG['fileMaxSize'],
                    "allowFiles" => $CONFIG['fileAllowFiles']
                );
                $fieldName = $CONFIG['fileFieldName'];
                break;
        }

        /* 生成上传实例对象并完成上传 */
        $up = new Ueditor($fieldName, $config, $base64);
        /**
         * 得到上传文件所对应的各个参数,数组结构
         * array(
         *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
         *     "url" => "",            //返回的地址
         *     "title" => "",          //新文件名
         *     "original" => "",       //原始文件名
         *     "type" => ""            //文件类型
         *     "size" => "",           //文件大小
         * )
         */
        $getInfo = $up->getFileInfo();
        // 添加成功以后，信息存储入数据库
        if ($getInfo['state'] == 'SUCCESS') {
            $data['filename'] = $getInfo['title'];
            $data['url'] = $getInfo['url'];
            Attr::create($data);
        }
        /* 返回数据 */
        return json_encode($getInfo);
    }
    // 列出图片
    private function action_list($res,$CONFIG)
    {
        /* 判断类型 */
        switch ($res->action) {
            /* 列出文件 */
            case 'listfile':
                $allowFiles = $CONFIG['fileManagerAllowFiles'];
                $listSize = $CONFIG['fileManagerListSize'];
                $path = $CONFIG['fileManagerListPath'];
                break;
            /* 列出图片 */
            case 'listimage':
            default:
                $allowFiles = $CONFIG['imageManagerAllowFiles'];
                $listSize = $CONFIG['imageManagerListSize'];
                $path = $CONFIG['imageManagerListPath'];
        }
        $allowFiles = substr(str_replace(".", "|", join("", $allowFiles)), 1);

        /* 获取参数 */
        $size = isset($res->size) ? htmlspecialchars($res->size) : $listSize;
        $start = isset($res->start) ? htmlspecialchars($res->start) : 0;
        $end = $start + $size;

        /* 获取文件列表 */
        $path = $_SERVER['DOCUMENT_ROOT'] . (substr($path, 0, 1) == "/" ? "":"/") . $path;
        $files = $this->getfiles($path, $allowFiles);
        if (!count($files)) {
            return json_encode(array(
                "state" => "no match file",
                "list" => array(),
                "start" => $start,
                "total" => count($files)
            ));
        }

        /* 获取指定范围的列表 */
        $len = count($files);
        for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
            $list[] = $files[$i];
        }
        //倒序
        //for ($i = $end, $list = array(); $i < $len && $i < $end; $i++){
        //    $list[] = $files[$i];
        //}

        /* 返回数据 */
        $result = json_encode(array(
            "state" => "SUCCESS",
            "list" => $list,
            "start" => $start,
            "total" => count($files)
        ));

        return $result;
    }
    /**
     * 遍历获取目录下的指定类型的文件
     * @param $path
     * @param array $files
     * @return array
     */
    private function getfiles($path, $allowFiles, &$files = array())
    {
        if (!is_dir($path)) return null;
        if(substr($path, strlen($path) - 1) != '/') $path .= '/';
        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $path2 = $path . $file;
                if (is_dir($path2)) {
                    $this->getfiles($path2, $allowFiles, $files);
                } else {
                    if (preg_match("/\.(".$allowFiles.")$/i", $file)) {
                        $files[] = array(
                            'url'=> substr($path2, strlen($_SERVER['DOCUMENT_ROOT'])),
                            'mtime'=> filemtime($path2)
                        );
                    }
                }
            }
        }
        return $files;
    }
    // 抓取远程图片
    private function action_crawler($res,$CONFIG)
    {
        set_time_limit(0);
        /* 上传配置 */
        $config = array(
            "pathFormat" => $CONFIG['catcherPathFormat'],
            "maxSize" => $CONFIG['catcherMaxSize'],
            "allowFiles" => $CONFIG['catcherAllowFiles'],
            "oriName" => "remote.png"
        );
        $fieldName = $CONFIG['catcherFieldName'];

        /* 抓取远程图片 */
        $list = array();
        $source = $res->$fieldName;
        foreach ($source as $imgUrl) {
            $item = new Ueditor($imgUrl, $config, "remote");
            $info = $item->getFileInfo();
            array_push($list, array(
                "state" => $info["state"],
                "url" => $info["url"],
                "size" => $info["size"],
                "title" => htmlspecialchars($info["title"]),
                "original" => htmlspecialchars($info["original"]),
                "source" => htmlspecialchars($imgUrl)
            ));
        }
        // 添加成功以后，信息存储入数据库
        if (count($list)) {
            // 循环出来可以插入的data
            $data = [];
            foreach ($list as $v) {
                $data[] = ['filename'=>$v['title'],'url'=>$v['url']];
            }
            Attr::insert($data);
        }
        /* 返回抓取数据 */
        return json_encode(array(
            'state'=> count($list) ? 'SUCCESS':'ERROR',
            'list'=> $list
        ));
    }
}
