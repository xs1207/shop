<?php

namespace App\Http\Controllers\Movie;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
class MvController extends Controller
{
    //
    public function index()
    {
        $key="test_bit";
        $seat_status = [];
        for($i=0;$i<=50;$i++){
            $status=Redis::getBit($key,$i);
            $seat_status[$i] = $status;
        }
        $data = [
            'seat'  => $seat_status
        ];
        return view('movie.index',$data);
    }

    public function buy($pos)
    {
        $key = 'test_bit';
        Redis::setbit($key,$pos,1);
        echo "购票成功";
        header("Refresh:1;url=/movie/seat");

    }
}
