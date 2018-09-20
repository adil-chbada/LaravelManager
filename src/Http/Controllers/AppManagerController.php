<?php

namespace Adilchbada\LaravelManager\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppManagerController extends Controller
{
    public function index(){
        return view('laravelManager::index');
    }
    public function exec(Request $request){
       $command= $request->get('cmd');
        \Artisan::call($command['cmd']);
        return \Artisan::output();
    }
}
