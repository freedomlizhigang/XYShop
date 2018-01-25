<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // 关闭普通订单
        \App\Console\Commands\CloseOrder::class,
        // 每十分钟关一次团购订单
        \App\Console\Commands\CloseTuan::class,
        // 每十分钟关一次抢购订单
        \App\Console\Commands\CloseTimetobuy::class,
        // 每小时，检查结束的团购订单，关掉
        \App\Console\Commands\OverTuan::class,
        // 结束活动，把商品属性归0
        \App\Console\Commands\OverPromotion::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 每小时关普通订单
        // $schedule->command('closeorder')->twiceDaily(1, 13)->withoutOverlapping();
        // 每十分钟关一次团购订单
        // $schedule->command('closetuan')->everyTenMinutes()->withoutOverlapping();
        // 每十分钟关一次抢购订单
        // $schedule->command('closetimetobuy')->everyTenMinutes()->withoutOverlapping();
        // 每小时，检查结束的团购订单，关掉
        // $schedule->command('overtuan')->->twiceDaily(2, 14)->withoutOverlapping();
        // 结束活动，把商品属性归0
        // $schedule->command('overtimetobuy')->->twiceDaily(8, 22)->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
