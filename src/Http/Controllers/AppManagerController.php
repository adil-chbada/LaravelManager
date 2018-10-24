<?php

namespace Adilchbada\LaravelManager\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppManagerController extends Controller
{
    public function index()
    {
        return view('laravelManager::index');
    }

    public function clear()
    {
        \Artisan::call('cache:clear');
        \Artisan::call('config:cache');
        return \Artisan::output();
    }

    public function exec(Request $request)
    {
        $command = $request->get('cmd');
        $options = [];
        foreach ($command['options'] as $option) {
            $options[$option['script']] = $option['value'];
        }
        \Artisan::call($command['cmd'], $options);
        return \Artisan::output();
    }

    public function cmd(Request $request)
    {
        $command = $request->input('cmd');
        dd(
            \shell_exec('cd  ../ &&    ' . $command . " 2>&1")
        );
        return dd(shell_exec($command));


    }
}
