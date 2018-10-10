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
       $options=[];
      foreach ($command['options'] as $option) {
          $options[$option['script']]=$option['value'];
      }
        \Artisan::call($command['cmd'],$options);
        return \Artisan::output();
    }
}
