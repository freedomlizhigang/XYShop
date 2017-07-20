<?php

namespace App\Console\Commands;

use App\Models\Area;
use App\Models\Community;
use Illuminate\Console\Command;

class CommunityToArea extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'community_area';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '给所有乡找到市省';

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
        // 给所有乡找到市省
        $all_com = Community::groupBy('areaid3')->select('id','areaid3')->get();
        foreach ($all_com as $ac) {
            // 开始找
            $areaid2 = Area::where('id',$ac->areaid3)->value('parentid');
            $areaid1 = Area::where('id',$areaid2)->value('parentid');
            Community::where('areaid3',$ac->areaid3)->update(['areaid2'=>$areaid2,'areaid1'=>$areaid1]);
            dump($ac->id);
        }
        dd('success');
    }
}
