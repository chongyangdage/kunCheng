<?php

namespace app\index\controller;
use think\Request;
use think\Db;
use app\index\model\Shop_car;
use app\index\model\Shop as Shop_m;
class Shopcar
{
    //添加购物车
    public function index()
    {

        $request = Request::instance();
        $get_all=$request->param();
        $userId=$get_all['userId'];
        $shopId=$get_all['shopId'];
        $shopNum=$get_all['shopNum'];
        $Shop_car=new Shop_car();
        $shop_data= $Shop_car->where('user_id', $userId)->where('shop_id', $shopId)->find();
        if($shop_data){
            $shop_data->shop_num= $shop_data['shop_num']+1;
            $shop_data->save();
        }else{
            $Shop_car->user_id=$userId;
            $Shop_car->shop_id=$shopId;
            $Shop_car->shop_num=$shopNum;
            $Shop_car->save();
        }
        return ['data'=>null,'code'=>200,'message'=>'操作完成'];
    }
    //查询所有购物车
    public function allShopCar()
    {
        $request = Request::instance();
        $get_all=$request->param();
        $userId=$get_all['userId'];
        // $userId=3;
        $shop_car=new Shop_car();
        $shop=new Shop_m();
        $data= $shop_car->where('user_id', $userId)->select();
        $data_main=[];
        for ($i=0; $i < count($data); $i++) {
            $shop_list=$shop->where('id', $data[$i]['shop_id'])->find();
            $shop_list['num']=$data[$i]['shop_num'];
            array_push($data_main,$shop_list);
        }
        return ['data'=>$data_main,'code'=>200,'message'=>'操作完成'];
    }


}
