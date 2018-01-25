<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Good\Order;
use App\Models\Good\Tuan;
use App\Models\User\User;
use DB;
use Illuminate\Http\Request;

class TestController extends Controller
{
    //
    public function getLock()
    {
        
       /* DB::beginTransaction();
        try {
            User::where('id',1)->sharedLock()->increment('user_money',10);
            User::where('id',1)->sharedLock()->decrement('points',10);
            // $user = User::where('id',1)->sharedLock()->first();
            // sleep(10);
            DB::commit();
            // return $user;
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }*/
    }
    public function getLockget()
    {
        DB::beginTransaction();
        try {
            $user = User::where('id',1)->where('points','>',10)->sharedLock()->decrement('points',10);
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }
}
