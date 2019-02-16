<?php

namespace App\Admin\Controllers;

use App\Model\GoodsModel;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class GoodsController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('商品管理')
            ->description('商品列表')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('商品管理')
            ->description('商品详情')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('商品管理')
            ->description('编辑')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('商品管理')
            ->description('添加')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new GoodsModel);
        $grid->model()->orderBy('goods_id','desc');     //倒序
        $grid->paginate('3');                             //分页
        $grid->goods_id('Goods id');
        $grid->goods_name('Goods name');
        $grid->updated_at('修改时间');
        $grid->add_time('添加时间')->display(function($time){
            return date('Y-m-d H:i:s',$time);
        });
        $grid->store('Store');
        $grid->price('Price');

        return $grid;
    }



    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(GoodsModel::findOrFail($id));

        $show->goods_id('Goods id');
        $show->goods_name('Goods name');
        $show->add_time('Add time');
        $show->add_time('Add time');
        $show->store('Store');
        $show->price('Price');

        return $show;
    }


    /**
     * Make a Form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new GoodsModel);

        $form->display('goods_id', 'Goods id');
        $form->text('goods_name', 'Goods name');
        $form->number('store', 'Store');
        $form->currency('price', 'Price')->symbol('￥');
        $form->ckeditor('content');
        return $form;
    }
    //添加
    public function store()
    {
        $data=[
            'goods_name'=>$_POST['goods_name'],
            'store'=>$_POST['store'],
            'price'=>$_POST['price'],
            'add_time'=>time(),
        ];
        GoodsModel::insert($data);
    }
    //删除
    public function destroy($id)
    {
        GoodsModel::where(['goods_id'=>$id])->delete();
        $response = [
            'status' => true,
            'message'   => 'ok'
        ];
        return $response;
    }

    //改
    public function update($id)
    {
        $data=[
            'goods_name'=>$_POST['goods_name'],
            'store'=>$_POST['store'],
            'price'=>$_POST['price'],
            'add_time'=>time(),
        ];
        GoodsModel::where(['goods_id'=>$id])->update($data);
    }

}

