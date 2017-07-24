<?php

namespace App\Http\Controllers;

use App\Models\Good\GoodCate;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function index()
    {
    	/*$data = [
    		['parentid'=>13,'name'=>'平板电视','mobilename'=>'平板电视'],
    	];*/
    	// GoodCate::where('id','>',0)->update(['created_at'=>date('Y-m-d H:i:s')]);
    	dd('success');
    }
}