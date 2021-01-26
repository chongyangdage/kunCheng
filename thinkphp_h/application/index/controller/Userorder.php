<?php

namespace app\index\controller;

use think\Request;
use think\Db;
use app\index\model\User_order as User_order_m;
use app\index\model\Order_details as Order_details_m;
use app\index\model\Shop as Shop_m;
//生成订单
class Userorder
{
    public function create_order()
    {
        $request = Request::instance();
        $get_all=$request->param();
        $userId=$get_all['userId'];
        $address_id=$get_all['receiverAddressId'];
        $total_price =$get_all['payTotal'];
        $orderSaveParams=json_decode($get_all['orderSaveParams'],true);
        $order_num = '';
        for ($i = 0; $i < 5; $i++) {
            $num = mt_rand(0, 9);
            $order_num .= $num;
        }
        $time=time();
        
        $order_num= $order_num.$time;
        $User_order_m = new User_order_m();
        $User_order_m->order_num=$order_num;
        $User_order_m->order_state=0;
        $User_order_m->payment_state=0;
        $User_order_m->total_price=$total_price;
        $User_order_m->user_id=$userId;
        $User_order_m->address_id=$address_id;
        $User_order_m->save();
        
        for ($i=0; $i < count($orderSaveParams); $i++) { 
            $Order_details_m = new Order_details_m();
            $Order_details_m->shop_id=$orderSaveParams[$i]['productId'];
            $Order_details_m->shop_num=$orderSaveParams[$i]['buyNumber'];
            $Order_details_m->subtotal=$orderSaveParams[$i]['subtotal'];
            $Order_details_m->order_num=$order_num;
            $Order_details_m->save();
            $e=$order_num;
        }
        return ['data' => '创建订单完成', 'code' => 200, 'message' => '操作完成'];
    }
    //查询订单

    public function get_order()
    {
        $request = Request::instance();
        $get_all=$request->param();
        $userId=$get_all['userId'];
        $state=$get_all['state'];
        $User_order_m = new User_order_m();
        $data1=$User_order_m->where('user_id',$userId)->where('order_state',$state)->select();
        $Order_details_m = new Order_details_m();
        $shop=new Shop_m();
        
        for ($i=0; $i <count($data1) ; $i++) {
            $arr=[]; 
            $data1[$i]['products']=[];
           
            $arr_list=$Order_details_m->where('order_num',$data1[$i]['order_num'])->select();
            for ($j=0; $j <count($arr_list); $j++) { 
                $shop_list=$shop->where('id',$arr_list[$j]['shop_id'])->find();
                $shop_list['shop_num']=$arr_list[$j]['shop_num'];
                $shop_list['subtotal']=$arr_list[$j]['subtotal'];
                array_push($arr,$shop_list);
                $data1[$i]['products']=$arr;
            }
            
            
        }
      
        return ['data' => $data1, 'code' => 200, 'message' => '操作完成'];
    }


     //删除订单

     public function delete_order()
     {
         $request = Request::instance();
         $get_all=$request->param();
         $order_num=$get_all['order_num'];
         $User_order_m = new User_order_m();
         $Order_details_m = new Order_details_m();
         $Order_details_m->where('order_num',$order_num)->delete();
         $User_order_m->where('order_num',$order_num)->delete();
       
         return ['data' => '取消订单成功', 'code' => 200, 'message' => '操作完成'];
     }


     //已完成订单

     public function finish_order()
     {
         $request = Request::instance();
         $get_all=$request->param();
         $order_num=$get_all['order_num'];
         $User_order_m = new User_order_m();
         $User_order_m->where('order_num',$order_num)->update(['order_state'=>3]);

       
         return ['data' => '订单更新成功', 'code' => 200, 'message' => '操作完成'];
     }
}
