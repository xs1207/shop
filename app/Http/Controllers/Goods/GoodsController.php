<?php

namespace App\Http\Controllers\Goods;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\GoodsModel;

class GoodsController extends Controller
{
    //
    public function index($goods_id)
    {
        $goods=GoodsModel::where(['goods_id'=>$goods_id])->first();
        if(!$goods){
            header('Refresh:1;url=/');
            echo "商品不存在，正在跳转到首页";exit;
        }
        $data=[
            'goods'=>$goods
        ];
        return view('goods.index',$data);
    }


    public function goodsList()
    {
        $list=GoodsModel::all()->toArray();
        $data=['list'=>$list];
        return view('goods.list',$data);
    }

//    public function add()
//    {
//
//    }
}
