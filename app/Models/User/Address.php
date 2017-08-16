<?php

namespace App\Models\User;

use App\Models\Common\Area;
use App\Models\Common\Community;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    // 送货地址
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'address';

    // 不可以批量赋值的字段，为空则表示都可以
    protected $guarded = [];

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $hidden = [];
    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = true;

    // 区域名称
    public function getAreanameAttribute()
    {
        $ids = explode('.',$this->attributes['area']);
        $areaname = '';
        foreach ($ids as $k => $v) {
            if ($k < 3) {
                $areaname .= Area::where('id',$v)->value('areaname');
            }
            else
            {
                $areaname .= Community::where('id',$v)->value('name');
            }
        }
        return $areaname;
    }
    public function order()
    {
        return $this->hasMany('\App\Models\Good\Order','address_id','id');
    }
}
