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

        $search = request('search');
        if(!empty($search)){
            $list=GoodsModel::where('goods_name', 'like', '%' . $search . '%')->paginate(3);
            $data=['list'=>$list];
        }else{
            $list=GoodsModel::paginate(3);
            $data=['list'=>$list];
        }

        return view('goods.list',$data);
    }



 /*

    //更新商品信息
    public function UpdateGoodsInfo($goods_id)
    {
        $name=str_random(6);
        $info=[]
    }

*/

    /**
     * 文件上传
     */
    public function uploadIndex()
    {
        return view('goods.upload');
    }
    public function uploadPdf(Request $request)
    {
//        echo "<pre>";print_r($_FILES);echo "</pre>";
        $pdf=$request->file('zhu');
//        var_dump($pdf);
        $res=$pdf->extension();
//        var_dump($res);
        if($res!='pdf'){
           die("上传格式不符合PDF格式");
        }
        $res1=$pdf->storeAs(date('Ymd'),str_random(4).'.pdf');
        if($res1){
            echo "上传成功";
        }
    }


}
