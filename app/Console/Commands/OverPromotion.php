<?php

namespace App\Console\Commands;

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
            $tids = Tuan::where('status',0)->orWhere('endtime','<=',$day)->orWhere('store',0)->pluck('id')->toArray();
        } catch (\Exception $e) {
            Log::warning('关闭团购订单记录');
            dump($e);
        }
    }
}
