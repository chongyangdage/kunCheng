<?php

namespace app\index\controller;
use think\Request;
use think\Db;
use app\index\model\Store as Store_m;
use app\index\model\Store_kind as Store_kind_m;
use app\index\model\Shop as shop_m;
//查询所有店铺名称
class Store
{
    //查询所有店铺名称
    public function index()
    {
        $Store=new Store_m();
        $data= $Store->select();
        return ['data'=>$data,'code'=>200,'message'=>'操作完成'];
    }
     //根据商铺id查商品分类
     public function kind()
     {
        $request = Request::instance();
        $word=$request->param();    
        $store_id=$word["store_id"];
         $Store=new Store_kind_m();
         $data= $Store->where('store_id', $store_id)->select();
         return ['data'=>$data,'code'=>200,'message'=>'操作完成'];
     }
     //根据分类id查商品
     public function kind_shop()
     {
        $request = Request::instance();
        $word=$request->param();    
        $kind_id=$word["kind_id"];
         $shop=new Shop_m();
        $data=$shop->alias('a')->join('store_shop b ','b.shop_id= a.id')->where('b.kind_id', $kind_id)->select();
        
         return ['data'=>$data,'code'=>200,'message'=>'操作完成'];
     }

      //根据商铺id店铺信息
      public function name()
      {
         $request = Request::instance();
         $word=$request->param();    
         $id=$word["id"];
          $Store=new Store_m();
          $data= $Store->where('id', $id)->find();
          return ['data'=>$data,'code'=>200,'message'=>'操作完成'];
      }

}
