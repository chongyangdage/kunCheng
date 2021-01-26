<?php

namespace app\index\controller;
use think\Request;
use think\Db;
use app\index\model\Shop as Shop_m;
use app\index\model\Searchreferral as Searchreferral_s;

class Shop
{
    //查询所有商品
    public function index()
    {
        $shop=new Shop_m();
        $data= $shop->select();
        return ['data'=>$data,'code'=>200,'message'=>'操作完成'];
    }
    //根据商品分类查询商品
    public function findShopById()
    {
        $shop=new shop_m();
        $request = Request::instance();
        $sort_id=$request->only(['sort_id']);
        $data=$shop->where('sort_id', $sort_id['sort_id'])->select();
         return ['data'=>$data,'code'=>200,'message'=>'操作完成'];
    }
    //查询热门搜索
    public function searchReferral()
    {
        $shop=new Searchreferral_s();
        $data= $shop->select();
         return ['data'=>$data,'code'=>200,'message'=>'操作完成'];
    }
    //模糊查询
    public function search()
    {
        $shop=new Shop_m();
        $request = Request::instance();
        $word=$request->param();    
        $con=$word["word"];
        $data=$shop->where('name','like',"%$con%")->select();
         return ['data'=>$data,'code'=>200,'message'=>'操作完成'];
    }
    // 商品详情
    public function findShopDetails()
    {
        $shop=new shop_m();
        $request = Request::instance();
        $id=$request->param();
        $data=$shop->where('id', $id['id'])->find();
         return ['data'=>$data,'code'=>200,'message'=>'操作完成'];
    }

}
