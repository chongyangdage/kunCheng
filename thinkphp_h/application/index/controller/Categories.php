<?php

namespace app\index\controller;

use think\Db;
use app\index\model\Categories as Categories_m;
//查询所有分类名称
class Categories
{
    public function index()
    {
        $Categories=new Categories_m();
        $data= $Categories->select();
        return ['data'=>$data,'code'=>200,'message'=>'操作完成'];
    }


}
