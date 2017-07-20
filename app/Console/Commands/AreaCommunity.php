<?php

namespace App\Console\Commands;

use App\Models\Area;
use App\Models\Community;
use Illuminate\Console\Command;

class AreaCommunity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'area_community';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '把省市县乡数据采来';

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
        $all = Area::where('id','>',1058)->get();
        foreach ($all as $a) {
            $tmp = [];
            $host = "http://ali-city.showapi.com";
            $path = "/areaDetail";
            $method = "GET";
            $appcode = "65b7c680fbde4dee920d897a397d702d";
            $headers = array();
            array_push($headers, "Authorization:APPCODE " . $appcode);
            $querys = "parentId=".$a->provinceid;
            $bodys = "";
            $url = $host . $path . "?" . $querys;

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_FAILONERROR, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl,CURLOPT_HEADER,0);
            if (1 == strpos("$".$host, "https://"))
            {
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            }
            $res = curl_exec($curl);
            if ($res == '') {
                dd($a->id);
            }
            try {
                $area = json_decode($res,true)['showapi_res_body']['data'];
                dump($a->areaname);
                $time = date('Y-m-d H:i:s');
                foreach ($area as $v) {
                    $tmp[] = ['areaid3'=>$a->id,'name'=>$v['areaName'],'created_at'=>$time,'updated_at'=>$time];
                }
                Community::insert($tmp);
                $tmp = [];
            } catch (\Exception $e) {
                continue;
            }
        }
        // dd($tmp);
        dd('success');
    }
}
