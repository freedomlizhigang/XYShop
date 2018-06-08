<?php

namespace App\Console\Commands;

use App\Models\Good\Fullgift;
use App\Models\Good\PromGood;
use App\Models\Good\Promotion;
use App\Models\Good\Timetobuy;
use App\Models\Good\Tuan;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;
use Log;

class OverPromotion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'overpromotion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '结束活动，把所有结束的活动里的商品prom_id归0';

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
            $day = Carbon::now();
            // 找到已经结束的团，或者到结束时间，或者库存为0的
            $tids = Tuan::where('status',0)->orWhere('endtime','<=',$day)->orWhere('store',0)->pluck('good_id')->unique()->toArray();
            // 找到已经结束的抢购，或者到结束时间，或者库存为0的
            $tbids = Timetobuy::where('status',0)->orWhere('endtime','<=',$day)->orWhere('good_num',0)->pluck('good_id')->unique()->toArray();
            // 找到已经结束的赠品，或者到结束时间，或者库存为0的
            $fids = Fullgift::where('status',0)->orWhere('endtime','<=',$day)->orWhere('store',0)->pluck('good_id')->unique()->toArray();
            // 找到已经结束的活动，或者到结束时间，或者库存为0的
            $pids = Promotion::where('status',0)->orWhere('endtime','<=',$day)->pluck('id');
            $pgids = PromGood::whereIn('prom_id',$pids)->pluck('good_id')->unique()->toArray();
            // 合并good_id
            $good_id = collect($tids,$tbids,$fids,$pgids)->collapse();
            // 归零操作
            Good::whereIn('id',$good_id)->update(['prom_type'=>0,'prom_id'=>0]);
        } catch (\Throwable $e) {
            Log::warning('关闭团购订单记录');
            dump($e);
        }
    }
}
