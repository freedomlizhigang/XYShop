<?php

namespace App\Console\Commands;

use App\Http\Controllers\Common\OrderApi;
use App\Models\Good\Order;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;
use Log;

class CloseTimetobuy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'closetimetobuy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '关闭抢购订单';

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
            // 找出来十分钟前的没付款抢购订单
            $orderids = Order::where('orderstatus',1)->where('paystatus',0)->where('prom_type',1)->where('created_at','<',Carbon::now()->subMinute(10))->pluck('id');
            // 抢购里的数量增加回去
            foreach ($orderids as $o) {
                // 增加库存
                OrderApi::updateStore($o,1);
            }
            // 关掉
            Order::whereIn('id',$orderids)->update(['orderstatus'=>0]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            Log::warning('关闭团购订单记录');
            // dump($e);
        }
    }
}
