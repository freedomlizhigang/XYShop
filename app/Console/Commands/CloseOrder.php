<?php

namespace App\Console\Commands;

use App\Http\Controllers\Common\Distribution;
use App\Http\Controllers\Common\OrderApi;
use App\Models\Good\Order;
use App\Models\Promotion\DistributionConfig;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;
use Log;

class CloseOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'closeorder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '定时关闭普通订单';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            $orderids = Order::where('orderstatus',1)->where('paystatus',0)->where('prom_type',0)->where('created_at','<',Carbon::now()->subday())->pluck('id');
            foreach ($orderids as $o) {
                // 增加库存
                OrderApi::updateStore($o,1);
            }
            // 关掉一天以前的未付款订单，同时把团购或者其它活动里的数量增加回去
            Order::whereIn('id',$orderids)->update(['orderstatus'=>0]);
            // 已经支付、发货的七天自动完成
            $subday = Carbon::now()->subday(7);
            Order::where('orderstatus',1)->where('shipstatus',1)->where('paystatus',1)->where('ship_at','<',$subday)->update(['orderstatus'=>2,'confirm_at'=>date('Y-m-d H:i:s')]);
            // 要关闭的订单开始计算分销值，先判断是否开启了分销功能
            if (DistributionConfig::where('id',1)->value('unlock')) {
                $orders = Order::where('orderstatus',1)->where('shipstatus',1)->where('paystatus',1)->where('ship_at','<',$subday)->select('id','user_id','total_prices')->get();
                foreach ($orders as $o) {
                    Distribution::clearing($o->user_id,$o->id,$o->total_prices);
                }
            }
            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollback();
            Log::warning('关闭订单失败记录');
            // dump($e);
            return false;
        }
    }
}
