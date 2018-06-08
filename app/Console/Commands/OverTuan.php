<?php

namespace App\Console\Commands;

use App\Http\Controllers\Common\OrderApi;
use App\Models\Good\Order;
use App\Models\Good\Tuan;
use App\Models\User\User;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;
use Log;

class OverTuan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'overtuan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '结束团购，把没成团的关掉';

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
        try {
            /*
            1.找出来所有付款但未发货的两天前的团购订单
            2.对比团购活动看是否已经结束
            3.已经结束，退款到用户余额
            */
            $day = Carbon::now()->subDay(2);
            $order = Order::where('orderstatus',1)->where('paystatus',1)->where('shipstatus',0)->where('prom_type',2)->where('created_at','<',$day)->get();
            // 找到已经结束的团，或者结束时间是两天前的团
            $tids = Tuan::whereIn('id',$order->pluck('tuan_id'))->where(function($q) use($day){
                        $q->where('status',0)->orWhere('endtime','<=',$day);
                    })->pluck('id')->toArray();
            // 如果已经结束团购，数量增加回去，退款
            foreach ($order as $o) {
                if (in_array($o->tuan_id,$tids)) {
                    DB::beginTransaction();
                    try {
                        OrderApi::updateStore($o->id,1);
                        // 用户的钱退回余额
                        User::where('id',$o->user_id)->sharedLock()->increment('user_money',$o->total_prices);
                        User::where('id',$o->user_id)->sharedLock()->decrement('points',$o->total_prices);
                        // 消费记录
                        app('com')->consume($o->user_id,$o->id,$o->total_prices,'团购订单（'.$order->order_id.'）退款');
                        // 关掉订单
                        Order::where('id',$o->id)->update(['orderstatus'=>0]);
                        DB::commit();
                    } catch (\Throwable $e) {
                        DB::rollback();
                        Log::warning('关闭团购订单记录：'.$o->id);
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('关闭团购订单记录');
            dump($e);
        }
    }
}
