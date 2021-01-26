<?php

namespace app\index\controller;

use think\Db;
use app\index\model\Username as Username_h;
//查询所有用户数量
class Username
{
    public function index()
    {
        $Username=new Username_h();
        $data= $Username->select();
        return ['data'=>$data,'code'=>200,"size"=>count($data),'message'=>'操作完成'];
    }


}
