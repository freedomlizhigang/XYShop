<?php
namespace App\Services;

use App\Models\User\Consume;
use App\Models\User\SignLog;
use App\Models\User\User;
use Cache;
use Gate;
use Storage;

class ComService
{
    //uuid生成方法（可以指定前缀）
    public function create_uuid($prefix = ""){
        $str = md5(uniqid(mt_rand(), true));
        $uuid  = substr($str,0,8) . '-';
        $uuid .= substr($str,8,4) . '-';
        $uuid .= substr($str,12,4) . '-';
        $uuid .= substr($str,16,4) . '-';
        $uuid .= substr($str,20,12);
        return $prefix . $uuid;
    }
    // 判断是不是微信浏览器
    public function is_weixin()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        }
        return false;
    }
    // 密码生成及判断
    public function makepwd($pwd = '',$crypt = '')
    {
        return md5(md5($pwd.'.'.$crypt));
    }

    // 处理属性值
    public function trim_value($v)
    {
        // 替换特殊字符
        $v = trim(str_replace('@','',str_replace('_', '', $v)));
        $v = explode(PHP_EOL, $v);
        $tmp = [];
        foreach ($v as $k) {
            if (trim($k) != '' ) {
                $tmp[] = trim($k);
            }
        }
        // 处理为json
        return json_encode($tmp);
    }

    /**
     * 多个数组的笛卡尔积
    *
    * @param unknown_type $data
    */
    public function combineDika() {
        $data = func_get_args();
        $data = current($data);
        $cnt = count($data);
        $result = array();
        $arr1 = array_shift($data);
        foreach($arr1 as $key=>$item)
        {
            $result[] = array($item);
        }

        foreach($data as $key=>$item)
        {
            $result = $this->combineArray($result,$item);
        }
        return $result;
    }


    /**
     * 两个数组的笛卡尔积
     * @param unknown_type $arr1
     * @param unknown_type $arr2
    */
    public function combineArray($arr1,$arr2) {
        $result = array();
        foreach ($arr1 as $item1)
        {
            foreach ($arr2 as $item2)
            {
                $temp = $item1;
                $temp[] = $item2;
                $result[] = $temp;
            }
        }
        return $result;
    }

    // 消费记录
    public function consume($uid,$oid = 0,$price = 0,$mark = '',$type = 0)
    {
        Consume::create(['user_id'=>$uid,'order_id'=>$oid,'price'=>$price,'mark'=>$mark,'type'=>$type]);
        // 消费增加积分
        if ($type == 0) {
            User::where('id',$uid)->lockForUpdate()->increment('points',$price);
            SignLog::create(['user_id'=>$uid,'point'=>$price,'days'=>0,'type'=>2,'signtime'=>date('Y-m-d H:i:s')]);
        }
    }
    // 生成订单号
    // 基于当前时间的微秒+8位随机字符串，uniqid() 函数基于以微秒计的当前时间，生成一个唯一的 ID。
    public function orderid()
    {
        // 系统生成订单号
        $str = '0123456789';
        // 当前毫秒
        list($usec, $sec) = explode(" ", microtime());
        $msectime = (float)$usec + (float)$sec;
        $msectime = str_replace('.','',$msectime);
        $orderid = $msectime.$this->random(8,$str);
        return $orderid;
    }

    // 随机中文字
    public function zh_rand($length = 10)
    {
        $hash = '';
        $chars = '一乙二十丁厂七卜人入八九几儿了力乃刀又三于干亏士工土才寸下大丈与万上小口巾山千乞川亿个勺久凡及夕丸么广亡门义之尸弓己已子卫也女飞刃习叉马乡丰王井开夫天无元专云扎艺木五支厅不太犬区历尤友匹车巨牙屯比互切瓦止少日中冈贝内水见午牛手毛气升长仁什片仆化仇币仍仅斤爪反介父从今凶分乏公仓月氏勿欠风丹匀乌凤勾文六方火为斗忆订计户认心尺引丑巴孔队办以允予劝双书幻玉刊示末未击打巧正扑扒功扔去甘世古节本术可丙左厉右石布龙平灭轧东卡北占业旧帅归且旦目叶甲申叮电号田由史只央兄叼叫另叨叹四生失禾丘付仗代仙们仪白仔他斥瓜乎丛令用甩印乐句匆册犯外处冬鸟务包饥主市立闪兰半汁汇头汉宁穴它讨写让礼训必议讯记永司尼民出辽奶奴加召皮边发孕圣对台矛纠母幼丝式刑动扛寺吉扣考托老执巩圾扩扫地扬场耳共芒亚芝朽朴机权过臣再协西压厌在有百存而页匠夸夺灰达列死成夹轨邪划迈毕至此贞师尘尖劣光当早吐吓虫曲团同吊吃因吸吗屿帆岁回岂刚则肉网年朱先丢舌竹迁乔伟传乒乓休伍伏优伐延件任伤价份华仰仿伙伪自血向似后行舟全会杀合兆企众爷伞创肌朵杂危旬旨负各名多争色壮冲冰庄庆亦刘齐交次衣产决充妄闭问闯羊并关米灯州汗污江池汤忙兴宇守宅字安讲军许论农讽设访寻那迅尽导异孙阵阳收阶阴防奸如妇好她妈戏羽观欢买红纤级约纪驰巡寿弄麦形进戒吞远违运扶抚坛技坏扰拒找批扯址走抄坝贡攻赤折抓扮抢孝均抛投坟抗坑坊抖护壳志扭块声把报却劫芽花芹芬苍芳严芦劳克苏杆杠杜材村杏极李杨求更束豆两丽医辰励否还歼来连步坚旱盯呈时吴助县里呆园旷围呀吨足邮男困吵串员听吩吹呜吧吼别岗帐财针钉告我乱利秃秀私每兵估体何但伸作伯伶佣低你住位伴身皂佛近彻役返余希坐谷妥含邻岔肝肚肠龟免狂犹角删条卵岛迎饭饮系言冻状亩况床库疗应冷这序辛弃冶忘闲间闷判灶灿弟汪沙汽沃泛沟没沈沉怀忧快完宋宏牢究穷灾良证启评补初社识诉诊词译君灵即层尿尾迟局改张忌际陆阿陈阻附妙妖妨努忍劲鸡驱纯纱纳纲驳纵纷纸纹纺驴纽奉玩环武青责现表规抹拢拔拣担坦押抽拐拖拍者顶拆拥抵拘势抱垃拉拦拌幸招坡披拨择抬其取苦若茂苹苗英范直茄茎茅林枝杯柜析板松枪构杰述枕丧或画卧事刺枣雨卖矿码厕奔奇奋态欧垄妻轰顷转斩轮软到非叔肯齿些虎虏肾贤尚旺具果味昆国昌畅明易昂典固忠咐呼鸣咏呢岸岩帖罗帜岭凯败贩购图钓制知垂牧物乖刮秆和季委佳侍供使例版侄侦侧凭侨佩货依的迫质欣征往爬彼径所舍金命斧爸采受乳贪念贫肤肺肢肿胀朋股肥服胁周昏鱼兔狐忽狗备饰饱饲变京享店夜庙府底剂郊废净盲放刻育闸闹郑券卷单炒炊炕炎炉沫浅法泄河沾泪油泊沿泡注泻泳泥沸波泼泽治怖性怕怜怪学宝宗定宜审宙官空帘实试郎诗肩房诚衬衫视话诞询该详建肃录隶居届刷屈弦承孟孤陕降限妹姑姐姓始驾参艰线练组细驶织终驻驼绍经贯奏春帮珍玻毒型挂封持项垮挎城挠政赴赵挡挺括拴拾挑指垫挣挤拼挖按挥挪某甚革荐巷带草茧茶荒茫荡荣故胡南药标枯柄栋相查柏柳柱柿栏树要咸威歪研砖厘厚砌砍面耐耍牵残殃轻鸦皆背战点临览竖省削尝是盼眨哄显哑冒映星昨畏趴胃贵界虹虾蚁思蚂虽品咽骂哗咱响哈咬咳哪炭峡罚贱贴骨钞钟钢钥钩卸缸拜看矩怎牲选适秒香种秋科重复竿段便俩贷顺修保促侮俭俗俘信皇泉鬼侵追俊盾待律很须叙剑逃食盆胆胜胞胖脉勉狭狮独狡狱狠贸怨急饶蚀饺饼弯将';
        $max = mb_strlen($chars,'utf-8') - 1;
        for($i = 0; $i < $length; $i++) {
            $tmp = rand(0, $max);
            $hash .= mb_substr($chars,$tmp,1,'utf-8');
        }
        return $hash;
    }

    /**
    * 产生随机字符串
    *
    * @param    int        $length  输出长度
    * @param    string     $chars   可选的 ，默认为 0123456789
    * @return   string     字符串
    */
    public function random($length, $chars = '0123456789') {
        $hash = '';
        $max = strlen($chars) - 1;
        for($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
        return $hash;
    }
    // 模板权限判断用，减少输出
    public function ifCan($priv = '')
    {
        // return Gate::forUser(session('console'))->allows($priv);
        $res = in_array($priv,session('console')->allPriv) || in_array(1,session('console')->allRole);
        return $res;
    }
	// 转成树形菜单数组
    public function toTree($data,$pid)
    {
        $tree = [];
        if ($data->count() > 0) {
            foreach($data as $v)
            {
                if ($v->parentid == $pid) {
                    $v = $v->toArray();
                    $v['parentid'] = $this->toTree($data,$v['id']);
                    $tree[] = $v;
                }
            }
        }
        return $tree;
    }
    // 树形菜单 html
    public function toTreeSelect($tree,$pid = 0)
    {
        $html = '';
        if (is_null($tree) || $tree == '') {
            return $html;
        }
        foreach ($tree as $v) {
            // 计算level
            $level = count(explode(',',$v['arrparentid']));
            $str = '';
            if($level > 1)
            {
                for ($i=2; $i < $level; $i++) {
                    $str .= '| ';
                }
                $str .= ' |—';
            }
            // level < 4 是为了不添加更多的层级关系，其它地方不用判断，只是后台菜单不用那么多级
            if ($pid == $v['id'])
            {
                if ($level == 1) {
                    $html .= "<option value='".$v['id']."' selected='selected' style='font-weight:bold;'>".$str.$v['name']."</option>";
                }
                else
                {
                    $html .= "<option value='".$v['id']."' selected='selected'>".$str.$v['name']."</option>";
                }
            }
            else
            {
                if ($level == 1) {
                    $html .= "<option value='".$v['id']."' style='font-weight:bold;'>".$str.$v['name']."</option>";
                }
                else
                {
                    $html .= "<option value='".$v['id']."'>".$str.$v['name']."</option>";
                }
            }
            if ($v['parentid'] != '')
            {
                $html .= $this->toTreeSelect($v['parentid'],$pid);
            }
        }
        return $html;
    }
    /**
     * 更新类别缓存用的操作
     * @param  [type] $model [模型]
     * @return [type] $cacheName [缓存名称]
     */
    public function updateCache($model,$cacheName){
        $this->types = $types = array();
        $this->types = $types = $model->get()->toArray();
        // 将数组索引转化为typeid，phpcms v9的select方法支持定义数组索引，这个坑花了两小时
        $this->types  = $types = $this->orderTypes($types,'id');
        if(is_array($this->types)) {
            foreach($this->types as $id => $type) {
                // 取得所有父栏目
                $arrparentid = $this->arrParentid($id);
                $arrchildid = $this->arrChildid($id);
                $child = is_numeric($arrchildid) ? 0 : 1;
                // 如果父栏目数组、子栏目数组，及是否含有子栏目不与原来相同，更新，字符串比较使用strcasecmp()方法，直接比较字符串会出问题
                if(strcasecmp($types[$id]['arrparentid'],$arrparentid) != 0 || strcasecmp($types[$id]['arrchildid'],$arrchildid) != 0 || $types[$id]['child'] != $child){
                    $model->where('id',$id)->update(['arrparentid'=>$arrparentid,'arrchildid'=>$arrchildid,'child'=>$child]);
                }
            }
        }
        //删除在非正常显示的栏目
        foreach($this->types as $type) {
            if($type['parentid'] != 0 && !isset($this->types[$type['parentid']])) {
                $model->destroy($type['id']);
            }
        }
        $newlist = $model->get()->toArray();
        // 重排数组
        $types = $this->orderTypes($newlist,'id');
        Cache::forget($cacheName);
        Cache::forever($cacheName,$types);
    }
    /**
     * 以索引重排结果数组
     * @param array $types
     * $id 主键
     */
    private function orderTypes($types = array() ,$id = '') {
        $temparr = array();
        if (is_array($types) && !empty($types)) {
            foreach ($types as $c) {
                // 以主键做为数组索引
                $temparr[$c[$id]] = $c;
            }
        }
        return $temparr;
    }
    /**
     *
     * 获取父栏目ID列表
     * @param integer $id              栏目ID
     * @param array $arrparentid          父目录ID
     * @param integer $n                  查找的层次
     */
    private function arrParentid($id, $arrparentid = '') {
        if(!is_array($this->types) || !isset($this->types[$id])) return false;
        $parentid = $this->types[$id]['parentid'];
        $arrparentid = $arrparentid ? $parentid.','.$arrparentid : $parentid;
        // 父ID不为0时
        if($parentid) {
            $arrparentid = $this->arrParentid($parentid, $arrparentid);
        } else {
            // 如果父ID为0
            $this->types[$id]['arrparentid'] = $arrparentid;
        }
        $parentid = $this->types[$id]['parentid'];
        return $arrparentid;
    }
    /**
     *
     * 获取子栏目ID列表
     * @param $id 栏目ID
     */
    private function arrChildid($id) {
        $arrchildid = $id;
        if(is_array($this->types)) {
            foreach($this->types as $k => $cat) {
                // $k != $id 不是自身
                // $cat['parentid'] 父栏目存在且不是顶级栏目
                // $cat['parentid'] == $id 父栏目ID是当前要获取子栏目的栏目id，即此次循环的栏目正是当前栏目子栏目
                if($cat['parentid'] && $k != $id && $cat['parentid']==$id) {
                    $arrchildid .= ','.$this->arrChildid($k);
                }
            }
        }
        return $arrchildid;
    }

    /**
     * 文件上传
     * @param  Request $res [取文件用，资源]
     * @param  string  $ext [文件类型]
     * @param  int  $allSize [允许的文件大小，单位M]
     */
    public function upload($res,$ext = array('jpg','jpeg','gif','png','doc','docx','xls','xlsx','ppt','pptx','pdf','txt','rar','zip','swf'),$allSize = 3)
    {
        try {
            $isAllow = collect($ext);
            /* 返回JSON数据 */
            $return['error'] = 1;
            // 验证是否有要上传的文件
            if(!$res->hasFile('imgFile')){
                $return['message'] = '文件不存在！';
                return json_encode($return);
            }
            // 取得文件后缀
            $ext = $res->file('imgFile')->getClientOriginalExtension();
            // 检查文件类型
            if(!$isAllow->contains(strtolower($ext)))
            {
                $return['message']  = '文件类型错误!';
                return json_encode($return);
            }
            // 检查文件大小，不得大于3M
            $size = $res->file('imgFile')->getClientSize();
            if($size > $allSize*1073741824)
            {
                $return['message']   = '单个文件大于'.$allSize.'M!';
                return json_encode($return);
            }
            // 生成文件名
            $filename = date('Ymdhis').rand(100, 999);
            // 压缩缩略图图片，gif/png/jpeg全转为jpg格式
            if($res->thumb)
            {
                // 缩略图设置图片位置
                // 移动到新的位置，先创建目录及更新文件名为时间点
                $dir = public_path('upload/thumb/'.date('Ymd').'/');
                if(!is_dir($dir)){
                    Storage::makeDirectory('thumb/'.date('Ymd'));
                }
                $outPath = $dir.$filename.'.jpg';
                // 缩略图
                $thumbWidth = isset($res->thumbWidth) ? $res->thumbWidth : 200;
                $thumbHeight = isset($res->thumbHeight) ? $res->thumbHeight : 160;
                $srcWidth = getimagesize($res->file('imgFile'))[0];
                $srcHeight = getimagesize($res->file('imgFile'))[1];
                switch($ext) {
                    case 'gif' :
                        // 新图像
                        $dstThumbPic = imagecreatetruecolor($thumbWidth, $thumbHeight);
                        // 原始图像
                        $source_img = imagecreatefromgif($res->file('imgFile'));
                        // 复制原始图像到新图像大小上
                        imagecopyresampled($dstThumbPic, $source_img, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $srcWidth, $srcHeight);
                        // 保存新图像
                        $isTrue = imagejpeg($dstThumbPic, $outPath, 100);
                        break;
                    case 'jpg' :
                        // 新图像
                        $dstThumbPic = imagecreatetruecolor($thumbWidth, $thumbHeight);
                        // 原始图像
                        $source_img = imagecreatefromjpeg($res->file('imgFile'));
                        // 复制原始图像到新图像大小上
                        imagecopyresampled($dstThumbPic, $source_img, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $srcWidth, $srcHeight);
                        // 保存新图像
                        $isTrue = imagejpeg($dstThumbPic, $outPath, 100);
                        break;
                    case 'jpeg' :
                        // 新图像
                        $dstThumbPic = imagecreatetruecolor($thumbWidth, $thumbHeight);
                        // 原始图像
                        $source_img = imagecreatefromjpeg($res->file('imgFile'));
                        // 复制原始图像到新图像大小上
                        imagecopyresampled($dstThumbPic, $source_img, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $srcWidth, $srcHeight);
                        // 保存新图像
                        $isTrue = imagejpeg($dstThumbPic, $outPath, 100);
                        break;
                    case 'png' :
                        // 新图像
                        $dstThumbPic = imagecreatetruecolor($thumbWidth, $thumbHeight);
                        // 原始图像
                        $source_img = imagecreatefrompng($res->file('imgFile'));
                        // 复制原始图像到新图像大小上
                        imagecopyresampled($dstThumbPic, $source_img, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $srcWidth, $srcHeight);
                        // 保存新图像
                        $isTrue = imagejpeg($dstThumbPic, $outPath, 100);
                        break;
                    default :
                        break;
                }
                $localurl = '/thumb/'.date('Ymd').'/'.$filename.'.jpg';
                imagedestroy($dstThumbPic);
                imagedestroy($source_img);
            }
            else
            {
                // 移动到新的位置，先创建目录及更新文件名为时间点
                $dir = public_path('upload/'.date('Ymd').'/');
                // if(!is_dir($dir)){
                //     Storage::makeDirectory(date('Ymd'));
                // }
                $outPath = $dir.$filename.'.jpg';
                // $isTrue = $res->file('imgFile')->move($dir, $filename.'.'.$ext);
                $isTrue = Storage::putFileAs(date('Ymd'),$res->file('imgFile'),$filename.'.'.$ext);
                $localurl = '/'.date('Ymd').'/'.$filename.'.'.$ext;
            }
            $url = '/upload'.$localurl;
            // 附件信息记入数据库
            $data['filename'] = $res->file('imgFile')->getClientOriginalName();
            $data['url'] = $url;
            if ($res->isattr) {
                $data['isattr'] = 1;
            }
            \App\Models\Common\Attr::create($data);
            if($isTrue){
                $return['error'] = 0;
                $return['url'] = $url;
            }
            return json_encode($return);
        } catch (\Throwable $e) {
            Storage::disk('log')->append('upload.log',json_encode($e).date('Y-m-d H:i:s'));
        }
    }
    // 请求接口用的CURL功能
    public function postCurl($url,$body,$type="POST",$json = 0){
        $header = array();
        //1.创建一个curl资源
        $ch = curl_init();
        //2.设置URL和相应的选项
        curl_setopt($ch,CURLOPT_URL,$url);//设置url
        //1)设置请求头
        if ($json) {
            array_push($header, 'Content-Type:application/json');
        }
        else
        {
            array_push($header,'Content-Type:application/x-www-form-urlencoded;charset=utf-8');
        }
        //设置请求头
        curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        //设置为false,只会获得响应的正文(true的话会连响应头一并获取到)
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt ( $ch, CURLOPT_TIMEOUT,5); // 设置超时限制防止死循环
        //设置发起连接前的等待时间，如果设置为0，则无限等待。
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
        //将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //上传文件相关设置
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);// 从证书中检查SSL加密算

        //3)设置提交方式
        switch($type){
            case "GET":
                curl_setopt($ch,CURLOPT_HTTPGET,true);
                break;
            case "POST":
                curl_setopt($ch,CURLOPT_POST,true);
                break;
            case "PUT"://使用一个自定义的请求信息来代替"GET"或"HEAD"作为HTTP请 求。这对于执行"DELETE" 或者其他更隐蔽的HTT
                curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"PUT");
                break;
            case "DELETE":
                curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"DELETE");
                break;
        }

        //2)设备请求体
        if (count($body)>0 && $type == 'POST') {
            $body = $json ? json_encode($body) : $body;
            // dd($body);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);//全部数据使用HTTP协议中的"POST"操作来发送。
        }

        //3.抓取URL并把它传递给浏览器
        $res=curl_exec($ch);
        $result=json_decode($res,true);
        //4.关闭curl资源，并且释放系统资源
        curl_close($ch);
        if(empty($result))
            return $res;
        else
            return $result;

    }
}